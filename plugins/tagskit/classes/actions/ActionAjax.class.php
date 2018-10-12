<?php

class PluginTagskit_ActionAjax extends PluginTagskit_Inherit_ActionAjax {

	protected function RegisterEvent() {
		$this->AddEventPreg('/^tk$/i','/^load-white-tags$/','EventTkLoadWhiteTags');
		$this->AddEventPreg('/^tk$/i','/^auto-search-tags$/','EventTkAutoSearchTags');
		parent::RegisterEvent();
	}

	/**
	 * Загружает список белых тегов
	 */
	public function EventTkLoadWhiteTags() {
		$iPage=(int)getRequestStr('page');
		if ($iPage<1) {
			$iPage=1;
		}

		$aResult=$this->PluginTagskit_Main_GetTopicTagsByTags(Config::Get('plugin.tagskit.tags_list_white'),'count',$iPage,Config::Get('plugin.tagskit.white_list_per_page'));
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('plugin.tagskit.white_list_per_page'),Config::Get('pagination.pages.count'),'');

		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aTagsTkWhite',$aResult['collection']);
		$oViewer->Assign('aPagingTagsTkWhite',$aPaging);
		$sTextResult=$oViewer->Fetch(Plugin::GetTemplatePath(__CLASS__).'white.tags.items.tpl');
		$this->Viewer_AssignAjax('sText',$sTextResult);
	}

	/**
	 * Выполняет автоматический поиск белых тегов в тексте
	 */
	public function EventTkAutoSearchTags() {
		$aTags=$this->PluginTagskit_Main_SearchWhiteTags(getRequestStr('text'));

		if (!$aTags) {
			$this->Message_AddErrorSingle($this->Lang_Get('plugin.tagskit.auto_search_not_found'));
		}

		$this->Viewer_AssignAjax('aTags',$aTags);
	}

}