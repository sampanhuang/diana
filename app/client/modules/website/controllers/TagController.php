<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-19
 * Time: 下午7:49
 * To change this template use File | Settings | File Templates.
 */
class Website_TagController extends Client_Controller_ActionDec
{
    function init()
    {
        parent::init();

    }

    function indexAction()
    {
        $request = array_filter($this->getRequest()->getParams());
        $queryGrid = array('ajax_print' => 'json_2','req_handle' => 'datagrid-result');
        $queryGrid = array_merge($queryGrid,$request);
        $serviceWebsiteTag = new Diana_Service_WebsiteTag();
        $this->view->request = $request;
        $this->view->queryGrid = $queryGrid;
        $this->view->pagesize = $this->getRequest()->getParam('rows',DIANA_DATAGRID_PAGESIZE_ADMIN) ;
        $this->view->queryOrderSc = array('desc','asc');
        $this->view->queryOrderColumns = $serviceWebsiteTag->getFilterColumnsForOrder();
        $configHandle = array(
            'datagrid-result' => array(
                'object' => $serviceWebsiteTag,
                'method' => 'makeDataGird',
            ),
        );
        $this->handleAjax($configHandle);
        /*
        $pagesize = 15;
        $this->view->page = $page = $this->getRequest()->getUserParam('page',1);
        $serviceWebsiteTag = new Diana_Service_WebsiteTag();
        $this->view->paginator = $paginator = $serviceWebsiteTag->pageByCondition($page,$pagesize);
        if($paginator['total'] > 0){
            $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
            $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
        }*/
    }

    /**
     * 标签明细
     */
    function detailAction()
    {
        $request = $this->getRequest()->getParams();
        $serviceWebsiteTag = new Diana_Service_WebsiteTag();
        if ($this->getRequest()->isPost()) {
            if($request['handle'] = 'query'){
                if(!$detail = $serviceWebsiteTag->getDetail($request['query_column'],$request['query_key'])){
                    $this->setMsgs('查询失败');
                    $this->setMsgs($serviceWebsiteTag->getMsgs());
                }
            }
        }
        if((empty($detail))&&(!empty($request['tag_id']))){
            $request['query_column'] = 'id';
            $request['query_key'] = $request['tag_id'];
            if(!$detail = $serviceWebsiteTag->getDetail('id',$request['tag_id'])){
                $this->setMsgs('查询失败');
                $this->setMsgs($serviceWebsiteTag->getMsgs());
            }
        }
        $this->view->queryColumns = array('id','name');
        $this->view->detail = $detail;
        $this->view->request = $request;
        /*
        $this->view->tagId = $tagId = $this->getRequest()->getUserParam('tag_id',1);
        $serviceWebsiteTag = new Diana_Service_WebsiteTag();
        if($detailWebsiteTag = $serviceWebsiteTag->detailById($tagId)){
            $this->view->detailWebsiteTag = $detailWebsiteTag;
        }*/
    }

}