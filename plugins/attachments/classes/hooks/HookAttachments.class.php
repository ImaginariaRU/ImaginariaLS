<?php
/**
 * Attachments plugin
 * (P) Rafrica.net Studio, 2010 - 2012
 *
 * (C) Rewrite by Karel Wintersky
 */

class PluginAttachments_HookAttachments extends Hook
{

    /**
     *
     */
    public function RegisterHook()
    {
        $this->AddHook('init_action', 'AddStylesAndJS');
        $this->AddHook('template_form_add_topic_topic_begin', 'TopicBegin');
        $this->AddHook('template_form_add_topic_photoset_begin', 'TopicBegin');
        $this->AddHook('template_topic_show_info', 'TopicShowGenInfo');
        $this->AddHook('template_topic_show_end', 'TopicShowFullInfo');
        $this->AddHook('topic_edit_show', 'TopicEditShow');
        $this->AddHook('topic_delete_before', 'TopicDeleteBefore');
        $this->AddHook('topic_add_after', 'TopicAddAfter');
    }

    /**
     *
     */
    public function AddStylesAndJS()
    {
        $sTemplateWebPath = Plugin::GetTemplateWebPath(__CLASS__);
        $this->Viewer_AppendStyle($sTemplateWebPath . 'css/style.css');
        $this->Viewer_AppendScript($sTemplateWebPath . 'js/init.js');
    }

    /**
     * @return mixed
     */
    public function TopicBegin()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'topic_begin.tpl');
    }

    /**
     * @param $aVars
     * @return mixed
     */
    public function TopicShowGenInfo($aVars)
    {
        $this->Viewer_Assign('oTopic', $aVars ['topic']);
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'topic_gen_info.tpl');
    }

    /**
     * @param $aVars
     * @return mixed
     */
    public function TopicShowFullInfo($aVars)
    {
        $this->Viewer_Assign('oTopic', $aVars ['topic']);
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'topic_info_file_list.tpl');
    }

    /**
     * @param $aVars
     */
    public function TopicEditShow($aVars)
    {
        $oTopic = $aVars['oTopic'];
        $aFiles = $oTopic->getAttachments();

        $this->Viewer_Assign('aFiles', $aFiles);
    }

    /**
     * @param $aVars
     */
    public function TopicDeleteBefore($aVars)
    {
        $oTopic = $aVars['oTopic'];
        $this->PluginAttachments_Attachments_DeleteFilesByTopicId($oTopic->getId());
    }

    /**
     * @param $aVars
     */
    public function TopicAddAfter($aVars)
    {
        $oTopic = $aVars['oTopic'];
        $iTopicId = $oTopic->getId();

        $oUser = $this->User_GetUserCurrent();
        $iUserId = $oUser->getId();

        $iFormId = getRequest('form_id_topic');

        if ((!empty($iTopicId)) and (!empty($iFormId))) {
            $this->PluginAttachments_Attachments_SetDebug($iTopicId . ' ^ ' . $iFormId);
            $this->PluginAttachments_Attachments_LinkFormIdToTopicId($iFormId, $iTopicId);
        }
    }

}
