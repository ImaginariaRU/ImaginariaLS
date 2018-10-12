<?php

class PluginAutoopenid_ModuleMain_EntityServiceVkontakte extends PluginAutoopenid_ModuleMain_EntityServiceType
{

    protected $oService = null;

    public function loadDataDetail()
    {
        $aResult = @json_decode($this->request('/users.get?fields=sex,bdate,site,screen_name,domain,about,nickname,photo_max_orig'),
            true);
        if ($aResult) {
            if (isset($aResult['error'])) {
                $this->Logger_Notice('Error request API ' . $this->getServiceType(), $aResult['error']);
                return false;
            }
            if (isset($aResult['response'][0]['uid'])) {
                $this->aDataDetail = $aResult['response'][0];
                $oToken = $this->oService->getStorage()->retrieveAccessToken($this->service());
                $aParams = $oToken->getExtraParams();
                if (isset($aParams['email'])) {
                    $this->aDataDetail['email'] = $aParams['email'];
                }
                return true;
            }
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
        return 'https://vk.com/' . $this->getFromDetail('domain');
    }

    public function getNameShort()
    {
        return $this->getFromDetail('first_name');
    }

    public function getNameFull()
    {
        return trim($this->getFromDetail('first_name') . ' ' . $this->getFromDetail('last_name'));
    }

    public function getAvatar()
    {
        return $this->getFromDetail('photo_max_orig') != 'http://vk.com/images/camera_a.gif' ? $this->getFromDetail('photo_max_orig') : null;
    }

    public function getPhoto()
    {
        return $this->getFromDetail('photo_max_orig') != 'http://vk.com/images/camera_a.gif' ? $this->getFromDetail('photo_max_orig') : null;
    }

    public function getGender()
    {
        $sGender = $this->getFromDetail('sex');
        if ($sGender == 2) {
            return PluginAutoopenid_ModuleMain::GENDER_TYPE_MALE;
        } elseif ($sGender == 1) {
            return PluginAutoopenid_ModuleMain::GENDER_TYPE_FEMALE;
        }
        return PluginAutoopenid_ModuleMain::GENDER_TYPE_INDEFINITE;
    }
}