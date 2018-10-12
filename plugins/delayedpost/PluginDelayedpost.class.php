<?php 
/*
  Delayed post plugin
  (P) Rafrica.net Studio, 2010 - 2012
  http://we.rafrica.net/
*/

if (!class_exists('Plugin')) {
	die('Hacking attemp!');
}

class PluginDelayedpost extends Plugin {
	
	public function Activate() {
		return true;
	}
	
	public function Init() {}
	
	//*********************************************************************************
	protected $aInherits = array(
		'mapper' 	=> array ('ModuleTopic_MapperTopic', 'ModuleStream_MapperStream'),
		'module'	=> array ('ModuleStream',	'ModuleTopic'),
	);
	
}

?>