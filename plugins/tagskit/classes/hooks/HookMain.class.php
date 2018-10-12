<?php
/**
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Хуки
 */
class PluginTagskit_HookMain extends Hook {
	/**
	 * Регистрация необходимых хуков
	 */
	public function RegisterHook() {
		$this->AddHook('template_tagskit_after_tag_form_search', 'AfterTagFormSearch');
		if (Config::Get('plugin.tagskit.type_tags_create')=='white') {
			$this->AddHook('template_add_topic_topic_end', 'AddTopicEnd');
			$this->AddHook('template_add_topic_link_end', 'AddTopicEnd');
			$this->AddHook('template_add_topic_photoset_end', 'AddTopicEnd');
			$this->AddHook('template_add_topic_question_end', 'AddTopicEnd');
		}
	}

	/**
	 * Добавляет опции для поиска по тегам
	 *
	 * @return string
	 */
	public function AfterTagFormSearch() {
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'inject.tags.search.options.tpl');
	}

	/**
	 * Добавляет модальное окно со списком белых тегов
	 *
	 * @return string
	 */
	public function AddTopicEnd() {
		$aResult=$this->PluginTagskit_Main_GetTopicTagsByTags(Config::Get('plugin.tagskit.tags_list_white'),'count',1,Config::Get('plugin.tagskit.white_list_per_page'));
		$aPaging=$this->Viewer_MakePaging($aResult['count'],1,Config::Get('plugin.tagskit.white_list_per_page'),Config::Get('pagination.pages.count'),'');


		$this->Viewer_Assign('aTagsTkWhite',$aResult['collection']);
		$this->Viewer_Assign('aPagingTagsTkWhite',$aPaging);
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'inject.tags.form.white.tpl');
	}
}