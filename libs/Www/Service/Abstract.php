<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-30
 * Time: 下午3:22
 * To change this template use File | Settings | File Templates.
 */
abstract class Www_Service_Abstract extends Diana_Service_Abstract
{
    var $sessionManager;

    function __construct()
    {
        parent::__construct();
        //$this->setSessionManager();
    }
}

