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

class PluginRole_ModuleComment_EntityComment extends PluginRole_Inherit_ModuleComment_EntityComment
{

    public function getEditUserLogin()
    {
        return $this->User_GetUserById($this->getCommentEditUserId());
    }

}

?>
