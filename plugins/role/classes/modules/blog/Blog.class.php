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

class PluginRole_ModuleBlog extends PluginRole_Inherit_ModuleBlog
{

    public function Init()
    {
        parent::Init();
    }

    /**
     * Получает список блогов в которые может постить юзер
     *
     * @param unknown_type $oUser
     * @return unknown
     */
    public function GetBlogsAllowByUser($oUser)
    {
        $aRole = $oUser->getRoleUser();
        if ($oUser->isAdministrator() or !empty($aRole['blog']['topic']['add'])) {
            return $this->GetBlogs();
        } else {
            $aAllowBlogsUser = $this->GetBlogsByOwnerId($oUser->getId());
            $aBlogUsers = $this->GetBlogUsersByUserId($oUser->getId());
            foreach ($aBlogUsers as $oBlogUser) {
                $oBlog = $oBlogUser->getBlog();
                if ($this->ACL_CanAddTopic($oUser, $oBlog) or $oBlogUser->getIsAdministrator() or $oBlogUser->getIsModerator()) {
                    $aAllowBlogsUser[$oBlog->getId()] = $oBlog;
                }
            }
            return $aAllowBlogsUser;
        }
    }

}

?>
