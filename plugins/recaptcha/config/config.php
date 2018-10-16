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
$config['public_key']  = '6LdWT3UUAAAAAAimyb6fpUaBg0Fdcv5rmYVRZ86w';
$config['private_key'] = '6LdWT3UUAAAAAOJvXDaGx7wOgwiRrDhsRguRehgj';

$config['use_ssl'] = true; // запрос капчи с https-сервера

return $config;