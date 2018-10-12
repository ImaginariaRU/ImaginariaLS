<?php

class PluginAutoopenid_ModuleMain_EntityServiceGoogle extends PluginAutoopenid_ModuleMain_EntityServiceType
{

    protected $oService = null;

    public function loadDataDetail()
    {
        $aResult = @json_decode($this->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);
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
        return $this->getFromDetail('email');
    }

    public function getLogin()
    {
        return null;
    }

    public function getLink()
    {
        return $this->getFromDetail('link');
    }

    public function getNameShort()
    {
        return $this->getFromDetail('given_name');
    }

    public function getNameFull()
    {
        return $this->getFromDetail('name');
    }

    public function getAvatar()
    {
        return $this->getFromDetail('picture');
    }

    public function getPhoto()
    {
        return null;
    }

    public function getGender()
    {
        $sGender = $this->getFromDetail('gender');
        if ($sGender == 'male') {
            return PluginAutoopenid_ModuleMain::GENDER_TYPE_MALE;
        } elseif ($sGender == 'female') {
            return PluginAutoopenid_ModuleMain::GENDER_TYPE_FEMALE;
        }
        return PluginAutoopenid_ModuleMain::GENDER_TYPE_INDEFINITE;
    }
}