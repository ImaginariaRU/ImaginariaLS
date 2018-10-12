<?php

/* -------------------------------------------------------
 *
 *   LiveStreet (v1.0)
 *   Plugin Role (v.0.6)
 *   Copyright © 2011 Bishovec Nikolay
 *
 * --------------------------------------------------------
 *
 *   Plugin Page: http://netlanc.net
 *   Contact e-mail: netlanc@yandex.ru
 *
  ---------------------------------------------------------
 */


if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

class PluginRole extends Plugin
{

    public $aInherits = array(
        'module' => array('ModuleACL', 'ModuleBlog', 'ModuleUser'),
        'mapper' => array('ModuleUser_MapperUser' => '_ModuleUser_MapperUser'),
        'entity' => array('ModuleComment_EntityComment')
    );
    public $aDelegates = array(
        'template' => array(
            'comment.tpl' => '_comment.tpl',
            'comment_tree.tpl' => '_comment_tree.tpl',
        ),
    );

    public function Activate()
    {
        if (!$this->isTableExists('prefix_role')) {
            $this->ExportSQL(dirname(__FILE__) . '/dump.sql');
        }
        if (!$this->isFieldExists('prefix_comment', 'comment_date_edit') and !$this->isFieldExists('prefix_comment', 'comment_edit_user_id')) {
            $this->ExportSQL(dirname(__FILE__) . '/dump_v04_update.sql');
        }
        if (!$this->isTableExists('prefix_role_place_block')) {
            $this->ExportSQL(dirname(__FILE__) . '/dump_v05_update.sql');
        }
        return true;
    }

    public function Init()
    {
        $this->Viewer_Assign('sTPRole', rtrim(Plugin::GetTemplatePath('role'), '/'));
        $this->Viewer_Assign('sTWPRole', rtrim(Plugin::GetTemplateWebPath('role'), '/'));

        $this->Viewer_AppendScript(Plugin::GetTemplateWebPath('role') . 'js/comments.js');
        $this->Viewer_AppendStyle(Plugin::GetTemplateWebPath('role') . 'css/style.css');
    }

}

?>
