<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-12-27
 * Time: 下午3:53
 * To change this template use File | Settings | File Templates.
 */
class Bulletin_IndexController extends Admin_Controller_ActionDec
{
    function init()
    {
        parent::init();
    }

    /**
     *
     */
    function indexAction()
    {
        $serviceBulletin = new Admin_Service_Bulletin();
        $request = array_filter($this->getRequest()->getParams());
        $queryGrid = array('ajax_print' => 'json_2','req_handle' => 'datagrid-result');
        $queryGrid = $serviceBulletin->filterRequestQuery(array_merge($queryGrid,$request)) ;
        $this->view->request = $request;
        $this->view->queryGrid = $queryGrid;
        $this->view->pagesize = $this->getRequest()->getParam('rows',DIANA_DATAGRID_PAGESIZE_ADMIN) ;
        $this->view->queryOrderSc = array('desc','asc');
        $this->view->queryOrderColumns = $serviceBulletin->getFilterColumnsForOrder();
        $configHandle = array(
            'datagrid-result' => array(
                'object' => $serviceBulletin,
                'method' => 'makeDataGird',
            ),
        );
        $this->handleAjax($configHandle);
    }

    function detailAction()
    {

    }

    function insertAction()
    {

    }

    function updateAction()
    {

    }

    function handleAction()
    {

    }
}