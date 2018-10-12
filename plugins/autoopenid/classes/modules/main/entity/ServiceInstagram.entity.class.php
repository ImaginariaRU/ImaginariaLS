<?php

class PluginAutoopenid_ModuleMain_EntityServiceInstagram extends PluginAutoopenid_ModuleMain_EntityServiceType
{

    protected $oService = null;

    public function loadDataDetail()
    {
        $aResult = @json_decode($this->request('/users/self'), true);
        if ($aResult and isset($aResult['data']['id'])) {
            $this->aDataDetail = $aResult['data'];
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
        return null;
    }

    public function getLogin()
    {
        return $this->getFromDetail('username');
    }

    public function getLink()
    {
        return 'https://www.instagram.com/'.$this->getFromDetail('username').'/';
    }

    public function getNameShort()
    {
        return $this->getFromDetail('full_name');
    }

    public function getNameFull()
    {
        return $this->getFromDetail('full_name');
    }

    public function getAvatar()
    {
        return $this->getFromDetail('profile_picture');
    }

    public function getPhoto()
    {
        return null;
    }

    public function getGender()
    {
        return PluginAutoopenid_ModuleMain::GENDER_TYPE_INDEFINITE;
    }
}