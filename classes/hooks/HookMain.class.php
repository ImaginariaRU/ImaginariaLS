<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
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

/**
 * Регистрация основных хуков
 *
 * @package hooks
 * @since 1.0
 */
class HookMain extends Hook
{
    /**
     * Регистрируем хуки
     */
    public function RegisterHook()
    {
        $this->AddHook('init_action', 'InitAction', __CLASS__, 1000);
    }

    /**
     * Обработка хука инициализации экшенов
     */
    public function InitAction()
    {
        /**
         * Проверка на закрытый режим
         */
        $oUserCurrent = $this->User_GetUserCurrent();
        if (!$oUserCurrent and Config::Get('general.close') and Router::GetAction() != 'registration' and Router::GetAction() != 'login') {
            Router::Action('login');
        }
        /**
         * Запуск обработки сборщика
         */
        $this->Ls_SenderRun();
    }
}

