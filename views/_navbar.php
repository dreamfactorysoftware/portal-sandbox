<?php
/**
 * This file is part of the DreamFactory Oasys(tm) Sample App
 * Copyright 2013 DreamFactory Software, Inc. {@email support@dreamfactory.com}
 *
 * DreamFactory Oasys(tm) {@link http://github.com/dreamfactorysoftware/oasys}
 * DreamFactory Oasys(tm) Sample App {@link http://github.com/dreamfactorysoftware/oasys-examples}
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
?>
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
			<li>
				<a href="#" id="app-close" target="_blank">Close</a>
			</li>
		</ul>
	</div>
</nav>
