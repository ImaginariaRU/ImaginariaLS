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

class PluginRole_ModuleRole_EntityRolePlace extends Entity
{
    /*
     * Гетеры
     */

    public function getId()
    {
        return $this->_aData['place_id'];
    }

    public function getRoleId()
    {
        return $this->_aData['role_id'];
    }

    public function getUrl()
    {
        return $this->_aData['place_url'];
    }

    public function getPosition()
    {
        return $this->_aData['block_position'];
    }

    /*
     * Cетеры
     */

    public function setId($data)
    {
        $this->_aData['place_id'] = $data;
    }

    public function setRoleId($data)
    {
        $this->_aData['role_id'] = $data;
    }

    public function setUrl($data)
    {
        $this->_aData['place_url'] = $data;
    }

    public function setPosition($data)
    {
        $this->_aData['block_position'] = $data;
    }

}

?>
