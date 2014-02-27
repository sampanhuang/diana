<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 14-2-26
 * Time: 下午11:27
 * To change this template use File | Settings | File Templates.
 */
class PluginController extends Www_Controller_Action
{


    function init()
    {
        parent::init();
        $this->getHelper("layout")->disableLayout();//关闭布局
    }

    function indexAction()
    {

    }

    function topUserAction()
    {
        $urlApi = 'http://'.DIANA_DOMAIN_CLIENT_CURRENT.'/default/api/user-info';
        echo DIANA_DOMAIN_CURRENT_PRE;
    }

}