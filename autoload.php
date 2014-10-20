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
use Kisma\Core\Exceptions\FileSystemException;

/**
 * Main entry point/bootstrap for DSP apps using PHP
 *
 * NOTE: This index file piggy-backs on the DSP's installation
 * therefore a running DSP is required to use this app.
 */
class AppLoader
{
    /**
     * Locates the installed DSP's base directory
     *
     * @param string $startPath
     *
     * @throws FileSystemException
     * @return string|bool The absolute path to the platform installation. False if not found
     */
    public static function locatePlatformBasePath( $startPath = __DIR__ )
    {
        //  Start path given or this file's directory
        $_path = $startPath ?: __DIR__;

        while ( true )
        {
            $_path = rtrim( $_path, ' /' );

            if ( file_exists( $_path . '/.dreamfactory.php' ) && is_dir( $_path . '/storage/.private' ) )
            {
                break;
            }

            //  Too low, go up a level
            $_path = dirname( $_path );

            //	If we get to the root, ain't no DSP...
            if ( '/' == $_path || empty( $_path ) )
            {
                return false;
            }
        }

        return $_path;
    }

}

if ( false === ( $_dspBase = AppLoader::locatePlatformBasePath() ) )
{
    header( 'Location: /?error=Unable+to+locate+platform+base+path.' );
    die();
}

$_autoloader = require( $_dspBase . '/vendor/autoload.php' );
require $_dspBase . '/vendor/dreamfactory/yii/framework/yiilite.php';

//  Comment both lines to disable debug mode
ini_set( 'display_errors', 1 );
defined( 'YII_DEBUG' ) or define( 'YII_DEBUG', true );

//	Create the application but do not run...
Pii::run(
    __DIR__ . '/src',
    $_autoloader,
    'DreamFactory\\Platform\\Yii\\Components\\PlatformWebApplication',
    $_dspBase . '/config/web.php',
    false,
    false
);
