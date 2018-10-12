<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Сделано руками @ Сергей Сарафанов (sersar)
*   ICQ: 172440790 | E-mail: sersar@ukr.net
*
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

if (!class_exists('Config'))
    die('Hacking attempt!');

Config::Set('router.page.ignore', 'PluginIgnore_ActionIgnore');

Config::Set('db.table.ignore', '___db.table.prefix___ignore');

$config = array();
$config['ignore_post_me_pm'] = true; //Запрет получения личных сообщений от пользователя
$config['ignore_hide_me_comments'] = true; //Запрет показа комментариев пользователя (я не вижу его комментарии вообще)
$config['ignore_reply_my_comment'] = false; //Запрет пользователю отвечать на мой комментарий
$config['ignore_post_comment_my_topic'] = true; //Запрет пользователю комментировать мои топики
$config['ignore_post_my_wall'] = true; //Запрет пользователю писать на моей стене
return $config;
?>