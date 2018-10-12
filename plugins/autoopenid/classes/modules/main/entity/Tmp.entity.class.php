<?php

class PluginAutoopenid_ModuleMain_EntityTmp extends EntityORM
{
    /**
     * Возвращает список дополнительных данных
     *
     * @return array|mixed
     */
    public function getData()
    {
        $aData = @unserialize($this->_getDataOne('data'));
        if (!$aData) {
            $aData = array();
        }
        return $aData;
    }

    /**
     * Устанавливает список дополнительных данных
     *
     * @param $aData
     */
    public function setData($aData)
    {
        $this->_aData['data'] = @serialize($aData);
    }

    public function getDataOne($sName, $mDefault = null)
    {
        $aData = $this->getData();
        return isset($aData[$sName]) ? $aData[$sName] : $mDefault;
    }
}