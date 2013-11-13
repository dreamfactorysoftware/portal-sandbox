<?php
use DreamFactory\Yii\Utility\Pii;

$_guest = Pii::guest();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Oasys Example Code</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<link rel="icon" type="image/png" href="img/apple-touch-icon.png">
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href="css/jquery.jscrollpane.min.css" rel="stylesheet" media="all" />
	<link type="text/css" href="css/jquery.jscrollpane.lozenge.css" rel="stylesheet" media="all" />
	<link rel="stylesheet" href="/css/jquery.multientry.css" media="all" />

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="//google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->    <!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>    <![endif]-->

	<link href="css/main.css" rel="stylesheet">
</head>
<body>

<div id="wrap">
	<nav class="navbar navbar-default navbar-inverse navbar-fixed-top df-header">
		<div class="navbar-header">
			<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="brand-wrap">
				<img src="img/logo-32x32.png" alt="" />

				<div class="pull-left">
					<a href="#" class="navbar-brand df-title">DreamFactory Oasys</a>
					<br />
					<small>Example Code</small>
				</div>
			</div>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<?php if ( $_guest )
				{ ?>
					<li class="active"><a href="#">Login</a></li>
				<?php }
				else
				{ ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="themes">Examples<b class="caret"></b></a>

						<ul class="dropdown-menu">
							<li class="dropdown-header">HTML</li>
							<li>
								<a href="#" class="example-code" data-provider="github">GitHub</a>
							</li>
							<li>
								<a href="#" class="example-code" data-provider="facebook">Facebook</a>
							</li>
							<li>
								<a href="#" class="example-code" data-provider="salesforce">Salesforce</a>
							</li>
						</ul>
					</li>

					<li>
						<a href="https://www.dreamfactory.com/developers/documentation" target="_blank">Docs</a>
					</li>
					<li>
						<a href="https://www.dreamfactory.com/developers/live_API" target="_blank">API</a>
					</li>
					<li>
						<a href="https://www.dreamfactory.com/developers/faq" target="_blank">FAQs</a>
					</li>
					<li>
						<a href="https://www.dreamfactory.com/developers/support" target="_blank">Support</a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</nav>

	<div class="container">

		<section id="call-settings">
			<div class="panel-group" id="call-settings-group">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#call-settings-group" href="#session-form-body">Call
								Settings</a>
						</h4>
					</div>
					<div id="session-form-body" class="panel-collapse collapse in">
						<div class="panel-body">

							<form class="form-horizontal" id="call-settings-form">
								<div class="form-group">
									<label for="request-uri" class="col-sm-2 control-label">Resource</label>

									<div class="col-sm-10">
										<input type="text" class="form-control" id="request-uri"
											   value="https://next.cloud.dreamfactory.com/rest/system/user"
											   placeholder="The request URI (i.e. /system/user)">

										<p class="help-block">Either an absolute or relative URL.</p>
									</div>
								</div>

								<div class="form-group">
									<label for="request-body" class="col-sm-2 control-label">Body</label>

									<div class="col-sm-10">
										<textarea id="request-body" rows="5" class="form-control"></textarea>

										<p class="help-block">Must be valid JSON</p>
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

									<label for="request-app" class="col-sm-2 control-label">Application</label>

									<div class="col-sm-4">
										<select class="form-control" id="request-app">
											<option value="admin">admin</option>
											<option value="launchpad">launchpad</option>
										</select>
									</div>
								</div>

								<div class="multientry" data-attribute="request-headers" data-name="request-headers"></div>

								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<button type="submit" class="btn btn-primary">Send Request</button>
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
							<a data-toggle="collapse" data-parent="#call-results-group" href="#call-results-body">Call Results</a>
						</h4>
					</div>
					<div id="call-results-body" class="panel-collapse collapse in">
						<div class="panel-body">
							<div id="example-code">No calls made</div>
						</div>
					</div>
				</div>
			</div>
		</section>

	</div>
</div>

<?php require_once( 'views/_footer.php' ); ?>

<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="js/mwheelintent.min.js"></script>
<script type="text/javascript" src="js/jquery.jscrollpane.min.js"></script>
<script src="js/jquery.multientry.js"></script>
<script src="js/oasys.jquery.js"></script>
</body>
</html>
