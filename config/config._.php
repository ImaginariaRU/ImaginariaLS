<?php
/** Переопределение конфигов для сервера */

$config['db']['params']['host'] = 'localhost';
$config['db']['params']['port'] = '3306';
$config['db']['params']['user'] = '';
$config['db']['params']['pass'] = '';
$config['db']['params']['type']   = 'mysqli';
$config['db']['params']['dbname'] = '';
$config['db']['table']['prefix'] = 'ls_';
$config['db']['tables']['engine'] = 'InnoDB';

$config['path']['root']['web'] = 'http://'.$_SERVER['HTTP_HOST'];
$config['path']['root']['server'] = '/path/to';
$config['path']['offset_request_url'] = '0';

$config['view']['name'] = '';
$config['view']['description'] = '';
$config['view']['keywords'] = '';
$config['view']['skin'] = 'synio';

$config['sys']['mail']['from_email'] = 'noreply@example.com';
$config['sys']['mail']['from_name'] = 'Сообщение с сайта';

$config['general']['close'] = false;
$config['general']['reg']['activation'] = true;
$config['general']['reg']['invite'] = false;

$config['lang']['current'] = 'russian';
$config['lang']['default'] = 'russian';

$config['module']['talk']['period']     = 1;

$config['sys']['cookie']['time'] = 60 * 60 * 24 * 3;        // время жизни куки когда пользователь остается залогиненым на сайте, 3 дня

$config['module']['topic']['max_length'] = 50000;           // Максимальное количество символов в одном топике
$config['module']['topic']['allow_empty_tags'] = true;      // Разрешать или нет не заполнять теги
$config['module']['user']['friend_on_profile']    = 30;     // Ограничение на вывод числа друзей пользователя на странице его профиля
$config['module']['user']['usernote_text_max'] = 500; 	    // Максимальный размер заметки о пользователе
$config['module']['image']['default']['watermark_use']      = true;
$config['module']['image']['default']['watermark_text']     = '(c) Site';
$config['module']['wall']['text_max'] = 500;		        // Ограничение на максимальное количество символов в одном сообщении на стене

$config['compress']['css']['merge'] = true;                 // указывает на необходимость слияния файлов по указанным блокам.
$config['compress']['js']['use']    = false;                // указывает на необходимость компрессии файлов. Компрессия используется только в активированном режиме слияния файлов.

$config['module']['ls']['use_counter'] = false;	            // Использование счетчика GA

/**
 * Переопределение настроек кэширования
 */
$config['sys']['cache']['use']          = false;                                        // использовать кеширование или нет
$config['sys']['cache']['dir']          = '___path.root.server___/.cache/filecache/';
$config['path']['smarty']['compiled']   = '___path.root.server___/.cache/compiled';     // Smarty compiled template parts
$config['path']['smarty']['cache']      = '___path.root.server___/.cache/assets';       // (скрипты и CSS)

$config['module']['rss']['sufficient_rating'] = 3; // необходимый и достаточный рейтинг для попадания топика в RSS-ленту

// CONFIG for TELEGRAM
$config['telegram'] = [
    'api_key'   =>  '',
    'bot_name'  =>  '',
    'chat_id'   =>  ''
];

// Config for Monolog
$config['monolog'] = [
    'channel'   =>  'imaginaria',
    'logfile'   =>  '$/imaginaria.log'
];

// Config for ReCaptcha
$config['recaptcha'] = [
    'public_key'    =>  '',
    'private_key'   =>  ''
];


return $config;