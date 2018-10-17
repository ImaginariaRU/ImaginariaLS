<?php
/* ---------------------------------------------------------------------------
 * @Plugin Name: reCAPTCHA
 * @Description: Replaces the standard captcha for reCAPTCHA
 * @Author URI: http://livestreet.net
 * @LiveStreet Version: 1.0.3
 * @Plugin Version:	4.0.0
 * ----------------------------------------------------------------------------
*/

$config = array();

// Ключи можно получить здесь https://www.google.com/recaptcha/admin/create
// По техническим причинам ключи хранятся в `config.local.php`

$config = Config::Get('recaptcha');

$config['use_ssl'] = true; // запрос капчи с https-сервера

return $config;