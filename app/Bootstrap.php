<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDb(){
		$resource = $this->getPluginResource('multidb');
		$resource->init();
		$dbAdapterMaster = $resource->getDb('master');
		$dbAdapterSlave = $resource->getDb('slave');
		Zend_Registry::set('dbAdapterMaster', $dbAdapterMaster);
		Zend_Registry::set('dbAdapterSlave', $dbAdapterSlave);
	}
}

