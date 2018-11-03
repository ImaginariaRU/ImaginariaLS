<?php
/**
 * Blacklist - проверка E-Mail пользователей на наличие в базах спамеров.
 *
 * Версия:    1.1.0
 * Автор:    Александр Вереник
 * Профиль:    http://livestreet.ru/profile/Wasja/
 * GitHub:    https://github.com/wasja1982/livestreet_blacklist
 *
 **/

class PluginBlacklist_HookCopyright extends Hook
{
    /**
     *
     */
    public function RegisterHook()
    {
        $this->AddHook('template_copyright', 'CopyrightLink', __CLASS__, -10000);
    }

    /**
     * @return string
     */
    public function CopyrightLink()
    {
        return '';
    }
}
