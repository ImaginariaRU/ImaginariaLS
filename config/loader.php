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
 * Основные константы
 */
define('LS_VERSION', '1.0.6');

/**
 * Operations with Config object
 */
require_once(dirname(dirname(__FILE__)) . "/engine/lib/internal/ConfigSimple/Config.class.php");
Config::LoadFromFile(dirname(__FILE__) . '/config.php');

$fGetConfig = function ($sPath) {
    $config = [];
    return include_once $sPath;
};

Config::Set("module.search", [
    'entity_prefix' =>  '',
    'sphinx'    =>  [
        'host'  =>  'localhost',
        'port'  =>  3312
    ]
]);

/**
 * ___path.root.server___/engine/include - файлы ядра
 * он там один такой
 * 
 */
$sDirInclude = Config::get('path.root.engine');
require_once $sDirInclude . '/include/function.php';

/**
 * Подгружаем файлы локального конфига
 */
if (file_exists(Config::Get('path.root.server') . '/config/config.local.php')) {
    Config::LoadFromFile(Config::Get('path.root.server') . '/config/config.local.php', false);
}


/**
 * Загружает конфиги плагинов вида /plugins/[plugin_name]/config/*.php
 * и include-файлы /plugins/[plugin_name]/include/*.php
 */
$sPluginsDir = Config::Get('path.root.server') . '/plugins';
$sPluginsListFile = $sPluginsDir . '/' . Config::Get('sys.plugins.activation_file');
if ($aPluginsList = @file($sPluginsListFile)) {
    $aPluginsList = array_map('trim', $aPluginsList);
    foreach ($aPluginsList as $sPlugin) {
        $aConfigFiles = glob($sPluginsDir . '/' . $sPlugin . '/config/*.php');
        if ($aConfigFiles and count($aConfigFiles) > 0) {
            foreach ($aConfigFiles as $sPath) {
                $aConfig = $fGetConfig($sPath);
                if (!empty($aConfig) && is_array($aConfig)) {
                    // Если конфиг этого плагина пуст, то загружаем массив целиком
                    $sKey = "plugin.$sPlugin";
                    if (!Config::isExist($sKey)) {
                        Config::Set($sKey, $aConfig);
                    } else {
                        // Если уже существую привязанные к плагину ключи,
                        // то сливаем старые и новое значения ассоциативно
                        Config::Set(
                            $sKey,
                            func_array_merge_assoc(Config::Get($sKey), $aConfig)
                        );
                    }
                }
            }
        }
        /**
         * Подключаем include-файлы
         */
        $aIncludeFiles = glob($sPluginsDir . '/' . $sPlugin . '/include/*.php');
        if ($aIncludeFiles and count($aIncludeFiles)) {
            foreach ($aIncludeFiles as $sPath) {
                require_once($sPath);
            }
        }
    }
}

