<?php

/* -------------------------------------------------------
 *
 *   LiveStreet (v1.0)
 *   Plugin Edit comment (v.0.3)
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

$config['edit_author'] = true; // разрешение на редактирование комментария автром комментария
$config['limit_edit_time'] = 60 * 60; // время (в секундах) жизни возможности редактировать коментарий автором после публикации (админы могут редактировать всегда) если 0 то отключено
$config['children_isset'] = true; // запрет на редактирование комментария автором если у него есть потомки даже если времы жизни ВР не истекло 


Config::Set('router.page.ec_ajax', 'PluginEc_ActionAjax');

return $config;
?>
