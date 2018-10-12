<?php

class PluginAutoopenid_ActionAjax extends PluginAutoopenid_Inherit_ActionAjax
{

    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^autoopenid$/i', '/^login-oauth$/', 'EventAutoopenidLoginOauth');
        $this->AddEventPreg('/^autoopenid$/i', '/^service-remove$/', 'EventAutoopenidServiceRemove');
        parent::RegisterEvent();
    }

    public function EventAutoopenidLoginOauth()
    {
        $sService = getRequestStr('service');
        /**
         * Получаем сервис
         */
        if (!$oService = $this->PluginAutoopenid_Main_GetService($sService)) {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
            return;
        }
        /**
         * Сохраняем реферер для обратного редиректа
         */
        $this->PluginAutoopenid_Main_SaveReferalUrl(getRequestStr('referal'));

        $aParams = array();
        if ($oService->getOAuthVersion() == 1) {
            if ($oToken = $oService->requestRequestToken()) {
                $aParams['oauth_token'] = $oToken->getRequestToken();
            } else {
                $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
                return;
            }
        }
        $oUrl = $oService->getAuthorizationUri($aParams);
        $this->Viewer_AssignAjax('sUrl', (string)$oUrl);
    }

    public function EventAutoopenidServiceRemove()
    {
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
            return;
        }
        if (!$oOpenidUser = $this->PluginAutoopenid_Main_GetUserByFilter(array(
            'user_id'      => $this->oUserCurrent->getId(),
            'service_type' => getRequestStr('type'),
            'service_id'   => getRequestStr('id')
        ))
        ) {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
            return;
        }
        $aServiceItems = $this->PluginAutoopenid_Main_GetUserItemsByUserId($this->oUserCurrent->getId());
        if (!$this->oUserCurrent->getMail() and count($aServiceItems) == 1) {
            $this->Message_AddErrorSingle($this->Lang_Get('plugin.autoopenid.errors.service_last'));
            return;
        }
        $this->Viewer_AssignAjax('id', $oOpenidUser->getId());
        $oOpenidUser->Delete();
    }

}