<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-4
 * Time: 上午11:48
 * To change this template use File | Settings | File Templates.
 */
class Website_AreaController extends Client_Controller_ActionDec
{
    function init()
    {
        parent::init();

    }

    /**
     * 地区首页
     */
    function indexAction()
    {
        $dataget = $this->getRequest()->getParams();
        if ($dataget['show_data'] == 'treegrid_data') {
            $serviceWebsiteArea = new Diana_Service_WebsiteArea();
            if($datatreegrid = $serviceWebsiteArea->makeTreegrid()){
                echo json_encode($datatreegrid);
            }
        }
    }

    /**
     * 历史快照
     */
    function historyAction()
    {

    }

}