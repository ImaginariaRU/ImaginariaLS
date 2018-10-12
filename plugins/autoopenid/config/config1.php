<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*   Copyright © 2011 Valentin Mendelev
*	vmnotathome@gmail.com
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

/*
Режим авторизации
1 -- автоматическое получение данных (логин, пол, аватарка) от провайдеров и создание пользователя
0 -- классический режим (пользователь в системе создается после проверки логина и адреса электронной почты посетителем)
*/

$config['auth_type'] = 1;

$config['use_curl'] = false;
$config["renew_tokens"] = true; //сохранение access token при каждой авторизации пользователя (используется лпагином Reposter)

/**
 * Используемые таблицы БД
 */
$config['table']['openid'] = '___db.table.prefix___openid';
$config['table']['openid_tmp'] = '___db.table.prefix___openid_tmp';


/**
 * Настраиваем роутинг
 */
Config::Set('router.page.openid_login', 'PluginAutoopenid_ActionLogin');
Config::Set('router.page.openid_settings', 'PluginAutoopenid_ActionSettings');

/**
 * Общие настройки
 */
$config['file_store']   = '___sys.cache.dir___php_consumer_livestreet'; // каталог для хранения данных OpenID
$config['time_key_limit']   = 60*60*1; // in seconds, время актуальности временных данных при авторизации
$config['mail_required']   = false; // обязательный ввод e-mail
$config['mark_as_activated']   = true; // считать пользователя активировавшим свой аккаунт
$config['buggy_gmp']   = false; // для обхода проблемы с шибкой "Bad signature" на некоторых серверах

/**
 * Настройки авторизации ВКонтакте
 */
$config['vk']['id']   = '3124020'; // ID приложения
$config['vk']['secure_key']   = 'WOIfVPelUF709zdpb96R'; // Защищенный ключ приложения
$config['vk']['transport_path']   = '/plugins/autoopenid/include/xd_receiver.html'; // Путь от корня сайта до файла транспорта
$config['vk']["scope"] = "friends"; //мало прав
//$config['vk']["scope"] = "friends,wall,offline,photos,notes"; //много прав. Для плагина Reposter

/**
 * Настройки Facebook Application
 */
$config['fb']['id']   = ''; // Application ID
$config['fb']['secret']   = ''; // Application Secret
$config['fb']["scope"] = "user_birthday,user_website,email,user_about_me"; // мало пправ
//$config['fb']["scope"] = "user_birthday,user_website,email,offline_access,user_about_me,friends_about_me,publish_stream"; // иного прав. Для плагина Reposter

/**
 * Настройки Twitter Application
 */
$config['twitter']['token']   = ''; // Consumer key
$config['twitter']['token_secret']   = ''; // Consumer secret
$config['stop_referrer'] = array('login', 'registration');

Config::Set('block.rule_openid' , array(
	'action'  => array( "openid_settings" ),
	'blocks'  => array( 'right' => array('actions/ActionProfile/sidebar.tpl') ),
));

return $config;
?>