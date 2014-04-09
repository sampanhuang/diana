<?php
/**
 * 十二月新版（重大变更）
 * 代号：Dec（December的缩写）
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-12-9
 * Time: 下午3:00
 * To change this template use File | Settings | File Templates.
 */
class Client_Controller_ActionDec extends Client_Controller_Action
{
    function init()
    {
        parent::init();
        $this->setViewPageSize();
    }

    function setViewPageSize()
    {
        $pageSize = $this->getRequest()->getParam('rows',DIANA_DATAGRID_PAGESIZE_CLIENT);
        $this->view->pagesize = $pageSize;
    }

}
