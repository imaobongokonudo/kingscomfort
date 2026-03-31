/**
 * Kings Comfort Luxury - Admin JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        KCLAdmin.init();
    });

    const KCLAdmin = {
        init: function() {
            this.initMediaUploader();
            this.initConfirmations();
            this.initTabs();
            this.initSiteImageUploader();
        },

        // Media Uploader for Gallery
        initMediaUploader: function() {
            let mediaUploader;

            $('.kcl-upload-gallery').on('click', function(e) {
                e.preventDefault();

                const $button = $(this);
                const $container = $button.siblings('.kcl-gallery-container');
                const $input = $button.siblings('input[type="hidden"]');

                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                mediaUploader = wp.media({
                    title: 'Select Gallery Images',
                    button: {
                        text: 'Add to Gallery'
                    },
                    multiple: true
                });

                mediaUploader.on('select', function() {
                    const attachments = mediaUploader.state().get('selection').map(function(attachment) {
                        attachment = attachment.toJSON();
                        return attachment.id;
                    });

                    $input.val(attachments.join(','));

                    // Update preview
                    let previewHtml = '';
                    mediaUploader.state().get('selection').each(function(attachment) {
                        attachment = attachment.toJSON();
                        previewHtml += '<img src="' + attachment.sizes.thumbnail.url + '" style="width:60px;height:60px;object-fit:cover;margin:5px;border-radius:5px;">';
                    });
                    $container.html(previewHtml);
                });

                mediaUploader.open();
            });
        },

        // Site Images Uploader - Enhanced for cleaner UX
        initSiteImageUploader: function() {
            // Handle clicks on the entire image card
            $(document).on('click', '.kcl-image-card', function(e) {
                if ($(e.target).hasClass('kcl-remove-image-btn') || $(e.target).closest('.kcl-remove-image-btn').length) {
                    return; // Don't open uploader if clicking remove button
                }
                const targetId = $(this).data('target');
                KCLAdmin.openMediaUploader(targetId);
            });

            // Remove button click
            $(document).on('click', '.kcl-remove-image-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const targetId = $(this).data('target');
                const $card = $(this).closest('.kcl-image-card');
                const $input = $('#' + targetId);

                $input.val('');
                $card.removeClass('has-image');
                $card.find('.kcl-image-thumb').html('<span class="dashicons dashicons-cloud-upload"></span><span class="upload-text">Click to Upload</span>');
                $card.find('.kcl-remove-image-btn').hide();
            });
        },

        openMediaUploader: function(targetId) {
            const $input = $('#' + targetId);
            const $card = $('[data-target="' + targetId + '"]');

            const mediaUploader = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use this image'
                },
                library: {
                    type: ['image']
                },
                multiple: false
            });

            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                const imgUrl = attachment.url;
                
                $input.val(imgUrl);
                $card.addClass('has-image');
                $card.find('.kcl-image-thumb').html('<img src="' + imgUrl + '" alt="">');
                $card.find('.kcl-remove-image-btn').show();
            });

            mediaUploader.open();
        },

        // Confirmation Dialogs
        initConfirmations: function() {
            $('a[href*="action=delete"]').on('click', function(e) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    e.preventDefault();
                }
            });

            $('a[href*="action=cancel"]').on('click', function(e) {
                if (!confirm('Are you sure you want to cancel this booking?')) {
                    e.preventDefault();
                }
            });
        },

        // Settings Tabs
        initTabs: function() {
            $('.kcl-admin-tabs a').on('click', function(e) {
                e.preventDefault();
                const target = $(this).attr('href');

                $('.kcl-admin-tabs a').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');

                $('.kcl-admin-tab-content').hide();
                $(target).show();
            });
        }
    };

    window.KCLAdmin = KCLAdmin;

})(jQuery);
