<?php

define( 'LS_VERSION', '1.1.0' );

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

header( 'Content-Type: text/html; charset=utf-8' );
header( 'X-Powered-By: LiveStreet CMS' );

// vhost:   fastcgi_param   LIVESTREET_INSTALL_PATH /var/www/imaginaria/;
// $LIVESTREET_PATH_INSTALL = getenv( 'LIVESTREET_INSTALL_PATH') ?: dirname(__DIR__, 2);

define( 'LIVESTREET_PATH_INSTALL', dirname( __DIR__, 1 ) );
define( 'LIVESTREET_PATH_WWW', LIVESTREET_PATH_INSTALL . '/www' );
define( 'LIVESTREET_PATH_ENGINE', LIVESTREET_PATH_INSTALL . '/www/engine');

require_once __DIR__ . '/loader.php';

// Заводим двигатель
require_once(LIVESTREET_PATH_WWW . "/engine/classes/Engine.class.php");

require_once LIVESTREET_PATH_WWW . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$app_instance = bin2hex( random_bytes( 8 ) );
$DATETIME_YMD = (new \DateTime())->format( 'Y-m-d' );
$MONOLOG_STATS_FILE = Config::Get( "path.root.server" )."/logs/{$DATETIME_YMD}__stat.log";
$MONOLOG_ERROR_FILE = Config::Get( "path.root.server" )."/logs/{$DATETIME_YMD}__error.log";
$MONOLOG_ERROR_MYSQL = Config::Get( "path.root.server" )."/logs/{$DATETIME_YMD}__mysql.log";

$LOGGER = new Logger( "imaginaria.{$app_instance}" );
$LOGGER->pushHandler( new StreamHandler( $MONOLOG_STATS_FILE, Logger::DEBUG ) );
$LOGGER->pushHandler( new StreamHandler( $MONOLOG_ERROR_FILE, Logger::ERROR, false ) );


$oProfiler = ProfilerSimple::getInstance( Config::Get( 'path.root.project' ).'/logs/'.Config::Get( 'sys.logs.profiler_file' ), Config::Get( 'sys.logs.profiler' ) );
$iTimeId = $oProfiler->Start( 'full_time' );

$oRouter = Router::getInstance();
$oRouter->Exec();

$oProfiler->Stop( $iTimeId );

$LOGGER->notice( "Usage:", [
    round( microtime( true ) - $_SERVER[ 'REQUEST_TIME' ], 3 ),
    memory_get_usage(),
    $_SERVER[ 'HTTP_HOST' ].$_SERVER[ 'REQUEST_URI' ],
] );