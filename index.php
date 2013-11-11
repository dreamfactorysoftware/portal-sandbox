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

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
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

				<div class="pull-left"><a href="#" class="navbar-brand df-title">DreamFactory Oasys</a><br />
					<small>Example Code</small>
				</div>
			</div>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="themes">Examples<b class="caret"></b></a>

					<ul class="dropdown-menu">
						<li class="dropdown-header">HTML</li>
						<li><a href="#" class="example-code" data-provider="github">GitHub</a></li>
						<li><a href="#" class="example-code" data-provider="facebook">Facebook</a></li>
						<li><a href="#" class="example-code" data-provider="salesforce">Salesforce</a></li>
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
			</ul>
		</div>
	</nav>
	<div class="container">
		<h3>Runtime Settings</h3>

		<form class="form-horizontal" id="session-form">

			<div class="form-group">
				<label for="dsp-name" class="col-sm-2 control-label">DSP Name</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="dsp-name" placeholder="Enter your DSP's name">
				</div>
			</div>

			<div class="form-group">
				<label for="request-body" class="col-sm-2 control-label">Body</label>
				<div class="col-sm-6">
					<textarea id="request-body" rows="5" class="form-control"></textarea>
				</div>
			</div>

			<div class="form-group">
				<label for="request-verb" class="col-sm-2 control-label">Verb</label>
				<div class="col-sm-2">
					<select class="form-control" id="request-verb">
						<option>GET</option>
						<option>POST</option>
						<option>PUT</option>
						<option>PATCH</option>
						<option>MERGE</option>
						<option>DELETE</option>
						<option>OPTIONS</option>
						<option>COPY</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label for="request-headers" class="col-sm-2 control-label">Headers</label>
				<div class="col-sm-6">
					<textarea id="request-headers" rows="5" class="form-control"></textarea>
				</div>
			</div>

		</form>

		<div id="example-code">

		</div>
	</div>
</div>

<div id="footer">
	<div class="container">
		<div class="social-links pull-right">
			<ul class="list-inline">
				<li>
					<a target="_blank" href="http://facebook.com/dreamfactory"><i class="fa fa-facebook-square fa-2x"></i></a>
				</li>
				<li>
					<a target="_blank" href="https://twitter.com/dfsoftwareinc"><i class="fa fa-twitter-square fa-2x"></i></a>
				</li>
				<li>
					<a target="_blank" href="https://github.com/dreamfactorysoftware"><i class="fa fa-github-square fa-2x"></i></a>
				</li>
			</ul>
		</div>
		<div class="clearfix"></div>
		<p>
			<span class="pull-left hidden-xs hidden-sm">Licensed under the <a
					target="_blank" href="http://www.apache.org/licenses/LICENSE-2.0">Apache License v2.0
				</a></span>
			<span class="pull-right">&copy; DreamFactory Software, Inc. <?php echo date( 'Y' ); ?>. All Rights Reserved.</span>
		</p>
	</div>
</div>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
<script src="js/jquery.multientry.js"></script>
<script src="js/oasys.jquery.js"></script>
</body>
</html>
