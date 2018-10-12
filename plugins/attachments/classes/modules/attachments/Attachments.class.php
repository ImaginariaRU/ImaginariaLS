<?php
//
//  Attachments plugin
//  (P) Rafrica.net Studio, 2010 - 2012
//  http://we.rafrica.net/
//

class PluginAttachments_ModuleAttachments extends Module {		
	protected $oMapper;
	protected $aErrorMessages = array();
	protected $bError = false;
		
	//**************************************************************************************************
	public function Init() {		
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}
	
	//**************************************************************************************************
	public function SetError($sError,$iToShow=0,$iToLog=1){
		if($iToShow == 1){
			$this->aErrorMessages[] = $sError;
		}
		
		if($iToLog == 1){
			$this->Logger_Error($sError);
		}
		
		$this->bError = true;
	}
	
	//**************************************************************************************************
	public function SetDebug($sText){
		if(Config::Get('plugin.attachments.debug_mode')){
			$this->Logger_Debug($sText);
		}
	}
	
	//**************************************************************************************************
	public function GetErrors(){
		return $this->aErrorMessages;
	}
	
	//**************************************************************************************************
	public function CheckErrorStatus(){
		return $this->bError;
	}
	
	//**************************************************************************************************
	public function GetUploadDir($sUserId) {
		return Config::Get('plugin.attachments.uploads_dir').'/'.preg_replace('~(.{2})~U', "\\1/", str_pad($sUserId, 6, "0", STR_PAD_LEFT)).date('Y/m/d');
	}

	//**************************************************************************************************
	public function AttachFile($sPath,$sName,$iFileSize,$sExtension,$iTopicId,$iFormId,$iUserId){
		$sDestDir = $this->GetUploadDir($iUserId);
		
		if(!is_dir(Config::Get('path.root.server').$sDestDir)){
			mkdir(Config::Get('path.root.server').$sDestDir,0777,true);
			if(!is_dir(Config::Get('path.root.server').$sDestDir)){
				$this->SetError($this->Lang_Get('plugin.attachments.upload_error_creating_subfolder'));
				return false;
			}
		}


		$sDestFullPath = $sDestDir."/".$sName;

		$this->SetDebug('p: '.$sDestFullPath);
		
		if (!move_uploaded_file($sPath,Config::Get('path.root.server').$sDestFullPath)) {
			$this->SetError($this->Lang_Get('plugin.attachments.upload_error_moving_to_subfolder'));
			return false;
		}
		
		$this->SetDebug($iFormId);
		return $this->oMapper->AttachFile($sDestFullPath,$sName,$iFileSize,$sExtension,$iTopicId,$iFormId,$iUserId);
	}
	
	//**************************************************************************************************
	public function GetAttachedFilesByTopicId($iTopicId){
		$sCacheKey			= "files_by_topic_$iTopicId";
		$aCacheTags			= array("delete_file_any", "topic_update");
		$iCacheLifeTime		= 5 *24*60*60;
		
		$aData	= $this->Cache_Get($sCacheKey);
		if($aData !== false) $aResult = $aData;
		else{
			$aResult	= $this->oMapper->GetAttachedFilesByTopicId($iTopicId);
			$this->Cache_Set($aResult, $sCacheKey, $aCacheTags, $iCacheLifeTime);
		}
		
		return $aResult;
	}
	
	//**************************************************************************************************
	public function GetAttachedFilesByFormId($iFormId){
		return $this->oMapper->GetAttachedFilesByFormId($iFormId);
	}	
	
	//**************************************************************************************************
	public function GetAttachedFilesByExtensionByTopicId($iTopicId, $sExtension){
		$sCacheKey			= "files_by_topic_{$iTopicId}_extension_{$sExtension}";
		$aCacheTags			= array("delete_file_any", "topic_update");
		$iCacheLifeTime		= 5 *24*60*60;
		
		$aData	= $this->Cache_Get($sCacheKey);
		if($aData !== false) $aResult = $aData;
		else{
			$aResult	= $this->oMapper->GetAttachedFilesByExtensionByTopicId($iTopicId, $sExtension);
			$this->Cache_Set($aResult, $sCacheKey, $aCacheTags, $iCacheLifeTime);
		}
		
		return $aResult;
	}
	
	//***************************************************************************************************
	public function GetFileById($iFileId){
		if ($aResult = $this->oMapper->GetFileById($iFileId)) return $aResult[0];
		else return false;
	}
	
	//***************************************************************************************************
	public function GetFilePathById($iFileId){
		if ($aResult = $this->oMapper->GetFilePathById($iFileId)) return $aResult[0]['attachment_url'];
		else return false;
	}
	
	//***************************************************************************************************
	public function GetFileNameById($iFileId){
		if ($aResult = $this->oMapper->GetFileNameById($iFileId)) return $aResult[0]['attachment_name'];
		else return false;
	}
	
	//***************************************************************************************************
	public function DeleteFileById($iFileId){
		$aFile = $this->GetFileById($iFileId);
		$sFullPath = Config::Get('path.root.server').$aFile['attachment_url'];
		
		@unlink($sFullPath);
		$this->oMapper->DeleteFileById($iFileId);
		
		$aDropcacheTags		= array("delete_file_any");
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, $aDropcacheTags);
	}
	
	//***************************************************************************************************	
	public function DeleteFilesByTopicId($iTopicId){
		$aFiles = $this->GetAttachedFilesByTopicId($iTopicId);
		if ($aFiles){
			foreach($aFiles as $aFile){
				$this->DeleteFileById($aFile['attachment_id']);
			}
		}
	}
	
	//****************************************************************************************************
	public function LinkFormIdToTopicId($iFormId,$iTopicId){
		$this->oMapper->LinkFormIdToTopicId($iFormId,$iTopicId);
	}
	
	//****************************************************************************************************
	public function LinkFilesToUserTopic($iTopicId,$iUserId){
		$this->oMapper->LinkFilesToUserTopic($iTopicId,$iUserId);
	}
	
	//****************************************************************************************************
	public function GetUnlinkedAttachmentsByUserId($iUserId){
		return $this->oMapper->GetUnlinkedAttachmentsByUserId($iUserId);
	}
	
	//****************************************************************************************************
	public function LinkFileToFormId($iFileId,$iFormId){
		return $this->oMapper->LinkFileToFormId($iFileId,$iFormId);
	}
}

?>