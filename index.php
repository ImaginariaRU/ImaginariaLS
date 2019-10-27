<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');
header('X-Powered-By: LiveStreet CMS');

$LIVESTREET_INSTALL_PATH = getenv( 'LIVESTREET_INSTALL_PATH');
if (false === $LIVESTREET_INSTALL_PATH) {
    $LIVESTREET_INSTALL_PATH = dirname(__FILE__);
}

set_include_path(get_include_path().PATH_SEPARATOR.$LIVESTREET_INSTALL_PATH);
chdir($LIVESTREET_INSTALL_PATH);

// Получаем объект конфигурации
require_once("./config/loader.php");

/**
 * Заводим двигатель
 */
require_once(Config::Get('path.root.engine')."/classes/Engine.class.php");

require_once 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$app_instance = bin2hex(random_bytes(8));
$DATETIME_YMD = (new \DateTime())->format('Y-m-d');
$MONOLOG_STATS_FILE = Config::Get("path.root.server") . "/logs/{$DATETIME_YMD}__stat.log";
$MONOLOG_ERROR_FILE = Config::Get("path.root.server") . "/logs/{$DATETIME_YMD}__error.log";
$MONOLOG_ERROR_MYSQL = Config::Get("path.root.server") . "/logs/{$DATETIME_YMD}__mysql.log";

$LOGGER = new Logger("imaginaria.{$app_instance}" );
$LOGGER->pushHandler(new StreamHandler($MONOLOG_STATS_FILE, Logger::DEBUG));
$LOGGER->pushHandler(new StreamHandler($MONOLOG_ERROR_FILE, Logger::ERROR, false));


$oProfiler=ProfilerSimple::getInstance(Config::Get('path.root.server').'/logs/'.Config::Get('sys.logs.profiler_file'),Config::Get('sys.logs.profiler'));
$iTimeId=$oProfiler->Start('full_time');

$oRouter=Router::getInstance();
$oRouter->Exec();

$oProfiler->Stop($iTimeId);

$LOGGER->notice("Usage:", [
    round(microtime(true) - $_SERVER['REQUEST_TIME'], 3),
    memory_get_usage(),
    $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
]);