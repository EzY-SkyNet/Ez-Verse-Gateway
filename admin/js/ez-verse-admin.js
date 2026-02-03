(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(document).on('click', '#ezverse-edit', function (e) {
        e.preventDefault();

        if (!confirm('Are you sure you want to edit credentials?')) {
            return;
        }

        $.post(
            ezverseAdmin.ajax_url,
            {
                action: 'ezverse_reset_profile',
                nonce: ezverseAdmin.nonce
            }
        ).done(function (response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Failed to reset Ez-Verse profile.');
            }
        }).fail(function () {
            alert('AJAX request failed.');
        });
    });

})( jQuery );
