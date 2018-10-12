<?php

/**
 * Основной модуль для работы с тегами
 */
class PluginTagskit_ModuleMain extends ModuleORM {

	protected $oMapper;
	protected $oUserCurrent;

	/**
	 * Инициализация модуля
	 */
	public function Init() {
		parent::Init();
		$this->oMapper=Engine::GetMapper(__CLASS__);
		$this->oUserCurrent=$this->User_GetUserCurrent();
	}

	/**
	 * Возвращает список топиков по тегам
	 *
	 * @param $aTag
	 * @param $aParams
	 * @param $iPage
	 * @param $iPerPage
	 *
	 * @return array
	 */
	public function GetTopicsByTags($aTag,$aParams,$iPage,$iPerPage) {
		$aCloseBlogs = ($this->oUserCurrent && isset($aParams['check_accessible']) && $aParams['check_accessible'])
			? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
			: $this->Blog_GetInaccessibleBlogsByUser();

		$sWho=isset($aParams['how']) ? $aParams['how'] : 'or';
		$sWhere=isset($aParams['where']) ? $aParams['where'] : 'all';

		$s = serialize(array($aCloseBlogs,$aTag,$iPage,$iPerPage));
		//if (false === ($data = $this->Cache_Get("topic_by_tags_{$s}"))) {
			$data = array('collection'=>$this->oMapper->GetTopicsByTags($aTag,$sWho,$sWhere,$aCloseBlogs,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "topic_by_tags_{$s}", array('topic_update','topic_new'), 60*60*24*2);
		//}
		$data['collection']=$this->Topic_GetTopicsAdditionalData($data['collection']);
		return $data;
	}

	/**
	 * Проверяем данные формы редактирования топика на корректность
	 *
	 * @param $oTopic
	 *
	 * @return bool
	 */
	public function CheckTopicFields($oTopic) {
		$aTags=explode(',',$oTopic->getTopicTags());
		/**
		 * Проверка на черный список
		 */
		if (Config::Get('plugin.tagskit.type_tags_create')=='black') {
			if ($aFind=array_intersect($aTags,Config::Get('plugin.tagskit.tags_list_black'))) {
				$this->Message_AddError($this->Lang_Get('plugin.tagskit.validate.black_error').': '.htmlspecialchars(join(', ',$aFind)),$this->Lang_Get('error'));
				return false;
			}
		}
		/**
		 * Проверка вхождения всех тегов в белый список
		 */
		if (Config::Get('plugin.tagskit.type_tags_create')=='white') {
			if ($aFind=array_diff($aTags,Config::Get('plugin.tagskit.tags_list_white'))) {
				$this->Message_AddError($this->Lang_Get('plugin.tagskit.validate.white_error').': '.htmlspecialchars(join(', ',$aFind)),$this->Lang_Get('error'));
				return false;
			}
		}
		return true;
	}

	/**
	 * Возвращает заданный список тегов с учетом сортировке по частоте использования или алфавиту
	 *
	 * @param        $aTags
	 * @param string $sOrder
	 * @param int    $iPage
	 * @param int    $iPerPage
	 *
	 * @return array
	 */
	public function GetTopicTagsByTags($aTags,$sOrder='text',$iPage=1,$iPerPage=10) {
		$aTags=$this->oMapper->GetTopicTagsByTags($aTags);
		/**
		 * Сортируем результирующий массив
		 */
		if ($sOrder=='count') {
			uasort($aTags,array($this,'_sortTagsByCount'));
		} else {
			uasort($aTags,array($this,'_sortTagsByText'));
		}
		/**
		 * Эмулируем постраничность
		 */
		return array('collection'=>$this->GetPageFromArray($aTags,$iPage,$iPerPage),'count'=>count($aTags));
	}

	/**
	 * Колбек сортировки тегов по алфавиту
	 *
	 * @param $oTag1
	 * @param $oTag2
	 *
	 * @return int
	 */
	public function _sortTagsByText($oTag1,$oTag2) {
		if($oTag1->getText()<$oTag2->getText()) {
			return -1;
		} elseif($oTag1->getText()>$oTag2->getText()) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * Колбек сортировки тегов по частотности использования
	 *
	 * @param $oTag1
	 * @param $oTag2
	 *
	 * @return int
	 */
	public function _sortTagsByCount($oTag1,$oTag2) {
		if($oTag1->getCount()<$oTag2->getCount()) {
			return 1;
		} elseif($oTag1->getCount()>$oTag2->getCount()) {
			return -1;
		} else {
			return 0;
		}
	}

	/**
	 * Разбиение массива на постраничность
	 *
	 * @param     $aItems
	 * @param int $iPage
	 * @param int $iPerPage
	 *
	 * @return array
	 */
	public function GetPageFromArray($aItems,$iPage=1,$iPerPage=10) {
		if ($iPage<1) {
			$iPage=1;
		}
		if ($iPerPage<1) {
			$iPerPage=10;
		}
		$aParts=array_chunk($aItems,$iPerPage);
		if (isset($aParts[$iPage-1])) {
			return $aParts[$iPage-1];
		}
		return array();
	}

	/**
	 * Выполняет поиск белых тегов в тексте
	 *
	 * @param $sText
	 *
	 * @return array
	 */
	public function SearchWhiteTags($sText) {
		if (!$sText) {
			return array();
		}
		$aTagsWhite=Config::Get('plugin.tagskit.tags_list_white');
		if (!$aTagsWhite or !is_array($aTagsWhite)) {
			return array();
		}
		/**
		 * Разбиваем текст на слова
		 */
		if (!preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u",$sText,$aMatches,PREG_PATTERN_ORDER)) {
			return array();
		}
		$aWords=$aMatches[0];
		foreach($aWords as $k=>$v) {
			if (mb_strlen($v,'UTF-8')<3) {
				unset($aWords[$k]);
			}
		}
		/**
		 * Сначала ищем точные совпадения
		 */
		$aTagsFind=array_intersect($aTagsWhite,$aWords);
		if (count($aTagsFind)==Config::Get('plugin.tagskit.auto_search_tags_max')) {
			return $aTagsFind;
		} elseif(count($aTagsFind)>Config::Get('plugin.tagskit.auto_search_tags_max')) {
			return array_slice($aTagsFind,0,Config::Get('plugin.tagskit.auto_search_tags_max'));
		}
		/**
		 * Теперь ещем по корням, для это делаем предварительные преобразования
		 */
		$aTagsWhiteStem=array();
		foreach($aTagsWhite as $k=>$v) {
			$aTagsWhiteStem[$k]=@$this->PluginTagskit_Stemmer_Stem($v);
		}
		$aWordsStem=array();
		foreach($aWords as $k=>$v) {
			$aWordsStem[$k]=@$this->PluginTagskit_Stemmer_Stem($v);
		}
		/**
		 * Ищем совпадения
		 */
		$aTagsFindStem=array_intersect($aTagsWhiteStem,$aWordsStem);
		/**
		 * Объединяем
		 */
		foreach($aTagsFindStem as $k=>$v) {
			$aTagsFind[]=$aTagsWhite[$k];
		}
		$aTagsFind=array_unique($aTagsFind);
		if (count($aTagsFind)>Config::Get('plugin.tagskit.auto_search_tags_max')) {
			return array_slice($aTagsFind,0,Config::Get('plugin.tagskit.auto_search_tags_max'));
		}
		return $aTagsFind;
	}
}