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

class PluginIgnore_ModuleIgnore extends ModuleORM {
	protected $oMapper;
	protected $oUserCurrent=null;
    
    const IGNORE_POST_ME_PM       = 1;
    const IGNORE_HIDE_ME_COMMENT  = 2;
    const IGNORE_REPLY_MY_COMMENT = 3;
    const IGNORE_POST_MY_TOPIC    = 4;
    const IGNORE_POST_MY_WALL     = 5;
    
    protected $aIgnoreUser        = null;
    protected $aIgnoreTarget      = null;
    
	public function Init() {
		parent::Init();
		$this->oMapper=Engine::GetMapper(__CLASS__);
		$this->oUserCurrent=$this->User_GetUserCurrent();
	}
    
    public function AddUserIgnore($sUserId, $sTargetId, $sTypes, $sReason) {
		if (!$oIgnore=$this->GetIgnoreByUserIdAndTargetId($sUserId, $sTargetId)) {
			$oIgnore = LS::Ent('PluginIgnore_Ignore_Ignore');
			$oIgnore->setUserId($sUserId);
			$oIgnore->setTargetId($sTargetId);
			$oIgnore->setTypes($sTypes);
			$oIgnore->setReason($sReason);
			$oIgnore->setDateAdd(date('Y-m-d H:i:s'));
			$oIgnore->Add();
            return $oIgnore;
		}
        return false;
    }
    
    public function UpdateUserIgnore($sUserId, $sTargetId, $aTypes, $sReason) {
        if (!is_array($aTypes)) {
            return false;
        }
        $sTypes = implode('|', $aTypes);
		if (!$oIgnore = $this->GetIgnoreByUserIdAndTargetId($sUserId, $sTargetId)) {
            if ($oIgnore = $this->AddUserIgnore($sUserId, $sTargetId, $sTypes, $sReason)) {
                if (in_array(PluginIgnore_ModuleIgnore::IGNORE_POST_ME_PM, $aTypes) and !$oIgnore->getIsIgnorePostMePM()) {
                    $this->TalkIgnore($sTargetId, 'add');
                }
                return true;
            }
            return false;
		}
        if (in_array(PluginIgnore_ModuleIgnore::IGNORE_POST_ME_PM, $aTypes) and !$oIgnore->getIsIgnorePostMePM()) {
            $this->TalkIgnore($sTargetId, 'add');
        }
        if (!in_array(PluginIgnore_ModuleIgnore::IGNORE_POST_ME_PM, $aTypes) and $oIgnore->getIsIgnorePostMePM()) {
            $this->TalkIgnore($sTargetId, 'delete');
        }
        $oIgnore->setTypes($sTypes);
        $oIgnore->setReason($sReason);
        if (!count($aTypes))
            $oIgnore->Delete();
        else
            $oIgnore->Save();
		return true;
    }
    
    public function UpdateUserIgnoreType($sUserId, $sTargetId, $iType, $sCmd) {
		if (!$oIgnore = $this->GetIgnoreByUserIdAndTargetId($sUserId, $sTargetId)) {
            if ($sCmd == 'add' and $oIgnore = $this->AddUserIgnore($sUserId, $sTargetId, $iType, '')) {
                return true;
            }
            return false;
		}
        $aTypes = $oIgnore->getIgnoreTypes();
        if ($sCmd == 'add') {
            if (in_array($iType, $aTypes))
                return true;
            array_push($aTypes, $iType);
        }
        if ($sCmd == 'delete') {
            if (!in_array($iType, $aTypes))
                return true;
            unset($aTypes[array_search($iType, $aTypes)]);
        }
        if (count($aTypes)) {
            $sTypes = implode('|', $aTypes);
            $oIgnore->setTypes($sTypes);
            $oIgnore->Save();
        }
        else
            $oIgnore->Delete();
		return true;
    }
    
    public function DeleteUserIgnore($sUserId, $sTargetId) {
		if ($oIgnore=$this->GetIgnoreByUserIdAndTargetId($sUserId, $sTargetId))
            $oIgnore->Delete();
        return true;
    }
    
    public function GetUserIgnoresByTargetId($sUserId, $sTargetId, $sType = '') {
		$oIgnore = null;
        if ($sType == 'user') {
            $this->GetUserIgnored($sUserId);
            if (isset($this->aIgnoreUser[$sTargetId]))
                $oIgnore = $this->aIgnoreUser[$sTargetId];
        } elseif ($sType == 'target') {
            $this->GetTargetIgnored($sTargetId);
            if (isset($this->aIgnoreTarget[$sUserId]))
                $oIgnore = $this->aIgnoreTarget[$sUserId];
        } else {
    		if (!$oIgnore = $this->GetIgnoreByUserIdAndTargetId($sUserId, $sTargetId)) {
                return null;
    		}
        }
        return $oIgnore;
    }
    
    public function TalkIgnore($sUserId, $sAction) {
		$aUserIgnore = $this->Talk_GetBlacklistByUserId($this->oUserCurrent->getId());
        if ($sAction == 'add') {
    		if (!isset($aUserIgnore[$sUserId]))
  				if ($this->Talk_AddUserToBlackList($sUserId,$this->oUserCurrent->getId())) {}
            return;
        }
        if ($sAction == 'delete') {
    		if (isset($aUserIgnore[$sUserId]))
        		if ($this->Talk_DeleteUserFromBlacklist($sUserId,$this->oUserCurrent->getId())) {}
            return;
        }
    }

	public function UpdateCountUserIgnore($sUserId) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('user_update'));
		$this->Cache_Delete("user_{$sUserId}");
		return $this->oMapper->UpdateCountUserIgnore($sUserId);
	}
    
    public function GetUserIgnored($sUserId, $bCache = true) {
        if ($this->aIgnoreUser !== null)
            return;
        $aIgnoreUser = array();
		$aResult=$this->GetIgnoreItemsByUserId($sUserId);
		foreach ($aResult as $oIgnore)
            $aIgnoreUser[$oIgnore->getTargetId()] = $oIgnore;
        if (!$bCache)
            return $aIgnoreUser;
        $this->aIgnoreUser = $aIgnoreUser;
    }
    
    public function GetTargetIgnored($sTargetId, $bCache = true) {
        if ($this->aIgnoreTarget !== null)
            return;
        $aIgnoreTarget = array();
		$aResult=$this->GetIgnoreItemsByTargetId($sTargetId);
		foreach ($aResult as $oIgnore)
            $aIgnoreTarget[$oIgnore->getUserId()] = $oIgnore;
        if (!$bCache)
            return $aIgnoreTarget;
        $this->aIgnoreTarget = $aIgnoreTarget;
    }
    
}


















?>