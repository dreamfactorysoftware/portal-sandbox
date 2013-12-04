/**
 * This file is part of the DreamFactory Portal Sandbox Application
 * Copyright 2013 DreamFactory Software, Inc. {@email support@dreamfactory.com}
 *
 * DreamFactory Portal Sandbox Application {@link http://github.com/dreamfactorysoftware/portal-sandbox}
 * DreamFactory Oasys(tm) {@link http://github.com/dreamfactorysoftware/oasys}
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

//********************************************************************************
//* The file contains common client-side functions for the app
//********************************************************************************

/**
 * Our global options
 * @var {*}
 */
var _options = {
	/** @var int **/
	alertHideDelay:      5000,
	/** @var int **/
	notifyDiv:           'div#request-message',
	/** @var int **/
	ajaxMessageFadeTime: 6000,
	/** @var {*} **/
	scrollPane:          null,
	/** @var string **/
	defaultUri:          '/rest/system/user',
	/** @var {*} **/
	currentProvider:     {},
	/** @var bool */
	readOnly:            true,

	//	These are set in index.php (ugh)

	/**
	 * @var string
	 */
	APPLICATION_NAME: null,
	/** @var {*}[] **/
	providers:        {},
	/** @var string **/
	baseUrl:          null
};

/**
 * Check if a var is defined and return default value if not optionally
 *
 * @param variable
 * @param [defaultValue]
 * @returns {*}
 * @private
 */
var _isDefined = function(variable, defaultValue) {
	if (typeof variable != 'undefined') {
		return variable;
	}

	if (typeof defaultValue != 'undefined') {
		return defaultValue;
	}

	//	Nope, not defined
	return false;
};

/**
 * A little URL builder
 * @param resource
 * @param [appName]
 * @param [excludeBase] If TRUE, the base URL will not be prepended to the returned endpoint
 * @returns {string}
 * @private
 */
var _getEndpoint = function(resource, appName, excludeBase) {
	return ( excludeBase ? '' : _options.baseUrl ) + resource;
};

/**
 * A System URL builder
 * @param resource
 * @param [appName]
 * @returns {string}
 * @private
 */
var _getSystemEndpoint = function(resource, appName) {
	return _getEndpoint('/rest/system/' + resource, appName);
};

/**
 * A Portal URL builder
 * @param portal
 * @param [appName]
 * @returns {string}
 * @private
 */
var _getPortalEndpoint = function(portal, appName) {
	return _getEndpoint('/rest/portal/' + portal, appName);
};

/**
 * A Portal URL builder
 * @param portal
 * @param [appName]
 * @returns {string}
 * @private
 */
var _getDefaultEndpoint = function(portal, appName) {
	return _getPortalEndpoint(portal, appName) +
		   (_options.currentProvider && _options.currentProvider.hasOwnProperty('config_text') && _options.currentProvider.config_text.length
			   ? _isDefined(_options.currentProvider.config_text.profile_resource, '') : '' );
};

/**
 * @param providerName
 * @private
 */
var _getAuthorizationUrl = function(providerName) {
	$('#revoke-auth-status').hide();

	$.ajax({
		async:   false,
		url: _getPortalEndpoint(providerName) + '?control=authorize_url&referrer=' + _getReferrer(true),
		type:    'GET', error: function(error) {
			$('#provider-auth-status').html('<i class="fa fa-times btn-danger status-icon"></i><small>Authorization required, but there was an error retrieving the authorization URL.</small>').show();
		},
		success: function(data) {
			if (data && data.authorize_url) {
				_showAuthorizeUrl(data.authorize_url);
			}
		}
	});
};

/**
 * @param {string} url
 * @private
 */
var _showAuthorizeUrl = function(url) {
	var _authUrl = '<small>Click <a target="_top" href="' + url + '">here</a> to begin the process.</small>';

	_showResults('<h3>Authorization Required</h3><p>' + _authUrl + '</p>', false);

	$('#revoke-auth-status').hide();

	$('#provider-auth-status').html('<i class="fa fa-times btn-danger status-icon"></i><small>Authorization required.</small>&nbsp;' + _authUrl).show();
};

/**
 * Load the provider stuff
 * @param [provider]
 * @private
 */
var _loadProvider = function(provider) {
	var $_app = $('#request-app'), $_list = $('#provider-list'), _userEndpoint = _getSystemEndpoint('provider_user');
	var _providerEndpoint = _getSystemEndpoint('provider');
	var _filter = 'user_id = :user_id AND provider_id = ' + $_list.find('option').filter(':selected').data('provider-id');
	var _providerName = _isDefined(provider, $_list.val());

	//	If we get the object, just pull out the name
	if (typeof _providerName == 'object') {
		_providerName = _providerName.api_name;
	}

	//	Fill in the request form
	if (!$_app.val()) {
		$_app.val(_options.APPLICATION_NAME);
	}
	$('#request-uri').val(_getDefaultEndpoint(_providerName));
	$('#request-method').val('GET');
	$('#loading-indicator').fadeOut().removeClass('fa-spin');
	$('#example-code').html('<small>Ready</small>');

	//	Disable controls
	$_list.addClass('disabled');
	$('#provider-auth-status').hide();
	$('#revoke-auth-status').hide();
	$('#provider-auth-check').show();
	$('html').css('cursor', 'wait');

	//	Pull the credentials
	$.ajax({
		url:      _userEndpoint,
		data:     {
			app_name: $_app.val(),
			filter:   _filter
		},
		complete: function() {
			//	Restore controls
			$('#provider-auth-check').hide();
			$('#provider-auth-status').show();
			$_list.removeClass('disabled');
			$('html').css('cursor', 'pointer');
		},
		error:    function(data) {
			_getAuthorizationUrl(_providerName);
		},
		success:  function(data) {
			var _auth = false;
			if (data && data.record && data.record.length) {
				var _provider = data.record[0];

				if (_provider.auth_text && _provider.auth_text.hasOwnProperty('access_token') && _provider.auth_text.access_token) {
					//	Authorized already
					$('#provider-auth-status').html('<i class="fa fa-check btn-success status-icon"></i><small>Authorization granted.</small>').show();

					//	Set provider user ID
					if (_provider.hasOwnProperty('provider_user_id')) {
						$('#revoke-auth-status').data({'provider-user-id': _provider.provider_user_id});
					}

					$('#revoke-auth-status').show();
					_auth = true;
				}
			}

			if (!_auth) {
				_getAuthorizationUrl(_providerName);
			}
		}
	});
};

/**
 * Shows the results pretty-printed
 * @param data
 * @param [pretty]
 * @returns {boolean}
 * @private
 */
var _showResults = function(data, pretty) {
	if (false === pretty) {
		$('#example-code').html(data);
	}
	else {
		$('#example-code').html('<pre class="prettyprint">' + JSON.stringify(data, null, '\t') + '</pre>');

		//noinspection JSUnresolvedFunction
		PR.prettyPrint();
	}

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
		var _args = [];

		if (arguments.length) {
			Array.prototype.push.apply(_args, arguments);
			_args.shift();
		}
		return _options.actions[method].apply(null, _args);
	}

	throw 'Invalid method "' + method + '"';
};

/**
 * Gets the URL to return to after a redirected AJAX call...
 * @returns {string}
 * @private
 */
var _getReferrer = function(encoded) {
	var _run = 'run=' + ($('#request-app').val() || _options.APPLICATION_NAME);
	var _referrer = window.parent.location.href;
	if (-1 == _referrer.indexOf(_run)) {
		_referrer += ( -1 == _referrer.indexOf('?') ? '?' : '&') + _run;
	}
	return !_isDefined(encoded, false) ? _referrer : encodeURI(_referrer);
};

/**
 * Runs the API call
 * @private
 */
var _execute = function() {
	var _method = $('#request-method').val();
	var _uri = $('#request-uri').val();
	var _app = $('#request-app').val() || _options.APPLICATION_NAME;
	var _raw = $('#request-body').val();
	var $_code = $('#example-code');

	if (!_uri || !_uri.length) {
		alert('Invalid Request URI specified.');
		return false;
	}

	_uri += ( -1 == _uri.indexOf('?') ? '?' : '&') + 'flow_type=1&referrer=' + _getReferrer(true);

	$_code.empty().html('<small>Loading...</small>');

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
				$('#loading-indicator').fadeIn().addClass('fa-spin');
				$('#send-request').addClass('disabled');

				if (_app) {
					xhr.setRequestHeader('X-DreamFactory-Application-Name', _app);
				}
			},
			success:     function(data) {
				return _showResults(data);
			},
			error:       function(err) {
				var _json = {};

				if (err.responseJSON) {
					_json = err.responseJSON.error[0];
				}
				else if (err.responseText) {
					_json = JSON.parse(err.responseText);
					if (!_json) {
						_json = err.responseText;
					}
				}

				if (302 == err.status || 307 == err.status) {
					var _location = _json.location || err.location;
					if (!_location) {
						_location = _getAuthorizationUrl(_options.currentProvider);
					}

					if (!_location) {
						_showResults('<div class="alert alert-fixed alert-danger"><strong>Authorization Required</strong><p>However, the authorization URL cannot be determined.</p></div>',
							false);
					}
					else {
						_showAuthorizeUrl(_location);
					}
				}
				else {
					_showResults('Error: ' + err.status, false);
				}
			},
			complete:    function() {
				$('#send-request').removeClass('disabled');
				$('#loading-indicator').removeClass('fa-spin').fadeOut();
			}
		});
	}
	catch (_ex) {
		$_code.html(' >> ' + _ex);
	}

	return false;
};

/**
 * Initialize the app
 * @private
 */
var _initialize = function() {
	if (!_options.actions) {
		_options.actions = window.parent.Actions;
		_options.config = window.parent.Config;
	}

	//	Load providers
	_loadProvider();
};

/**
 * Initialize any buttons and set fieldset menu classes
 */
jQuery(function($) {
	//	Initialize...
	_initialize();

	$('body').on('open.dsp reopen.dsp',function() {
		_actions('toggleFullScreen', true);
	}).on('close.dsp', function() {
			_actions('toggleFullScreen', false);
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
		if (window.parent && window.parent.Actions) {
			window.parent.Actions.showAdmin();
			window.parent.Actions.toggleFullScreen(false);
		}
	});

	if (!_options.readOnly) {
		$('#add-provider').on('click', function(e) {
			e.preventDefault();
			if (!$(this).hasClass('disabled')) {
				$('#select-provider').slideUp();
				$('#new-provider').slideDown();
				$('#add-provider').addClass('disabled');
			}
		});

		$('#add-provider-cancel').on('click', function(e) {
			e.preventDefault();
			$('#select-provider').slideDown();
			$('#new-provider').slideUp();
			$('#add-provider').removeClass('disabled');
		});
	}

	$('#send-request').on('click', function(e) {
		e.preventDefault();
		_execute();
	});

	$('#reset-request').on('click', function(e) {
		e.preventDefault();
		$('#request-uri').val(_options.defaultUri);
		$('#request-method').val('GET');
		$('#request-app').val(_options.APPLICATION_NAME);
		$('#example-code').html('<small>Ready</small>');
		$('#loading-indicator').hide().removeClass('fa-spin');
	});

	$('#revoke-auth').on('click', function(e) {
		e.preventDefault();

		if (!confirm('Really revoke your authorization for this provider?')) {
			return false;
		}

		$('html').css('cursor', 'wait');

		$.ajax({
			async:    false,
			url: _getPortalEndpoint($('#provider-list').val()) + '?control=revoke&provider_user_id=' + $('#revoke-auth-status').data('provider-user-id') +
				 '&referrer=' + _getReferrer(true),
			type:     'GET',
			complete: function() {
				$('html').css('cursor', 'pointer');
			},
			error:    function(error) {
				$('#provider-auth-status').html('<i class="fa fa-times btn-danger status-icon"></i><small>Revocation failed.</small>').show();
			},
			success:  function(data) {
				$('#revoke-auth-status').hide();

				if (data && data.authorize_url) {
					_showAuthorizeUrl(data.authorize_url);
				}
				else {
					$('#provider-auth-status').html('<i class="fa fa-times btn-danger status-icon"></i><small>Revocation status uncertain. Unexpected result.</small>').show();
				}
			}
		});

		return true;
	});

	$('#provider-list').on('change', function() {
		var _id = $(this).val();

		if (_options.providers && _options.providers.hasOwnProperty(_id)) {
			_options.currentProvider = _options.providers[_id];
			_loadProvider(_options.providers[_id]);
			return true;
		}

		return false;
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
