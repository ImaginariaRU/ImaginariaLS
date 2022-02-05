<?php

set_include_path( get_include_path().PATH_SEPARATOR.LIVESTREET_PATH_WWW );


// Получаем объект конфигурации
require_once LIVESTREET_PATH_WWW . "/engine/lib/internal/ConfigSimple/Config.class.php";
Config::LoadFromFile(LIVESTREET_PATH_INSTALL . '/config/config.php');

Config::Set("module.search", [
    'entity_prefix' =>  '',
    'sphinx'    =>  [
        'host'  =>  'localhost',
        'port'  =>  3312
    ]
]);

require_once LIVESTREET_PATH_WWW . '/engine/include/function.php';


/**
 * Подгружаем файлы локального конфига
 */
Config::LoadFromFile(LIVESTREET_PATH_INSTALL . '/config/config.local.php', false);

/**
 * Загружает конфиги плагинов вида /plugins/[plugin_name]/config/*.php
 * и include-файлы /plugins/[plugin_name]/include/*.php
 */

$sPluginsDir = LIVESTREET_PATH_WWW . '/plugins';
$sPluginsListFile = LIVESTREET_PATH_WWW . '/plugins/' . Config::Get('sys.plugins.activation_file');

if ($aPluginsList = @file($sPluginsListFile)) {
    
    $aPluginsList = array_map('trim', $aPluginsList);
    
    foreach ($aPluginsList as $sPlugin) {
        
        $aConfigFiles = glob($sPluginsDir . '/' . $sPlugin . '/config/*.php');
        
        if ($aConfigFiles and count($aConfigFiles) > 0) {
            
            foreach ($aConfigFiles as $sPath) {
                
                $aConfig = include $sPath;
                
                if (!empty($aConfig) && is_array($aConfig)) {
                    // Если конфиг этого плагина пуст, то загружаем массив целиком
                    $sKey = "plugin.{$sPlugin}";
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

