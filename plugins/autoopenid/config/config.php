<?php

/**
 * Общие настройки
 */
$config['time_key_limit'] = 60 * 60 * 1; // in seconds, время актуальности временных данных при авторизации
$config['mail_required'] = true; // обязательный ввод e-mail
$config['auto_registration'] = true; // пытаться автоматически зарегистрировать пользователя по его имени


/**
 * Используемые сервисы для авторизации
 */
$config['services'] = array(
    // https://developers.facebook.com/apps/
    // redirect/callback url = http://ваш-сайт/login/autoopenid/oauth/facebook/
    'facebook'      => array(
        'client_id'     => '', // App ID
        'client_secret' => '', // App Secret
        'scope'         => array('email', 'user_about_me'),
    ),
    // https://vk.com/editapp?act=create
    // redirect/callback url = http://ваш-сайт/login/autoopenid/oauth/vkontakte/
    'vkontakte'     => array(
        'client_id'     => '3124020', // ID приложения
        'client_secret' => 'WOIfVPelUF709zdpb96R', // Защищенный ключ
        'scope'         => array('email'),
    ),
    // https://apps.twitter.com/app/new
    // redirect/callback url = http://ваш-сайт/login/autoopenid/oauth/twitter/
    'twitter'       => array(
        'client_id'     => '', // API key
        'client_secret' => '', // API secret
        'scope'         => array(),
    ),
    // https://console.developers.google.com/project
    // redirect/callback url = http://ваш-сайт/login/autoopenid/oauth/google/
    'google'        => array(
        'client_id'     => '', // Client ID
        'client_secret' => '', // Client secret
        'scope'         => array('userinfo_email', 'userinfo_profile'),
    ),
    // https://oauth.yandex.ru/client/new
    // redirect/callback url = http://ваш-сайт/login/autoopenid/oauth/yandex/
    'yandex'        => array(
        'client_id'     => '', // ID
        'client_secret' => '', // Пароль
        'scope'         => array(),
    ),
    // http://api.mail.ru/sites/my/add/
    // redirect/callback url = http://ваш-сайт/login/autoopenid/oauth/mailru/
    'mailru'        => array(
        'client_id'     => '', // ID
        'client_secret' => '', // Секретный ключ
        'scope'         => array(),
    ),
    // http://apiok.ru/wiki/pages/viewpage.action?pageId=42476486
    // redirect/callback url = http://ваш-сайт/login/autoopenid/oauth/odnoklassniki/
    'odnoklassniki' => array(
        'client_id'      => '', // Application ID
        'client_secret'  => '', // Секретный ключ приложения
        'request_params' => array(
            'application_key' => '' // Публичный ключ приложения
        ),
    ),
    // https://www.instagram.com/developer/clients/register/
    // redirect/callback url = http://ваш-сайт/login/autoopenid/oauth/instagram/
    'instagram' => array(
        'client_id'      => '', // Application ID
        'client_secret'  => '', // Секретный ключ приложения
        'scope'         => array('basic'),
    ),
);


/**
 * Используемые таблицы БД
 */
$config['$root$']['db']['table']['autoopenid_main_user'] = '___db.table.prefix___autoopenid_user';
$config['$root$']['db']['table']['autoopenid_main_tmp'] = '___db.table.prefix___autoopenid_tmp';
$config['$root$']['db']['table']['autoopenid_main_openid_old'] = '___db.table.prefix___openid';

/**
 * Роутинг
 */
$config['$root$']['router']['page']['autoopenid_login'] = 'PluginAutoopenid_ActionLogin';

return $config;