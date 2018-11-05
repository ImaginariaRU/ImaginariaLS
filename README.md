WTF
===

Здесь хранятся сырцы текущей версии имажинарии на базе движка Livestreet 1.0.3

Лицензия GPL.

Из репозитория исключен конфигурационный файл `config/config.local.php`

В данный момент код в репозитории очень-очень `legacy`.

Install
=======

git clone https://github.com/ImaginariaRU/ImaginariaLS.git .
git submodule init
git submodule update
composer install

cp /srv/Imaginaria.Config/config.local.php /path/to/config/config.local.php
cp /srv/Imaginaria.Config/plugins.dat /path/to/plugins/plugins.dat




