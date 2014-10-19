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
use DreamFactory\Yii\Utility\Pii;

/**
 * Main entry point/bootstrap for PHP applications
 */
if ( !class_exists( '\\Yii', false ) )
{
    $_dspBase = realpath( __DIR__ );

    while ( true )
    {
        if ( file_exists( $_dspBase . '/.dreamfactory.php' ) && is_dir( $_dspBase . '/storage/.private' ) )
        {
            break;
        }

        $_dspBase = dirname( $_dspBase );

        if ( empty( $_dspBase ) || $_dspBase == '.' || $_dspBase == '/' )
        {
            throw new Exception( 'Unable to locate DSP installation.', 500 );
        }
    }

    //	Load up composer...
    $_autoloader = require_once( $_dspBase . '/vendor/autoload.php' );

    if ( is_object( $_autoloader ) )
    {
        \Kisma::set( 'app.autoloader', $_autoloader );
    }
    else
    {
        $_autoloader = \Kisma::get( 'app.autoloader' );
    }

    //	Load up Yii
    require_once $_dspBase . '/vendor/dreamfactory/yii/framework/yiilite.php';

    //  Comment both lines to disable debug mode
    ini_set( 'display_errors', 1 );
    defined( 'YII_DEBUG' ) or define( 'YII_DEBUG', true );

    if ( !\Yii::app() )
    {
        //	Create the application but do not run...
        Pii::run(
            __DIR__ . '/src',
            is_object( $_autoloader ) ? $_autoloader : null,
            'DreamFactory\\Platform\\Yii\\Components\\PlatformWebApplication',
            $_dspBase . '/config/web.php',
            false,
            false
        );
    }
}
