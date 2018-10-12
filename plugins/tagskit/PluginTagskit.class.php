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
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
	die('Hacking attempt!');
}

class PluginTagskit extends Plugin {

	/**
	 * Массив в записями о наследовании плагином части функционала
	 *
	 * @var array
	 */
	protected $aInherits = array(
		'template'=>array(
			'actions/ActionTag/index.tpl' => '_delegates/template/actions/ActionTag/index.tpl',
		),
		'action'=>array(
			'ActionTag'=>'_ActionTag',
			'ActionAjax'=>'_ActionAjax',
			'ActionTopic'=>'_ActionTopic',
			'ActionLink'=>'_ActionLink',
			'ActionQuestion'=>'_ActionQuestion',
			'ActionPhotoset'=>'_ActionPhotoset',
		),
		'mapper'=>array(
			'ModuleTopic_MapperTopic'=>'_ModuleTopic_MapperTopic',
		),
	);


	public function Init() {
		$this->Viewer_AppendScript(Plugin::GetWebPath(__CLASS__).'js/main.js');
		$this->Viewer_AppendStyle(Plugin::GetTemplateWebPath(__CLASS__).'css/main.css');
	}

	public function Activate() {
		if (!$this->isTableExists('prefix_tk_tag')) {
			/**
			 * При активации выполняем SQL дамп
			 */
			$this->ExportSQL(dirname(__FILE__).'/install/dump.sql');
		}
		return true;
	}

	public function Deactivate() {
		return true;
	}
}