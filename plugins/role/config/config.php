<?php

/* -------------------------------------------------------
 *
 *   LiveStreet (v1.0)
 *   Plugin Role (v.0.6)
 *   Copyright © 2011 Bishovec Nikolay
 *
 * --------------------------------------------------------
 *
 *   Plugin Page: http://netlanc.net
 *   Contact e-mail: netlanc@yandex.ru
 *
  ---------------------------------------------------------
 */

$config = array();

$config['table']['role'] = '___db.table.prefix___role';
$config['table']['role_user'] = '___db.table.prefix___role_user';
$config['table']['role_users'] = '___db.table.prefix___role_users';
$config['table']['role_place_block'] = '___db.table.prefix___role_place_block';

$config['max_length_text'] = 500; // максимальная длинна в описании роли
$config['max_life_time'] = 1; // время жизни сессии аватара в днях

$config['admins_id'] = array('1','700'); // Id дминов которых запрещено удалять из списк админов

$config['edit_author'] = true; // разрешение на редактирование комментария автром комментария
$config['limit_edit_time'] = 60*10; // время (в секундах) жизни возможности редактировать коментарий автором после публикации (админы могут редактировать всегда) если 0 то отключено
$config['children_isset'] = true; // запрет на редактирование комментария автором если у него есть потомки даже если времы жизни ВР не истекло

$config['avatar_size'] = array(96, 64, 48, 24, 12, 0); // размеры для аватаров ролей

$config['auto_role'] = array(
    'exc_users_id' => array(1), // id пользователей которых стоит исключить из авторолей
);
// блок меню ролей
Config::Set('block.rule_role', array(
    'action' => array(
        'role', 'role_people'
    ),
    'blocks' => array(
        'right' => array(
            'role' => array('params' => array('plugin' => 'role'), 'priority' => 99),
        )
    ),
    'clear' => false,
));

Config::Set('path.uploads.role', '___path.uploads.root___/role');

Config::Set('router.page.role', 'PluginRole_ActionRole');
Config::Set('router.page.role_ajax', 'PluginRole_ActionAjax');

Config::Set('router.page.role_people', 'PluginRole_ActionPeople');

return $config;
?>
