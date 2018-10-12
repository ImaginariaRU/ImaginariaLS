<?php
//
//  Attachments plugin
//  (P) Rafrica.net Studio, 2010 - 2012
//  http://we.rafrica.net/
//

class PluginAttachments extends Plugin {

	public function Activate () {
		if (!$this->isTableExists('attachments')){
			$this->ExportSQL(dirname(__FILE__).'/dump.sql');
		}
		// check up php settings
		$PostMaxSize = @ini_get ('post_max_size');
		$UploadMaxFilesize = @ini_get ('upload_max_filesize');
		$MaxInputTime = @ini_get ('max_input_time');
		
		$StringToInform = '';
		$StringToInform .= ($PostMaxSize ? '<br />- Maximum post file size : <b>' . $PostMaxSize . 'b</b> (post_max_size)' : '');
		$StringToInform .= ($UploadMaxFilesize ? '<br />- Maximum uploaded file size : <b>' . $UploadMaxFilesize . 'b</b> (upload_max_filesize)' : '');
		$StringToInform .= ($MaxInputTime ? '<br />- Maximum execution time : <b>' . $MaxInputTime . ' seconds</b> (max_input_time)' : '');
		
		$StringToInform .= '<br />Change your server configuration as you need in your <b>php.ini</b> file or contact your hoster for more info.';
		$StringToInform .= '<br />Plugin "Attachments" controls file size after server check up, so first you need to config correctly your php.ini.';
		
		if ($StringToInform) {
      $this -> Message_AddNoticeSingle ($StringToInform, 'Attention! Your server configuration allows ', true);
		}
		
		return true;
	}
	
	public function Deactivate () {
		return true;
	}
	
	public function Init () {}
	
	protected $aInherits = array (
    'entity' => array ('ModuleTopic_EntityTopic' => '_ModuleAttachments_EntityTopic')
  );
	
}

?>