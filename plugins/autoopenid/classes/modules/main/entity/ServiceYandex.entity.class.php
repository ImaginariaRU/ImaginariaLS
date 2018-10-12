<?php

class PluginAutoopenid_ModuleMain_EntityServiceYandex extends PluginAutoopenid_ModuleMain_EntityServiceType
{

    protected $oService = null;

    public function loadDataDetail()
    {
        $aResult = @json_decode($this->request('https://login.yandex.ru/info'), true);
        if ($aResult and isset($aResult['id'])) {
            $this->aDataDetail = $aResult;
            return true;
        }
        $this->Logger_Notice('Error request API ' . $this->getServiceType());
        return false;
    }

    public function getId()
    {
        return $this->getFromDetail('id');
    }

    public function getMail()
    {
        return $this->getFromDetail('default_email');
    }

    public function getLogin()
    {
        return null;
    }

    public function getLink()
    {
        return null;
    }

    public function getNameShort()
    {
        return $this->getFromDetail('first_name');
    }

    public function getNameFull()
    {
        return $this->getFromDetail('real_name');
    }

    public function getAvatar()
    {
        return null;
    }

    public function getPhoto()
    {
        return null;
    }

    public function getGender()
    {
        $sGender = $this->getFromDetail('sex');
        if ($sGender == 'male') {
            return PluginAutoopenid_ModuleMain::GENDER_TYPE_MALE;
        } elseif ($sGender == 'female') {
            return PluginAutoopenid_ModuleMain::GENDER_TYPE_FEMALE;
        }
        return PluginAutoopenid_ModuleMain::GENDER_TYPE_INDEFINITE;
    }
}