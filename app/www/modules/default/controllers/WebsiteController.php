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
    	//获取国际化数据
        $translate = Zend_Registry::get('Zend_Translate');
        $this->setHeadTitle($translate->_('www_menu_website_index'));
        $this->setHeadMetaKeywords($translate->_('www_seo_keyword'));
        $this->setHeadMetaDescription($translate->_('www_seo_description'));
        //定义输出数组
        $indexContent = array();
        $serviceWebsite = new Diana_Service_Website();
        //获取全部分类信息
        $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
        $this->view->allWebsiteCategory = $allWebsiteCategory = $serviceWebsiteCategory->getAll(null,'website');
        //获取全部地区信息
        $serviceWebsiteArea = new Diana_Service_WebsiteArea();
        $this->view->allWebsiteArea = $allWebsiteArea = $serviceWebsiteArea->getAll(null,'website');
        //分类或是地区数据需要事先定义
        if(empty($allWebsiteArea) || empty($allWebsiteCategory)){
        	$this->setMsgs('分类或是地区数据需要事先定义');
        	return false;
        }
        //父级地区,array(id=>array(...))
        $rowsWebsiteAreaFather = array();
        //子级地区,array(father_id => array( id => array(..) ))
        $rowsWebsiteAreaSon = array();
        //从$allWebsiteArea中循环得到$rowsWebsiteAreaFather与$rowsWebsiteAreaSon
        foreach ($allWebsiteArea as $rowWebsiteArea){
        	$tmpAreaId = $rowWebsiteArea['area_id'];
        	$tmpAreaFatherId = $rowWebsiteArea['area_fatherId'];
        	if(empty($tmpAreaFatherId)){
        		$rowsWebsiteAreaFather[$tmpAreaId] = $rowWebsiteArea;
        	}else{
        		$rowsWebsiteAreaSon[$tmpAreaFatherId][$tmpAreaId] = $rowWebsiteArea;
        	}
        }
        foreach($rowsWebsiteAreaFather as $websiteAreaFatherId => $rowWebsiteAreaFather){
            $condition = array('website_areaId' => array_keys($rowsWebsiteAreaSon[$websiteAreaFatherId]));
            if($rowsWebsiteNew = $serviceWebsite->listByCondition(15,$condition,'new')){
                $indexContent[$websiteAreaFatherId]['website']['new'] = $rowsWebsiteNew;
            }
        }
        $this->view->rowsWebsiteAreaFather = $rowsWebsiteAreaFather;
        $this->view->rowsWebsiteAreaSon = $rowsWebsiteAreaSon;
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
            //获取全部分类信息
            $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
            $this->view->allWebsiteCategory = $allWebsiteCategory = $serviceWebsiteCategory->getAll(null,'website');
            //获取全部地区信息
            $serviceWebsiteArea = new Diana_Service_WebsiteArea();
            $this->view->allWebsiteArea = $allWebsiteArea = $serviceWebsiteArea->getAll(null,'website');
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
    	//获取外部数据
        $this->view->page = $page = $this->getRequest()->getParam('page',1);//排序
        $this->view->pagesize = $pageSize = $this->getRequest()->getParam('pagesize',30);//每页多少条
        $this->view->order = $order = $this->getRequest()->getParam('order','new');//排序
        $this->view->category = $category = $this->getRequest()->getParam('category',0);//分类
        $this->view->areaFather = $areaFather = $this->getRequest()->getParam('area_father');//大陆
        $this->view->area = $area = $this->getRequest()->getParam('area',0);//国家
        //取得多国语言
        $translate = Zend_Registry::get('Zend_Translate');
        $arrSuffixHeadKeyWork = explode(",",$translate->_('www_seo_keyword'));
        //获取分类信息
        $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
        $this->view->allWebsiteCategory = $allWebsiteCategory = $serviceWebsiteCategory->getAll();
        //获取地区信息
        $serviceWebsiteArea = new Diana_Service_WebsiteArea();
        $this->view->allWebsiteArea = $allWebsiteArea = $serviceWebsiteArea->getAll();
        $condition = array();
        //搜索条件
        $condition = array();
        //如果一级区域ID不为空
        if(!empty($areaFather)){
            //开始SEO
            $this->setHeadTitle($allWebsiteArea[$areaFather]['area_name_'.DIANA_TRANSLATE_CURRENT]);
            foreach($arrSuffixHeadKeyWork as $valSuffixHeadKeyWork){
                $this->setHeadMetaDescription($allWebsiteArea[$areaFather]['area_name_'.DIANA_TRANSLATE_CURRENT].$valSuffixHeadKeyWork);
                $this->setHeadMetaKeywords($allWebsiteArea[$areaFather]['area_name_'.DIANA_TRANSLATE_CURRENT].$valSuffixHeadKeyWork);
            }
            //获取二级地区信息
            $rowWebsiteAreaSon = array();//二级地区信息
            foreach($allWebsiteArea as $rowWebsiteArea){
                //if(($rowWebsiteArea['area_fatherId'] == $areaFather)&&($rowWebsiteArea['area_count_website'] > 0)){
                if(($rowWebsiteArea['area_fatherId'] == $areaFather)){
                    $rowWebsiteAreaSon[$rowWebsiteArea['area_id']] = $rowWebsiteArea;
                }
            }
            $this->view->rowWebsiteAreaSon = $rowWebsiteAreaSon;
            $condition['website_areaId'] = array_keys($rowWebsiteAreaSon);
        }
        //如果二级区域ID不为空
        if(!empty($area)){
            //开始SEO
            $this->setHeadTitle($allWebsiteArea[$area]['area_name_'.DIANA_TRANSLATE_CURRENT]);
            foreach($arrSuffixHeadKeyWork as $valSuffixHeadKeyWork){
                $this->setHeadMetaDescription($allWebsiteArea[$area]['area_name_'.DIANA_TRANSLATE_CURRENT].$valSuffixHeadKeyWork);
                $this->setHeadMetaKeywords($allWebsiteArea[$area]['area_name_'.DIANA_TRANSLATE_CURRENT].$valSuffixHeadKeyWork);
            }
            $areaFather = $allWebsiteArea[$area]['area_fatherId'];
            $condition['website_areaId'] = $area;
        }

        foreach($allWebsiteCategory as $rowWebsiteCategory){
            if(($rowWebsiteCategory['category_fatherId'] == $areaFather)&&($rowWebsiteCategory['category_count_website'] > 0)){
                $rowsAreaSon[$rowWebsiteCategory['category_id']] = $rowWebsiteCategory;
            }
        }
        //网站分类不能为空
        if(!empty($category)){
            $this->setHeadTitle($allWebsiteCategory[$category]['category_name_'.DIANA_TRANSLATE_CURRENT]);
            $this->setHeadMetaDescription($allWebsiteCategory[$category]['category_name_'.DIANA_TRANSLATE_CURRENT]);
            foreach($arrSuffixHeadKeyWork as $valSuffixHeadKeyWork){
                $this->setHeadMetaKeywords($allWebsiteCategory[$category]['category_name_'.DIANA_TRANSLATE_CURRENT].$valSuffixHeadKeyWork);
            }
            $condition['website_categoryId'] = $category;
        }
        //获取网站数据
        $serviceWebsite = new Diana_Service_Website();
        $this->view->paginator = $paginator = $serviceWebsite->pageByCondition($page,$pageSize,$condition,$order);
        if($paginator['total'] > 0){
            $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pageSize,$page);
            $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
        }
        //再次SEO
        if(!empty($paginator['rows'])){
            foreach($paginator['rows'] as $tmpWebsite){
                $this->setHeadMetaDescription($tmpWebsite['website_name']);
            }
        }
    }

    /**
     * 网站详情
     */
    function detailAction()
    {
        $this->view->websiteId = $websiteId = $this->getRequest()->getParam('website_id',0);
        //获取分类信息
        $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
        $this->view->allWebsiteCategory = $allWebsiteCategory = $serviceWebsiteCategory->getAll();
        //获取地区信息
        $serviceWebsiteArea = new Diana_Service_WebsiteArea();
        $this->view->allWebsiteArea = $allWebsiteArea = $serviceWebsiteArea->getAll();
        //取得多国语言
        $translate = Zend_Registry::get('Zend_Translate');
        $arrSuffixHeadKeyWork = explode(",",$translate->_('www_seo_keyword'));
        //获取网站信息
        $serviceWebsite = new Diana_Service_Website();
        if($detailWebsite = $serviceWebsite->getWebsiteById($websiteId,true)){
            //开始SEO
            $this->setHeadTitle($allWebsiteCategory[$detailWebsite['website_categoryId']]['category_name_'.DIANA_TRANSLATE_CURRENT]);
            $this->setHeadTitle($allWebsiteArea[$detailWebsite['website_country']]['area_name_'].DIANA_TRANSLATE_CURRENT);
            $this->setHeadTitle($detailWebsite['website_name']);
            $this->setHeadMetaKeywords($detailWebsite['website_meta_keywords']);
            $this->setHeadMetaDescription($detailWebsite['website_meta_description']);
            foreach($arrSuffixHeadKeyWork as $valSuffixHeadKeyWork){
                //SEO地区
                $this->setHeadMetaDescription($allWebsiteArea[$detailWebsite['website_areaId']]['area_name_'.DIANA_TRANSLATE_CURRENT].$valSuffixHeadKeyWork);
                $this->setHeadMetaKeywords($allWebsiteArea[$detailWebsite['website_areaId']]['area_name_'.DIANA_TRANSLATE_CURRENT].$valSuffixHeadKeyWork);
                //SEO类别
                $this->setHeadMetaDescription($allWebsiteCategory[$detailWebsite['website_categoryId']]['category_name_'.DIANA_TRANSLATE_CURRENT].$valSuffixHeadKeyWork);
                $this->setHeadMetaKeywords($allWebsiteCategory[$detailWebsite['website_categoryId']]['category_name_'.DIANA_TRANSLATE_CURRENT].$valSuffixHeadKeyWork);
            }

            $this->view->detailWebsite = $detailWebsite;
            //猜你喜欢
            //$conditionLike = array("website_categoryId" => $detailWebsite["website_categoryId"],"website_continent" => $detailWebsite["website_continent"]);
            $conditionLike = array("website_areaId" => $detailWebsite["website_areaId"],'website_id_not' => $websiteId);
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
        $serviceWebsiteArea = new Diana_Service_WebsiteArea();
        $this->view->optionsFather = $optionsFather =  $serviceWebsiteArea->getOptionsWithFather();
        $this->view->optionsSon = $optionsSon =  $serviceWebsiteArea->getOptionsWithSon();
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