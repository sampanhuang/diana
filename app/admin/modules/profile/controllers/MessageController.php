<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 下午11:14
 * To change this template use File | Settings | File Templates.
 */
class Profile_MessageController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    /**
     * 查询模块
     */
    function inboxAction(){
        $this->view->page = $page = $this->getRequest()->getUserParam('page',0);


    }

    function outboxAction()
    {

    }

    function sendAction()
    {

    }

    function deleteAction()
    {

    }

    function detailAction()
    {

    }
}
