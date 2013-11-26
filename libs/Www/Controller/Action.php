<?php
/**
 * 控制类
 *
 */
class Www_Controller_Action extends Diana_Controller_Action
{

    var $channels = array();
    var $headTitle = array();//标题
    var $headMetaKeywords = array();//关键字
    var $headMetaDescription = array();//描述
    var $mainChannels = array();//主菜单
    function init()
    {
        parent::init();
        $translate = Zend_Registry::get('Zend_Translate');
        $this->setHeadTitle('['.$translate->_('www_title').' - '.$translate->_('www_title_subtitle').']');
        $this->setHeadMetaKeywords($translate->_('www_title'));
        $this->setChannels();
        /*
        $serviceIndex = new Www_Service_Index();
        $this->view->hotWords = $serviceIndex->getHotWords();//获取热词
        */
        //获取主菜单
        $serviceFront = new Diana_Service_Front();
        if($mainChannels = $serviceFront->getMainChannels()){
            $this->view->mainChannels = $this->mainChannels =  $mainChannels;
        }
    }

    /**
     * 设置频道
     */
    function setChannels()
    {
        if(empty($this->channel)){
            $serviceFront = new Diana_Service_Front();
            $this->channels = $this->view->channels = $serviceFront->getChannels();
        }

    }

    /**
     * 设置网页标题
     *
     */
    function setHeadTitle($title = null)
    {
        if (!empty($title)) {
            $this->headTitle[] = $title;
        }
    }

    /**
     * 设置网页关键字
     *
     */
    function setHeadMetaKeywords($keyword = null)
    {
        if (!empty($keyword)) {
            $this->headMetaKeywords[] = $keyword;
        }
    }

    /**
     * 设置网页描述
     *
     */
    function setHeadMetaDescription($description)
    {
        if (!empty($description)) {
            $this->headMetaDescription[] = $description;
        }
    }

    public function postDispatch() {
        parent::postDispatch();
        //渲染页面
        krsort($this->headTitle);
        krsort($this->headMetaKeywords);
        krsort($this->headMetaDescription);
        $this->view->headTitle(implode("-",$this->headTitle));
        $this->view->headMeta()->appendName('keywords', implode(",",$this->headMetaKeywords));
        $this->view->headMeta()->appendName('description', implode(";",$this->headMetaDescription));
    }
}