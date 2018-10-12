<?php

class PluginAutoopenid_ActionSettings extends PluginAutoopenid_Inherit_ActionSettings
{

    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^openid$/i', '/^$/', 'EventOpenId');
        parent::RegisterEvent();
    }

    public function EventOpenId()
    {
        /**
         * Подключенные аккаунты
         */
        $aServicesItems = $this->PluginAutoopenid_Main_GetUserItemsByFilter(array(
                'user_id' => $this->oUserCurrent->getId(),
                '#order'  => array('id' => 'desc')
            ));
        $this->Viewer_Assign('aServicesItems', $aServicesItems);

        $this->Viewer_Assign('aAutoopenidServicesAvailable', $this->PluginAutoopenid_Main_GetServicesAvailable());
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($this->Lang_Get('plugin.autoopenid.settings.menu'));
        $this->sMenuSubItemSelect = 'openid';
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplate(Plugin::GetTemplatePath(__CLASS__) . 'actions/ActionSettings/openid.tpl');
    }

}