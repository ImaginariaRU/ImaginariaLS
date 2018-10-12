<?php
//
//  Attachments plugin
//  (P) Rafrica.net Studio, 2010 - 2012
//  http://we.rafrica.net/
//

class PluginAttachments_ModuleAttachments_EntityTopic extends PluginAttachments_Inherit_ModuleTopic_EntityTopic{    
	public function getAttachments(){
		return $this->PluginAttachments_Attachments_GetAttachedFilesByTopicId($this->getId());
	}
	
	public function getAttachmentsByExtension($sExtension){
		return $this->PluginAttachments_Attachments_GetAttachedFilesByTopicId($this->getId());
	} 
}
?>