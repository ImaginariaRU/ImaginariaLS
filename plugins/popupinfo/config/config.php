<?php
/**
 * Popupinfo plugin
 *
 * @copyright Serge Pustovit (PSNet), 2008 - 2014
 * @author    Serge Pustovit (PSNet) <light.feel@gmail.com>
 *
 * @link      http://psnet.lookformp3.net
 * @link      http://livestreet.ru/profile/PSNet/
 * @link      https://catalog.livestreetcms.com/profile/PSNet/
 * @link      http://livestreetguide.com/developer/PSNet/
 */

$config = array();

// Не показывать на длинные урлы попап окна
// например, не показывать попап на урл /profile/admin/favourites/
// а только на урл вида /profile/admin/
$config['Leave_Long_Links_Alone'] = true;

// Количество пользователей для показа на странице блога в попап окне
// На полный список пользователей будет вести ссылка
$config['Blog_User_On_Page'] = 7;

// Показывать попап окна только зарегистрированным пользователям
$config['Only_Registered_Users_Can_See_Info_Tips'] = false;

// Задержка перед показом подсказки (посылкой запроса к серверу).
// Время на которое курсор на ссылкой должен остановится чтобы всплыла подсказка
// Чтобы при случайном "пролете" курсора мыши над ссылкой не посылать ненужные запросы к серверу
$config['Panel_Showing_Delay'] = 600;	// мс

// Время, в течении которого после последнего обращения к сайту считается что пользователь онлайн
$config['Time_To_Stay_Online'] = 900;	// сек

// ---

$config['url'] = 'popupinfo';
$config['$root$']['router']['page'][$config['url']] = 'PluginPopupinfo_ActionPopupinfo';

return $config;

?>