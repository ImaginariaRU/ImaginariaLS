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
class PluginRole_BlockRoleUserBlockPlace extends Block
{

    public function Exec()
    {
        $oRole = $this->GetParam('oRole');
        $this->Viewer_Assign("oRole", $oRole);
    }

}