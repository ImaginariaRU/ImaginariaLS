<?php
/*
  Urlredirect plugin
  (P) PSNet, 2008 - 2012
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
*/

class PluginUrlredirect_HookUrlredirect extends Hook {

  public function RegisterHook () {
    $this -> AddHook ('init_action', 'AddStylesAndJS');
  }

  // ---

  public function AddStylesAndJS () {
    if (!Config::Get ('plugin.urlredirect.Highlight_External_Links')) return false;
    $sTemplateWebPath = Plugin::GetTemplateWebPath (__CLASS__);
    $this -> Viewer_AppendStyle ($sTemplateWebPath . 'css/style.css');
  }

}

?>