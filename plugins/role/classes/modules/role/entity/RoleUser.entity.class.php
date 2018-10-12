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

class PluginRole_ModuleRole_EntityRoleUser extends Entity
{
    /*
     * Гетеры
     */

    public function getId()
    {
        return $this->_aData['role_user_id'];
    }

    public function getRoleId()
    {
        return $this->_aData['role_id'];
    }

    public function getUserId()
    {
        return $this->_aData['user_id'];
    }

    /*
     * Cетеры
     */

    public function setId($data)
    {
        $this->_aData['role_user_id'] = $data;
    }

    public function setRoleId($data)
    {
        $this->_aData['role_id'] = $data;
    }

    public function setUserId($data)
    {
        $this->_aData['user_id'] = $data;
    }

}

?>
