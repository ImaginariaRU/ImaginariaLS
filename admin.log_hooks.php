<?php
/**
 * User: Karel Wintersky <karel.wintersky@gmail.com>
 * Date: 13.11.2018, time: 0:44
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

require_once(Config::Get('path.root.engine')."/classes/Engine.class.php");

require_once 'vendor/autoload.php';

$oRouter=Router::getInstance();

$oRouter->Exec(true);