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
        $serviceSafeFilterWord = new Diana_Service_SafeFilterWord();
        if($summarize = $serviceSafeFilterWord->summarize()){
            $this->view->summarize = $summarize;
        }else{
            $this->setMsgs($serviceSafeFilterWord->getMsgs());
        }

    }

    /**
     * 常用敏感词
     */
    function hotAction()
    {
        $this->view->count = $count = $this->getRequest()->getParam('count',20);
        $serviceSafeFilterWord = new Diana_Service_SafeFilterWord();
        if($rowsHot = $serviceSafeFilterWord->getHot($count)){
            $this->view->rowsHot = $rowsHot;
        }else{
            $this->setMsgs($serviceSafeFilterWord->getMsgs());
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
    }
}
