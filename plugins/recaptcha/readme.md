1. Установить из корня движка `composer require google/recaptcha`

2. Добавить в index.php:

`require_once 'vendor/autoload.php';`

3. Получить ключи https://www.google.com/recaptcha/admin#list

4. Вписать их в `config.php` или, лучше, в глобальный конфиг:

```
// Config for ReCaptcha
$config['recaptcha'] = [
    'public_key'    =>  '',
    'private_key'   =>  ''
];

```

5. Активировать плагин
