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

class PluginRole_ModulePeople extends Module
{

    protected $oMapper;

    public function Init()
    {
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

    public function DeleteUserById($oUser)
    {

        if ($this->oMapper->DeleteUserById($oUser->getId())) {
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('user_update'));
            $this->Cache_Delete("user_{$oUser->getId()}");
            $this->User_DeleteAvatar($oUser);
            $this->User_DeleteFoto($oUser);
            return true;
        }
        return false;
    }

    public function GetUserList(&$iCount, $iCurrPage, $iPerPage, $aFilter = Array(), $aSort = Array())
    {
        $filter = serialize($aFilter);
        $sort = serialize($aSort);
        $sCacheKey = 'adm_user_list_' . $filter . '_' . $sort . '_' . $iCurrPage . '_' . $iPerPage;
        //if (false === ($data = $this->Cache_Get($sCacheKey))) {
        $data = array('collection' => $this->oMapper->GetUserList($iCount, $iCurrPage, $iPerPage, $aFilter, $aSort), 'count' => $iCount);
        if ($data['count']) {
            $aUserId = array();
            foreach ($data['collection'] as $oUser) {
                $aUserId[] = $oUser->getId();
            }
            $aSessions = $this->User_GetSessionsByArrayId($aUserId);
            foreach ($data['collection'] as $oUser) {
                if (isset($aSessions[$oUser->getId()])) {
                    $oUser->setSession($aSessions[$oUser->getId()]);
                } else {
                    $oUser->setSession(null); // или $oUser->setSession(new UserEntity_Session());
                }
            }
        }
        $this->Cache_Set($data, $sCacheKey, array("user_update", "user_new"), 60 * 15);
        //}
        return $data;
    }

}

?>
