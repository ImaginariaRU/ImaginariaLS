<?php

class PluginAutoopenid_ModuleMain_EntityServiceTwitter extends PluginAutoopenid_ModuleMain_EntityServiceType
{

    protected $oService = null;

    public function loadDataDetail()
    {
        $aResult = @json_decode($this->request('account/verify_credentials.json'), true);
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
        return null;
    }

    public function getLogin()
    {
        return null;
    }

    public function getLink()
    {
        return 'http://twitter.com/' . $this->getFromDetail('screen_name');
    }

    public function getNameShort()
    {
        return $this->getFromDetail('name');
    }

    public function getNameFull()
    {
        return $this->getFromDetail('name');
    }

    public function getAvatar()
    {
        $sUrl = $this->getFromDetail('profile_image_url');
        if (!strpos($sUrl, 'default_profile_')) {
            return str_replace("_normal.", "_bigger.", $sUrl);
        }
        return null;
    }

    public function getPhoto()
    {
        return $this->getAvatar();
    }

    public function getGender()
    {
        return null;
    }
}