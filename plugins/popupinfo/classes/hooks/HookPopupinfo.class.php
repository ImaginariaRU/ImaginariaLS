<?php
/**
 * Popupinfo plugin
 *
 * @copyright Serge Pustovit (PSNet), 2008 - 2014
 * @author    Serge Pustovit (PSNet) <light.feel@gmail.com>
 *
 * @link      http://psnet.lookformp3.net
 * @link      http://livestreet.ru/profile/PSNet/
 * @link      https://catalog.livestreetcms.com/profile/PSNet/
 * @link      http://livestreetguide.com/developer/PSNet/
 */


class PluginPopupinfo_HookPopupinfo extends Hook {

	public function RegisterHook() {
		$this->AddHook('engine_init_complete', 'AddStylesAndJS');
		$this->AddHook('template_body_begin', 'BodyBegin');
	}


	public function BodyBegin() {
		if (Config::Get('plugin.popupinfo.Only_Registered_Users_Can_See_Info_Tips') and !$this->User_IsAuthorization()) {
			return false;
		}
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'body_begin.tpl');
	}


	public function AddStylesAndJS() {
		$sTemplateWebPath = Plugin::GetTemplateWebPath(__CLASS__);
		$this->Viewer_AppendStyle($sTemplateWebPath . 'css/style.css');
		$this->Viewer_AppendScript($sTemplateWebPath . 'js/init.js');
	}

}


?>