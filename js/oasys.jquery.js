/**
 * oasys.jquery.js
 * The file contains common client-side functions for the DreamFactory Oasys(tm) Example Code
 */

/**
 * Our global options
 */
var _options = {
	alertHideDelay:      5000,
	notifyDiv:           'div#request-message',
	ajaxMessageFadeTime: 6000
};

/**
 * Initialize any buttons and set fieldset menu classes
 */
jQuery(function($) {
	$('.multientry').multientry({
		label:       'Header(s)',
		formId:      'runtime-settings-form',
		placeholder: 'Header (i.e. &quot;Content-Type: application/json&quot;)'

	});

	$('a.example-code').on('click', function(e) {
		e.preventDefault();
		var _which = $(this).data('provider');

		if (_which) {
			$('div#example-code').load('salesforce.html');
		}
	});

	/**
	 * Clear any alerts after configured time
	 */
	if (_options.alertHideDelay) {
		window.setTimeout(function() {
			$('div.alert').not('.alert-fixed').fadeTo(500, 0).slideUp(500, function() {
				$(this).remove();
			});
		}, _options.alertHideDelay);
	}
});
