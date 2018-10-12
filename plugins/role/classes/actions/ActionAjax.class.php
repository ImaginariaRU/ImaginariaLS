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

class PluginRole_ActionAjax extends ActionPlugin
{

    protected $oUserCurrent = null;

    /**
     * Инициализация
     *
     */
    public function Init()
    {
        $this->Viewer_SetResponseAjax('json');
        $this->oUserCurrent = $this->User_GetUserCurrent();
    }

    /**
     * Регистрируем необходимые евенты
     *
     */
    protected function RegisterEvent()
    {
        $this->AddEvent('adduser', 'AddUser');
        $this->AddEvent('saveuserrole', 'SaveUserRole');
        $this->AddEvent('saveroleacl', 'SaveRoleAcl');
        $this->AddEvent('saverole', 'SaveRole');
        $this->AddEvent('deluser', 'DelUser');
        $this->AddEvent('delrole', 'DelRole');
        $this->AddEvent('deluserrole', 'DelUserRole');

        $this->AddEvent('deladmin', 'DelAdmin');

        $this->AddEvent('savecomment', 'SaveComment');
    }

    protected function SaveComment()
    {

        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return;
        }
        if (!($oComment = $this->Comment_GetCommentById(getRequest('commentId', null)))) {
            $this->Message_AddErrorSingle($this->Lang_Get('plugin.role.comment_error_not_found'), $this->Lang_Get('error'));
            return;
        }

        if (!$this->ACL_CanEditComment($oComment, $this->oUserCurrent)) {
            $this->Message_AddErrorSingle($this->Lang_Get('plugin.role.comment_error_can_edit'), $this->Lang_Get('error'));
            return;
        }

        $sText = $this->Text_Parser(getRequest('text'));
        if (!func_check($sText, 'text', 2, 10000)) {
            $this->Message_AddErrorSingle($this->Lang_Get('topic_comment_add_text_error'), $this->Lang_Get('error'));
            return;
        }

        $oComment->setText($sText);
        $oComment->setCommentTextSource(getRequest('comment_text'));
        $oComment->setTextHash(md5($sText));
        $oComment->setCommentDateEdit(date("Y-m-d H:i:s"));
        $oComment->setCommentEditUserId($this->oUserCurrent->getId());

        if ($this->Comment_UpdateComment($oComment)) {
            $this->PluginRole_Role_UpdateComment($oComment);
            $this->oUserCurrent->setDateCommentLast(date("Y-m-d H:i:s"));
            $this->User_Update($this->oUserCurrent);
        }

        $this->Viewer_AssignAjax('sText', $sText);
        $this->Message_AddNotice($this->Lang_Get('plugin.role.comment_edit_ok'), $this->Lang_Get('attention'));
        return;
    }

    protected function DelAdmin()
    {
        if (!$this->oUserCurrent->isAdministrator()) {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return;
        }
        $sUserId = getRequest('sId');
        if (!($oUser = $this->User_GetUserById($sUserId))) {
            $this->Message_AddError($this->Lang_Get('user_not_found'), $this->Lang_Get('error'));
            return;
        }
        if (!$oUser->isAdministrator()) {
            $this->Message_AddError($this->Lang_Get('plugin.role.admin_users_not_admin'), $this->Lang_Get('error'));
            return;
        }
        $aAdminId = Config::Get('plugin.role.admins_id');
        if (in_array($oUser->getId(), $aAdminId)) {
            $this->Message_AddError($this->Lang_Get('plugin.role.admin_not_accesses'), $this->Lang_Get('error'));
            return;
        }

        if ($this->PluginRole_Role_DeleteAdmin($oUser->getId())) {
            $this->Message_AddNotice($this->Lang_Get('plugin.role.user_delete_ok'), $this->Lang_Get('attention'));
            return;
        } else {
            $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
            return;
        }
    }

    protected function DelUser()
    {
        if (!$this->oUserCurrent->isAdministrator()) {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return;
        }
        $sUserId = getRequest('sUserId');
        if (!($oUser = $this->User_GetUserById($sUserId))) {
            $this->Message_AddError($this->Lang_Get('user_not_found'), $this->Lang_Get('error'));
            return;
        }
        if (!($oRoleUser = $this->PluginRole_Role_GetAclRoleUsersByUserId($oUser->getId()))) {
            $this->Message_AddError($this->Lang_Get('plugin.role.users_not_id'), $this->Lang_Get('error'));
            return;
        }

        if ($this->PluginRole_Role_DeleteAclRoleUserByUserId($oUser->getId())) {
            $this->Message_AddNotice($this->Lang_Get('plugin.role.user_delete_ok'), $this->Lang_Get('attention'));
            return;
        } else {
            $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
            return;
        }
    }

    protected function DelUserRole()
    {
        if (!$this->oUserCurrent->isAdministrator()) {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return;
        }
        $sRoleId = getRequest('sRoleId');
        $sUserId = getRequest('sUserId');

        if (!($oUser = $this->User_GetUserById($sUserId))) {
            $this->Message_AddError($this->Lang_Get('user_not_found'), $this->Lang_Get('error'));
            return;
        }
        if (!($oRole = $this->PluginRole_Role_GetRoleById($sRoleId))) {
            $this->Message_AddError($this->Lang_Get('plugin.role.not_found'), $this->Lang_Get('error'));
            return;
        }
        if (!($oUserRole = $this->PluginRole_Role_GetUserRoleByIds($oUser->getId(), $oRole->getId()))) {
            $this->Message_AddError($this->Lang_Get('plugin.role.user_role_not_exist'), $this->Lang_Get('error'));
            return;
        }

        if ($this->PluginRole_Role_DeleteUserRole($oUserRole)) {
            $this->Message_AddNotice($this->Lang_Get('plugin.role.user_role_del_ok'), $this->Lang_Get('attention'));
            return;
        } else {
            $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
            return;
        }
    }

    protected function DelRole()
    {
        if (!$this->oUserCurrent->isAdministrator()) {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return;
        }
        $sRoleId = getRequest('sRoleId');
        if (!($oRole = $this->PluginRole_Role_GetRoleById($sRoleId))) {
            $this->Message_AddError($this->Lang_Get('plugin.role.not_found'), $this->Lang_Get('error'));
            return;
        }

        if ($this->PluginRole_Role_DeleteRole($oRole)) {
            $this->Message_AddNotice($this->Lang_Get('plugin.role.delete_ok'), $this->Lang_Get('attention'));
            return;
        } else {
            $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
            return;
        }
    }

    protected function SaveRole()
    {
        if (getRequest('is_iframe')) {
            $this->Viewer_SetResponseAjax('jsonIframe', false);
        } else {
            $this->Viewer_SetResponseAjax('json');
        }
        if (!$this->oUserCurrent->isAdministrator()) {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return;
        }
        $sRoleId = getRequest('role_id');
        if (!($oRole = $this->PluginRole_Role_GetRoleById($sRoleId))) {
            $this->Message_AddError($this->Lang_Get('plugin.role.not_found'), $this->Lang_Get('error'));
            return;
        }
        if (!func_check(getRequest('role_name', null, 'post'), 'text', 2, 200)) {
            $this->Message_AddError($this->Lang_Get('plugin.role.create_name_error'), $this->Lang_Get('error'));
            return;
        }
        if (!func_check(getRequest('role_text', null, 'post'), 'text', 2, Config::Get('plugin.role.max_length_text'))) {
            $this->Message_AddError($this->Lang_Get('plugin.role.create_text_error'), $this->Lang_Get('error'));
            $bOk = false;
        }

        if (!isPost('role_name')) {
            $this->Message_AddError($this->Lang_Get('plugin.role.create_name_error'), $this->Lang_Get('error'));
            return;
        }

        $oRole->setName(getRequest('role_name'));
        $oRole->setText(getRequest('role_text'));
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

        $oRole->setDateEdit(date("Y-m-d H:i:s"));

        if (getRequest('avatar_delete')) {
            $this->PluginRole_Role_DeleteAvatar($oRole);
            $this->Viewer_AssignAjax('delete_avatar', true);
            $oRole->setAvatar(null);
        }

        if (isset($_FILES['avatar']) and is_uploaded_file($_FILES['avatar']['tmp_name'])) {
            if ($sPath = $this->PluginRole_Role_UploadAvatar($_FILES['avatar'], $oRole)) {
                $oRole->setAvatar($sPath);
                $this->Viewer_AssignAjax('edit_avatar', true);
                $aSize = Config::Get('plugin.role.avatar_size');
                $sAvatarHtml = '';
                $bAvatarHtml = false;

                foreach ($aSize as $iSize) {
                    if ($iSize) {
                        $sAvatarHtml .= '<img src="' . $oRole->getAvatarPath($iSize) . '" />';
                        $bAvatarHtml = true;
                    }
                }

                $this->Viewer_AssignAjax('bAvatarHtml', $bAvatarHtml);
                $this->Viewer_AssignAjax('sAvatarHtml', $sAvatarHtml);
            } else {
                $this->Message_AddError($this->Lang_Get('blog_create_avatar_error'), $this->Lang_Get('error'));
                return false;
            }
        }

        $oRole->setPlace(getRequest('role_place_list'));

        if ($this->PluginRole_Role_UpdateRole($oRole)) {
            $this->Viewer_AssignAjax('sName', $oRole->getName());
            $this->Message_AddNotice($this->Lang_Get('plugin.role.edit_role_ok'), $this->Lang_Get('attention'));
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
        }
    }

    protected function SaveRoleAcl()
    {
        if (!$this->oUserCurrent->isAdministrator()) {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return;
        }
        $sRoleId = getRequest('role_id');
        if (!($oRole = $this->PluginRole_Role_GetRoleById($sRoleId))) {
            $this->Message_AddError($this->Lang_Get('plugin.role.not_found'), $this->Lang_Get('error'));
            return;
        }

        if (!isPost('role')) {
            $this->Message_AddError($this->Lang_Get('plugin.role.create_acl_error'), $this->Lang_Get('error'));
            return;
        }

        if (!isPost('role')) {
            $_REQUEST['role'] = array();
        }


        $sRole = serialize(getRequest('role'));

        if ($sRole == $oRole->getAcl()) {
            $this->Message_AddError($this->Lang_Get('plugin.role.acl_no_edit'), $this->Lang_Get('error'));
            return;
        }

        $oRole->setAcl($sRole);
        $oRole->setDateEdit(date("Y-m-d H:i:s"));

        if ($this->PluginRole_Role_UpdateRole($oRole)) {
            $this->Message_AddNotice($this->Lang_Get('plugin.role.edit_role_ok'), $this->Lang_Get('attention'));
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
        }
    }

    protected function SaveUserRole()
    {
        if (!$this->oUserCurrent->isAdministrator()) {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return;
        }

        $bOk = true;
        if (!($oUser = $this->User_GetUserById(getRequest('user_id', null, 'post')))) {
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
        $this->Message_AddNotice($this->Lang_Get('plugin.role.save_ok'), $this->Lang_Get('attencion'));
    }

    protected function AddUser()
    {
        if (!$this->oUserCurrent->isAdministrator()) {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return;
        }
        $sRoleId = getRequest('sRoleId');
        $sLogin = getRequest('sLogin');

        if (!($oUser = $this->User_GetUserByLogin($sLogin))) {
            $this->Message_AddError($this->Lang_Get('user_not_found'), $this->Lang_Get('error'));
            return;
        }
        if (!($oRole = $this->PluginRole_Role_GetRoleById($sRoleId))) {
            $this->Message_AddError($this->Lang_Get('plugin.role.not_found'), $this->Lang_Get('error'));
            return;
        }


        if (!($oUserRole = $this->PluginRole_Role_GetUserRoleByIds2($oUser->getId(), $oRole->getId()))) {
            $oUserRole = new PluginRole_ModuleRole_EntityRoleUser();
            $oUserRole->setUserId($oUser->getId());
            $oUserRole->setRoleId($oRole->getId());
            if ($this->PluginRole_Role_AddUserRole($oUserRole)) {
                $this->Message_AddNotice($this->Lang_Get('plugin.role.user_role_add_ok'), $this->Lang_Get('attention'));
                $this->Viewer_AssignAjax('sLogin', $oUser->getLogin());
                $this->Viewer_AssignAjax('sId', $oUser->getId());
                return;
            } else {
                $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
                return;
            }
        } else {
            $this->Message_AddError($this->Lang_Get('plugin.role.user_role_exist'), $this->Lang_Get('error'));
            return;
        }
    }

}

?>