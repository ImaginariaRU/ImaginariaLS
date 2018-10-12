<?php

/* -------------------------------------------------------
 *
 *   LiveStreet (v1.0)
 *   Plugin Role (v.0.6)
 *   Copyright © 2011 Bishovec Nikolay
 *
 * --------------------------------------------------------
 *
 *   Plugin Page: http://netlanc.net
 *   Contact e-mail: netlanc@yandex.ru
 *
  ---------------------------------------------------------
 */

class PluginRole_ModuleRole_EntityRole extends Entity
{
    /*
     * Гетеры
     */

    public function getId()
    {
        return $this->_aData['role_id'];
    }

    public function getName()
    {
        return $this->_aData['role_name'];
    }

    public function getAcl()
    {
        return $this->_aData['role_acl'];
    }

    public function getText()
    {
        return $this->_aData['role_text'];
    }

    public function getRating()
    {
        return number_format(round($this->_aData['role_rating'], 2), 2, '.', '');
    }

    public function getRatingUse()
    {
        return $this->_aData['role_rating_use'];
    }

    public function getReg()
    {
        return $this->_aData['role_reg'];
    }

    public function getDateAdd()
    {
        return $this->_aData['role_date_add'];
    }

    public function getDateEdit()
    {
        return $this->_aData['role_date_edit'];
    }

    public function getAvatar()
    {
        return $this->_aData['role_avatar'];
    }

    public function getAvatarPath($iSize = 48)
    {
        if ($sPath = $this->getAvatar()) {
            return preg_replace("#_\d{1,3}x\d{1,3}(\.\w{3,4})$#", (($iSize == 0) ? "" : "_{$iSize}x{$iSize}\\1"), $sPath);
        } else {
            return Config::Get('path.static.skin') . '/images/avatar_' . $iSize . 'x' . $iSize . '.gif';
        }
    }

    public function getPlace()
    {
        return $this->_aData['role_place'];
    }

    /*
     * Cетеры
     */

    public function setId($data)
    {
        $this->_aData['role_id'] = $data;
    }

    public function setName($data)
    {
        $this->_aData['role_name'] = $data;
    }

    public function setAcl($data)
    {
        $this->_aData['role_acl'] = $data;
    }

    public function setText($data)
    {
        $this->_aData['role_text'] = $data;
    }

    public function setRating($data)
    {
        $this->_aData['role_rating'] = $data;
    }

    public function setRatingUse($data)
    {
        $this->_aData['role_rating_use'] = $data;
    }

    public function setReg($data)
    {
        $this->_aData['role_reg'] = $data;
    }

    public function setDateAdd($data)
    {
        $this->_aData['role_date_add'] = $data;
    }

    public function setDateEdit($data)
    {
        $this->_aData['role_date_edit'] = $data;
    }

    public function setAvatar($data)
    {
        $this->_aData['role_avatar'] = $data;
    }

    public function setPlace($data)
    {
        $this->_aData['role_place'] = $data;
    }

}

?>
