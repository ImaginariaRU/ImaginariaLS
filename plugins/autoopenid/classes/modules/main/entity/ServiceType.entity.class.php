<?php

abstract class PluginAutoopenid_ModuleMain_EntityServiceType extends Entity
{

    protected $oService = null;
    protected $aDataDetail = array();
    protected $aConfig = array();

    /**
     * Возвращает ID пользователя внутри сервиса
     *
     * @return string
     */
    abstract public function getId();

    /**
     * Возвращает емайл
     *
     * @return null
     */
    public function getMail()
    {
        return null;
    }

    /**
     * Возвращает логин
     * Если null, то далее будет попытка сформировать логин автоматически из других данных
     *
     * @return string|null
     */
    public function getLogin()
    {
        return null;
    }

    /**
     * Возвращает ссылку на профиль в соцсети
     *
     * @return string|null
     */
    public function getLink()
    {
        return null;
    }

    /**
     * Возвращает короткое имя
     *
     * @return string|null
     */
    public function getNameShort()
    {
        return null;
    }

    /**
     * Возвращает полное имя
     *
     * @return string|null
     */
    public function getNameFull()
    {
        return null;
    }

    /**
     * Возвращает веб-путь до картинки аватара
     *
     * @return string|null
     */
    public function getAvatar()
    {
        return null;
    }

    /**
     * Возвращает веб-путь до фото
     *
     * @return string|null
     */
    public function getPhoto()
    {
        return null;
    }

    /**
     * Возвращает пол
     *
     * @return string
     */
    public function getGender()
    {
        return PluginAutoopenid_ModuleMain::GENDER_TYPE_INDEFINITE;
    }


    /**
     * Выполняет загрузку данных о пользователе
     *
     * @return bool
     */
    public function loadDataDetail()
    {
        $this->aDataDetail = array();
        return false;
    }


    public function setServiceObject($oService)
    {
        $this->oService = $oService;
    }

    public function getServiceObject()
    {
        return $this->oService;
    }

    public function getFromDetail($sKey = null, $mDefault = null)
    {
        if (!$this->aDataDetail) {
            $this->loadDataDetail();
        }
        if (is_null($sKey)) {
            return $this->aDataDetail;
        }
        return isset($this->aDataDetail[$sKey]) ? $this->aDataDetail[$sKey] : $mDefault;
    }

    public function requestAccessToken()
    {
        try {
            $oToken = call_user_func_array(array($this->oService, 'requestAccessToken'), func_get_args());
            return $oToken;
        } catch (Exception $e) {
            $this->Logger_Notice('Error requestAccessToken API ' . $e->getMessage() . ' ' . $e->getCode());
            return false;
        }
    }

    public function requestRequestToken()
    {
        try {
            $oToken = call_user_func_array(array($this->oService, 'requestRequestToken'), func_get_args());
            return $oToken;
        } catch (Exception $e) {
            $this->Logger_Notice('Error requestRequestToken API ' . $e->getMessage() . ' ' . $e->getCode());
            return false;
        }
    }

    public function request($sPath, $sMethod = 'GET', $sBody = null, $aExtraHeaders = array())
    {
        try {
            $aRequestParams = isset($this->aConfig['request_params']) ? $this->aConfig['request_params'] : array();
            if ($aRequestParams) {
                if (is_string($sPath)) {
                    $sParams = http_build_query($aRequestParams, '', '&');
                    if (strpos($sPath, '?') === false) {
                        $sPath .= '?' . $sParams;
                    } else {
                        $sPath .= '&' . $sParams;
                    }
                }
            }
            $mResult = $this->oService->request($sPath, $sMethod, $sBody, $aExtraHeaders);
            return $mResult;
        } catch (Exception $e) {
            $this->Logger_Notice('Error request API ' . $e->getMessage() . ' ' . $e->getCode());
            return false;
        }
    }

    public function generateLogin()
    {
        $sName = $this->getNameFull() ? $this->getNameFull() : $this->getNameShort();
        if ($sName) {
            /**
             * Вырезаем пробелы
             */
            $sName = str_replace(' ', '', $sName);
            return $this->PluginAutoopenid_Main_Translit($sName, false);
        }
        return null;
    }

    public function getNameDisplay()
    {
        if ($this->getNameFull()) {
            return $this->getNameFull();
        }
        if ($this->getNameShort()) {
            return $this->getNameShort();
        }
        return $this->getId();
    }

    public function getOAuthVersion()
    {
        $oService = $this->oService;
        return $oService::OAUTH_VERSION;
    }

    public function setConfig($aConfig)
    {
        $this->aConfig = $aConfig;
    }

    public function getServiceType()
    {
        return strtolower($this->oService->service());
    }

    public function __call($sName, $aArgs)
    {
        if ($this->oService and method_exists($this->oService, $sName)) {
            return call_user_func_array(array($this->oService, $sName), $aArgs);
        } else {
            return parent::__call($sName, $aArgs);
        }
    }
}