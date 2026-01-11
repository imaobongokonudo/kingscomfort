/**
 * Kings Comfort Luxury - Service Worker
 */

const CACHE_NAME = 'kcl-cache-v1';
const urlsToCache = [
    '/',
    '/apartments/',
    '/booking/',
    '/amenities/',
    '/concierge/',
    '/loyalty/',
    '/profile/'
];

// Install event
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                console.log('KCL Cache opened');
                return cache.addAll(urlsToCache);
            })
            .catch(function(error) {
                console.log('Cache install failed:', error);
            })
    );
});

// Fetch event
self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                // Return cached version or fetch from network
                if (response) {
                    return response;
                }

                return fetch(event.request).then(function(response) {
                    // Check if valid response
                    if (!response || response.status !== 200 || response.type !== 'basic') {
                        return response;
                    }

                    // Clone response
                    const responseToCache = response.clone();

                    caches.open(CACHE_NAME)
                        .then(function(cache) {
                            cache.put(event.request, responseToCache);
                        });

                    return response;
                });
            })
            .catch(function() {
                // Return offline page if available
                return caches.match('/');
            })
    );
});

// Activate event - cleanup old caches
self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.filter(function(cacheName) {
                    return cacheName !== CACHE_NAME;
                }).map(function(cacheName) {
                    return caches.delete(cacheName);
                })
            );
        })
    );
});

// Background sync for offline bookings
self.addEventListener('sync', function(event) {
    if (event.tag === 'sync-bookings') {
        event.waitUntil(syncBookings());
    }
});

function syncBookings() {
    return new Promise(function(resolve, reject) {
        // This will be handled by the main JavaScript
        resolve();
    });
}
