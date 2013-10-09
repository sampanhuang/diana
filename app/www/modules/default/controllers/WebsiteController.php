<?php
/**
 * 网站
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-29
 * Time: 下午5:54
 * To change this template use File | Settings | File Templates.
 */
class WebsiteController extends Www_Controller_Action
{
    function init()
    {
        parent::init();

    }

    /**
     * 网站首页
     */
    function indexAction()
    {
        $translate = Zend_Registry::get('Zend_Translate');
        $this->setHeadTitle($translate->_('www_menu_website_index'));
        $this->setHeadMetaKeywords($translate->_('www_seo_keyword'));
        $this->setHeadMetaDescription($translate->_('www_seo_description'));
        $indexContent = array();
        $serviceCountry = new Diana_Service_Country();
        if($continents = $serviceCountry->getContinentsKey()){
            $serviceWebsite = new Diana_Service_Website();
            $serviceWebsiteCountry = new Diana_Service_WebsiteCountry();
            foreach($continents as $continent){
                $condition = array('website_continent' => $continent);
                if($rowsWebsiteNew = $serviceWebsite->listByCondition(15,$condition,'new')){
                    $indexContent[$continent]['website']['new'] = $rowsWebsiteNew;
                }
                if($countries = $serviceWebsiteCountry->getCountriesByContinent($continent)){
                    $indexContent[$continent]['countries'] = $countries;
                }
            }
        }
        $this->view->indexContent = $indexContent;
    }


    /**
     * 频道首页
     */
    function channelAction()
    {
        print_r($_SERVER);
    }

    /**
     * 网站搜索
     */
    function searchAction()
    {
        $this->view->keyword = $keyword = $this->getRequest()->getParam('keyword','');
        $this->view->page = $page = $this->getRequest()->getParam('page',1);//页
        $this->view->order = $order = $this->getRequest()->getParam('order',1);//排序
        $this->view->pagesize = $pagesize = $this->getRequest()->getParam('pagesize',10);//每页多少条
        if(empty($keyword)){
            $this->setMsgs('请输入搜索关键字');
        }else{
            //获取网站数据
            $serviceWebsite = new Www_Service_Website();
            if(!$this->view->paginator = $paginator = $serviceWebsite->search($keyword,$page,$pagesize,$order)){
                $this->setMsgs($serviceWebsite->getMsgs());
            }else{
                if($paginator['total'] > 0){
                    $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
                    $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
                }
            }
        }
    }

    /**
     * 列表页
     */
    function listAction()
    {
        $this->view->page = $page = $this->getRequest()->getParam('page',1);//排序
        $this->view->pagesize = $pagesize = $this->getRequest()->getParam('pagesize',10);//每页多少条
        $this->view->order = $order = $this->getRequest()->getParam('order','new');//排序
        $this->view->category = $category = $this->getRequest()->getParam('category',0);//分类
        $this->view->continent = $continent = $this->getRequest()->getParam('continent','as');//大陆
        $this->view->country = $country = $this->getRequest()->getParam('country','');//国家
        $translate = Zend_Registry::get('Zend_Translate');
        $this->setHeadMetaDescription($translate->_('www_seo_description'));
        $condition = array();
        //如果大陆ID不为空
        if(!empty($continent)){
            //开始SEO
            $this->setHeadTitle($translate->_('continent_code_'.$continent));
            $this->setHeadMetaDescription($translate->_('continent_code_'.$continent));
            $arrSuffixHeadKeywork = explode(",",$translate->_('www_seo_keyword'));
            foreach($arrSuffixHeadKeywork as $valSuffixHeadKeywork){
                $this->setHeadMetaKeywords($translate->_('continent_code_'.$continent).$valSuffixHeadKeywork);
            }
            $condition['website_continent'] = $continent;
            $serviceWebsiteCountry = new Diana_Service_WebsiteCountry();
            if($countries = $serviceWebsiteCountry->getCountriesByContinent($continent)){
                $this->view->countries = $countries;
                //如果国家ID不为空
                if((!empty($country))&&(!empty($countries[$country]))){
                    $this->setHeadTitle($translate->_('country_code_'.$country));
                    $this->setHeadMetaDescription($translate->_('country_code_'.$country));
                    foreach($arrSuffixHeadKeywork as $valSuffixHeadKeywork){
                        $this->setHeadMetaKeywords($translate->_('country_code_'.$country).$valSuffixHeadKeywork);
                    }
                    $condition['website_country'] = $country;
                }
            }
        }
        //网站分类不能为空
        if(!empty($category)){
            $this->setHeadTitle($translate->_('category_id_'.$category));
            $this->setHeadMetaDescription($translate->_('category_id_'.$category));
            $condition['website_categoryId'] = $category;
        }
        //获取网站分类
        $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
        if($rowsCategory = $serviceWebsiteCategory->getAll()){
            $this->view->rowsCategory = $rowsCategory;
        }
        //获取网站数据
        $serviceWebsite = new Diana_Service_Website();
        $this->view->paginator = $paginator = $serviceWebsite->pageByCondition($page,$pagesize,$condition,$order);
        if($paginator['total'] > 0){
            $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
            $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
        }
    }

    /**
     * 网站详情
     */
    function detailAction()
    {
        $this->view->websiteId = $websiteId = $this->getRequest()->getParam('website_id',0);
        $serviceWebsite = new Diana_Service_Website();
        if($detailWebsite = $serviceWebsite->detailById($websiteId,true)){
            $translate = Zend_Registry::get('Zend_Translate');
            $this->setHeadTitle($translate->_('category_id_'.$detailWebsite['website_categoryId']));
            $this->setHeadTitle($translate->_('country_code_'.$detailWebsite['website_country']));
            $this->setHeadTitle($translate->_('continent_code_'.$detailWebsite['website_continent']));
            $this->setHeadTitle($detailWebsite['website_name']);
            $this->setHeadMetaKeywords($detailWebsite['website_meta_keywords']);
            $this->setHeadMetaDescription($detailWebsite['website_meta_description']);
            $this->view->detailWebsite = $detailWebsite;
            //猜你喜欢
            //$conditionLike = array("website_categoryId" => $detailWebsite["website_categoryId"],"website_continent" => $detailWebsite["website_continent"]);
            $conditionLike = array("website_continent" => $detailWebsite["website_continent"]);
            if($rowsWebsiteLike = $serviceWebsite->listByCondition(10,$conditionLike,'click_out')){
                $this->view->rowsWebsiteLike = $rowsWebsiteLike;
            }
        }else{
            $this->setMsgs('无效的参数 website_id '.$websiteId);
            $this->setMsgs($serviceWebsite->getMsgs());
        }
    }

    /**
     * 点击跳转
     */
    function jumperAction()
    {
        $this->view->websiteId = $websiteId = $this->getRequest()->getParam('website_id',0);
        $serviceWebsite = new Www_Service_Website();
        if($domain = $serviceWebsite->jumper($websiteId)){
            $this->getHelper("layout")->disableLayout();//关闭布局
            $this->getHelper("viewRenderer")->setNoRender();//关闭视图
            ob_clean();
            header("location:".$domain);
            exit();
        }else{
            $this->setMsgs($serviceWebsite->getMsgs());
        }
    }

    /**
     * 提交申请
     */
    function applyAction()
    {
        //当前用户是否登录
        $serviceDoorkeeper = new Www_Service_Doorkeeper();
        if ($sessionMemberId = $serviceDoorkeeper->checkSession()) {
            if ($detailMember = $serviceDoorkeeper->checkMember($sessionMemberId)) {
                $this->view->detailMember = $detailMember;
            }
        }
        //获取网站分类代码
        $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
        $this->view->websiteCategoryIds = $serviceWebsiteCategory->getIds();
        //获取洲与国家代码
        $serviceCountry = new Diana_Service_Country();
        $this->view->websiteContinents = $continents =  $serviceCountry->getContinentsKey();
        if($countries = $serviceCountry->getCountriesKey()){
            $optionsCountry = array();
            $translate = Zend_Registry::get('Zend_Translate');
            foreach($countries as $continentKey => $countries){
                foreach($countries as $countryKey => $countryValue){
                    $optionsCountry[$continentKey][$countryKey] = $translate->_('country_code_'.$countryKey);
                }
            }
            $this->view->optionsCountry = $optionsCountry;
        }
        //处理提交请求
        $request = $this->_request;
        if($request->isPost()) {
            $post = $request->getPost();
            if(!empty($detailMember['member_email'])){
                $post['website_memberEmail'] = $detailMember['member_email'];
            }
            //判断验证码是否正确
            if(empty($detailMember)){
                if(empty($post['captcha'])){
                    $this->setMsgs("验证码不能为空!");
                    return false;
                }
                $serviceCaptcha = new Diana_Service_Captcha();
                if (!$serviceCaptcha->checkCaptchaWord($post['captcha'],"www-website-apply")) {
                    $this->setMsgs($serviceCaptcha->getMsgs());
                    return false;
                }
            }
            //提交网站注册申请
            $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
            if($serviceWebsiteApplyRegister->postApply($post)){
                $this->setMsgs('提交成功，我会们在你的网站审核成功后以电子邮件的形式通知你');
                unset($post);
            }else{
                $this->setMsgs($serviceWebsiteApplyRegister->getMsgs());
                $this->view->post = $post;
                if(!empty($post['website_continent'])){
                    if($countriesKey = $serviceCountry->getCountriesKey()){
                        $this->view->countriesByContinent = array_keys($countriesKey[$post['website_continent']]);
                    }
                }
            }
        }
    }
}