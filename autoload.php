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

/**
 * Main entry point/bootstrap for PHP applications
 */
//	Base directory of DSP core
$_appBaseDir = dirname( __DIR__ );
$_dspBaseDir = dirname( $_appBaseDir );

//	Load up composer...
$_autoloader = require_once( $_dspBaseDir . '/vendor/autoload.php' );

//	Turn on debugging
\Kisma::setDebug( true );

//	Load up Yii
require_once $_dspBaseDir . '/vendor/dreamfactory/yii/framework/yii.php';

if ( \Kisma::getDebug() )
{
	//	Yii debug settings
	defined( 'YII_DEBUG' ) or define( 'YII_DEBUG', true );
	defined( 'YII_TRACE_LEVEL' ) or define( 'YII_TRACE_LEVEL', 3 );
}

//	Create the application but do not run...
DreamFactory\Yii\Utility\Pii::run(
							$_appBaseDir,
							$_autoloader,
							'DreamFactory\\Platform\\Yii\\Components\\PlatformWebApplication',
							null,
							false,
							false
);
