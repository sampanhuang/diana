<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:12
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_Website extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 刷新首页
     */
    function flushHtmlIndex()
    {
        try{
            $arrLang = array( 'zh-cn' => DIANA_DOMAIN_WWW_CN, 'zh-tw' => DIANA_DOMAIN_WWW_TW);
            foreach($arrLang as $valLang => $valDomain){
                $tmpPath = DIANA_DIR_TEMP.'/html/index_'.$valLang.'.htm';
                if(!file_exists(dirname($tmpPath))){
                    mkdir(dirname($tmpPath),0755,true);
                }
                $tmpContent = file_get_contents('http://'.$valDomain.'/default/website/index').chr(10).'<!--time '.date('Y-m-d H:i:s').' form '.$valDomain.'-->';
                file_put_contents($tmpPath,$tmpContent);
            }
            return true;
        }catch (Exception $e){
            $this->setMsgs($e->getMessage());
            return false;
        }
    }

    /**
     * 获取首页内容
     * @return string
     */
    function getHtmlIndex()
    {
        $tmpPath = DIANA_DIR_TEMP.'/html/index_'.DIANA_TRANSLATE_CURRENT.'.htm';
        //echo $tmpPath;
        //不存在就会刷新
        if(!file_exists($tmpPath)){
            $this->flushHtmlIndex();
        }
        //超过一小时也会刷新
        $tmpFIleTime = filemtime($tmpPath);
        if( (time() - $tmpFIleTime) > 3600){
            $this->flushHtmlIndex();
        }
        $content = file_get_contents($tmpPath);
        return $content;
    }


    /**
     * 获取网站列表
     * @param $counter
     * @param array $condition
     * @param null $order
     * @return array|bool
     */
    function listByCondition($counter,$condition = array(),$order = null)
    {
        $modelWebsite = new Diana_Model_Website();
        if(!$rowsWebsite = $modelWebsite->getRowsByCondition(null,$condition,$order,$counter,0)){
            return false;
        }
        return $rowsWebsite;
    }



    /**
     * 生成easyui需要的ＪＳＯＮ
     * @param $request
     * @return array
     */
    function makeDataGird($request)
    {
        $page = $request['page'];
        $pageSize = $request['rows'];
        $format = array('website_id','website_name','website_domain','wait_pass','website_memberId');
        $condition = $this->filterColumns(array($request),$format);
        return $this->pageByCondition($page,$pageSize,$condition[0]);

    }

    /**
     * 分页
     * @param $key 关键字
     * @param $page 当前页
     * @param $pagesize 每页的纪录数
     */
    function pageByCondition($page = 1,$pagesize = 1,$condition = array(),$order = null,$isDetail = null)
    {

        $offset = ($page - 1)*$pagesize;
        if($offset < 0){
            $offset = 0;
        }
        //获取所有地区信息
        $serviceWebsiteArea = new Diana_Service_WebsiteArea();
        if(!$allWebsiteArea = $serviceWebsiteArea->getall()){
            return false;
        }
        //获取所有分类信息
        $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
        if(!$allWebsite = $serviceWebsiteCategory->getAll()){
            return false;
        }
        //获取网站数据
        $modelWebsite = new Diana_Model_Website();
        if($countWebsite = $modelWebsite->getCountByCondition(null,$condition)){
            if($rowsWebsite = $modelWebsite->getRowsByCondition(null,$condition,null,$pagesize,$offset)){
                $memberIds = array();//会员ID
                $websiteIds = array();//网站ID
                foreach($rowsWebsite as &$rowWebsite){
                    $rowWebsite['website_areaFatherId'] = $allWebsiteArea[$rowWebsite['website_areaId']]['area_fatherId'];
                    $rowWebsite['website_categoryFatherId'] = $allWebsiteArea[$rowWebsite['website_categoryId']]['category_fatherId'];
                    $websiteIds[] = $rowWebsite['website_id'];
                    if(!empty($rowWebsite['website_memberId'])){
                        $memberIds[$rowWebsite['website_memberId']] = $rowWebsite['website_memberId'];
                    }
                }
                //获取地区名字
                $serviceWebsiteArea = new Diana_Service_WebsiteArea();
                if($allWebsiteArea = $serviceWebsiteArea->getAll()){
                    foreach($rowsWebsite as &$rowWebsite){
                        $tmpWebsiteAreaId = $rowWebsite['website_areaId'];
                        if(!empty($tmpWebsiteAreaId)){
                            $rowWebsite['website_areaName'] = $allWebsiteArea[$tmpWebsiteAreaId]['area_name_'.DIANA_TRANSLATE_CURRENT];
                            $tmpWebsiteAreaFatherId = $allWebsiteArea[$tmpWebsiteAreaId]['area_fatherId'];
                            if(!empty($tmpWebsiteAreaFatherId)){
                                $rowWebsite['website_areaFatherId'] = $tmpWebsiteAreaFatherId;
                                $rowWebsite['website_areaFatherName'] = $allWebsiteArea[$tmpWebsiteAreaFatherId]['area_name_'.DIANA_TRANSLATE_CURRENT];
                            }
                        }
                    }
                }

                //获取分类名字
                $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
                if($allWebsiteCategory = $serviceWebsiteCategory->getAll()){
                    foreach($rowsWebsite as &$rowWebsite){
                        $tmpWebsiteCategoryId = $rowWebsite['website_categoryId'];
                        if(!empty($tmpWebsiteCategoryId)){
                            $rowWebsite['website_categoryName'] = $allWebsiteCategory[$tmpWebsiteCategoryId]['category_name_'.DIANA_TRANSLATE_CURRENT];
                            $tmpWebsiteCategoryFatherId = $allWebsiteCategory[$tmpWebsiteCategoryId]['category_fatherId'];
                            if(!empty($tmpWebsiteCategoryFatherId)){
                                $rowWebsite['website_categoryFatherId'] = $tmpWebsiteCategoryFatherId;
                                $rowWebsite['website_categoryFatherName'] = $allWebsiteCategory[$tmpWebsiteCategoryId]['category_name_'.DIANA_TRANSLATE_CURRENT];
                            }
                        }
                    }
                }
                //获取会员信息
                $modelMember = new Diana_Model_Member();
                if($rowsMember = $modelMember->getRowsById(null,$memberIds)){
                    foreach($rowsWebsite as &$rowWebsite){
                        foreach($rowsMember as $rowMember){
                            if($rowWebsite['website_memberId'] == $rowMember['member_id']){
                                $rowWebsite['website_memberName'] = $rowMember['member_name'];
                                $rowWebsite['website_memberEmail'] = $rowMember['member_email'];
                                break;
                            }
                        }
                    }
                }

                if($isDetail){
                    //获取简介
                    $modelWebsiteIntro = new Diana_Model_WebsiteIntro();
                    if($optionsWebsiteIntro = $modelWebsiteIntro->getIntroById(null,$websiteIds)){
                        foreach($rowsWebsite as &$rowWebsite){
                            $rowWebsite['website_intro'] = $optionsWebsiteIntro[$rowWebsite['website_id']]['website_intro'];
                        }
                    }
                }
            }
        }
        return array('total' => $countWebsite,'rows' => $rowsWebsite);
    }

    /**
     * 通过多种渠道获取会员详细信息
     * @param $column 字段，id,name,email
     * @param $key 值
     */
    function getDetail($column,$key)
    {
        if ((empty($column))||(!is_scalar($column))) {
            $this->setMsgs("Invalid Param - Column");
            return false;
        }
        if ((empty($key))||(!is_scalar($key))) {
            $this->setMsgs("Invalid Param - Key");
            return false;
        }

        if($column == 'id'){
            $methodName = 'getRowsById';
        }elseif($column == 'name'){
            $methodName = 'getRowsByName';
        }elseif($column == 'domain'){
            $methodName = 'getRowsByDomain';
        }else{
            $this->setMsgs("Invalid Param - column ".$column);
            return false;
        }
        $modelWebsite = new Diana_Model_Website();
        if(!$rowsWebsite = $modelWebsite->$methodName(null,$key)){
            return false;
        }
        return $this->getWebsiteByRow($rowsWebsite[0]);
    }

    function getWebsiteById($websiteId,$trend = false)
    {
        if(empty($websiteId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        if(!is_numeric($websiteId)){
            $this->setMsgs('参数类型错误');
            return false;
        }
        //获取网站信息
        $modelWebsite = new Diana_Model_Website();
        if(!$rowsWebsite = $modelWebsite->getRowsById(null,$websiteId)){
            return false;
        }
        $rowWebsite = $rowsWebsite[0];
        return $this->getWebsiteByRow($rowWebsite,$trend = false);
    }
    /**
     * 通过获取网站详细信息
     * @param $websiteId 网站ID
     * @param bool $trend 是否添加统计
     * @return array|bool 网站详细信息
     */
    function getWebsiteByRow($rowWebsite,$trend = false)
    {
        if(empty($rowWebsite)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        if(!is_array($rowWebsite)){
            $this->setMsgs('参数类型错误');
            return false;
        }
        $websiteId = $rowWebsite['website_id'];
        //获取地区级
        $modelWebsiteArea = new Diana_Model_WebsiteArea();
        if($rowsWebsiteArea = $modelWebsiteArea->getRowsById(null,$rowWebsite['website_areaId'])){
            $rowWebsite['website_areaName'] = $rowsWebsiteArea[0]['area_name_'.DIANA_TRANSLATE_CURRENT];
            $rowWebsite['website_areaFatherId'] =  $rowsWebsiteArea[0]['area_fatherId'];
            if($rowWebsite['website_areaFatherId']){
                if($rowsWebsiteAreaFather = $modelWebsiteArea->getRowsById(null,$rowWebsite['website_areaFatherId'])){
                    $rowWebsite['website_areaFatherName'] = $rowsWebsiteAreaFather[0]['area_name_'.DIANA_TRANSLATE_CURRENT];
                }
            }
        }
        //获取分类
        $modelWebsiteCategory = new Diana_Model_WebsiteCategory();
        if($rowsWebsiteCategory = $modelWebsiteCategory->getRowsById(null,$rowWebsite['website_categoryId'])){
            $rowWebsite['website_categoryName'] = $rowsWebsiteCategory[0]['category_name_'.DIANA_TRANSLATE_CURRENT];
            $rowWebsite['website_categoryFatherId'] =  $rowsWebsiteCategory[0]['category_fatherId'];
            if($rowWebsite['website_categoryFatherId']){
                if($rowsWebsiteCategoryFather = $modelWebsiteCategory->getRowsById(null,$rowWebsite['website_categoryFatherId'])){
                    $rowWebsite['website_categoryFatherName'] = $rowsWebsiteCategoryFather[0]['category_name_'.DIANA_TRANSLATE_CURRENT];
                }
            }
        }
        //获取网站描述
        $modelWebsiteIntro = new Diana_Model_WebsiteIntro();
        if($websiteIntro = $modelWebsiteIntro->getIntroById(null,$websiteId)){
            $rowWebsite = array_merge($rowWebsite,$websiteIntro[$websiteId]);
        }
        //网站SEO
        $modelWebsiteMeta = new Diana_Model_WebsiteMeta();
        if($rowsWebsiteMeta = $modelWebsiteMeta->getRowsById(null,$websiteId)){
            $rowWebsite['website_meta_keywords'] = $rowsWebsiteMeta[0]['website_meta_keywords'];
            $rowWebsite['website_meta_description'] = $rowsWebsiteMeta[0]['website_meta_description'];
        }else{
            $rowWebsite['website_meta_keywords'] = Com_Functions::utf8substr($rowWebsite['website_tag'],256);
            $rowWebsite['website_meta_description'] = Com_Functions::utf8substr($rowWebsite['website_intro'],256);
        }
        //获取归属人信息
        $modelMember = new Diana_Model_Member();
        if($rowsMember = $modelMember->getRowsById(null,$rowWebsite['website_memberId'])){
            $rowWebsite['website_memberName'] = $rowsMember[0]['website_memberName'];
            $rowWebsite['website_memberEmail'] = $rowsMember[0]['website_memberEmail'];
            $rowWebsite = array_merge($rowWebsite,$rowsMember[0]);
        }
        if($trend)
        {
            //更新点击
            $modelWebsite = new Diana_Model_Website();
            $modelWebsite->updateClickIn($websiteId);
            $modelWebsiteTrendClickIn = new Diana_Model_WebsiteTrendClickIn();
            $modelWebsiteTrendClickIn->update(1,$rowWebsite['website_id']);
            $modelWebsiteCategory = new Diana_Model_WebsiteCategory();
            $modelWebsiteCategory->updateCountClickIn(1,$rowWebsite['website_categoryId']);
            $modelWebsiteArea = new Diana_Model_WebsiteArea();
            $modelWebsiteArea->updateCountClickIn(1,$rowWebsite['website_areaId']);
        }
        return $rowWebsite;
    }



    /**
     * 删除网站
     * @param $websiteId 网站ID
     */
    function deleteById($websiteId)
    {
        if(empty($websiteId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        //删除条件
        $condition = array("website_id" => $websiteId);
        //删除主体内容
        $modelWebsite = new Diana_Model_Website();
        if(!$rowsWebsite = $modelWebsite->getRowsById(null,$websiteId)){
            $this->setMsgs('错误的流水号'.implode(',',$websiteId));
            return false;
        }
        if(!$modelWebsite->delData($condition)){
            $this->setMsgs('主体删除失败');
            return false;
        }
        //删除网站简介
        $modelWebsiteIntro = new Diana_Model_WebsiteIntro();
        if($rowsWebsiteIntro = $modelWebsiteIntro->getRowsById(null,$websiteId)){
            if(!$modelWebsiteIntro->delData($condition)){
                foreach($rowsWebsite as $rowWebsite){
                    $modelWebsite->saveData(1,$rowWebsite);
                }
                $this->setMsgs('简介删除失败');
                return false;
            }
        }
        //删除网站标签关系
        $serviceWebsiteTag = new Diana_Service_WebsiteTag();
        foreach($rowsWebsite as $rowWebsite){
            $serviceWebsiteTag->deleteByWebsite($rowWebsite['website_id']);
        }
        return true;
    }


    /**
     * 更新网站资料
     * @param $websiteId 网站ID
     * @param $data 网站资料
     * @param $man 邮箱
     * @return bool|array 是否成功，成功返回更新后的东西，失败就返回false
     */
    function updateById($websiteId,$data,$man = null)
    {
        //参数不能为空
        if(empty($websiteId)||empty($data)){
            $this->setMsgs("参数不能为空");
            return false;
        }
        if((!is_numeric($websiteId))||(!is_array($data))){
            $this->setMsgs("参数类型错误");
            return false;
        }
        //参数过滤
        $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
        if(!$data = $serviceWebsiteApplyRegister->checkApplyParams($data)){
            $this->setMsgs($serviceWebsiteApplyRegister->getMsgs());
            return false;
        }
        //确认ID是否正确
        $modelWebsite = new Diana_Model_Website();
        if(!$oldRowsWebsite = $modelWebsite->getRowsById(null,$websiteId)){
            $this->setMsgs("无效的网站ID");
            return false;
        }
        //网站名称和域名不能重复
        if($modelWebsite->checkName(true,$data['website_name'],$websiteId)){
            $this->setMsgs("当前网站名称【".$data['website_name']."】已经被使用");
            return false;
        }
        if($modelWebsite->checkDoamin(true,$data['website_domain'],$websiteId)){
            $this->setMsgs("当前网站域名【".$data['website_domain']."】已经被使用");
            return false;
        }
        //确认ID是否正确
        if(!$oldRowsWebsite = $modelWebsite->getRowsById(null,$websiteId)){
            $this->setMsgs("无效的网站ID");
            return false;
        }
        $oldRowWebsite = $oldRowsWebsite[0];
        //保存编辑的信息
        if(!$rowsWebsite = $modelWebsite->updateMainById($websiteId,$data,$man)){
            $this->setMsgs("网站数据保存失败");
            return false;
        }
        $rowWebsite = $rowsWebsite[0];
        //更新网站简介
        if($data['website_intro'] <> $oldRowWebsite['website_intro']){
        	$modelWebsiteIntro = new Diana_Model_WebsiteIntro();
	        if(!$rowsWebsiteIntro = $modelWebsiteIntro->saveIntro($websiteId,$data['website_intro'])){
	            $this->setMsgs("网站简介保存失败");
	            return false;
	        }
	        $rowWebsite['website_intro'] = $rowsWebsiteIntro[0]['website_intro'];
        }        
        //更新网站标签
        $serviceWebsiteTag = new Diana_Service_WebsiteTag();
        if(!$serviceWebsiteTag->updateWebsiteTag($rowWebsite['website_id'],$rowWebsite['website_tag'].",".$rowWebsite['website_name'].",".$rowWebsite['website_domain'])){
            $this->setMsgs("网站标签保存失败");
            $this->setMsgs($serviceWebsiteTag->getMsgs());
            return false;
        }
        //如果已经网站类别变更了
        $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
        if(!$serviceWebsiteCategory->websiteChangeCategory($oldRowWebsite['website_categoryId'],$rowWebsite['website_categoryId'],1,$rowWebsite['website_click_in'],$rowWebsite['website_click_out'])){
            $this->setMsgs("网站类别保存失败");
            $this->setMsgs($serviceWebsiteCategory->getMsgs());
            return false;
        }
        //如果网站国家已经变更了
        $serviceWebsiteArea = new Diana_Service_WebsiteArea();
        if(!$serviceWebsiteArea->websiteChangeArea($oldRowWebsite['website_areaId'],$rowWebsite['website_areaId'],1,$rowWebsite['website_click_in'],$rowsWebsite['website_click_out'])){
            $this->setMsgs("网站区域信息保存失败");
            $this->setMsgs($serviceWebsiteArea->getMsgs());
            return false;
        }
        return $rowWebsite;
    }

}