<?php
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
use DreamFactory\Platform\Utility\ResourceStore;
use DreamFactory\Platform\Yii\Models\App;
use DreamFactory\Platform\Yii\Models\Provider;
use DreamFactory\Yii\Utility\Pii;
use Kisma\Core\Utility\Curl;
use Kisma\Core\Utility\HtmlMarkup;
use Kisma\Core\Utility\Inflector;
use Kisma\Core\Utility\Log;
use Kisma\Core\Utility\Option;

//*************************************************************************
//	Constants
//*************************************************************************

/**
 * @var string
 */
const APPLICATION_NAME = 'portal-sandbox';

//********************************************************************************
//* Bootstrap and Debugging
//********************************************************************************

require_once dirname( __DIR__ ) . '/autoload.php';

//	Debugging?
if ( \Kisma::getDebug() )
{
	error_reporting( -1 );
	ini_set( 'display_errors', 1 );
}

$_dspUrl = Curl::currentUrl( false, false );

//	Must be logged in...
if ( Pii::guest() )
{
	header( 'Location: ' . $_dspUrl . '/' );
	die();
}


//********************************************************************************
//* Load data for dropdowns...
//********************************************************************************

$_apps = $_providers = null;
$_providerCache = new \stdClass();

$_models = ResourceStore::model( 'app' )->findAll(
	array(
		'select' => 'id, api_name, name',
		'order'  => 'name'
	)
);

if ( !empty( $_models ) )
{
	/** @var App[] $_models */
	foreach ( $_models as $_model )
	{
		$_attributes = array( 'value' => $_model->api_name, 'name' => $_model->api_name );

		if ( APPLICATION_NAME == $_model->api_name )
		{
			$_attributes['selected'] = 'selected';
		}

		$_apps .= HtmlMarkup::tag( 'option', $_attributes, $_model->name );
		unset( $_model );
	}

	unset( $_models );
}

$_models = ResourceStore::model( 'provider' )->findAll(
	array(
		'select' => 'id, provider_name, api_name',
		'order'  => 'provider_name',
	)
);

if ( !empty( $_models ) )
{
	$_first = true;

	/** @var Provider[] $_models */
	foreach ( $_models as $_model )
	{
		$_attributes = array(
			'name'                  => $_model->api_name,
			'value'                 => $_model->api_name,
			'data-provider-id'      => $_model->id,
			'data-provider-tag'     => Inflector::neutralize( $_model->provider_name ) . ':' . $_model->api_name,
			//@todo	Needs to pull from template somehow...
			'data-profile-resource' => strtolower( $_model->provider_name ) == 'facebook' ? '/me' : '/user',
		);

		if ( $_first )
		{
			$_attributes['selected'] = 'selected';
			$_first = false;
		}

		$_providers .= HtmlMarkup::tag( 'option', $_attributes, $_model->api_name . ' (' . Inflector::display( $_model->provider_name ) . ')' );
		$_providerCache->{$_model->api_name} = $_model->getAttributes();

		unset( $_model, $_config, $_attributes, $_profileResource );
	}

	unset( $_models );
}

//	Default url
$_defaultUrl = '/rest/system/user';
?>
<!DOCTYPE html>
<html lang="en">
<?php require_once __DIR__ . '/views/_head.php'; ?>
<body>
<div id="wrap">
	<?php require_once __DIR__ . '/views/_navbar.php'; ?>

	<div class="container">
		<h1>Portal Sandbox</h1>
		<blockquote>[Sample application description copy and help links to go here].</blockquote>
		<section id="provider-settings">
			<div class="panel-group" id="provider-settings-group">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#provider-settings-group" href="#provider-form-body">Providers</a>
                            <span class="pull-right hide"><button id="add-provider" type="button" class="btn btn-info btn-xs">
									<i class="fa fa-plus-square"></i>Add...
								</button></span>
						</h4>
					</div>
					<div id="provider-form-body" class="panel-collapse collapse in">
						<div class="panel-body">
							<form class="form-horizontal" id="provider-settings-form">
								<div id="select-provider">
									<div class="form-group">
										<label for="provider-list" class="col-sm-2 control-label">Providers</label>

										<div class="col-sm-3">
											<select class="form-control" id="provider-list"><?php echo $_providers; ?></select>
										</div>
										<div class="col-sm-6">
											<div id="provider-auth-check" style="display: none;" class="pull-left">
												<i class="fa fa-spinner fa-spin"></i>
												<small>Checking authorization...</small>
											</div>
											<div id="provider-auth-status" style="display: none;" class="pull-left"></div>
											<div id="revoke-auth-status" style="display: none;" class="pull-left" data-provider-user-id="">
												<i class="fa fa-trash-o btn-danger status-icon"></i>
												<small>Click
													<a href="#" id="revoke-auth">here</a>
													to revoke.
												</small>
											</div>
										</div>
									</div>
								</div>
								<div id=" new-provider" style="display: none;">
									<div class="form-group">
										<label for="provider-name" class="col-sm-2 control-label">Name</label>

										<div class="col-sm-10">
											<input type="text" class="form-control" id="provider-name" placeholder="">
										</div>
									</div>
									<div class="form-group">
										<label for="provider-name" class="col-sm-2 control-label">Name</label>

										<div class="col-sm-10">
											<input type="text" class="form-control" id="provider-name" placeholder="">
										</div>
									</div>
									<div class="form-group">
										<label for="provider-name" class="col-sm-2 control-label">Name</label>

										<div class="col-sm-10">
											<input type="text" class="form-control" id="provider-name" placeholder="">
										</div>
									</div>
									<div class="form-group">
										<label for="provider-name" class="col-sm-2 control-label">Name</label>

										<div class="col-sm-10">
											<input type="text" class="form-control" id="provider-name" placeholder="">
										</div>
									</div>
									<hr />
									<div class="form-group">
										<div class="form-buttons">
											<button id="add-provider-cancel" type="button" class="btn btn-danger">Cancel</button>
											<button id="add-provider-save" type="button" class="btn btn-warning">Save</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section id="call-settings">
			<div class="panel-group" id="call-settings-group">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#call-settings-group" href="#session-form-body">Call Settings</a>
						</h4>
					</div>
					<div id="session-form-body" class="panel-collapse collapse in">
						<div class="panel-body">
							<form class="form-horizontal" id="call-settings-form">
								<div class="form-group">
									<label for="request-uri" class="col-sm-2 control-label">Resource</label>

									<div class="col-sm-10">
										<div class="input-group">
											<span id="request-server" class="input-group-addon muted">https://*.cloud.dreamfactory.com</span>

											<input type="text"
												   class="form-control"
												   id="request-uri"
												   value="<?php echo $_defaultUrl; ?>"
												   placeholder="The request URI (i.e. /system/user)">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label for="request-method" class="col-sm-2 control-label">Method</label>

									<div class="col-sm-4">
										<select class="form-control" id="request-method">
											<option value="GET">GET</option>
											<option value="POST">POST</option>
											<option value="PUT">PUT</option>
											<option value="PATCH">PATCH</option>
											<option value="MERGE">MERGE</option>
											<option value="DELETE">DELETE</option>
											<option value="OPTIONS">OPTIONS</option>
											<option value="COPY">COPY</option>
										</select>
									</div>
									<label for="request-app" class="col-sm-2 control-label">App/API Key</label>

									<div class="col-sm-4">
										<select class="form-control" id="request-app">
											<optgroup label="Built-In">
												<option value="admin">admin</option>
												<option value="launchpad">launchpad</option>
											</optgroup>
											<optgroup label="Available">
												<?php echo $_apps; ?>
											</optgroup>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="request-body" class="col-sm-2 control-label">Body</label>

									<div class="col-sm-10">
										<textarea id="request-body" rows="2" class="form-control"></textarea>

										<p class="help-block">Must be valid JSON</p>
									</div>
								</div>
								<hr />
								<div class="form-group">
									<div class="form-buttons">
										<button id="reset-request" type="button" class="btn btn-danger">Reset</button>
										<button id="send-request" type="button" class="btn btn-warning">Send Request</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section id="provider-results">
			<div class="panel-group" id="call-results-group">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a name="provider-results" data-toggle="collapse" data-parent="#call-results-group" href="#call-results-body">Call Results
							</a>
							<span id="request-elapsed"></span>
							<span id="loading-indicator" class="pull-right"><i class="fa fa-spinner"></i></span>
						</h4>
					</div>
					<div id="call-results-body" class="panel-collapse collapse in">
						<div class="panel-body">
							<div id="example-code">
								<small>Ready</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<?php require_once( 'views/_footer.php' ); ?>

<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
<script src="//google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
<script src="js/app.jquery.js"></script>
<script>
//	This needs to be last because _options is defined in app.jquery.js... lame, I know...
_options.baseUrl = '<?php echo $_dspUrl; ?>';
_options.providers = <?php echo json_encode( $_providerCache ); ?>;
_options.APPLICATION_NAME = '<?php echo APPLICATION_NAME; ?>';
</script>
</body>
</html>
