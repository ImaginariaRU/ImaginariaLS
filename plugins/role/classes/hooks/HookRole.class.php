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

class PluginRole_HookRole extends Hook
{

    public function RegisterHook()
    {
        $this->AddHook('init_action', 'InitAction', __CLASS__);
        $this->AddHook('engine_init_complete', 'AddRoleBlock', __CLASS__, 0);
        $this->AddHook('template_profile_whois_item', 'WhoisInfo', __CLASS__);
        if (!empty($_COOKIE['ls_avatar_id'])) {
            $this->AddHook('template_body_begin', 'BodyBegin', __CLASS__);
        }
        $this->AddHook('template_form_registration_begin', 'FormRregistrationBegin', __CLASS__);
        $this->AddHook('template_comment_action', 'CommentAction', __CLASS__);

        $this->AddHook('template_topic_content_begin', 'TopicContentBegin', __CLASS__);
        $this->AddHook('template_blog_info_end', 'BlogInfoEnd', __CLASS__);
        $this->AddHook('template_admin_action_item', 'MenuAdmin', __CLASS__);

        $this->AddHook('registration_after', 'RegistrationValidateAfter', __CLASS__);
        $this->AddHook('registration_after', 'RegistrationAfter', __CLASS__);
    }

    public function RegistrationAfter($aVar)
    {
        $oUser = $aVar['oUser'];
        if ($oRole = $this->PluginRole_Role_GetRoleById(getRequest('role_id'))) {
            $oUserRole = new PluginRole_ModuleRole_EntityRoleUser();
            $oUserRole->setUserId($oUser->getId());
            $oUserRole->setRoleId($oRole->getId());
            $this->PluginRole_Role_AddUserRole($oUserRole);
        }
    }

    public function RegistrationValidateAfter($aVar)
    {
        /**
         * Проверка роли
         */
        $bError = false;
        if (getRequest('role_add')) {
            if (!isPost('role_id')) {
                $this->Message_AddError($this->Lang_Get('plugin.role.registration_empty'), $this->Lang_Get('error'));
                $bError = true;
            } else {
                if (!($oRole = $this->PluginRole_Role_GetRoleById(getRequest('role_id')))) {
                    $this->Message_AddError($this->Lang_Get('plugin.role.registration_error'), $this->Lang_Get('error'));
                    $bError = true;
                }
            }
            if ($bError) {
                return;
            }
        }
    }

    public function AddRoleBlock()
    {
        if (Router::GetAction() != 'role') {
            $aRolePlaces = $this->PluginRole_Role_GetSideBarRole($_SERVER['REQUEST_URI']);
            if (!empty($aRolePlaces)) {
                foreach ($aRolePlaces as $oRole) {
                    if ($oRole->getUsers()) {
                        $this->Viewer_AddBlock('right', 'roleUserBlockPlace', array('plugin' => 'role', 'oRole' => $oRole), $oRole->getPosition());
                    }
                }
            }
        }
    }

    public function WhoisInfo($aVar)
    {
        $oUserProfile = $aVar['oUserProfile'];
        $aRole = $oUserProfile->getRole();

        $this->Viewer_Assign('oRole', $aRole['object']);
        return $this->Viewer_Fetch(Plugin::GetTemplatePath('role') . 'profile.avatar_role.tpl');
    }

    public function BodyBegin()
    {
        if (isset($_GET['exitavatar'])) {
            if ($oUserExit = $this->User_GetUserById($_COOKIE['ls_avatar_id'])) {
                setcookie('ls_avatar_id', '', 1, Config::Get('sys.cookie.path'), Config::Get('sys.cookie.host'));
                $this->User_Authorization($oUserExit, true);
                Router::Location(Router::GetPath('role') . 'avatar/');
            }
        }

        $oUserAvatar = $this->User_GetUserById($_COOKIE['ls_avatar_id']);
        $this->Viewer_Assign('oUserAvatar', $oUserAvatar);
        return $this->Viewer_Fetch(Plugin::GetTemplatePath('role') . 'body_begin.tpl');
    }

    public function InitAction()
    {
        if (Router::GetAction() == 'role' and Router::GetActionEvent() == 'people') {
            Router::Action('role_people', 'settings');
        }
    }

    public function CommentAction($aVar)
    {
        $oComment = $aVar['comment'];
        $oUserCurrent = $aVar['user_current'];

        if ($this->ACL_CanEditComment($oComment, $oUserCurrent)) {
            $this->Viewer_Assign('oComment', $oComment);
            $this->Viewer_Assign('oUserCurrent', $oUserCurrent);

            $this->Viewer_Assign('bEditAuthor', Config::Get('plugin.role.edit_author'));
            return $this->Viewer_Fetch(Plugin::GetTemplatePath('role') . 'comment_action.tpl');
        }
    }

    public function FormRregistrationBegin()
    {

        $aRoleReg = $this->PluginRole_Role_GetRoleByReg(1);
        $this->Viewer_Assign('aRoleReg', $aRoleReg);
        return $this->Viewer_Fetch(Plugin::GetTemplatePath('role') . 'form_registration_begin.tpl');
    }

    /**
     * Меню топика
     */
    public function TopicContentBegin($aVars)
    {
        $this->Viewer_Assign('oTopic', $aVars['topic']);
        return $this->Viewer_Fetch(Plugin::GetTemplatePath('role') . 'topic.menu.tpl');
    }

    /**
     * Меню блога
     */
    public function BlogInfoEnd()
    {
        $this->Viewer_Assign('oBlog', $aVars['oBlog']);
        return $this->Viewer_Fetch(Plugin::GetTemplatePath('role') . 'blog.menu.tpl');
    }

    /**
     * Меню админа
     */
    public function MenuAdmin()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath('role') . 'menu.admin.tpl');
    }

}

?>
