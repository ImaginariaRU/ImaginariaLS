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

class PluginRole_ModuleRole_EntityUser extends Entity
{
    /*
     * Гетеры
     */

    public function getId()
    {
        return $this->_aData['user_id'];
    }

    public function getAcl()
    {
        return $this->_aData['role_acl'];
    }

    /*
     * Cетеры
     */

    public function setId($data)
    {
        $this->_aData['user_id'] = $data;
    }

    public function setAcl($data)
    {
        $this->_aData['role_acl'] = $data;
    }

}

?>
