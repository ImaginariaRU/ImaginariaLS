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

class PluginRole_ModuleUser extends PluginRole_Inherit_ModuleUser
{

    public function Init()
    {
        parent::Init();
    }

    public function Update(ModuleUser_EntityUser $oUser)
    {
        $oUserOld = $this->User_GetUserById($oUser->getId());
        if ($oUserOld->getRating() != $oUser->getRating() and !$oUser->isAdministrator() and !in_array($oUser->getId(), Config::Get('plugin.role.auto_role.exc_users_id'))) {
            if ($oRoleChange = $this->PluginRole_Role_GetRolesByChange($oUser->getRating())) {
                //print_r(array($oRoleChange, $oUser, $oUserOld));
                $aUserRole = $oUser->getRole();
                if (!$aUserRole or ($oRoleChange->getId() != $aUserRole['object']->getId())) {
                    // меняем роль пользователю
                    // удаляем старую
                    if ($aUserRole)
                        if ($oUserRole = $this->PluginRole_Role_GetUserRoleByIds($oUser->getId(), $aUserRole['object']->getId())) {
                            $this->PluginRole_Role_DeleteUserRole($oUserRole);
                        }
                    // добавляем новую
                    $oUserRole = new PluginRole_ModuleRole_EntityRoleUser();
                    $oUserRole->setUserId($oUser->getId());
                    $oUserRole->setRoleId($oRoleChange->getId());
                    //print_r($oUserRole);
                    $this->PluginRole_Role_AddUserRole($oUserRole);
                }
            } else {
                if ($oUserOld->getRating() > $oUser->getRating()) {
                    $this->PluginRole_Role_DeleteUserRole($oUser->getId());
                }
            }
        }

        return parent::Update($oUser);
    }

}

?>
