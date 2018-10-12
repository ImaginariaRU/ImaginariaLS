<?php

/**
 * Регистрация хуков
 *
 */
class PluginAutoopenid_HookLogin extends Hook
{

    public function RegisterHook()
    {
        /**
         * Хук на инициализацию экшенов
         */
        $this->AddHook('init_action', 'InitAction', __CLASS__);
        /**
         * Хук на страницу авторизации
         */
        $this->AddHook('template_login_popup_begin', 'LoginTpl', __CLASS__);
        $this->AddHook('template_form_login_begin', 'LoginTpl', __CLASS__);
        /**
         * Хук на всплывающее окно авторизации
         */
        $this->AddHook('template_form_registration_begin', 'LoginTpl', __CLASS__);

        $this->AddHook('template_menu_settings_settings_item', 'NavSettings', __CLASS__);
    }

    /**
     * Отлавливаем нужные экшены и перенаправляем на экшены плагина
     *
     */
    public function InitAction()
    {
        /**
         * Подхватываем обработку URL вида /login/autoopenid/
         */
        if (Router::GetAction() == 'login' and Router::GetActionEvent() == 'autoopenid') {
            Router::Action('autoopenid_login', 'login', Router::GetParams());
        }
    }

    /**
     * Вставляем кнопку OpenID на форму авторизации
     *
     * @return unknown
     */
    public function LoginTpl()
    {
        $this->Viewer_Assign('aAutoopenidServicesAvailable', $this->PluginAutoopenid_Main_GetServicesAvailable());
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject_login.tpl');
    }

    public function NavSettings($aParams)
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject_settings.tpl');
    }
}