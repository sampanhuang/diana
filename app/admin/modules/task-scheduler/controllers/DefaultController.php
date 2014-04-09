<?php
/**
 * 计划任务
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-12-12
 * Time: 下午6:28
 * To change this template use File | Settings | File Templates.
 */
class TaskScheduler_DefaultController extends Zend_Controller_Action
{
    function init()
    {
        parent::init();
    }

    /**
     * IP转换为地址
     */
    function ipToAddressAction()
    {
        $this->getHelper("layout")->disableLayout();//关闭布局
        $this->getHelper("viewRenderer")->setNoRender();//关闭视图
        //关于配置
        $serviceIpToAddress = new Admin_Service_IpToAddress();
        $serviceIpToAddress->updateAddress();
    }

}