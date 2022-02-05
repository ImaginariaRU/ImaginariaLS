<?php
/**
 * User: Karel Wintersky <karel.wintersky@gmail.com>
 * Date: 12.11.2018, time: 8:01
 */

if (php_sapi_name() !== 'cli') die('Can be launched only from console');
 
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

$dbname = Config::Get('db.params.dbname');
$dbuser = Config::Get('db.params.user');
$dbpass = Config::Get('db.params.pass');

$dsl = "mysql:host=localhost;port=3306;dbname={$dbname}";
$dbh = new \PDO($dsl, $dbuser, $dbpass);
$dbh->exec("SET NAMES UTF8;");
$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

// ----

$pattern = '"(http:\/\/imaginaria\.ru\/redirect\/([\w=\/]*))"';

$query_select = "SELECT comment_id, comment_text FROM `ls_comment` WHERE comment_text LIKE '%redirect%'";

$query_update = "UPDATE ls_comment SET comment_text = :comment_text WHERE comment_id = :comment_id";

// ----

$sth_select_all = $dbh->query($query_select);

while ($row = $sth_select_all->fetch()) {
    $found_redirects = null;

    $new_string = preg_replace_callback($pattern, function(array $matches){
        return base64_decode($matches[2]);
    }, $row['comment_text'], -1, $count);

    $sth_update = $dbh->prepare($query_update);
    $sth_update->execute([
        'comment_text'  =>  $new_string,
        'comment_id'    =>  $row['comment_id']
    ]);

    echo "Comment {$row['comment_id']} converted <br>" . PHP_EOL;
}