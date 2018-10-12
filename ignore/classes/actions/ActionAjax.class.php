<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Сделано руками @ Сергей Сарафанов (sersar)
*   ICQ: 172440790 | E-mail: sersar@ukr.net
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

class PluginIgnore_ActionAjax extends PluginIgnore_Inherit_ActionAjax
{
    protected function RegisterEvent(){
        parent::RegisterEvent();
		$this->AddEventPreg('/^ignore$/i','/^setting$/','EventIgnoreSetting');
		$this->AddEventPreg('/^ignore$/i','/^window$/','EventIgnoreWindow');
    }

	protected function EventIgnoreSetting() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		$sUserId=getRequestStr('idUser');
		$sData=getRequestStr('data','');
		$sReason=getRequestStr('reason','');
		if ($this->oUserCurrent->getId()==$sUserId) {
			$this->Message_AddErrorSingle($this->Lang_Get('error'),$this->Lang_Get('error'));
			return;
		}
		if( !$oUser=$this->User_GetUserById($sUserId) ) {
			$this->Message_AddErrorSingle($this->Lang_Get('user_not_found'),$this->Lang_Get('error'));
			return;
		}
        $aIgnore = explode('|', $sData);
        if (empty($sData)) {
            if ($this->PluginIgnore_Ignore_DeleteUserIgnore($this->oUserCurrent->getId(), $sUserId)) {
    			$this->Message_AddNoticeSingle($this->Lang_Get('plugin.ignore.notice_delete_user_ignore'));
                $this->PluginIgnore_Ignore_TalkIgnore($sUserId, 'delete');
                $this->PluginIgnore_Ignore_UpdateCountUserIgnore($sUserId);
            }
            return;
        }
        if ($this->PluginIgnore_Ignore_UpdateUserIgnore($this->oUserCurrent->getId(), $sUserId, $aIgnore, $sReason)) {
			$this->Message_AddNoticeSingle($this->Lang_Get('plugin.ignore.notice_update_user_ignore'));
            $this->PluginIgnore_Ignore_UpdateCountUserIgnore($sUserId);
			return;
        } else {
			$this->Message_AddErrorSingle($this->Lang_Get('error'),$this->Lang_Get('error'));
			return;
        }
    }
    
	protected function EventIgnoreWindow() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		$sUserId=getRequestStr('idUser');
		if ($this->oUserCurrent->getId()==$sUserId) {
			$this->Message_AddErrorSingle($this->Lang_Get('error'),$this->Lang_Get('error'));
			return;
		}
		if( !$oUser=$this->User_GetUserById($sUserId) ) {
			$this->Message_AddErrorSingle($this->Lang_Get('user_not_found'),$this->Lang_Get('error'));
			return;
		}
        $oIgnore = $this->PluginIgnore_Ignore_GetUserIgnoresByTargetId($this->oUserCurrent->getId(), $oUser->getId());
		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign("oIgnore",$oIgnore);
        $oViewer->Assign("bIgnore", $oIgnore ? false : true);
        $oViewer->Assign("oUserProfile",$oUser);
		$sTextResult=$oViewer->Fetch(Plugin::GetTemplatePath('ignore').'ignore_window.tpl');
		$this->Viewer_AssignAjax('sText',$sTextResult);
    }
}


















?>