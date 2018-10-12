<?php

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

class PluginAutoopenid extends Plugin
{

    /**
     * Массив с записями о наследовании плагином части функционала
     *
     * @var array
     */
    protected $aInherits = array(
        'action' => array(
            'ActionAjax'     => '_ActionAjax',
            'ActionSettings' => '_ActionSettings',
        ),
    );

    /**
     * Активация плагина.
     */
    public function Activate()
    {
        if (!$this->isTableExists('prefix_autoopenid_tmp')) {
            /**
             * При активации выполняем SQL дамп
             */
            $this->ExportSQL(dirname(__FILE__) . '/data/install.sql');
        }
        return true;
    }

    /**
     * Инициализация плагина
     */
    public function Init()
    {
        $this->Viewer_AppendScript(Plugin::GetWebPath(__CLASS__) . 'js/main.js?v=2');
        $this->Viewer_AppendStyle(Plugin::GetTemplateWebPath(__CLASS__) . 'css/main.css?v=2');
    }

}