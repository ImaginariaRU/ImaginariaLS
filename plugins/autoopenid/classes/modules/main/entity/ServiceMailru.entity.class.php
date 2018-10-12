<?php

class PluginAutoopenid_ModuleMain_EntityServiceMailru extends PluginAutoopenid_ModuleMain_EntityServiceType
{

    protected $oService = null;

    public function loadDataDetail()
    {
        $aResult = @json_decode($this->request('/?method=users.getInfo'), true);
        if ($aResult and isset($aResult[0]['uid'])) {
            $this->aDataDetail = $aResult[0];
            return true;
        }
        $this->Logger_Notice('Error request API ' . $this->getServiceType());
        return false;
    }

    public function getId()
    {
        return $this->getFromDetail('uid');
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
        return null;
    }

    public function getNameShort()
    {
        return $this->getFromDetail('first_name');
    }

    public function getNameFull()
    {
        return $this->getFromDetail('first_name') . ' ' . $this->getFromDetail('last_name');
    }

    public function getAvatar()
    {
        if ($this->getFromDetail('has_pic')) {
            return $this->getFromDetail('pic_128');
        }
        return null;
    }

    public function getPhoto()
    {
        if ($this->getFromDetail('has_pic')) {
            return $this->getFromDetail('pic_big');
        }
        return null;
    }

    public function getGender()
    {
        $sGender = $this->getFromDetail('sex');
        if ($sGender == 0) {
            return PluginAutoopenid_ModuleMain::GENDER_TYPE_MALE;
        } elseif ($sGender == 1) {
            return PluginAutoopenid_ModuleMain::GENDER_TYPE_FEMALE;
        }
        return PluginAutoopenid_ModuleMain::GENDER_TYPE_INDEFINITE;
    }
}