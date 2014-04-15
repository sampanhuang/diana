<?php
/**
 * Created by PhpStorm.
 * User: sampan
 * Date: 14-4-16
 * Time: 上午1:41
 */

class FriendLinkController extends Www_Controller_Action
{


    function init()
    {
        parent::init();

    }

    function indexAction()
    {

    }

    /**
     * 友情链接跳转
     * @return bool
     */
    function jumpAction()
    {
        $this->getHelper("viewRenderer")->setNoRender();//关闭视图
        $linkId = $this->getRequest()->getParam('link_id');
        $serviceFriendLink = new Diana_Service_FriendLink();
        if(!$rowFriendLink = $serviceFriendLink->click($linkId)){
            $this->setMsgs($serviceFriendLink->getMsgs());
            return false;
        }
        if(empty($rowFriendLink['link_url'])){
            $this->setMsgs('link_url 不能为空！');
            return false;
        }
        $targetUrl = $rowFriendLink['link_url'].'/?source='.$_SERVER['SERVER_NAME'];
        $this->getHelper("layout")->disableLayout();//关闭布局
        ob_clean();
        header("location:".$targetUrl);
        exit();
    }

}