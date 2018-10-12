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
 * Сущность связи OpenID с пользователем
 *
 */
class PluginAutoopenid_ModuleOpenid_EntityOpenid extends Entity 
{    
	protected $aData=null;
	
	public function extractData() {
    	if (is_null($this->aData)) {
    		$this->aData=unserialize($this->getData());
    	}
    }
    
    public function setDataValue($sName,$data) {
    	$this->extractData();
    	$this->aData[$sName]=$data;
    	$this->setData($this->aData);
    }
    
    public function getDataValue($sName) {
    	$this->extractData();
    	if (isset($this->aData[$sName])) {
    		return $this->aData[$sName];
    	}
    	return null;
    }
	
    public function setData($data) {
        $this->_aData['data']=serialize($data);
    }	
}
?>