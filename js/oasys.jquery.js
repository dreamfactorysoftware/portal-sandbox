/**
 * oasys.jquery.js
 * The file contains common client-side functions for the DreamFactory Oasys(tm) Example Code
 */

/**
 * Our global options
 */
var _options = {
    alertHideDelay: 5000,
    notifyDiv: 'div#request-message',
    ajaxMessageFadeTime: 6000,
    scrollPane: null
};

/**
 * Runs the API call
 * @private
 */
var _execute = function () {
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
            url: _uri,
            async: true,
            type: _method,
            dataType: 'json',
            cache: false,
            processData: false,
            data: _body,
            beforeSend: function (xhr) {
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
            success: function (data) {
                $('#example-code').html(JSON.stringify(data, null, '\t'));
                return true;
            },
            error: function (err) {
                if (err.responseText) {
                    $('#example-code').html('<pre class="prettyprint">' + JSON.stringify(JSON.parse(err.responseText)) + '</pre>');
                    PR.prettyPrint();
                }
            }
        });
    } catch (_ex) {
        $('#example-code').html(' >> ' + _ex);
    }

    return false;
};

/**
 * Initialize any buttons and set fieldset menu classes
 */
jQuery(function ($) {
    $('.multientry').multientry({
        label: 'Header(s)',
        formId: 'call-settings-form',
        placeholder: 'Header (i.e. &quot;Content-Type: application/json&quot;)',
        items: ["abc", "def", "ghi", "klm", "nop", "qrs", "tuv", "xyz"]
    });

    $('a.example-code').on('click', function (e) {
        e.preventDefault();
        var _which = $(this).data('provider');

        if (_which) {
            $('div#example-code').load('salesforce.html');
        }
    });

    $('#call-settings-form').on('submit', function (e) {
        e.preventDefault();
        return _execute();
    });

    /**
     * Clear any alerts after configured time
     */
    if (_options.alertHideDelay) {
        window.setTimeout(function () {
            $('div.alert').not('.alert-fixed').fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, _options.alertHideDelay);
    }
});
