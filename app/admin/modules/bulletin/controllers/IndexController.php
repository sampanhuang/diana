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
        $request = array_filter($this->getRequest()->getParams());
        $this->view->queryColumns = array('bulletin_id');
        $request['query_column'] = $request['query_column']?$request['query_column']:'bulletin_id';
        $request['query_key'] = $request['query_key']?$request['query_key']:$request['bulletin_id'];
        $this->view->request = $request;
        $serviceBulletin = new Admin_Service_Bulletin();
        if(!$this->view->detail = $detail = $serviceBulletin->getDetail($request['query_column'],$request['query_key'])){
            $this->setMsgs('查询失败');
            $this->setMsgs($serviceBulletin->getMsgs());
        }

    }

    function insertAction()
    {
        $request = array_filter($this->getRequest()->getParams());
        if(empty($request['bulletin_author'])){
            $request['bulletin_author'] = $this->currentManagerName.'&lt;'.$this->currentManagerEmail.'&gt;';
        }
        $this->view->reqHandle = 'insert';
        $this->view->request = $this->view->detail = $request;
        $serviceBulletin = new Admin_Service_Bulletin();
        $serviceBulletinChannel = new Admin_Service_BulletinChannel();
        $this->view->optionsBulletinChannelOfFather = $serviceBulletinChannel->makeOptionsOfFather();
        $this->view->optionsBulletinChannelOfSon = $serviceBulletinChannel->makeOptionsOfSon();
        $configHandle = array(
            'insert' => array(
                'object' => $serviceBulletin,
                'method' => 'insert',
            ),
        );
        if($this->handlePost($configHandle)){
            unset($this->view->detail);
        }
    }

    function updateAction()
    {
        $request = array_filter($this->getRequest()->getParams());
        $this->view->reqHandle = 'update';
        if(empty($request['bulletin_id'])){
            $this->setMsgs('参数ID不能为空');
            return false;
        }
        $serviceBulletin = new Admin_Service_Bulletin();
        $configHandle = array(
            'update' => array(
                'object' => $serviceBulletin,
                'method' => 'update',
            ),
        );
        $this->handlePost($configHandle);
        if($detailBulletin = $serviceBulletin->getDetailById($request['bulletin_id'])){
            $this->view->detail = $detailBulletin;
        }
        $serviceBulletinChannel = new Admin_Service_BulletinChannel();
        $this->view->optionsBulletinChannelOfFather = $serviceBulletinChannel->makeOptionsOfFather();
        $this->view->optionsBulletinChannelOfSon = $serviceBulletinChannel->makeOptionsOfSon();
    }

    function handleAction()
    {

    }
}