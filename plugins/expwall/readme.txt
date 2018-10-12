LiveStreet (v1.x)
Plugin Expanded wall (v.0.3)
Copyright © 2011 Bishovec Nikolay

Plugin Page: http://netlanc.net
CMS Page http://livestreetcms.com
Contact e-mail: netlanc@yandex.ru

ЛИЦЕНЗИЯ
Плагин распространяется  по лицензии Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0) (http://creativecommons.org/licenses/by-sa/3.0/deed.ru)
Обязательным условием использования плагина является наличие активной ссылки на сайт разработчика Netlanc (http://netlanc.net/),
которая уже прописана в плагине. Отключить ссылку можно за донейт от 5уе.

УСТАНОВКА:
1. Скопировать плагин в каталог /plugins/
2. Через панель управления плагинами (/admin/plugins/) запустить его активацию.
3. Выполнить настройки плагина в файле /plugins/expwall/config/config.php
4. Для работы стены в проффиле пользователя, в файл шаблона \templates\skin\_Ваш_шаблон_\actions\ActionProfile\whois.tpl
после кода

		{hook run='profile_whois_item' oUserProfile=$oUserProfile}
	</div>
</div>

добавить хук

{hook run='profile_whois_end' oUserProfile=$oUserProfile}

ОБНОВЛЕНИЯ
v0.3
- добавлен редактор в форму сообщения стенок.
v0.2.1
- адаптированы шаблоны developer и social.
v0.2
- поправлено имя хука для главного меню;
- добавлена стена в профиль пользователя.

ВАШЕ УЧАСТИЕ

Если Вы хотите поддержать написание бесплатных плагинов и можете помочь материально:

Webmoney
Z387210433696
R378645698316

http://livestreet.ru/profile/netlanc/
http://netlanc.net/

СПОНСОРЫ
Спонсор v0.2 - http://mf7.me