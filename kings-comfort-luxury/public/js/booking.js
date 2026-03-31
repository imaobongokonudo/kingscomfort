/**
 * Kings Comfort Luxury - Booking JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        KCLBooking.init();
    });

    const KCLBooking = {
        selectedApartment: null,
        pricePerNight: 0,
        nights: 0,
        totalAmount: 0,

        init: function() {
            this.initBookingForm();
            this.initSummaryUpdate();
            this.prefillFromUrl();
        },

        // Initialize Booking Form
        initBookingForm: function() {
            const self = this;

            $('#kcl-booking-form').on('submit', function(e) {
                e.preventDefault();

                const formData = {
                    action: 'kcl_create_booking',
                    nonce: kcl_ajax.nonce,
                    apartment_id: $('#apartment_id').val(),
                    guest_name: $('#guest_name').val(),
                    guest_email: $('#guest_email').val(),
                    guest_phone: $('#guest_phone').val(),
                    check_in: $('#check_in').val(),
                    check_out: $('#check_out').val(),
                    guests: $('#guests').val(),
                    special_requests: $('#special_requests').val()
                };

                // Validate
                if (!formData.apartment_id) {
                    alert('Please select an apartment.');
                    return;
                }

                if (!formData.check_in || !formData.check_out) {
                    alert('Please select check-in and check-out dates.');
                    return;
                }

                if (!formData.guest_name || !formData.guest_email || !formData.guest_phone) {
                    alert('Please fill in all guest information.');
                    return;
                }

                // Create booking
                $.ajax({
                    url: kcl_ajax.ajax_url,
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#kcl-pay-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
                    },
                    success: function(response) {
                        if (response.success) {
                            // Initiate payment
                            self.initiatePayment(response.data);
                        } else {
                            alert(response.data.message);
                            $('#kcl-pay-btn').prop('disabled', false).html('<i class="fas fa-lock"></i> Proceed to Payment');
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                        $('#kcl-pay-btn').prop('disabled', false).html('<i class="fas fa-lock"></i> Proceed to Payment');
                    }
                });
            });
        },

        // Initiate Paystack Payment
        initiatePayment: function(bookingData) {
            const self = this;

            // Get payment config
            $.ajax({
                url: kcl_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'kcl_init_payment',
                    nonce: kcl_ajax.nonce,
                    email: bookingData.email,
                    amount: bookingData.total_amount,
                    booking_id: bookingData.booking_id
                },
                success: function(response) {
                    if (response.success) {
                        const paymentData = response.data;

                        if (paymentData.public_key) {
                            // Use Paystack
                            const handler = PaystackPop.setup({
                                key: paymentData.public_key,
                                email: paymentData.email,
                                amount: paymentData.amount,
                                currency: paymentData.currency,
                                ref: paymentData.ref,
                                metadata: {
                                    booking_id: paymentData.booking_id
                                },
                                callback: function(response) {
                                    self.verifyPayment(response.reference, paymentData.booking_id);
                                },
                                onClose: function() {
                                    $('#kcl-pay-btn').prop('disabled', false).html('<i class="fas fa-lock"></i> Proceed to Payment');
                                    alert('Payment cancelled. Your booking is saved but not confirmed.');
                                }
                            });

                            handler.openIframe();
                        } else {
                            // Test mode without Paystack key
                            self.verifyPayment('TEST_' + Date.now(), bookingData.booking_id);
                        }
                    } else {
                        alert('Payment initialization failed. Please try again.');
                        $('#kcl-pay-btn').prop('disabled', false).html('<i class="fas fa-lock"></i> Proceed to Payment');
                    }
                }
            });
        },

        // Verify Payment
        verifyPayment: function(reference, bookingId) {
            $.ajax({
                url: kcl_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'kcl_verify_payment',
                    nonce: kcl_ajax.nonce,
                    reference: reference,
                    booking_id: bookingId
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        $('#kcl-booking-form').html(
                            '<div style="text-align: center; padding: 60px 20px;">' +
                            '<i class="fas fa-check-circle" style="font-size: 80px; color: #4CAF50; margin-bottom: 30px;"></i>' +
                            '<h2 style="color: #E2C369; margin-bottom: 15px;">Booking Confirmed!</h2>' +
                            '<p style="margin-bottom: 10px;">Your booking ID: <strong>' + bookingId + '</strong></p>' +
                            '<p style="color: rgba(255,255,255,0.7); margin-bottom: 30px;">A confirmation email has been sent to your email address.</p>' +
                            '<a href="' + window.location.origin + '" class="kcl-btn kcl-btn-primary">Return to Home</a>' +
                            '</div>'
                        );
                    } else {
                        alert('Payment verification failed. Please contact support with your booking ID: ' + bookingId);
                        $('#kcl-pay-btn').prop('disabled', false).html('<i class="fas fa-lock"></i> Proceed to Payment');
                    }
                }
            });
        },

        // Update Booking Summary
        initSummaryUpdate: function() {
            const self = this;

            // Apartment selection
            $('#apartment_id').on('change', function() {
                const $selected = $(this).find(':selected');
                self.selectedApartment = $selected.text().split(' - ')[0];
                self.pricePerNight = parseFloat($selected.data('price')) || 0;
                self.updateSummary();
            });

            // Date changes
            $('#check_in, #check_out').on('change', function() {
                self.updateSummary();
            });

            // Trigger initial update if apartment is pre-selected
            if ($('#apartment_id').val()) {
                $('#apartment_id').trigger('change');
            }
        },

        // Calculate and update summary
        updateSummary: function() {
            const checkIn = $('#check_in').val();
            const checkOut = $('#check_out').val();

            $('#summary-apartment').text(this.selectedApartment || '-');
            $('#summary-checkin').text(checkIn || '-');
            $('#summary-checkout').text(checkOut || '-');

            if (checkIn && checkOut) {
                const checkInDate = new Date(checkIn);
                const checkOutDate = new Date(checkOut);
                this.nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));

                if (this.nights > 0) {
                    $('#summary-nights').text(this.nights);
                    this.totalAmount = this.pricePerNight * this.nights;
                    $('#summary-total').text('₦' + this.totalAmount.toLocaleString());
                } else {
                    $('#summary-nights').text('-');
                    $('#summary-total').text('₦0');
                }
            } else {
                $('#summary-nights').text('-');
                $('#summary-total').text('₦0');
            }
        },

        // Prefill from URL parameters
        prefillFromUrl: function() {
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.has('apartment')) {
                $('#apartment_id').val(urlParams.get('apartment')).trigger('change');
            }

            if (urlParams.has('check_in')) {
                $('#check_in').val(urlParams.get('check_in'));
            }

            if (urlParams.has('check_out')) {
                $('#check_out').val(urlParams.get('check_out'));
            }

            if (urlParams.has('guests')) {
                $('#guests').val(urlParams.get('guests'));
            }

            // Trigger summary update
            this.updateSummary();
        }
    };

    window.KCLBooking = KCLBooking;

})(jQuery);
