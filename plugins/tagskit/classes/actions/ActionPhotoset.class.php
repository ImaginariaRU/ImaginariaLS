<?php

class PluginTagskit_ActionPhotoset extends PluginTagskit_Inherit_ActionPhotoset {

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