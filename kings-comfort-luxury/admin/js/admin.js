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

        // Site Images Uploader
        initSiteImageUploader: function() {
            // Upload button click
            $('.kcl-upload-btn').on('click', function(e) {
                e.preventDefault();
                const targetId = $(this).data('target');
                const $input = $('#' + targetId);
                const $preview = $('#preview_' + targetId);

                const mediaUploader = wp.media({
                    title: 'Select Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    const attachment = mediaUploader.state().get('selection').first().toJSON();
                    $input.val(attachment.url);
                    $preview.html('<img src="' + attachment.url + '" alt="">');
                });

                mediaUploader.open();
            });

            // Remove button click
            $('.kcl-remove-btn').on('click', function(e) {
                e.preventDefault();
                const targetId = $(this).data('target');
                const $input = $('#' + targetId);
                const $preview = $('#preview_' + targetId);

                $input.val('');
                $preview.html('<span class="dashicons dashicons-format-image"></span><span>Click to upload</span>');
            });

            // Preview click to upload
            $('.kcl-image-preview').on('click', function() {
                $(this).siblings('.kcl-upload-btn').trigger('click');
            });
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
