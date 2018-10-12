<?php

class PluginTagskit_ActionTopic extends PluginTagskit_Inherit_ActionTopic {

	/**
	 * Подхватываем проверку полей формы редактирования топика
	 *
	 * @param $oTopic
	 *
	 * @return bool
	 */
	protected function checkTopicFields($oTopic) {
		$bResult=parent::checkTopicFields($oTopic);
		if ($bResult) {
			$bResult=$this->PluginTagskit_Main_CheckTopicFields($oTopic);
		}
		return $bResult;
	}

}