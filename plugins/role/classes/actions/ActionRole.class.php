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

class PluginRole_ActionRole extends ActionPlugin
{

    protected $sMenuItemSelect = 'roles';

    public function Init()
    {
        if (!($this->oUserCurrent = $this->User_GetUserCurrent()) or !$this->oUserCurrent->isAdministrator()) {
            return parent::EventNotFound();
        }
        $this->SetDefaultEvent('roles');
    }

    protected function RegisterEvent()
    {
        $this->AddEvent('roles', 'EventRoles');
        $this->AddEvent('users', 'EventUsers');
        $this->AddEvent('admins', 'EventAdmins');
        $this->AddEvent('avatar', 'EventAvatar');
    }

    protected function EventAvatar()
    {
        $this->sMenuItemSelect = 'avatar';
        if (isPost('role_create_avatar_submit')) {

            if (!($oUser = $this->User_GetUserByLogin(getRequest('login', null, 'post')))) {
                $this->Message_AddError($this->Lang_Get('user_not_found'), $this->Lang_Get('error'));
                return;
            }

            if ($oUser->getId() == $this->oUserCurrent->getId()) {
                $this->Message_AddError($this->Lang_Get('plugin.role.no_sense_to_choose'), $this->Lang_Get('error'));
                return;
            }
            $sLifetime = time() + 3600 * 24 * Config::Get('plugin.role.max_life_time');
            setcookie('ls_avatar_id', $this->oUserCurrent->getId(), $sLifetime, Config::Get('sys.cookie.path'), Config::Get('sys.cookie.host'));
            $this->User_Authorization($oUser, true);
            Router::Location(Config::Get('path.root.web') . '/');
        }
    }

    protected function EventAdmins()
    {
        $this->sMenuItemSelect = 'admins';
        if (isPost('role_create_submit')) {
            $this->SubmitAddAdmin();
        }
        $aAdmins = $this->PluginRole_Role_GetAdmins();

        $this->Viewer_Assign('aAdmins', $aAdmins);
    }

    protected function SubmitAddAdmin()
    {
        $bOk = true;
        if (!($oUser = $this->User_GetUserByLogin(getRequest('login', null, 'post')))) {
            $this->Message_AddError($this->Lang_Get('user_not_found'), $this->Lang_Get('error'));
            return;
        }
        if ($oUser->isAdministrator()) {
            $this->Message_AddError($this->Lang_Get('plugin.role.admin_create_error'), $this->Lang_Get('error'));
            $bOk = false;
        }

        if (!$bOk) {
            return;
        }

        $this->PluginRole_Role_AddAdmin($oUser->getId());

        Router::Location(Router::GetPath('role') . 'admins/');
    }

    protected function EventUsers()
    {
        $this->sMenuItemSelect = 'users';
        //print_r($_POST);
        if (isPost('role_create_submit')) {
            $this->SubmitAddUser();
        }
        //$aRole = $this->PluginRole_Role_AllRole();
        if ($aUsers = $this->PluginRole_Role_GetAllUsersRole()) {
            foreach ($aUsers as $oUser) {
                if ($oUser->getRoleAcl()) {
                    $oUser->setRole(unserialize($oUser->getRoleAcl()));
                }
            }
            $this->Viewer_Assign('aUsers', $aUsers);
        }
    }

    protected function SubmitAddUser()
    {
        $bOk = true;
        if (!($oUser = $this->User_GetUserByLogin(getRequest('plugin.role.user_login', null, 'post')))) {
            $this->Message_AddError($this->Lang_Get('user_not_found'), $this->Lang_Get('error'));
            return;
        }
        if (!isPost('role')) {
            $this->Message_AddError($this->Lang_Get('plugin.role.create_acl_error'), $this->Lang_Get('error'));
            $bOk = false;
        }


        if (!$bOk) {
            return;
        }

        $sRole = serialize(getRequest('role'));

        $oRole = new PluginRole_ModuleRole_EntityUser();
        $oRole->setId($oUser->getId());
        $oRole->setAcl($sRole);

        if ($oRoleUser = $this->PluginRole_Role_GetAclRoleUsersByUserId($oUser->getId())) {
            $this->PluginRole_Role_UpdateRoleUser($oRole);
        } else {
            $this->PluginRole_Role_AddRoleUser($oRole);
        }
        Router::Location(Router::GetPath('role') . 'users/');
    }

    protected function EventRoles()
    {
        $this->sMenuItemSelect = 'roles';
        //print_r($_POST);
        if (isPost('role_create_submit')) {
            $this->SubmitAddRole();
        }
        $aRole = $this->PluginRole_Role_AllRole();

        //$aRow = $this->PluginLstat_Lstat_GetAllRow();
        $this->Viewer_Assign('aRole', $aRole);
    }

    protected function SubmitAddRole()
    {
        $bOk = true;
        if (!func_check(getRequest('role_name', null, 'post'), 'text', 2, 200)) {
            $this->Message_AddError($this->Lang_Get('plugin.role.create_name_error'), $this->Lang_Get('error'));
            $bOk = false;
        }
        if (!isPost('role') and !isPost('role_reg')) {
            $this->Message_AddError($this->Lang_Get('plugin.role.create_acl_error'), $this->Lang_Get('error'));
            $bOk = false;
        }

        if (!func_check(getRequest('role_name', null, 'post'), 'text', 2, 200)) {
            $this->Message_AddError($this->Lang_Get('plugin.role.create_name_error'), $this->Lang_Get('error'));
            return;
        }
        if (!func_check(getRequest('role_text', null, 'post'), 'text', 2, Config::Get('plugin.role.max_length_text'))) {
            $this->Message_AddError($this->Lang_Get('plugin.role.create_text_error'), $this->Lang_Get('error'));
            $bOk = false;
        }
        if (!$bOk) {
            return;
        }

        if (!isPost('role')) {
            $_REQUEST['role'] = array();
        }
        $sRole = serialize(getRequest('role'));

        $oRole = Engine::GetEntity('PluginRole_Role');
        $oRole->setName(getRequest('role_name'));
        $oRole->setAcl($sRole);
        $oRole->setText($this->Text_Parser(getRequest('role_text')));
        $oRole->setRating(0);
        $oRole->setRatingUse(0);
        if (getRequest('role_rating_use')) {
            $oRole->setRatingUse(1);
            $oRole->setRating(getRequest('role_rating'));
        }
        $oRole->setReg(0);
        if (getRequest('role_reg')) {
            $oRole->setReg(1);
        }
        $oRole->setAvatar(null);
        $oRole->setDateAdd(date("Y-m-d H:i:s"));
        $oRole->setPlace(getRequest('role_place_list'));

        if ($bOk and ($sId = $this->PluginRole_Role_AddRole($oRole))) {
            $oRole->setId($sId);
            /**
             * Загрузка аватара, делаем ресайзы
             */
            if (isset($_FILES['avatar']) and is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                if ($sPath = $this->PluginRole_Role_UploadAvatar($_FILES['avatar'], $oRole)) {
                    $oRole->setAvatar($sPath);
                    $oRole->setDateEdit(date("Y-m-d H:i:s"));
                    $this->PluginRole_Role_UpdateRole($oRole);
                } else {
                    $this->Message_AddError($this->Lang_Get('blog_create_avatar_error'), $this->Lang_Get('error'));
                    return false;
                }
            }
            //Router::Location(Router::GetPath('role'));
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
        }
    }

    /**
     * Выполняется при завершении работы экшена
     *
     */
    public function EventShutdown()
    {
        /**
         * Загружаем в шаблон необходимые переменные
         */
        $this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);
    }

}

?>
