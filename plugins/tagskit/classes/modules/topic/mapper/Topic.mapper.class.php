<?php


class PluginTagskit_ModuleTopic_MapperTopic extends PluginTagskit_Inherit_ModuleTopic_MapperTopic {

	/**
	 * Получает список тегов из топиков открытых блогов (open,personal)
	 *
	 * @param  int $iLimit	Количество
	 * @param  int|null $iUserId	ID пользователя, чью теги получаем
	 * @return array
	 */
	public function GetOpenTopicTags($iLimit,$iUserId=null) {
		$sql = "
			SELECT
				IF(tk.main_text IS NULL, tt.topic_tag_text, tk.main_text) AS topic_tag_text_group,
				IF(tk.main_text IS NULL, tt.topic_tag_text, tk.main_text) AS topic_tag_text,
				count(tt.topic_tag_text)	as count
			FROM
				".Config::Get('db.table.topic_tag')." as tt
				LEFT JOIN ".Config::Get('db.table.tagskit_main_tag')." as tk ON (tk.text = tt.topic_tag_text)
				,".Config::Get('db.table.blog')." as b
			WHERE
				1 = 1
				{ AND tt.user_id = ?d }
				AND
				tt.blog_id = b.blog_id
				AND
				b.blog_type <> 'close'
			GROUP BY
				topic_tag_text_group
			ORDER BY
				count desc
			LIMIT 0, ?d
				";
		$aReturn=array();
		$aReturnSort=array();
		if ($aRows=$this->oDb->select($sql,is_null($iUserId) ? DBSIMPLE_SKIP : $iUserId,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[mb_strtolower($aRow['topic_tag_text'],'UTF-8')]=$aRow;
			}
			ksort($aReturn);
			foreach ($aReturn as $aRow) {
				$aReturnSort[]=Engine::GetEntity('Topic_TopicTag',$aRow);
			}
		}
		return $aReturnSort;
	}


	/**
	 * Получает список топиков по тегу
	 *
	 * @param  string $sTag	Тег
	 * @param  array    $aExcludeBlog	Список ID блогов для исключения
	 * @param  int    $iCount	Возвращает общее количество элементов
	 * @param  int    $iCurrPage	Номер страницы
	 * @param  int    $iPerPage	Количество элементов на страницу
	 * @return array
	 */
	public function GetTopicsByTag($sTag,$aExcludeBlog,&$iCount,$iCurrPage,$iPerPage) {
		$aTags=array($sTag);
		/**
		 * Получаем основной тег
		 */
		$sql = "
							SELECT
								main_text
							FROM
								".Config::Get('db.table.tagskit_main_tag')."
							WHERE
								text = ?
                            LIMIT 0,1";
		$aMainTags=array($sTag);
		if ($aRow=$this->oDb->selectRow($sql,$sTag)) {
			$aMainTags[]=$aRow['main_text'];
			$aTags[]=$aRow['main_text'];
		}
		/**
		 * Получаем все дочерние теги
		 */
		$sql = "
							SELECT
								text
							FROM
								".Config::Get('db.table.tagskit_main_tag')."
							WHERE
								main_text IN (?a)
                            LIMIT 0,500";

		if ($aRows=$this->oDb->select($sql,$aMainTags)) {
			foreach($aRows as $aRow) {
				$aTags[]=$aRow['text'];
			}
		}
		$aTags=array_unique($aTags);

		$sql = "
							SELECT
								topic_id
							FROM
								".Config::Get('db.table.topic_tag')."
							WHERE
								topic_tag_text IN (?a)
								{ AND blog_id NOT IN (?a) }
                            ORDER BY topic_id DESC
                            LIMIT ?d, ?d ";

		$aTopics=array();
		if ($aRows=$this->oDb->selectPage(
			$iCount,$sql,$aTags,
			(is_array($aExcludeBlog)&&count($aExcludeBlog)) ? $aExcludeBlog : DBSIMPLE_SKIP,
			($iCurrPage-1)*$iPerPage, $iPerPage
		)
		) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}
		return $aTopics;
	}
}