<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-4
 * Time: 上午11:48
 * To change this template use File | Settings | File Templates.
 */
class Website_CategoryController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    function indexAction()
    {
        $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
        $this->view->rows = $serviceWebsiteCategory->getAll();
    }

}