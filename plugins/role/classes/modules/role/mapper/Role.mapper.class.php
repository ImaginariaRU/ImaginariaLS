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

class PluginRole_ModuleRole_MapperRole extends Mapper
{
    /**
     * Geters
     *
     */

    /**
     * Селект админов
     *
     */
    public function GetAdmins()
    {
        $sql = "SELECT
			u.*
		FROM
			" . Config::Get('db.table.user_administrator') . " as ua
			LEFT JOIN " . Config::Get('db.table.user') . " AS u ON ua.user_id=u.user_id";
        $aUsers = array();
        if ($aRows = $this->oDb->select($sql)) {
            foreach ($aRows as $aUser) {
                $aUsers[] = Engine::GetEntity('User', $aUser);
            }
        }
        return $aUsers;
    }

    public function GetAclRoleUsersByUserId($sUserId)
    {
        $sql = "SELECT * FROM " . Config::Get('plugin.role.table.role_users') . " WHERE user_id = ?d";
        if ($aRow = $this->oDb->selectRow($sql, $sUserId)) {
            return new PluginRole_ModuleRole_EntityUser($aRow);
        }

        return false;
    }

    public function AllRole()
    {
        $sql = "SELECT * FROM " . Config::Get('plugin.role.table.role');
        $aResult = array();
        if ($aRows = $this->oDb->select($sql)) {
            foreach ($aRows as $aRow) {
                $aResult[] = Engine::GetEntity('PluginRole_Role', $aRow);
            }
        }

        return $aResult;
    }

    public function GetRoleById($sRoleId)
    {
        $sql = "SELECT * FROM " . Config::Get('plugin.role.table.role') . " WHERE role_id = ?d";
        if ($aRow = $this->oDb->selectRow($sql, $sRoleId)) {
            return Engine::GetEntity('PluginRole_Role', $aRow);
        }

        return false;
    }

    public function GetRoleUserByUserId($sUserId)
    {
        $sql = "SELECT r.role_acl FROM " . Config::Get('plugin.role.table.role_user') . " AS ru
		    JOIN " . Config::Get('plugin.role.table.role') . " AS r
			ON (ru.role_id=r.role_id)
		WHERE user_id = ?d";
        if ($aRow = $this->oDb->selectRow($sql, $sUserId)) {
            return unserialize($aRow['role_acl']);
        }

        return false;
    }

    public function GetUserRoleByIds($sUserId, $sRoleId)
    {
        $sql = "SELECT * FROM " . Config::Get('plugin.role.table.role_user') . " WHERE user_id = ?d AND role_id = ?d";
        if ($aRow = $this->oDb->selectRow($sql, $sUserId, $sRoleId)) {
            return new PluginRole_ModuleRole_EntityRoleUser($aRow);
        }

        return false;
    }

    public function GetUserRoleByIds2($sUserId, $sRoleId)
    {
        $sql = "SELECT * FROM " . Config::Get('plugin.role.table.role_user') . " WHERE user_id = ?d";
        if ($aRow = $this->oDb->selectRow($sql, $sUserId)) {
            return new PluginRole_ModuleRole_EntityRoleUser($aRow);
        }

        return false;
    }

    public function GetUsersByRoleId($sRoleId)
    {
        $sql = "SELECT u.user_id, u.user_login, u.user_profile_avatar FROM " . Config::Get('plugin.role.table.role_user') . " AS ru
		JOIN " . Config::Get('db.table.user') . " u
		    ON (ru.user_id=u.user_id)
		WHERE role_id = ?d";
        $aResult = array();
        if ($aRows = $this->oDb->select($sql, $sRoleId)) {
            foreach ($aRows as $aRow) {
                $aResult[] = Engine::GetEntity('User', $aRow);
            }
            return $aResult;
        }

        return false;
    }

    /**
     * Селект всех пользователей
     *
     */
    public function GetAllUsersRole()
    {
        $sql = "SELECT ru.*, u.* FROM " . Config::Get('plugin.role.table.role_users') . " AS ru
		JOIN " . Config::Get('db.table.user') . " u
		    ON (ru.user_id=u.user_id)";
        //WHERE user_id = ?d";
        $aResult = array();
        if ($aRows = $this->oDb->select($sql)) {
            foreach ($aRows as $aRow) {
                $aResult[] = Engine::GetEntity('User', $aRow);
            }
            return $aResult;
        }

        return false;
    }

    /**
     * Селект ролей для регистрации
     *
     */
    public function GetRoleByReg($sReg)
    {
        $sql = "SELECT r.* FROM " . Config::Get('plugin.role.table.role') . " AS r
		    WHERE role_reg = ?d";
        //WHERE user_id = ?d";
        $aResult = array();
        if ($aRows = $this->oDb->select($sql, $sReg)) {
            foreach ($aRows as $aRow) {
                $aResult[] = Engine::GetEntity('PluginRole_Role', $aRow);
            }
            return $aResult;
        }

        return false;
    }

    /**
     * Insert
     *
     */

    /**
     * Добавление админа
     *
     */
    public function AddAdmin($sId)
    {
        $sql = "INSERT INTO " . Config::Get('db.table.user_administrator') . "
			(
			    user_id
			)
			VALUES(?d)
		";
        if ($iId = $this->oDb->query($sql, $sId)) {
            return $iId;
        }
        return false;
    }

    /**
     * Добавление пользователю прав
     *
     */
    public function AddRoleUser($oRole)
    {
        $sql = "INSERT INTO " . Config::Get('plugin.role.table.role_users') . "
			(
			    user_id, role_acl
			)
			VALUES(?d,?)
		";
        if ($iId = $this->oDb->query($sql, $oRole->getId(), $oRole->getAcl())) {
            return $iId;
        }
        return false;
    }

    /**
     * Добавление роли
     *
     */
    public function AddRole($oRole)
    {
        $sql = "INSERT INTO " . Config::Get('plugin.role.table.role') . "
			(
			    role_name, role_acl, role_text, role_rating_use, role_rating, role_reg, role_date_add, role_place
			)
			VALUES(?, ?, ?, ?d, ?f, ?d, ?, ?)
		";
        if ($iId = $this->oDb->query($sql, $oRole->getName(), $oRole->getAcl(), $oRole->getText(), $oRole->getRatingUse(), $oRole->getRating(), $oRole->getReg(), $oRole->getDateAdd(), $oRole->getPlace())) {
            return $iId;
        }
        return false;
    }

    /**
     * Добавление пользователя к роли
     *
     */
    public function AddUserRole($oUserRole)
    {
        $sql = "INSERT INTO " . Config::Get('plugin.role.table.role_user') . "
		    (
			role_id, user_id
		    )
		    VALUES(?d, ?d)
		";
        if ($iId = $this->oDb->query($sql, $oUserRole->getRoleId(), $oUserRole->getUserId())) {
            return $iId;
        }
        return false;
    }

    /**
     * Update
     *
     */
    public function UpdateRoleUser($oRole)
    {
        $sql = "UPDATE " . Config::Get('plugin.role.table.role_users') . "
			SET
				role_acl = ?
			WHERE
				user_id = ?d
		";
        if ($this->oDb->query($sql, $oRole->getAcl(), $oRole->getId())) {
            return true;
        }
        return false;
    }

    public function UpdateRole($oRole)
    {
        $sql = "UPDATE " . Config::Get('plugin.role.table.role') . "
			SET
				role_name = ?,
				role_acl = ?,
				role_text = ?,
				role_rating_use = ?d,
				role_rating = ?f,
				role_reg = ?d,
				role_date_edit = ?,
				role_avatar = ?,
				role_place = ?
			WHERE
				role_id = ?d
		";
        if ($this->oDb->query($sql, $oRole->getName(), $oRole->getAcl(), $oRole->getText(), $oRole->getRatingUse(), $oRole->getRating(), $oRole->getReg(), $oRole->getDateEdit(), $oRole->getAvatar(), $oRole->getPlace(), $oRole->getId())) {
            return true;
        }
        return false;
    }

    /**
     * Delete
     *
     */
    public function DeleteRole($sRoleId)
    {
        $sql = "DELETE FROM " . Config::Get('plugin.role.table.role') . " WHERE role_id = ?d";
        return $this->oDb->query($sql, $sRoleId);
    }

    public function DeleteUserRole($sUserRoleId)
    {
        $sql = "DELETE FROM " . Config::Get('plugin.role.table.role_user') . " WHERE role_user_id = ?d";
        return $this->oDb->query($sql, $sUserRoleId);
    }

    public function DeleteUsersByRoleId($sRoleId)
    {
        $sql = "DELETE FROM " . Config::Get('plugin.role.table.role_user') . " WHERE role_id = ?d";
        return $this->oDb->query($sql, $sRoleId);
    }

    public function DeleteAclRoleUserByUserId($sUserId)
    {
        $sql = "DELETE FROM " . Config::Get('plugin.role.table.role_users') . " WHERE user_id = ?d";
        return $this->oDb->query($sql, $sUserId);
    }

    public function DeleteAdmin($sId)
    {
        $sql = "DELETE FROM " . Config::Get('db.table.user_administrator') . " WHERE user_id = ?d";
        return $this->oDb->query($sql, $sId);
    }

    /**
     *
     *
     *
     */
    public function UpdateUser(ModuleUser_EntityUser $oUser)
    {
        $sql = "UPDATE " . Config::Get('db.table.user') . "
			SET
				user_login = ? ,
				user_password = ? ,
				user_mail = ? ,
				user_skill = ? ,
				user_rating = ? ,
				user_profile_name = ? ,
				user_profile_sex = ? ,
				user_profile_birthday = ?,
				user_profile_date = ?
			WHERE user_id = ?
		";
        if ($this->oDb->query($sql, $oUser->getLogin(), $oUser->getPassword(), $oUser->getMail(), $oUser->getSkill(), $oUser->getRating(), $oUser->getProfileName(),
            $oUser->getProfileSex(), $oUser->getProfileAbout(), time(), $oUser->getId())
        ) {
            return true;
        }
        return false;
    }

    public function GetCountChildrenByCommentId($sId)
    {
        $sql = "SELECT
			count(comment_id) as c
		FROM
			" . Config::Get('db.table.comment') . "
		WHERE
			comment_pid = ?d
			AND comment_publish = '1';";

        if ($aRow = $this->oDb->selectRow($sql, $sId)) {
            return $aRow['c'];
        }
    }

    public function AddPlaceBlock($oPlace)
    {
        $sql = "INSERT INTO " . Config::Get('plugin.role.table.role_place_block') . "
			(
			    role_id, place_url, block_position
			)
			VALUES(?d, ?, ?d)
		";
        if ($iId = $this->oDb->query($sql, $oPlace->getRoleId(), $oPlace->getUrl(), $oPlace->getPosition())) {
            return $iId;
        }
        return false;
    }

    public function DeletePlaceByRoleId($sId)
    {
        $sql = "DELETE FROM " . Config::Get('plugin.role.table.role_place_block') . " WHERE role_id = ?d";
        return $this->oDb->query($sql, $sId);
    }

    public function GetRoleByUrl($sUrl)
    {
        $sql = 'SELECT
                    r.*, prb.role_id

                FROM
                ' . Config::Get('plugin.role.table.role_place_block') . ' prb
                    LEFT JOIN
                            ' . Config::Get('plugin.role.table.role') . ' r
                         ON prb.role_id = r.role_id
                WHERE
                        ? LIKE prb.place_url';

        return $this->oDb->select($sql, $sUrl);
    }

    /**
     * Селект ролей учавствующих в авторолях по рейтингу
     *
     */
    public function GetRolesByAllRatingUse()
    {
        $sql = "SELECT r.* FROM " . Config::Get('plugin.role.table.role') . " AS r
		    WHERE role_rating_use = 1 ORDER BY role_rating ASC";
        //WHERE user_id = ?d";
        $aResult = array();
        if ($aRows = $this->oDb->select($sql)) {
            foreach ($aRows as $aRow) {
                $aResult[] = Engine::GetEntity('PluginRole_Role', $aRow);
            }
            return $aResult;
        }

        return false;
    }

    /**
     * Селект текущей роли по рейтингу
     *
     */
    public function GetRolesByChange($sRating)
    {
        $sql = "SELECT r.* FROM " . Config::Get('plugin.role.table.role') . " AS r
		    WHERE role_rating_use = 1 AND role_rating <= ? ORDER BY role_rating DESC LIMIT 1";
        if ($aRow = $this->oDb->selectRow($sql, $sRating)) {
            return Engine::GetEntity('PluginRole_Role', $aRow);
        }
        return false;
    }

    /**
     * Comment
     */
    public function UpdateComment(ModuleComment_EntityComment $oComment)
    {
        $sql = "UPDATE " . Config::Get('db.table.comment') . "
				SET
					comment_date_edit = ?,
					comment_edit_user_id = ?d
				WHERE
					comment_id = ?d";
        if ($this->oDb->query($sql, $oComment->getCommentDateEdit(), $oComment->getCommentEditUserId(), $oComment->getId())) {
            return true;
        }
        return false;
    }
}

?>
