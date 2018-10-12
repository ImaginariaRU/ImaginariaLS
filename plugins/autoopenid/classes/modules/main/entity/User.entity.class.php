<?php

class PluginAutoopenid_ModuleMain_EntityUser extends EntityORM
{

    /**
     * Связи с другими таблицами
     *
     * @var array
     */
    protected $aRelations = array(
        'user' => array(self::RELATION_TYPE_BELONGS_TO, 'ModuleUser_EntityUser', 'user_id'),
    );


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

    public function getNameDisplay()
    {
        return $this->getDataOne('name_display', $this->getServiceId());
    }
}