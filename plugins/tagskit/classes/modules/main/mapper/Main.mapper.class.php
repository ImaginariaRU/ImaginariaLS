<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Маппер, обрабатывает запросы к БД
 *
 */
class PluginTagskit_ModuleMain_MapperMain extends Mapper {

	/**
	 * Возвращает список топиков по тегам с дополнительными условиями
	 *
	 * @param $aTag
	 * @param $sWho
	 * @param $sWhere
	 * @param $aExcludeBlog
	 * @param $iCount
	 * @param $iCurrPage
	 * @param $iPerPage
	 *
	 * @return array
	 */
	public function GetTopicsByTags($aTag,$sWho,$sWhere,$aExcludeBlog,&$iCount,$iCurrPage,$iPerPage) {
		if (!is_array($aTag)) {
			$aTag=array($aTag);
		}

		if (!$aTag) {
			$iCount=0;
			return array();
		}

		if (count($aTag)>Config::Get('plugin.tagskit.search_tags_max')) {
			$aTag=array_slice($aTag,0,Config::Get('plugin.tagskit.search_tags_max'));
		}

		/**
		 * Получаем зависимые теги
		 */
		$aTagsDepend=$this->GetDependTags($aTag);

		$sJoin='';
		$aFilterTags=array();
		if ($sWho=='or') {
			$sFilterTags=' and t.topic_tag_text IN (?a) ';
			foreach($aTagsDepend as $aTagItems) {
				$aFilterTags=array_merge($aFilterTags,$aTagItems);
			}
		} else {
			$aFilterTags=array_shift($aTagsDepend);
			$sFilterTags=' and t.topic_tag_text IN (?a) ';
			$k=1;
			foreach($aTagsDepend as $aTagItems) {
				foreach($aTagItems as $i=>$sTag) {
					$aTagItems[$i]=$this->oDb->escape($sTag,false);
				}
				$sOn=join(', ',$aTagItems);
				$sJoin.=" JOIN ".Config::Get('db.table.topic_tag')." as t{$k} ON ( t{$k}.topic_id =  t.topic_id and t{$k}.topic_tag_text IN (".$sOn.") ) ";
				$k++;
			}
		}

		$aBlogTypes=array();
		$aBlogTypesExlude=array();

		if ($sWhere=='all') {
			$aBlogTypes=array();
		} elseif($sWhere=='corp') {
			$aBlogTypes=array('company');
		} elseif($sWhere=='pers') {
			$aBlogTypes=array('personal');
		} elseif($sWhere=='other') {
			$aBlogTypesExlude=array('personal','company');
		}

		$sql = "
							SELECT
								DISTINCT t.topic_id
							FROM
								".Config::Get('db.table.topic_tag')." as t
								{$sJoin}
								,".Config::Get('db.table.blog')." as b
							WHERE
								1 = 1
								{ {$sFilterTags} }
								{ AND t.blog_id NOT IN (?a) }
								AND t.blog_id = b.blog_id
								{ AND b.blog_type IN (?a) }
								{ AND b.blog_type NOT IN (?a) }
                            ORDER BY t.topic_id DESC
                            LIMIT ?d, ?d ";

		$aTopics=array();
		if ($aRows=$this->oDb->selectPage(
			$iCount,$sql,
			$aFilterTags ? $aFilterTags : DBSIMPLE_SKIP,
			(is_array($aExcludeBlog)&&count($aExcludeBlog)) ? $aExcludeBlog : DBSIMPLE_SKIP,
			$aBlogTypes ? $aBlogTypes : DBSIMPLE_SKIP,$aBlogTypesExlude ? $aBlogTypesExlude : DBSIMPLE_SKIP,
			($iCurrPage-1)*$iPerPage, $iPerPage
		)
		) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}
		return $aTopics;
	}

	/**
	 * Возвращает список тегов с частотностью по заданным тегам
	 *
	 * @param $aTags
	 *
	 * @return array
	 */
	public function GetTopicTagsByTags($aTags) {
		if (!is_array($aTags)) {
			$aTags=array($aTags);
		}
		if (!$aTags) {
			return array();
		}
		$sql = "SELECT
			topic_tag_text,
			count(topic_tag_text)	as count
			FROM
				".Config::Get('db.table.topic_tag')."
			WHERE
				topic_tag_text IN (?a)
			GROUP BY
				topic_tag_text ";
		$aReturn=array();
		$aTagsFind=array();
		if ($aRows=$this->oDb->select($sql,$aTags)) {
			foreach ($aRows as $aRow) {
				$aTagsFind[]=$aRow['topic_tag_text'];
				$aReturn[]=Engine::GetEntity('Topic_TopicTag',$aRow);
			}
		}
		$aTags=array_diff($aTags,$aTagsFind);
		foreach($aTags as $sTag) {
			$aReturn[]=Engine::GetEntity('Topic_TopicTag',array(
				'topic_tag_text'=>$sTag,
				'count'=>0
			));
		}
		return $aReturn;
	}


	/**
	 * Возвращает список зависимых тегов
	 *
	 * @param $aTag
	 *
	 * @return array
	 */
	public function GetDependTags($aTag) {
		if (!is_array($aTag)) {
			$aTag=array($aTag);
		}

		$aReturn=array();
		/**
		 * Получаем основной тег
		 */
		$sql = "
							SELECT
								main_text,
								text
							FROM
								".Config::Get('db.table.tagskit_main_tag')."
							WHERE
								text IN (?a)
                            LIMIT 0,100";
		if ($aRows=$this->oDb->select($sql,$aTag)) {
			foreach($aRows as $aRow) {
				$aReturn[$aRow['main_text']][]=$aRow['text'];
				$aReturn[$aRow['main_text']][]=$aRow['main_text'];
				$aTag=array_diff($aTag,array($aRow['text']));
			}
		}
		foreach($aTag as $sTag) {
			$aReturn[$sTag][]=$sTag;
		}
		/**
		 * Получаем все дочерние теги
		 */
		$sql = "
							SELECT
								text,
								main_text
							FROM
								".Config::Get('db.table.tagskit_main_tag')."
							WHERE
								main_text IN (?a)
                            LIMIT 0,500";

		if ($aRows=$this->oDb->select($sql,array_keys($aReturn))) {
			foreach($aRows as $aRow) {
				$aReturn[$aRow['main_text']][]=$aRow['text'];
			}
		}
		foreach($aReturn as $k=>$v) {
			$aReturn[$k]=array_unique($v);
		}
		return $aReturn;
	}
}