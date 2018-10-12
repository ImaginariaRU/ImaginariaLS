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

class PluginRole_ModuleRole extends Module
{

    protected $oMapper;

    public function Init()
    {
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

    /**
     * Geters
     *
     */
    public function GetAdmins()
    {
        if ($aAdmins = $this->oMapper->GetAdmins()) {
            return $aAdmins;
        }
        return false;
    }

    public function AllRole()
    {

        if ($aRole = $this->oMapper->AllRole()) {
            foreach ($aRole as $oRole) {
                $aRoleAcl = unserialize($oRole->getAcl());
//		foreach ($aRoleAcl as $key=>$aAcl){
//		    if (empty($aAcl['blog'])){
//			$aRoleAcl[$key]['blog'] = array();
//		    }
//		    if (empty($aAcl['blog']['topic'])){
//			$aRoleAcl[$key]['blog']['topic'] = array();
//		    }
//		    if (empty($aAcl['blog']['topic']['comment'])){
//			$aRoleAcl[$key]['blog']['topic']['comment'] = array();
//		    }
//		}
//		print_r($aRoleAcl);
                $oRole->setRole($aRoleAcl);
                $oRole->setUsers($this->oMapper->GetUsersByRoleId($oRole->getId()));
            }
            return $aRole;
        }
        return false;
    }

    public function GetRoleById($sRoleId)
    {
        return $this->oMapper->GetRoleById($sRoleId);
    }

    public function GetRoleUserByUserId($sUserId)
    {
        if ($aRole = $this->oMapper->GetRoleUserByUserId($sUserId)) {
            return $aRole;
        }
        return false;
    }

    public function GetUserRoleByIds($sUserId, $sRoleId)
    {
        return $this->oMapper->GetUserRoleByIds($sUserId, $sRoleId);
    }

    public function GetUserRoleByIds2($sUserId, $sRoleId)
    {
        return $this->oMapper->GetUserRoleByIds2($sUserId, $sRoleId);
    }

    /**
     * Селект ролей для регистрации
     *
     */
    public function GetRoleByReg($sReg)
    {
        return $this->oMapper->GetRoleByReg($sReg);
    }

    /**
     * Селект прав пользователя
     *
     */
    public function GetAclRoleUsersByUserId($sUserId)
    {
        return $this->oMapper->GetAclRoleUsersByUserId($sUserId);
    }

    /**
     * Селект всех пользователей
     *
     */
    public function GetAllUsersRole()
    {
        return $this->oMapper->GetAllUsersRole();
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
        if ($sId = $this->oMapper->AddAdmin($sId)) {
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('user_update'));
            $this->Cache_Delete("user_{$sId}");
            return $sId;
        }
        return false;
    }

    /**
     * Добавление пользователю прав
     *
     */
    public function AddRoleUser($oRole)
    {
        if ($sId = $this->oMapper->AddRoleUser($oRole)) {
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('user_update'));
            $this->Cache_Delete("user_{$oRole->getUserId()}");
            return $sId;
        }
        return false;
    }

    /**
     * Добавление роли
     *
     */
    public function AddRole($oRole)
    {
        if ($sId = $this->oMapper->AddRole($oRole)) {
            $oRole->setId($sId);
            //$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('role_update'));
            if ($oRole->getPlace()) {
                $aPlace = explode("\r\n", $oRole->getPlace());
                if (!empty($aPlace['0'])) {
                    foreach ($aPlace as $sPlace) {
                        $aExtPlace = explode(';', $sPlace);
                        $oPlace = new PluginRole_ModuleRole_EntityRolePlace();
                        $oPlace->setRoleId($oRole->getId());
                        $oPlace->setUrl(trim($aExtPlace['0']));
                        $oPlace->setPosition(0);
                        if (!empty($aExtPlace['1']))
                            $oPlace->setPosition(trim($aExtPlace['1']));
                        $this->oMapper->AddPlaceBlock($oPlace);
                    }
                }
            }
            return $sId;
        }
        return false;
    }

    /**
     * Добавление пользователя к роли
     *
     */
    public function AddUserRole($oUserRole)
    {
        if ($sId = $this->oMapper->AddUserRole($oUserRole)) {
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('user_update'));
            $this->Cache_Delete("user_{$oUserRole->getUserId()}");
            return $sId;
        }
        return false;
    }

    /**
     * Update
     *
     */
    public function UpdateRoleUser($oRole)
    {
        if ($this->oMapper->UpdateRoleUser($oRole)) {
            return true;
        }
        return false;
    }

    /**
     * Delete
     *
     */

    /**
     * Удаление роли
     *
     */
    public function DeleteRole($oRole)
    {
        if ($this->oMapper->DeleteRole($oRole->getId())) {
            /**
             * Удаление всех пользователей у роли
             */
            $this->oMapper->DeleteUsersByRoleId($oRole->getId());
            return true;
        }
        return false;
    }

    /**
     * Удаление пользователя у роли
     *
     */
	public function DeleteUserRole($oUserRole)
    {
        if ($this->oMapper->DeleteUserRole(is_object($oUserRole)?$oUserRole->getId():$oUserRole))
            return true;
        return false;
	}

    /**
     * Удаление прав пользователя у пользователя
     *
     */
    public function DeleteAclRoleUserByUserId($sUserId)
    {
        if ($this->oMapper->DeleteAclRoleUserByUserId($sUserId)) {
            return true;
        }
        return false;
    }

    /**
     * UpdateUser
     *
     */
    public function UpdateUser(ModuleUser_EntityUser $oUser)
    {
        $oUserOld = $this->User_GetUserById($oUser->getId());
        if ($oUserOld->getRating() != $oUser->getRating()) {
            if ($oRoleChange = $this->oMapper->GetRolesByChange($oUser->getRating())) {
                //print_r(array($oRoleChange, $oUser, $oUserOld));
                $aUserRole = $oUser->getRole();
                if (!$aUserRole or ($oRoleChange->getId() != $aUserRole['object']->getId())) {
                    // меняем роль пользователю
                    // удаляем старую
                    if ($aUserRole)
                        if ($oUserRole = $this->GetUserRoleByIds($oUser->getId(), $aUserRole['object']->getId())) {
                            $this->oMapper->DeleteUserRole($oUserRole->getId());
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
                    $this->oMapper->DeleteUserRole($oUser->getId());
                }
            }
        }
        //чистим зависимые кеши
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('user_update'));
        $this->Cache_Delete("user_{$oUser->getId()}");
        return $this->oMapper->UpdateUser($oUser);
    }

    public function GetRolesByChange($sRating)
    {
        return $this->oMapper->GetRolesByChange($sRating);
    }


    public function UpdateRole($oRole)
    {
        $oRoleOld = $this->GetRoleById($oRole->getId());
        if ($oRole->getPlace() != $oRoleOld->getPlace()) {
            $this->DeletePlaceByRoleId($oRole->getId());
            $aPlace = explode("\r\n", $oRole->getPlace());
            if (!empty($aPlace['0'])) {
                foreach ($aPlace as $sPlace) {
                    $aExtPlace = explode(';', $sPlace);
                    $oPlace = new PluginRole_ModuleRole_EntityRolePlace();
                    $oPlace->setRoleId($oRole->getId());
                    $oPlace->setUrl(trim($aExtPlace['0']));
                    $oPlace->setPosition(0);
                    if (!empty($aExtPlace['1']))
                        $oPlace->setPosition(trim($aExtPlace['1']));
                    $this->oMapper->AddPlaceBlock($oPlace);
                }
            }
        }
        //чистим зависимые кеши
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('user_update'));
        $this->Cache_Delete("user");

        return $this->oMapper->UpdateRole($oRole);
    }

    public function DeletePlaceByRoleId($sId)
    {
        return $this->oMapper->DeletePlaceByRoleId($sId);
    }

    public function DeleteAdmin($sId)
    {
        //чистим зависимые кеши
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('user_update'));
        $this->Cache_Delete("user_{$sId}");

        return $this->oMapper->DeleteAdmin($sId);
    }

    public function GetCountChildrenByCommentId($sId)
    {
        return $this->oMapper->GetCountChildrenByCommentId($sId);
    }

    public function UploadAvatar($aFile, $oRole)
    {
        if (!is_array($aFile) || !isset($aFile['tmp_name'])) {
            return false;
        }

        $sFileTmp = Config::Get('sys.cache.dir') . func_generator();
        if (!move_uploaded_file($aFile['tmp_name'], $sFileTmp)) {
            return false;
        }

        $sPath = Config::Get('path.uploads.role') . '/' . $oRole->getId() . '/';
        $aParams = $this->Image_BuildParams('avatar');

        $oImage = new LiveImage($sFileTmp);
        /**
         * Если объект изображения не создан,
         * возвращаем ошибку
         */
        if ($sError = $oImage->get_last_error()) {
            // Вывод сообщения об ошибки, произошедшей при создании объекта изображения
            // $this->Message_AddError($sError,$this->Lang_Get('error'));
            @unlink($sFileTmp);
            return false;
        }
        /**
         * Срезаем квадрат
         */
        $oImage = $this->Image_CropSquare($oImage);

        $aSize = Config::Get('plugin.role.avatar_size');
        rsort($aSize, SORT_NUMERIC);
        $sSizeBig = array_shift($aSize);
        if ($oImage && $sFileAvatar = $this->Image_Resize($sFileTmp, $sPath, "avatar_{$oRole->getId()}_{$sSizeBig}x{$sSizeBig}", Config::Get('view.img_max_width'), Config::Get('view.img_max_height'), $sSizeBig, $sSizeBig, false, $aParams, $oImage)) {
            foreach ($aSize as $iSize) {
                if ($iSize == 0) {
                    $this->Image_Resize($sFileTmp, $sPath, "avatar_{$oRole->getId()}", Config::Get('view.img_max_width'), Config::Get('view.img_max_height'), null, null, false, $aParams, $oImage);
                } else {
                    $this->Image_Resize($sFileTmp, $sPath, "avatar_{$oRole->getId()}_{$iSize}x{$iSize}", Config::Get('view.img_max_width'), Config::Get('view.img_max_height'), $iSize, $iSize, false, $aParams, $oImage);
                }
            }
            @unlink($sFileTmp);
            /**
             * Если все нормально, возвращаем расширение загруженного аватара
             */
            return $this->Image_GetWebPath($sFileAvatar);
        }
        @unlink($sFileTmp);
        /**
         * В случае ошибки, возвращаем false
         */
        return false;
    }

    /**
     * Delete avatar from server
     *
     */
    public function DeleteAvatar($oRole)
    {
        /**
         * Если аватар есть, удаляем его и его рейсайзы
         */
        if ($oRole->getAvatar()) {
            $aSize = array_merge(Config::Get('plugin.role.avatar_size'), array(48));
            foreach ($aSize as $iSize) {
                @unlink($this->Image_GetServerPath($oRole->getAvatarPath($iSize)));
            }
        }
    }

    public function GetSideBarRole($sUrl)
    {
        if ($aRoles = $this->oMapper->GetRoleByUrl($sUrl)) {
            $aResult = array();
            foreach ($aRoles as $aRole) {
                $oRole = Engine::GetEntity('PluginRole_Role', $aRole);
                $oRole->setUsers($this->oMapper->GetUsersByRoleId($oRole->getId()));
                $aResult[] = $oRole;
            }
            return $aResult;
        }
        return false;
    }

    public function UpdateComment(ModuleComment_EntityComment $oComment)
    {
        if ($this->oMapper->UpdateComment($oComment)) {
            //чистим зависимые кеши
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array("comment_update", "comment_update_{$oComment->getTargetType()}_{$oComment->getTargetId()}"));
            $this->Cache_Delete("comment_{$oComment->getId()}");
            return true;
        }
        return false;
    }
}

?>
