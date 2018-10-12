<?php

class PluginAutoopenid_ModuleMain extends ModuleORM
{

    const GENDER_TYPE_FEMALE = 'woman';
    const GENDER_TYPE_MALE = 'man';
    const GENDER_TYPE_INDEFINITE = 'other';

    public function Init()
    {
        parent::Init();
        require_once(Plugin::GetPath(__CLASS__) . 'vendor/OAuth/bootstrap.php');
    }


    public function CreateService($sService, $aConfig = array(), $sUrl = null, $aScope = array())
    {
        $oCredentials = new \OAuth\Common\Consumer\Credentials(
            $aConfig['client_id'],
            $aConfig['client_secret'],
            $sUrl ? $sUrl : Router::GetPath('/')
        );

        $oServiceFactory = new \OAuth\ServiceFactory();
        $oStorage = new \OAuth\Common\Storage\Session();

        $oService = $oServiceFactory->createService($sService, $oCredentials, $oStorage, $aScope);
        $sClass = 'PluginAutoopenid_ModuleMain_EntityService' . func_camelize($sService);
        if ($oService and class_exists($sClass)) {
            $oServiceType = new $sClass;
            $oServiceType->setServiceObject($oService);
            $oServiceType->setConfig($aConfig);
            return $oServiceType;
        }
        return null;
    }

    public function GetConfigService($sService)
    {
        if (!$sService or !($aConfig = Config::Get('plugin.autoopenid.services.' . $sService))) {
            return null;
        }
        return $aConfig;
    }

    public function GetService($sService)
    {
        /**
         * Получаем конфиг сервиса
         */
        $aConfig = $this->PluginAutoopenid_Main_GetConfigService($sService);
        if (is_null($aConfig)) {
            return null;
        }
        $aScope = isset($aConfig['scope']) ? $aConfig['scope'] : array();
        /**
         * Получаем сервис
         */
        if ($oService = $this->PluginAutoopenid_Main_CreateService($sService, $aConfig,
            Router::GetPath("login/autoopenid/oauth/{$sService}"), $aScope)
        ) {
            return $oService;
        }
        return null;
    }

    public function GetServicesAvailable()
    {
        $aConfig = (array)Config::Get('plugin.autoopenid.services');
        return array_keys($aConfig);
    }

    public function SaveReferalUrl($sUrl)
    {
        /**
         * Проверяем URL на корректность
         */
        if (!$sUrl) {
            return;
        }
        $aHostRef = @parse_url($sUrl, PHP_URL_HOST); // bla.ya.ru | ya.ru
        $aHostSite = @parse_url(Config::Get('path.root.web'), PHP_URL_HOST); // ya.ru
        if ($aHostRef and $aHostSite) {
            $aHostRef = $aHostRef;
            $aHostSite = preg_quote($aHostSite);
            if (preg_match("#{$aHostSite}$#", $aHostRef)) {
                setcookie('openidreferal', $sUrl, time() + Config::Get('plugin.autoopenid.time_key_limit'),
                    Config::Get('sys.cookie.path'), Config::Get('sys.cookie.host'));
            }
        }
    }

    public function RetrieveReferalUrl($bRemove = true)
    {
        $sUrl = Config::Get('path.root.web');
        if (isset($_COOKIE['openidreferal'])) {
            $sUrl = $_COOKIE['openidreferal'];
            if ($bRemove) {
                setcookie('openidreferal', '', 1, Config::Get('sys.cookie.path'), Config::Get('sys.cookie.host'));
            }
        }
        return $sUrl;
    }

    public function DownloadTmpFile($sUrl)
    {
        if ($sUrl) {
            if ($sContent = @file_get_contents($sUrl)) {
                $sFileTmp = Config::Get('sys.cache.dir') . func_generator();
                if (@file_put_contents($sFileTmp, $sContent)) {
                    return $sFileTmp;
                }
            }
        }
        return false;
    }

    public function GetRequestToken($oService)
    {
        if ($oService->getOAuthVersion() == 1) {
            $sToken = getRequestStr('oauth_token');
            $sVerify = getRequestStr('oauth_verifier');
            /**
             * Запрашиваем токен
             */
            if ($oToken = $oService->requestAccessToken($sToken, $sVerify)) {
                return $oToken;
            }
        } elseif ($oService->getOAuthVersion() == 2) {
            /**
             * Получаем код
             */
            if (!$sCode = getRequestStr('code')) {
                return false;
            }
            /**
             * Запрашиваем токен
             */
            if ($oToken = $oService->requestAccessToken($sCode)) {
                return $oToken;
            }
        }
        return false;
    }

    public function Translit($sText, $bLower = true)
    {
        $aConverter = array(
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ь' => "'",
            'ы' => 'y',
            'ъ' => "'",
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'E',
            'Ж' => 'Zh',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'Y',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'C',
            'Ч' => 'Ch',
            'Ш' => 'Sh',
            'Щ' => 'Sch',
            'Ь' => "'",
            'Ы' => 'Y',
            'Ъ' => "'",
            'Э' => 'E',
            'Ю' => 'Yu',
            'Я' => 'Ya',
            " " => "-",
            "." => "",
            "/" => "-"
        );
        $sRes = strtr($sText, $aConverter);
        if ($sResIconv = @iconv("UTF-8", "ISO-8859-1//IGNORE//TRANSLIT", $sRes)) {
            $sRes = $sResIconv;
        }
        if (preg_match('/[^A-Za-z0-9_\-]/', $sRes)) {
            $sRes = preg_replace('/[^A-Za-z0-9_\-]/', '', $sRes);
            $sRes = preg_replace('/\-+/', '-', $sRes);
        }
        if ($bLower) {
            $sRes = strtolower($sRes);
        }
        return $sRes;
    }
}