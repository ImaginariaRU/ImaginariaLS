<?php

class PluginTagskit_ActionLink extends PluginTagskit_Inherit_ActionLink {

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