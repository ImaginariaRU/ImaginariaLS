<?php
/********************************************
 * Author: Vladimir Linkevich
 * e-mail: Vladimir.Linkevich@gmail.com
 * since 2011-02-25
 ********************************************/
class PluginAutocut_HookAutocut extends Hook {
	public function RegisterHook() {
		/* 
		 * ActionTopic Hook
		 * CheckTopicFields function hook.
		 */ 
			$this -> AddHook('topic_edit_before', 'CheckTopicFieldsCut');
			$this -> AddHook('topic_add_before', 'CheckTopicFieldsCut');	
			#$this -> AddHook('check_photoset_fields', 'CheckTopicFieldsCut');	
	}

	public function CheckTopicFieldsCut($var) {	
		
		#Disables AutoCut for Admin
		$oUser=$this->User_GetUserCurrent();
		if($oUser->isAdministrator() && !Config::Get('plugin.autocut.cutAdmin')){
			return $var;
		}
		$oTopic=$var['oTopic'];
		
		#check if we are posting to personal blog and if we need to cut personal topics
		if(getRequest('blog_id')==0 && !Config::Get('plugin.autocut.cutPersonal')){
			return $var;
		}
		#Avoid plugin pokupalka inheritance
		if(getRequest('goods_currency')){
			return $var;
		}
		
		/**
		 * Получаемый и устанавливаем разрезанный текст по тегу <cut>
		 */
		list($sTextShort,$sTextNew,$sTextCut) = $this->Text_Cut($this -> PluginAutocut_Autocut_CutAdd($oTopic->getTextSource()));
		
		$oTopic->setCutText($sTextCut);
		$oTopic->setText($this->Text_Parser($sTextNew));
		$oTopic->setTextShort($this->Text_Parser($sTextShort));
		$var['oTopic']=$oTopic;
		return $var;
	}
#End of class
}
?>