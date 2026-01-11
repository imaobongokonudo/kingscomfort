/**
 * Kings Comfort Luxury - Offline Support JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        KCLOffline.init();
    });

    const KCLOffline = {
        dbName: 'KCLOfflineDB',
        dbVersion: 1,
        db: null,

        init: function() {
            this.initIndexedDB();
            this.checkOnlineStatus();
            this.initSyncOnReconnect();
        },

        // Initialize IndexedDB
        initIndexedDB: function() {
            const self = this;

            if (!('indexedDB' in window)) {
                console.log('IndexedDB not supported');
                return;
            }

            const request = indexedDB.open(this.dbName, this.dbVersion);

            request.onerror = function(event) {
                console.log('Error opening IndexedDB:', event.target.error);
            };

            request.onsuccess = function(event) {
                self.db = event.target.result;
                console.log('IndexedDB opened successfully');

                // Sync any pending offline bookings
                if (navigator.onLine) {
                    self.syncOfflineBookings();
                }
            };

            request.onupgradeneeded = function(event) {
                const db = event.target.result;

                // Create bookings store
                if (!db.objectStoreNames.contains('offlineBookings')) {
                    const store = db.createObjectStore('offlineBookings', { keyPath: 'id', autoIncrement: true });
                    store.createIndex('booking_id', 'booking_id', { unique: true });
                    store.createIndex('synced', 'synced', { unique: false });
                }

                // Create apartments cache store
                if (!db.objectStoreNames.contains('apartmentsCache')) {
                    db.createObjectStore('apartmentsCache', { keyPath: 'id' });
                }

                console.log('IndexedDB upgraded');
            };
        },

        // Check Online Status
        checkOnlineStatus: function() {
            const self = this;

            // Create status indicator
            $('body').append('<div id="kcl-offline-indicator" style="display: none; position: fixed; top: 80px; left: 50%; transform: translateX(-50%); background: #ff5722; color: white; padding: 10px 20px; border-radius: 5px; z-index: 9999; font-size: 14px;"><i class="fas fa-wifi-slash"></i> You are offline. Bookings will sync when connected.</div>');

            const updateStatus = function() {
                if (navigator.onLine) {
                    $('#kcl-offline-indicator').fadeOut();
                    self.syncOfflineBookings();
                } else {
                    $('#kcl-offline-indicator').fadeIn();
                }
            };

            window.addEventListener('online', updateStatus);
            window.addEventListener('offline', updateStatus);

            // Initial check
            if (!navigator.onLine) {
                $('#kcl-offline-indicator').show();
            }
        },

        // Save Booking Offline
        saveBookingOffline: function(bookingData) {
            const self = this;

            if (!this.db) {
                console.log('IndexedDB not available');
                return false;
            }

            const transaction = this.db.transaction(['offlineBookings'], 'readwrite');
            const store = transaction.objectStore('offlineBookings');

            const booking = {
                booking_id: 'OFFLINE_' + Date.now(),
                apartment_id: bookingData.apartment_id,
                guest_name: bookingData.guest_name,
                guest_email: bookingData.guest_email,
                guest_phone: bookingData.guest_phone,
                check_in: bookingData.check_in,
                check_out: bookingData.check_out,
                guests: bookingData.guests,
                total_amount: bookingData.total_amount,
                special_requests: bookingData.special_requests,
                synced: false,
                created_at: new Date().toISOString()
            };

            const request = store.add(booking);

            request.onsuccess = function() {
                console.log('Booking saved offline:', booking.booking_id);
                alert('You are offline. Your booking has been saved and will be synced when you reconnect.');
            };

            request.onerror = function() {
                console.log('Error saving booking offline');
            };

            return true;
        },

        // Sync Offline Bookings
        syncOfflineBookings: function() {
            const self = this;

            if (!this.db) return;

            const transaction = this.db.transaction(['offlineBookings'], 'readonly');
            const store = transaction.objectStore('offlineBookings');
            const index = store.index('synced');
            const request = index.getAll(false);

            request.onsuccess = function(event) {
                const offlineBookings = event.target.result;

                if (offlineBookings.length === 0) {
                    console.log('No offline bookings to sync');
                    return;
                }

                console.log('Syncing', offlineBookings.length, 'offline bookings');

                $.ajax({
                    url: kcl_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'kcl_sync_offline_bookings',
                        nonce: kcl_ajax.nonce,
                        bookings: JSON.stringify(offlineBookings)
                    },
                    success: function(response) {
                        if (response.success) {
                            // Mark as synced
                            self.markBookingsSynced(offlineBookings);
                            console.log('Offline bookings synced:', response.data.synced);

                            if (response.data.synced > 0) {
                                alert(response.data.synced + ' offline booking(s) have been synced successfully!');
                            }
                        }
                    }
                });
            };
        },

        // Mark Bookings as Synced
        markBookingsSynced: function(bookings) {
            if (!this.db) return;

            const transaction = this.db.transaction(['offlineBookings'], 'readwrite');
            const store = transaction.objectStore('offlineBookings');

            bookings.forEach(function(booking) {
                booking.synced = true;
                store.put(booking);
            });
        },

        // Sync on Reconnect
        initSyncOnReconnect: function() {
            const self = this;

            window.addEventListener('online', function() {
                setTimeout(function() {
                    self.syncOfflineBookings();
                }, 2000);
            });
        },

        // Cache Apartments for Offline Use
        cacheApartments: function() {
            const self = this;

            if (!this.db) return;

            $.ajax({
                url: kcl_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'kcl_get_offline_data'
                },
                success: function(response) {
                    if (response.success) {
                        const transaction = self.db.transaction(['apartmentsCache'], 'readwrite');
                        const store = transaction.objectStore('apartmentsCache');

                        // Clear existing cache
                        store.clear();

                        // Add apartments
                        response.data.apartments.forEach(function(apt) {
                            store.add(apt);
                        });

                        console.log('Apartments cached for offline use');
                    }
                }
            });
        }
    };

    window.KCLOffline = KCLOffline;

})(jQuery);
