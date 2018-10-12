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

class PluginRole_ModuleACL extends PluginRole_Inherit_ModuleACL
{

    public function Init()
    {
        parent::Init();
    }

    /**
     * Проверяет можно или нет пользователю удалять данный топик
     *
     * @param object $oTopic
     * @param object $oUser
     */
    public function IsAllowDeleteTopic($oTopic, $oUser)
    {

        if ($oTopic->getId() == 1)
            return false;

        $aResult = parent::IsAllowDeleteTopic($oTopic, $oUser);

        if ($aResult == true)
            return true;

        if ($oUser) {
            $aRole = $oUser->getRole();
            if (!empty($aRole['blog']['topic']['delete'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет может ли пользователь создавать топики в определенном блоге
     *
     * @param Entity_User $oUser
     * @param Entity_Blog $oBlog
     * @return bool
     */
    public function CanAddTopic(ModuleUser_EntityUser $oUser, ModuleBlog_EntityBlog $oBlog)
    {
        $aResult = parent::CanAddTopic($oUser, $oBlog);

        if ($aResult == true)
            return true;

        if ($oUser) {
            $aRole = $oUser->getRole();
            if (!empty($aRole['blog']['topic']['edit']) or !empty($aRole['blog']['topic']['add'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет можно или нет юзеру постить в данный блог
     *
     * @param object $oBlog
     * @param object $oUser
     */
    public function IsAllowBlog($oBlog, $oUser)
    {
        $aResult = parent::IsAllowBlog($oBlog, $oUser);

        if ($aResult == true)
            return true;

        if ($oUser) {
            $aRole = $oUser->getRole();
            if (!empty($aRole['blog']['topic']['edit']) or !empty($aRole['blog']['topic']['add'])) {
                return true;
            }
            $aParams = Router::GetParams();
            $iTopicId = $aParams[0];
            if ($oTopic = $this->Topic_GetTopicById($iTopicId) and $oTopic->getType() == 'cck') {
                $oType = $this->PluginCck_Cck_GetTypeByTopicId($oTopic->getId());
                if (!empty($aRole[$oType->getSystem()]) and !empty($aRole[$oType->getSystem()]['edit'])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Проверяет можно или нет пользователю редактировать данный топик
     *
     * @param  object $oTopic
     * @param  object $oUser
     * @return bool
     */
    public function IsAllowEditTopic($oTopic, $oUser)
    {
        if ($oTopic->getId() == 1)
            return false;

        $aResult = parent::IsAllowEditTopic($oTopic, $oUser);

        if ($aResult == true)
            return true;

        if ($oUser) {
            $aRole = $oUser->getRole();
            if (!empty($aRole['blog']['topic']['edit'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет может ли пользователь создавать топик по времени
     *
     * @param  Entity_User $oUser
     * @return bool
     */
    public function CanPostTopicTime(ModuleUser_EntityUser $oUser)
    {
        $aResult = parent::CanPostTopicTime($oUser);

        if ($aResult == true)
            return true;

        if ($oUser) {
            $aRole = $oUser->getRole();
            if (!empty($aRole['blog']['topic']['add'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет может ли пользователь создавать блоги
     *
     * @param Entity_User $oUser
     * @return bool
     */
    public function CanCreateBlog(ModuleUser_EntityUser $oUser)
    {

        $aResult = parent::CanCreateBlog($oUser);

        if ($aResult == true)
            return true;

        if ($oUser) {
            $aRole = $oUser->getRole();
            if (!empty($aRole['blog']['add'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет можно или нет пользователю редактировать данный блог
     *
     * @param  object $oBlog
     * @param  object $oUser
     * @return bool
     */
    public function IsAllowEditBlog($oBlog, $oUser)
    {
        $aResult = parent::IsAllowEditBlog($oBlog, $oUser);

        if ($aResult == true)
            return true;

        if ($oUser) {
            $aRole = $oUser->getRole();
            if (!empty($aRole['blog']['edit'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет можно или нет пользователю удалять данный блог
     *
     * @param object $oBlog
     * @param object $oUser
     */
    public function IsAllowDeleteBlog($oBlog, $oUser)
    {
        $aResult = parent::IsAllowDeleteBlog($oBlog, $oUser);

        if ($aResult == true)
            return true;

        if ($oUser) {
            $aRole = $oUser->getRole();
            if (!empty($aRole['blog']['delete'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет может ли пользователь редактировать комментарий
     *
     * @param  Entity_User $oUser
     * @return bool
     */
    public function CanEditComment($oComment, $oUser)
    {
        if (!$oUser)
            return false;

        if ($oUser->isAdministrator()) {
            return true;
        }

        $sTargetType = $oComment->getTargetType();
        $aRole = $oUser->getRole();
        if ($sTargetType == 'topic') {

            if (!empty($aRole['blog']['topic']['comment']['edit'])) {
                return true;
            }

            if ($oUser->getId() == $oComment->getUserId() and Config::Get('plugin.role.edit_author')) {
                if (Config::Get('plugin.role.limit_edit_time') and strtotime($oComment->getDate()) < strtotime(date("Y-m-d H:i:s", time() - Config::Get('plugin.role.limit_edit_time')))) {
                    $this->Message_AddErrorSingle($this->Lang_Get('not_access_time_limit'), $this->Lang_Get('error'));
                    return false;
                }
                if (Config::Get('plugin.role.children_isset') and $this->PluginRole_Role_GetCountChildrenByCommentId($oComment->getId())) {
                    $this->Message_AddErrorSingle($this->Lang_Get('not_access_children_isset'), $this->Lang_Get('error'));
                    return false;
                }
                return true;
            }
        }

        if ($sTargetType != 'cck' and !empty($aRole[$sTargetType]) and !empty($aRole[$sTargetType]['comments']['edit'])) {
            return true;
        }

        if ($sTargetType == 'cck') {
            $oType = $this->PluginCck_Cck_GetTypeByTopicId($oComment->getTargetId());
            if (!empty($aRole[$oType->getSystem()]) and !empty($aRole[$oType->getSystem()]['comments']['edit'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет может ли пользователь удалить комментарий
     *
     * @param  Entity_User $oUser
     * @return bool
     */
    public function CanDeleteComment($oUser)
    {
        $aResult = parent::CanDeleteComment($oUser);

        if ($aResult == true)
            return true;


        $idComment = getRequest('idComment', null, 'post');

        if ($oComment = $this->Comment_GetCommentById($idComment)) {
            $sTargetType = $oComment->getTargetType();
            $aRole = $oUser->getRole();
            if ($sTargetType == 'topic') {
                if (!empty($aRole['blog']['topic']['comment']['delete'])) {
                    return true;
                }
            }

            if (!empty($aRole[$sTargetType]) and !empty($aRole[$sTargetType]['comments']['delete'])) {
                return true;
            }

        }

        return false;
    }

    /**
     * Права для баннероида
     *
     */

    /**
     * Проверяет может ли пользователь добавить баннер
     *
     * @param  Entity_User $oUser
     * @return bool
     */
    public function CanAddBanner($oUser)
    {

        if ($oUser) {
            $aRole = $oUser->getRole();
            if ($oUser->isAdministrator() or !empty($aRole['banner']['add'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет может ли пользователь редактировать баннер
     *
     * @param  Entity_User $oUser
     * @return bool
     */
    public function IsAllowEditBanner($oUser)
    {

        if ($oUser) {
            $aRole = $oUser->getRole();
            if ($oUser->isAdministrator() or !empty($aRole['banner']['edit'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет может ли пользователь удалить баннер
     *
     * @param  Entity_User $oUser
     * @return bool
     */
    public function CanDeleteBanner($oUser)
    {

        if ($oUser) {
            $aRole = $oUser->getRole();
            if ($oUser->isAdministrator() or !empty($aRole['banner']['delete'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет может ли пользователь смотреть статистику баннеров
     *
     * @param  Entity_User $oUser
     * @return bool
     */
    public function CanStatBanner($oUser)
    {

        if ($oUser) {
            $aRole = $oUser->getRole();
            if ($oUser->isAdministrator() or !empty($aRole['banner']['stat'])) {
                return true;
            }
        }

        return false;
    }

}

?>
