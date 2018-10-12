<?php
/*
  Delayed post plugin
  (P) Rafrica.net Studio, 2010 - 2012
  http://we.rafrica.net/
*/

class PluginDelayedpost_ModuleTopic_MapperTopic extends PluginDelayedpost_Inherit_ModuleTopic_MapperTopic{  
	
	//*********************************************************************************	
	protected function buildFilter($aFilter) {
		$sDateNow = date("Y-m-d H:i:s"); //fcku php.ini
		if(isset($aFilter['delayed']) && ($aFilter['delayed'])){
			$sWhere = parent::buildFilter($aFilter)." AND t.topic_date_add > '".$sDateNow."'";
		}else $sWhere = parent::buildFilter($aFilter)." AND t.topic_date_add <= '".$sDateNow."'";
		return $sWhere;
	}
	
	//*********************************************************************************
	public function GetTopicsByTag($sTag,$aExcludeBlog,&$iCount,$iCurrPage,$iPerPage) {
		$sDateNow = date("Y-m-d H:i:s");	
		$sql = "				
							SELECT 		
								topic_id										
							FROM 
								".Config::Get('db.table.topic_tag')."								
							WHERE 
								topic_tag_text = ? 	
								{ AND blog_id NOT IN (?a) }
								AND topic_id IN (SELECT topic_id FROM ".Config::Get('db.table.topic')." WHERE ".Config::Get('db.table.topic').".topic_date_add <= '".$sDateNow."')
                            ORDER BY topic_id DESC	
                            LIMIT ?d, ?d ";
		
		$aTopics=array();
		if ($aRows=$this->oDb->selectPage(
				$iCount,$sql,$sTag,
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
	
	//*********************************************************************************
	public function GetNextDelayedTopicDate(){
		$sDateNow 	= date("Y-m-d H:i:s");	
		$sQuery		= "SELECT topic_date_add FROM ".Config::Get('db.table.topic')."
						WHERE topic_date_add > '$sDateNow'
						ORDER BY topic_date_add ASC
						LIMIT 1	";
		return $this->oDb->SelectRow($sQuery);
	}
	

}
?>