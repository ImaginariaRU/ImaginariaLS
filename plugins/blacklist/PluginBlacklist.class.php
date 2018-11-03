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

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

/**
 * Class PluginBlacklist
 */
class PluginBlacklist extends Plugin
{

    protected $aInherits = array(
        'entity' => array('ModuleUser_EntityUser'),
        'module' => array('ModuleUser'),
    );

    /**
     * @param $sMail
     * @return mixed
     */
    static function blackMail($sMail)
    {
        $oEngine = Engine::getInstance();
        return $oEngine->PluginBlacklist_ModuleBlacklist_blackMail($sMail);
    }

    /**
     * Активация плагина
     */
    public function Activate()
    {
        if (!$this->isTableExists('prefix_blacklist')) {
            $this->ExportSQL(dirname(__FILE__) . '/dump.sql');
        } elseif (!$this->isFieldExists('prefix_blacklist', 'service')) {
            $this->ExportSQL(dirname(__FILE__) . '/dump2.sql');
        }
        return true;
    }

    /**
     * Инициализация плагина
     */
    public function Init()
    {
    }
}
