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

$config = array();

$config['ignore_post_me_pm'] = true; //Запрет получения личных сообщений от пользователя
$config['ignore_hide_me_comments'] = true; //Запрет показа комментариев пользователя (я не вижу его комментарии вообще)
$config['ignore_reply_my_comment'] = true; //Запрет пользователю отвечать на мой комментарий
$config['ignore_post_comment_my_topic'] = true; //Запрет пользователю комментировать мои топики
$config['ignore_post_my_wall'] = true; //Запрет пользователю писать на моей стене
$config['ignore_hide_me_topics'] = true; //Запрет показа топиков пользователя (в блоке прямой эфир)

$config['ignore_target_reply_my_comment'] = true; //Запрет пользователю отвечать на мой комментарий в выбранном топике
$config['ignore_target_post_comment_my_topic'] = true; //Запрет пользователю комментировать выбранный топик

$config['allow_ignore_comment'] = 8; // Показать игнорируемый комментарий если его рейтинг равен или больше заданного числа

/*-------------------- СТРОЧКИ НИЖЕ НЕ ТРОГАТЬ! --------------------*/

$config['$root$']['router']['page']['ignore'] = 'PluginIgnore_ActionIgnore';

$config['table']['ignore']  = '___db.table.prefix___ignore';
$config['table']['ignore_target']  = '___db.table.prefix___ignore_target';

if (Config::Get('db.table.prefix') == '')
    $config['table']['ignore']  = '`___db.table.prefix___ignore`';

return $config;