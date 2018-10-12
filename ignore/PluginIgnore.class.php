<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Сделано руками @ Сергей Сарафанов (sersar)
*   ICQ: 172440790 | E-mail: sersar@ukr.net
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

if (!class_exists('Plugin')) {
	die('Hacking attempt!');
}

class PluginIgnore extends Plugin {

    public $aInherits = array(
        'action' => array(
            'ActionAjax'
        ),
        'module' => array(
            //'ModuleACL' => '_ModuleACL',
			//'ModuleStream' => '_ModuleStream',
			'ModuleTalk' => '_ModuleTalk',
        )
    );
	// Активация плагина
	public function Activate() {
        $this->Cache_Clean();
        if (!$this->isTableExists('prefix_ignore')) {
            $resutls = $this->ExportSQL(dirname(__FILE__) . '/install.sql');
            return $resutls['result'];
        }

        return true;
	}
    
	// Деактивация плагина
	public function Deactivate(){
		//$this->ExportSQL(dirname(__FILE__).'/uninstall.sql');
        $this->Cache_Clean();
		return true;
    }

	public function Init() {
        LS::GetInstance()->Lang_AddLangJs(array(
            'plugin.ignore.js_ignore_post_my_wall',
            'plugin.ignore.js_ignore_reply_my_comment',
            'plugin.ignore.js_ignore_hide_me_comments',
            'plugin.ignore.js_ignore_post_comment_my_topic',
        ));
		$this->Viewer_AppendScript(Plugin::GetTemplatePath('PluginIgnore')."js/ignore.js");
		$this->Viewer_AppendStyle ( Plugin::GetTemplateWebPath ( 'PluginIgnore' ) . 'css/ignore.css' );
	}
}


















?>