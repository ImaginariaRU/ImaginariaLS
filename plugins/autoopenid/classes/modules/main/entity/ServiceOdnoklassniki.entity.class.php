<?php

class PluginAutoopenid_ModuleMain_EntityServiceOdnoklassniki extends PluginAutoopenid_ModuleMain_EntityServiceType
{

    protected $oService = null;

    public function loadDataDetail()
    {
        $aResult = @json_decode($this->request('/?method=users.getCurrentUser&fields=uid,first_name,last_name,name,gender,birthday,pic_2,pic_4,'),
            true);
        if ($aResult and isset($aResult['uid'])) {
            $this->aDataDetail = $aResult;
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
        return null;
    }

    public function getLogin()
    {
        return null;
    }

    public function getLink()
    {
        return 'http://www.odnoklassniki.ru/profile/' . $this->getFromDetail('uid');
    }

    public function getNameShort()
    {
        return $this->getFromDetail('first_name');
    }

    public function getNameFull()
    {
        return $this->getFromDetail('name');
    }

    public function getAvatar()
    {
        return $this->getFromDetail('pic_2');
    }

    public function getPhoto()
    {
        return $this->getFromDetail('pic_4');
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