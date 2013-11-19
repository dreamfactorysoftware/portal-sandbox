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
	ajaxMessageFadeTime: 6000,
	scrollPane:          null,
	actions:             window.parent.Actions || null
};

/**
 * Shows the results pretty-printed
 * @param data
 * @returns {boolean}
 * @private
 */
var _showResults = function(data) {
	$('#example-code').html('<pre class="prettyprint">' + JSON.stringify(data, null, '\t') + '</pre>');
	PR.prettyPrint();
	if (-1 == window.location.href.indexOf('#')) {
		window.location.href += '#provider-results';
	}
	return true;
};

/**
 * Call the local DSP
 * @param method
 * @returns {*}
 * @private
 */
var _actions = function(method) {
	if (_options.actions && _options.actions[method]) {
		return _options.actions[method];
	}
};

/**
 * Runs the API call
 * @private
 */
var _execute = function() {
	var _method = $('#request-method').val(), _xMethod = $('#request-x-method').val();
	var _uri = $('#request-uri').val(), _folder = $('#request-x-folder-name').val();
	var _app = $('#request-x-app-name').val() || 'oasys';
	var _raw = $('#request-body').val();

	if (!_uri || !_uri.length) {
		alert('Invalid Request URI specified.');
		return false;
	}

	try {
		var _body = null;

		if (_raw.length) {
			_body = JSON.stringify(JSON.parse(_raw));
		}

		$.ajax({
			url:         _uri,
			async:       true,
			type:        _method,
			dataType:    'json',
			cache:       false,
			processData: false,
			data:        _body,
			beforeSend:  function(xhr) {
				if (_xMethod) {
					xhr.setRequestHeader('X-HTTP-Method', _xMethod);
				}
				if (_folder) {
					xhr.setRequestHeader('X-Folder-Name', _folder);
				}
				if (_app) {
					xhr.setRequestHeader('X-DreamFactory-Application-Name', _app);
				}
			},
			success:     function(data) {
				return _showResults(data);
			},
			error:       function(err) {
				if (err.responseText) {
					return _showResults(JSON.parse(err.responseText));
				}
			}
		});
	}
	catch (_ex) {
		$('#example-code').html(' >> ' + _ex);
	}

	return false;
};

/**
 * Initialize any buttons and set fieldset menu classes
 */
jQuery(function($) {
	$(window).on('blur', function(e) {
		_actions('toggleFullScreen')(true);
	});

	$('.multientry').multientry({
		label:       'Header(s)',
		formId:      'call-settings-form',
		placeholder: 'Header (i.e. &quot;Content-Type: application/json&quot;)',
	});

	$('a.example-code').on('click', function(e) {
		e.preventDefault();
		var _which = $(this).data('provider');

		if (_which) {
			$('div#example-code').load('salesforce.html');
		}
	});

	//	Close the app
	$('#app-close').on('click', function(e) {
		e.preventDefault();
		_actions('toggleFullScreen')(false);
		_actions('showAdmin')();
	});

	$('#send-request').on('click', function(e) {
		e.preventDefault();
		_execute();
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
