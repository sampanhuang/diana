<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-12-27
 * Time: 下午3:54
 * To change this template use File | Settings | File Templates.
 */
class Bulletin_ChannelController extends Admin_Controller_ActionDec
{
    function init()
    {
        parent::init();
    }

    /**
     * 频道索引
     */
    function indexAction()
    {
        $serviceBulletinChannel = new Admin_Service_BulletinChannel();
        $request = array_filter($this->getRequest()->getParams());

        $queryGrid = array('ajax_print' => 'json','req_handle' => 'treegrid-result');
        $queryGrid = $serviceBulletinChannel->filterRequestQuery(array_merge($queryGrid,$request)) ;
        $this->view->request = $request;
        $this->view->queryGrid = $queryGrid;
        $this->view->pagesize = $this->getRequest()->getParam('rows',DIANA_DATAGRID_PAGESIZE_ADMIN) ;
        $this->view->queryOrderSc = array('desc','asc');
        $this->view->queryOrderColumns = $serviceBulletinChannel->getFilterColumnsForOrder();
        $configHandle = array(
            'treegrid-result' => array(
                'object' => $serviceBulletinChannel,
                'method' => 'makeTreeGrid',
            ),
        );
        $this->handleAjax($configHandle);
    }

    /**
     * 频道明细
     */
    function detailAction()
    {
        $request = array_filter($this->getRequest()->getParams());
        $this->view->queryColumns = array('channel_id');
        $request['query_column'] = $request['query_column']?$request['query_column']:'channel_id';
        $request['query_key'] = $request['query_key']?$request['query_key']:$request['channel_id'];
        $this->view->request = $request;
        $serviceBulletinChannel = new Admin_Service_BulletinChannel();
        if(!$this->view->detail = $detail = $serviceBulletinChannel->getDetail($request['query_column'],$request['query_key'])){
            $this->setMsgs('查询失败');
            $this->setMsgs($serviceBulletinChannel->getMsgs());
        }
    }

    /**
     * 添加频道
     */
    function insertAction()
    {
        $request = array_filter($this->getRequest()->getParams());
        $this->view->reqHandle = 'insert';
        $this->view->request = $this->view->detail = $request;
        $serviceBulletinChannel = new Admin_Service_BulletinChannel();
        $this->view->optionsOfFather = $optionsOfFather = $serviceBulletinChannel->makeOptionsOfFather();
        $configHandle = array(
            'insert' => array(
                'object' => $serviceBulletinChannel,
                'method' => 'insert',
            ),
        );
        if($this->handlePost($configHandle)){
            unset($this->view->detail);
        }
    }

    /**
     * 更新频道
     */
    function updateAction()
    {
        $request = array_filter($this->getRequest()->getParams());
        $this->view->reqHandle = 'update';
        if(empty($request['channel_id'])){
            $this->setMsgs('参数ID不能为空');
            return false;
        }
        $serviceBulletinChannel = new Admin_Service_BulletinChannel();
        $configHandle = array(
            'update' => array(
                'object' => $serviceBulletinChannel,
                'method' => 'update',
            ),
        );
        $this->handlePost($configHandle);
        if($detailBulletin = $serviceBulletinChannel->getDetailById($request['channel_id'])){
            $this->view->detail = $detailBulletin;
        }
        $serviceBulletinChannel = new Admin_Service_BulletinChannel();
        $this->view->optionsOfFather = $optionsOfFather = $serviceBulletinChannel->makeOptionsOfFather();

    }

    /**
     * 频道处理
     */
    function handleAction()
    {
        $serviceBulletinChannel = new Admin_Service_BulletinChannel();
        $configHandle = array(
            'delete' => array(
                'object' => $serviceBulletinChannel,
                'method' => 'delete',
            ),
        );
        $this->handleAjax($configHandle);
    }

}
