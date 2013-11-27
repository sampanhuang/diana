<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-9-10
 * Time: 下午11:45
 * To change this template use File | Settings | File Templates.
 */
class System_FilterWordController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    /**
     * 索引
     */
    function indexAction()
    {
        $dataGet = $this->view->dataget = $this->getRequest()->getParams();
        $queryGrid = array('show_ajax' => 'json','data_ajax' => 'datagrid_word');
        $serviceSafeFilterWord = new Diana_Service_SafeFilterWord();
        $serviceManagerMsg = new Admin_Service_ManagerMsg();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $this->view->datapost = $dataPost;
            $queryGridPost = $serviceSafeFilterWord->filterFormSearchAboutWord($dataPost);
            $queryGrid = array_merge($queryGrid,$queryGridPost);
        }
        $this->view->queryGrid = $queryGrid;
        //默认是20纪录一页
        if(empty($dataGet['rows'])){$dataGet['rows'] = DIANA_DATAGRID_PAGESIZE_ADMIN;}
        $this->view->pagesize = $dataGet['rows'];
        //取数据源
        if ($dataGet['data_ajax'] == 'datagrid_word') {
            if($dataGrid = $serviceSafeFilterWord->makeDataGridWithWord($dataGet['page'],$dataGet['rows'],$dataGet)){
                echo json_encode($dataGrid);
            }
        }
    }

    /**
     * 导入敏感词
     */
    function importAction()
    {
        $request = $this->getRequest();
        if($request->isPost()) {
            $post = $request->getPost();
            $serviceSafeFilterWord = new Diana_Service_SafeFilterWord();
            if($counter = $serviceSafeFilterWord->import($post['filter_word'])){
                $this->setMsgs('成功导入'.$counter.'个敏感词');
            }else{
                $this->setMsgs($serviceSafeFilterWord->getMsgs());
            }
        }
        $serviceSafeFilterWord = new Diana_Service_SafeFilterWord();
        if($summarize = $serviceSafeFilterWord->summarize()){
            $this->view->summarize = $summarize;
        }else{
            $this->setMsgs($serviceSafeFilterWord->getMsgs());
        }
    }

    function wordHandleAction()
    {
        $this->view->dataGet = $dataGet = $this->getRequest()->getParams();
        $json = array('stat' => 0 ,'msgs' => '');
        if(empty($dataGet['word_id'])){
            $json['msgs'] = '无法接收到word_id';
        }else{
            if($dataGet['data_ajax'] == 'delete'){
                $serviceSafeFilterWord = new Diana_Service_SafeFilterWord();
                if($countDelete = $serviceSafeFilterWord->deleteWithWord($dataGet['word_id'])){
                    $json['stat'] = 1;
                    $json['msgs'] = '成功删除'.$countDelete.'条敏感词';
                }else{
                    $json['msgs'] = '删除失败;'.implode($serviceSafeFilterWord->getMsgs());
                }
            }
        }
        echo json_encode($json);
    }
}
