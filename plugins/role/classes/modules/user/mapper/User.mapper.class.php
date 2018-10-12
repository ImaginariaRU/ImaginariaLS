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

class PluginRole_ModuleUser_MapperUser extends PluginRole_Inherit_ModuleUser_MapperUser
{

    public function GetUsersByArrayId($aArrayId)
    {
        if (!is_array($aArrayId) or count($aArrayId) == 0) {
            return array();
        }
        $aRoles = $this->GetArrayRolesByUser($aArrayId);
        $sql = "SELECT
			    u.*	,
			    IF(ua.user_id IS NULL,0,1) as user_is_administrator
		    FROM
			    " . Config::Get('db.table.user') . " as u
			    LEFT JOIN " . Config::Get('db.table.user_administrator') . " AS ua ON u.user_id=ua.user_id
		    WHERE
			    u.user_id IN(?a)
		    ORDER BY FIELD(u.user_id,?a) ";
        $aUsers = array();
        if ($aRows = $this->oDb->select($sql, $aArrayId, $aArrayId)) {
            foreach ($aRows as $aUser) {
                if (!empty($aRoles[$aUser['user_id']]))
                    $aUser['role'] = $aRoles[$aUser['user_id']];
                $aUsers[] = Engine::GetEntity('User', $aUser);
            }
        }
        return $aUsers;
    }

    public function GetArrayRolesByUser($aArrayId)
    {
        if (!is_array($aArrayId) or count($aArrayId) == 0) {
            return array();
        }
        $sql = "SELECT
			    rus.*
		    FROM
			    " . Config::Get('plugin.role.table.role_users') . " as rus
		    WHERE
			    rus.user_id IN(?a)
		    ORDER BY FIELD(rus.user_id,?a) ";
        $aUsers = array();
        if ($aRowsUsers = $this->oDb->select($sql, $aArrayId, $aArrayId)) {
            foreach ($aRowsUsers as $aUser) {
                $aUsers[$aUser['user_id']] = $aUser;
            }
        }

        $sql = "SELECT r.*, ru.user_id FROM " . Config::Get('plugin.role.table.role_user') . " AS ru
		    JOIN " . Config::Get('plugin.role.table.role') . " AS r
			ON (ru.role_id=r.role_id)
		WHERE ru.user_id IN(?a) ORDER BY FIELD(ru.user_id,?a) ";
        $aRoles = array();
        if ($aRowsRoles = $this->oDb->select($sql, $aArrayId, $aArrayId)) {
            foreach ($aRowsRoles as $aRole) {
                $aRoles[$aRole['user_id']] = $aRole;
            }
        }

        $aRolesOld = $aRoles;

        if (!empty($aUsers)) {
            foreach ($aUsers as $sId => $aUser) {
                if (empty($aRoles[$sId])) {
                    $aRole = unserialize($aUser['role_acl']);
                    $aRoles[$sId] = $aRole;
                } else {
                    $aUserAcl = unserialize($aUser['role_acl']);
                    $aRoleAcl = unserialize($aRoles[$sId]['role_acl']);
                    $sRoleName = $aRoles[$sId]['role_name'];
                    $aRoles[$sId] = array_merge_recursive($aUserAcl, $aRoleAcl);
                    $aRoles[$sId]['role_name'] = $sRoleName;
                    $aRoles[$sId]['object'] = Engine::GetEntity('PluginRole_Role', $aRolesOld[$sId]);
                }
            }
        }

        if (!empty($aRoles)) {

            foreach ($aRoles as $sId => $aRole) {
                if (empty($aUsers[$sId])) {
                    $sRoleName = $aRoles[$sId]['role_name'];
                    $aRoles[$sId] = unserialize($aRole['role_acl']);
                    $aRoles[$sId]['role_name'] = $sRoleName;
                    $aRoles[$sId]['object'] = Engine::GetEntity('PluginRole_Role', $aRole);
                }
            }
            ;
        }
        return $aRoles;
    }

}

?>
