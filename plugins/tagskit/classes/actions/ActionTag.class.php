<?php

class PluginTagskit_ActionTag extends PluginTagskit_Inherit_ActionTag {

	protected function RegisterEvent() {
		$this->AddEventPreg('/^(page([1-9]\d{0,5}))?$/i','EventIndex');
		parent::RegisterEvent();
	}

	/**
	 * Поиск по нескольку тегов
	 */
	public function EventIndex() {
		$this->PluginTagskit_Main_GetTagItemsAll();
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetEventMatch(2) ? $this->GetParamEventMatch(2) : 1;
		/**
		 * Получаем параметры из реквеста
		 */
		$sTags=getRequestStr('tags');
		$sHow=in_array(getRequestStr('tk_how'),array('or','and')) ? getRequestStr('tk_how') : 'or';
		$sWhere=in_array(getRequestStr('tk_where'),array('all','corp','pers','other')) ? getRequestStr('tk_where') : 'all';
		$_REQUEST['tk_how']=$sHow;
		$_REQUEST['tk_where']=$sWhere;


		$sTags=trim($sTags,', ');
		if ($sTags) {
			$aTags=preg_split("#, *#",$sTags);
		} else {
			$aTags=array();
		}

		$aParams=array(
			'check_accessible'=>true,
			'how'=>$sHow,
			'where'=>$sWhere,
		);
		$aResult=$this->PluginTagskit_Main_GetTopicsByTags($aTags,$aParams,$iPage,Config::Get('module.topic.per_page'));
		$aTopics=$aResult['collection'];
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topics_list_show',array('aTopics'=>$aTopics));
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),Config::Get('pagination.pages.count'),Router::GetPath('tag'),array('tags'=>$sTags,'tk_how'=>$sHow,'tk_where'=>$sWhere));
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('sTag',$sTags);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('tag_title'));
		if ($aTags) {
			$this->Viewer_AddHtmlTitle(join(', ',$aTags));
		}
		$this->SetTemplateAction('index');
	}

}