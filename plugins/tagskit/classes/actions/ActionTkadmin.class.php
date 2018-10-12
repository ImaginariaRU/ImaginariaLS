<?php

class PluginTagskit_ActionTkadmin extends ActionPlugin {

	/**
	 * Инициализация
	 */
	public function Init() {
		$this->oUserCurrent=$this->User_GetUserCurrent();
		if (!$this->oUserCurrent or !$this->oUserCurrent->isAdministrator()) {
			return $this->EventNotFound();
		}

		$this->SetDefaultEvent('index');
	}

	/**
	 * Регистрация обработчиков
	 */
	protected function RegisterEvent() {
		$this->AddEvent('index','EventAdmin');
		$this->AddEventPreg('/^ajax$/i','/^load-depend-tags$/i','EventAjaxLoadDependTags');
		$this->AddEventPreg('/^ajax$/i','/^save-depend-tags$/i','EventAjaxSaveDependTags');
	}

	/**
	 * Страница управления группами тегов
	 */
	protected function EventAdmin() {
		if ($sTag=getRequestStr('add_main_tag','','post')) {
			if ($this->PluginTagskit_Main_GetTagByFilter(array('#where'=>array('main_text = ? or text = ?'=>array($sTag,$sTag))))) {
				$this->Message_AddErrorSingle($this->Lang_Get('plugin.tagskit.admin.main_tag_already_exists'));
			} else {
				$oTag=Engine::GetEntity('PluginTagskit_ModuleMain_EntityTag');
				$oTag->setMainText($sTag);
				$oTag->setText($sTag);
				$oTag->Add();
			}
		}

		/**
		 * Получаем список основных тегов
		 */
		$aTagItems=$this->PluginTagskit_Main_GetTagItemsByFilter(array('#group'=>'main_text','#order'=>array('main_text'=>'asc')));
		$this->Viewer_Assign('aTagItems',$aTagItems);

		$this->SetTemplateAction('index');
	}

	/**
	 * Загружает спсиок зависимых тегов
	 */
	protected function EventAjaxLoadDependTags() {
		$this->Viewer_SetResponseAjax('json');
		$sTag=getRequestStr('text');

		$aTags=$this->PluginTagskit_Main_GetTagItemsByFilter(array('main_text'=>$sTag,'#order'=>array('text'=>'asc')));

		$sText='';
		foreach($aTags as $oTag) {
			$sText.="{$oTag->getText()}\r\n";
		}

		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('sText',$sText);
		$oViewer->Assign('sTag',$sTag);
		$sTextResult=$oViewer->Fetch(Plugin::GetTemplatePath(__CLASS__).'depend.tags.items.tpl');
		$this->Viewer_AssignAjax('sText',$sTextResult);
	}

	/**
	 * Сохраняет список зависимых тегов
	 */
	protected function EventAjaxSaveDependTags() {
		$this->Viewer_SetResponseAjax('json');

		$sTag=getRequestStr('tag');
		$sText=getRequestStr('text');

		/**
		 * Делим текст на строки - в каждой строке по одному тегу
		 */
		$aTagsDepend=explode("\n",$sText);
		foreach($aTagsDepend as $k=>$v) {
			$aTagsDepend[$k]=trim($v," \n\r");
		}
		$aTagsDepend=array_filter($aTagsDepend);
		/**
		 * Получаем текущие теги
		 */
		$aTags=$this->PluginTagskit_Main_GetTagItemsByFilter(array('main_text'=>$sTag,'#index-from'=>'text'));
		$aTags=array_keys($aTags);

		$aTagsAdd=array_diff($aTagsDepend,$aTags);
		$aTagsRemove=array_diff($aTags,$aTagsDepend);
		/**
		 * Добавляем новые теги
		 */
		foreach($aTagsAdd as $sItem) {
			if (!$this->PluginTagskit_Main_GetTagByFilter(array('#where'=>array('main_text = ? or text = ?'=>array($sItem,$sItem))))) {
				$oTag=Engine::GetEntity('PluginTagskit_ModuleMain_EntityTag');
				$oTag->setMainText($sTag);
				$oTag->setText($sItem);
				$oTag->Add();
			}
		}
		/**
		 * Удаляем не нужные теги
		 */
		if ($aTagsRemove) {
			$aTagsRemove=$this->PluginTagskit_Main_GetTagItemsByFilter(array('main_text'=>$sTag,'text in'=>$aTagsRemove));
			foreach($aTagsRemove as $oTag) {
				$oTag->Delete();
			}
		}
		/**
		 * Сбрасываем кеш, необходимо для обновления облака тегов
		 */
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("topic_update"));
	}

}