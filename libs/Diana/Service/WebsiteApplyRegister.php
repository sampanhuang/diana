<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-1
 * Time: 下午9:07
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_WebsiteApplyRegister extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }



    /**
     * 提交申请
     * 手工回滚
     * @param $data
     * @return bool
     */
    function postApply($data)
    {
        if(!$data = $this->checkApplyParams($data)){
            return false;
        }
        //判断这个网站名是否已经被注册（简体＼繁体）
        $modelWebsite = new Diana_Model_Website();
        if($rowsWebsite = $modelWebsite->getRowsByName(null,$data['website_name'])){
            $this->setMsgs('当前站点[<a href="/default/website/detail/website_id/'.$rowsWebsite[0]['website_id'].'" target="_blank" title="'.$rowsWebsite[0]['website_name'].'--'.$rowsWebsite[0]['website_domain'].'">'.$data['website_name'].'</a>]已经存在，请不要重复提交');
            return false;
        }
        //判断这个域名是否已经被注册
        if($rowsWebsite = $modelWebsite->getRowsByDomain(null,$data['website_domain'])){
            $this->setMsgs('当前域名[<a href="/default/website/detail/website_id/'.$rowsWebsite[0]['website_id'].'" target="_blank" title="'.$rowsWebsite[0]['website_name'].'--'.$rowsWebsite[0]['website_domain'].'">'.$data['website_domain'].'</a>]已经存在，请不要重复提交');
            return false;
        }
        //回滚的时候是否需要删除
        $rollbackMember = 0;
        //查询这个邮箱有没有注册过用户，通过这个邮箱得到ID
        $modelMember = new Diana_Model_Member();
        if(!$rowsMember = $modelMember->getRowsByEmail(null,$data['website_memberEmail'])){
            if(!$rowsMember = $modelMember->register(1,$data['website_memberEmail'],md5($data['website_memberEmail']),md5(time()))){
                $this->setMsgs('邮箱'.$data['website_memberEmail'].'注册失败');
                return false;
            }
            $rollbackMember = 1;
        }
        $rowMember = $rowsMember[0];
        $memberId = $rowMember['member_id'];
        $memberEmail = $rowMember['member_email'];
        $memberName = $rowMember['member_name'];
        //写入纪录
        $modelWebsiteApplyRegister = new Diana_Model_WebsiteApplyRegister();
        if(!$rowsWebsiteApply = $modelWebsiteApplyRegister->postApply($memberId,$data['website_name'],$data['website_domain'],$data['website_tag'],$data['website_categoryId'],$data['website_areaId'],$data['register_language'])){
            //删除会员数据
            if($rollbackMember == 1){
                $modelMember->deleteById($memberId);
            }
            //返回提示信息
            $this->setMsgs('网站'.$data['website_name'].'申请失败，无法插入网站纪录');
            return false;
        }
        $registerId = $rowsWebsiteApply[0]['register_id'];
        //添加网站介绍
        $modelWebsiteApplyRegisterIntro = new Diana_Model_WebsiteApplyRegisterIntro();
        if(!$modelWebsiteApplyRegisterIntro->saveIntro($registerId,$data['website_intro'])){
            //删除提交数据
            $modelWebsiteApplyRegister->deleteById($registerId);
            //删除会员数据
            if($rollbackMember == 1){
                $modelMember->deleteById($memberId);
            }
            //返回提示信息
            $this->setMsgs('网站'.$data['website_name'].'申请失败，无法插入网站简介');
            return false;
        }
        //写入动态
        $modelWebsiteTrendApplyRegister = new Diana_Model_WebsiteTrendApplyRegister();
        if(!$modelWebsiteTrendApplyRegister->update()){
            //删除提交数据
            $modelWebsiteApplyRegister->deleteById($registerId);
            //删除网站描述
            $modelWebsiteApplyRegisterIntro->clearIntro($registerId);
            //删除会员数据
            if($rollbackMember == 1){
                $modelMember->deleteById($memberId);
            }
            //返回提示信息
            $this->setMsgs('网站'.$data['website_name'].'申请失败，无法更新分析数据');
            return false;
        }
        //写入网站提交日志
        $serviceMemberLog = new Diana_Service_MemberLog();
        if(!$serviceMemberLog->write(31,$memberId,$memberEmail,$memberName)){
        	$this->setMsgs($serviceMemberLog->getMsgs());
        }
        return true;
    }

    /**
     * 检查申请参数
     * @param $data
     * @return array|bool
     */
    function checkApplyParams($data)
    {
        if(empty($data)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        if(!is_array($data))
        {
            $this->setMsgs('参数类型错误');
            return false;
        }
        if(empty($data['website_name'])){
            $this->setMsgs('网站名不能为空');
            return false;
        }
        if(empty($data['website_domain'])){
            $this->setMsgs('网站域名不能为空');
            return false;
        }
        if(empty($data['website_categoryId'])){
            $this->setMsgs('请选择网站类型');
            return false;
        }
        if(empty($data['website_areaId'])){
            $this->setMsgs('请选择所处区域');
            return false;
        }
        if(empty($data['website_memberEmail'])){
            $this->setMsgs('网站联系邮箱不能为空!这很重要！');
            return false;
        }
        foreach($data as $key => &$value){
            $value = htmlspecialchars(trim($value));
            if(in_array($key,array('website_domain','website_memberEmail'))){
                $value = strtolower($value);
            }
            if($key == 'website_domain'){
                $value = Com_Functions::clearSprit($value);//去掉后面的斜杠
                $value = Com_Functions::UrlAddHttpPre($value);//加上http://前缀
            }elseif($key == 'website_tag'){
                $value = str_replace("、",",",$value);
                $value = str_replace("，",",",$value);
                $value = str_replace(" ",",",$value);
                $value = str_replace("|",",",$value);
                $value = explode(',',$value);
                $value = array_map('trim',$value);//过滤空值
                $value = array_unique($value);//过滤重复值
                $value = array_filter($value);//过滤空值
                $value = implode(",",$value);
            }
        }
        return $data;
    }

    /**
     * 同意申请
     * @param $params
     * @return array|bool
     */
    function acceptApply($params)
    {
        $registerId = $params['register_id'];
        //确认申请单ID是否正确，并取出申请单
        if(!$rowsWebsiteApply = $this->checkApplyId($registerId)){
            return false;
        }
        if(!is_array($registerId)){
            $registerId = explode(',',$registerId);
        }
        //获取他们的简介
        $modelWebsiteApplyRegisterIntro = new Diana_Model_WebsiteApplyRegisterIntro();
        if(!$optionsWebsiteApplyIntro = $modelWebsiteApplyRegisterIntro->getIntroById(null,$registerId)){
            return false;
        }
        //已经存在重复网站名和域名的申请ID
        $existApplyId = array();
        //已经存在的网站名
        $existWebsiteName = array();
        //已经存在的域名
        $existWebsiteDomain = array();
        //没有出现重复网站名和重复域名的申请单
        $okApplyId = array();
        //需要被检查的网站名
        $checkName = array();
        //需要被检查的域名
        $checkDomain = array();
        //要插入的数据
        $insertData = array();
        //临时要插入的数据
        $tmpInsertData = array();
        $rowsWebsite = array();
        foreach($rowsWebsiteApply as $rowWebsiteApply){
            if($rowWebsiteApply['register_pass'] !== 1){
                $tmpApplyId = $rowWebsiteApply['register_id'];
                $checkName[$tmpApplyId] = $rowWebsiteApply['website_name'];
                $checkDomain[$tmpApplyId] = $rowWebsiteApply['website_domain'];
                $tmpRegisterLanguage = $rowWebsiteApply['register_language'];
                $tmpInsertData[$tmpApplyId] = array(
                    'website_memberId' => $rowWebsiteApply['website_memberId'],
                    'website_name' => $rowWebsiteApply['website_name'],
                    'website_domain' => $rowWebsiteApply['website_domain'],
                    'website_logo' => $rowWebsiteApply['website_logo'],
                    'website_cover' => $rowWebsiteApply['website_cover'],
                    'website_tag' => $rowWebsiteApply['website_tag'],
                    'website_categoryId' => $rowWebsiteApply['website_categoryId'],
                    'website_areaId' => $rowWebsiteApply['website_areaId'],
                    'website_apply_time' => $rowWebsiteApply['register_insert_time'],
                    'website_apply_ip' => $rowWebsiteApply['register_insert_ip'],
                    'website_applyId' => $rowWebsiteApply['register_id'],
                    'website_insert_time' => time(),
                    'register_language' => $rowWebsiteApply['register_language'],
                );
            }
        }
        //找出已经存在的纪录
        $modelWebsite = new Diana_Model_Website();
        if($rowsWebsiteByName = $modelWebsite->getRowsByName(true,$checkName)){
            foreach($rowsWebsiteByName as $rowWebsiteByName){
                $existWebsiteName[] = $rowWebsiteByName['website_name'];
            }
        }
        if($rowsWebsiteByDomain = $modelWebsite->getRowsByDomain(true,$checkDomain)){
            foreach($rowsWebsiteByDomain as $rowWebsiteByDomain){
                $existWebsiteDomain[] = $rowWebsiteByDomain['website_domain'];
            }
        }
        //剔除已经存在的纪录，避免重复提交
        foreach($tmpInsertData as $keyApplyId => $valApply){
            $tmpRegisterLanguage = $valApply['register_language'];
            if(in_array($valApply['website_name_'.$tmpRegisterLanguage],$existWebsiteName)){
                break;
            }
            if(in_array($valApply['website_domain'],$existWebsiteDomain)){
                break;
            }
            $insertData[$keyApplyId] = $valApply;
            $okApplyId[] = $keyApplyId;
        }
        $existApplyId = array_diff($registerId,$okApplyId);
        //没有冲突的插入纪录
        if(empty($insertData)){
            $this->setMsgs('没有要插入的纪录，也就是说你要注册的网站已经存在了');
            return false;
        }
        if(!empty($insertData)){
            $serviceWebsiteTag = new Diana_Service_WebsiteTag();
            $modelWebsiteIntro = new Diana_Model_WebsiteIntro();
            foreach($insertData as $keyApplyId => $valWebsite){
                if($tmpRowsWebsite = $modelWebsite->saveData(1,$valWebsite)){
                    $modelWebsiteIntro->saveIntro($tmpRowsWebsite[0]['website_id'],$optionsWebsiteApplyIntro[$tmpRowsWebsite[0]['website_applyId']]);
                    if(!$serviceWebsiteTag->updateWebsiteTag($tmpRowsWebsite[0]['website_id'],$tmpRowsWebsite[0]['website_tag'])){
                        $this->setMsgs($serviceWebsiteTag->getMsgs());
                    }
                    $rowsWebsite = array_merge($rowsWebsite,$tmpRowsWebsite);
                }
            }
        }
        //更新OK的
        $modelWebsiteApplyRegister = new Diana_Model_WebsiteApplyRegister();
        if(!empty($okApplyId)){
            $modelWebsiteApplyRegister->updatePass($okApplyId,1);
        }
        //已经存在的就没有办法通过审核
        if(!empty($existApplyId)){
            $modelWebsiteApplyRegister->updatePass($existApplyId,2);
        }
        //更新网站注册动态
        $modelWebsiteTrendRegister = new Diana_Model_WebsiteTrendRegister();
        if(!empty($okApplyId)){
            $modelWebsiteTrendRegister->update(count($okApplyId));
        }
        //更新网站类型与网站国家动态
        $modelWebsiteCategory = new Diana_Model_WebsiteCategory();
        $modelWebsiteArea = new Diana_Model_WebsiteArea();
        foreach($rowsWebsiteApply as $rowWebsiteApply){
            $modelWebsiteCategory->updateCountWebsite(1,$rowWebsiteApply['website_categoryId']);
            $modelWebsiteArea->updateCountWebsite(1,$rowWebsiteApply['website_areaId']);
        }
        //刷新首页
        $serviceWebsite = new Diana_Service_Website();
        $serviceWebsite->flushHtmlIndex();
        return $rowsWebsite;
    }

    /**
     * 拒绝申请
     * @param $params
     * @return bool
     */
    function rejectApply($params)
    {
        $registerId = $params['register_id'];
        if(!$rowsWebsiteApply = $this->checkApplyId($registerId)){
            return false;
        }
        $modelWebsiteApplyRegister = new Diana_Model_WebsiteApplyRegister();
        $rows = $modelWebsiteApplyRegister->updatePass($registerId,2);
        $this->setMsgs('成功拒绝'.count($rows).'条纪录！');
        return $rowsWebsiteApply;
    }

    /**
     * 确认申请单ID是否正确
     * @param $registerId
     * @return array|bool
     */
    function checkApplyId($registerId)
    {
        if(empty($registerId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        $modelWebsiteApplyRegister = new Diana_Model_WebsiteApplyRegister();
        if(!$rowsWebsiteApply = $modelWebsiteApplyRegister->getRowsById(null,$registerId)){
            $this->setMsgs('无效的参数 - '.implode(",",$registerId));
            return false;
        }
        return $rowsWebsiteApply;
    }

    /**
     * @param $params
     * @return bool
     */
    function deleteWithAjax($params)
    {
        $registerId = $params['register_id'];
        return $this->deleteById($registerId);
    }

    /**
     * 删除申请
     * @param $registerId
     * @return bool
     */
    function deleteById($registerId)
    {
        if(empty($registerId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        //删除条件
        $condition = array("register_id" => $registerId);
        //删除主体内容
        $modelWebsiteApplyRegister = new Diana_Model_WebsiteApplyRegister();
        if(!$rowsWebsiteApply = $modelWebsiteApplyRegister->getRowsById(null,$registerId)){
            $this->setMsgs('错误的流水号');
            return false;
        }
        if(!$rowsAffected = $modelWebsiteApplyRegister->delData($condition)){
            $this->setMsgs('主体删除失败');
            return false;
        }
        //删除网站简介
        $modelWebsiteApplyRegisterIntro = new Diana_Model_WebsiteApplyRegisterIntro();
        if($rowsWebsiteApplyIntro = $modelWebsiteApplyRegisterIntro->getRowsById(null,$registerId)){
            if(!$modelWebsiteApplyRegisterIntro->delData($condition)){
                foreach($rowsWebsiteApply as $rowWebsiteApply){
                    $modelWebsiteApplyRegister->saveData(1,$rowWebsiteApply);
                }
                $this->setMsgs('简介删除失败');
                return false;
            }
        }
        $this->setMsgs('成功删除'.$rowsAffected.'条纪录');
        return true;
    }

    /**
     * 分页
     * @param int $params 当前页
     * @return array
     */
    function makeDataGrid($params)
    {
        $page = $params['page'];//前页
        $pageSize = $params['rows'];//每页纪录数
        $condition = $this->filterFormSearch($params);//查询条件
        $order = $params;//排序
        $offset = ($page - 1) * $pageSize;
        if($offset < 0){
            $offset = 0;
        }
        $modelWebsiteApplyRegister = new Diana_Model_WebsiteApplyRegister();
        if($countWebsiteApply = $modelWebsiteApplyRegister->getCountByCondition(null,$condition)){
            if($rowsWebsiteApply = $modelWebsiteApplyRegister->getRowsByCondition(null,$condition,null,$pageSize,$offset)){
                $memberIds = array();
                //$areaIds = array();
                //$categoryIds = array();
                foreach($rowsWebsiteApply as $rowWebsiteApply){
                    $memberIds[$rowWebsiteApply['website_memberId']] = $rowWebsiteApply['website_memberId'];
                    //$areaIds[$rowWebsiteApply['website_areaId']] = $rowWebsiteApply['website_areaId'];
                    //$categoryIds[$rowWebsiteApply['website_categoryId']] = $rowWebsiteApply['website_categoryId'];
                }
                //获取地区名字
                $serviceWebsiteArea = new Diana_Service_WebsiteArea();
                if($allWebsiteArea = $serviceWebsiteArea->getAll()){
                    foreach($rowsWebsiteApply as &$rowWebsiteApply){
                        $tmpWebsiteAreaId = $rowWebsiteApply['website_areaId'];
                        if(!empty($tmpWebsiteAreaId)){
                            $rowWebsiteApply['website_areaName'] = $allWebsiteArea[$tmpWebsiteAreaId]['area_name_'.DIANA_TRANSLATE_CURRENT];
                            $tmpWebsiteAreaFatherId = $allWebsiteArea[$tmpWebsiteAreaId]['area_fatherId'];
                            if(!empty($tmpWebsiteAreaFatherId)){
                                $rowWebsiteApply['website_areaFatherId'] = $tmpWebsiteAreaFatherId;
                                $rowWebsiteApply['website_areaFatherName'] = $allWebsiteArea[$tmpWebsiteAreaFatherId]['area_name_'.DIANA_TRANSLATE_CURRENT];
                            }
                        }
                    }
                }

                //获取分类名字
                $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
                if($allWebsiteCategory = $serviceWebsiteCategory->getAll()){
                    foreach($rowsWebsiteApply as &$rowWebsiteApply){
                        $tmpWebsiteCategoryId = $rowWebsiteApply['website_categoryId'];
                        if(!empty($tmpWebsiteCategoryId)){
                            $rowWebsiteApply['website_categoryName'] = $allWebsiteCategory[$tmpWebsiteCategoryId]['category_name_'.DIANA_TRANSLATE_CURRENT];
                            $tmpWebsiteCategoryFatherId = $allWebsiteCategory[$tmpWebsiteCategoryId]['category_fatherId'];
                            if(!empty($tmpWebsiteCategoryFatherId)){
                                $rowWebsiteApply['website_categoryFatherId'] = $tmpWebsiteCategoryFatherId;
                                $rowWebsiteApply['website_categoryFatherName'] = $allWebsiteCategory[$tmpWebsiteCategoryId]['category_name_'.DIANA_TRANSLATE_CURRENT];
                            }
                        }
                    }
                }
                //获取会员名字
                $modelMember = new Diana_Model_Member();
                if($rowsMember = $modelMember->getRowsById(null,$memberIds)){
                    foreach($rowsWebsiteApply as &$rowWebsiteApply){
                        foreach($rowsMember as $rowMember){
                            if($rowWebsiteApply['website_memberId'] == $rowMember['member_id']){
                                $rowWebsiteApply['website_memberName'] = $rowMember['member_name'];
                                $rowWebsiteApply['website_memberEmail'] = $rowMember['member_email'];
                                break;
                            }
                        }
                    }
                }
            }
        }
        return array('total' => intval($countWebsiteApply),'rows' => $rowsWebsiteApply);
    }


    /**
     * 通过多种渠道获取会员详细信息
     * @param $column 字段，id,name,domain
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
        if($column == 'register_id'){
            $detailMember = $this->getApplyById($key);
        }elseif($column == 'website_id'){
            $detailMember = $this->getApplyByWebsiteId($key);
        }else{
            $this->setMsgs("Invalid Param - column ".$column);
            return false;
        }
        return $detailMember;
    }


    /**
     * 通过ID查看
     * @param $id
     * @return array|bool
     */
    function getApplyById($registerId)
    {
        if(empty($registerId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        if(!is_numeric($registerId)){
            $this->setMsgs('参数类型错误');
            return false;
        }
        //获取网站信息
        $modelWebsiteApplyRegister = new Diana_Model_WebsiteApplyRegister();
        if(!$rowsWebsiteApply = $modelWebsiteApplyRegister->getRowsById(null,$registerId)){
            return false;
        }
        return $this->getApplyByRow($rowsWebsiteApply[0]);
    }

    /**
     * 通过ID查看
     * @param $id
     * @return array|bool
     */
    function getApplyByWebsiteId($websiteId)
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
        $modelWebsiteApplyRegister = new Diana_Model_WebsiteApplyRegister();
        if(!$rowsWebsiteApply = $modelWebsiteApplyRegister->getRowsByWebsiteId(null,$websiteId)){
            return false;
        }
        return $this->getApplyByRow($rowsWebsiteApply[0]);
    }

    /**
     * 通过
     * @param $rowWebsiteApply
     * @return array|bool
     */
    function getApplyByRow($rowWebsiteApply)
    {
        if(empty($rowWebsiteApply)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        if(!is_array($rowWebsiteApply)){
            $this->setMsgs('参数类型错误');
            return false;
        }
        $registerId = $rowWebsiteApply['register_id'];
        //获取地区级
        $modelWebsiteArea = new Diana_Model_WebsiteArea();
        if($rowsWebsiteArea = $modelWebsiteArea->getRowsById(null,$rowWebsiteApply['website_areaId'])){
            $rowWebsiteApply['website_areaName'] = $rowsWebsiteArea[0]['area_name_'.DIANA_TRANSLATE_CURRENT];
            $rowWebsiteApply['website_areaFatherId'] =  $rowsWebsiteArea[0]['area_fatherId'];
            if($rowWebsiteApply['website_areaFatherId']){
                if($rowsWebsiteAreaFather = $modelWebsiteArea->getRowsById(null,$rowWebsiteApply['website_areaFatherId'])){
                    $rowWebsiteApply['website_areaFatherName'] = $rowsWebsiteAreaFather[0]['area_name_'.DIANA_TRANSLATE_CURRENT];
                }
            }
        }
        //获取分类
        $modelWebsiteCategory = new Diana_Model_WebsiteCategory();
        if($rowsWebsiteCategory = $modelWebsiteCategory->getRowsById(null,$rowWebsiteApply['website_categoryId'])){
            $rowWebsiteApply['website_categoryName'] = $rowsWebsiteCategory[0]['category_name_'.DIANA_TRANSLATE_CURRENT];
            $rowWebsiteApply['website_categoryFatherId'] =  $rowsWebsiteCategory[0]['category_fatherId'];
            if($rowWebsiteApply['website_categoryFatherId']){
                if($rowsWebsiteCategoryFather = $modelWebsiteCategory->getRowsById(null,$rowWebsiteApply['website_categoryFatherId'])){
                    $rowWebsiteApply['website_categoryFatherName'] = $rowsWebsiteCategoryFather[0]['category_name_'.DIANA_TRANSLATE_CURRENT];
                }
            }
        }
        //获取网站详情
        $modelWebsiteApplyRegisterIntro = new Diana_Model_WebsiteApplyRegisterIntro();
        if($rowsWebsiteIntro = $modelWebsiteApplyRegisterIntro->getIntroById(null,$registerId)){
            $rowWebsiteApply['website_intro'] = $rowsWebsiteIntro[$registerId];
        }
        //获取归属人信息
        $modelMember = new Diana_Model_Member();
        if($rowsMember = $modelMember->getRowsById(null,$rowWebsiteApply['website_memberId'])){
            $rowWebsiteApply['website_memberName'] = $rowsMember[0]['member_name'];
            $rowWebsiteApply['website_memberEmail'] = $rowsMember[0]['member_email'];
            $rowWebsiteApply = array_merge($rowWebsiteApply,$rowsMember[0]);
        }
        return $rowWebsiteApply;
    }



    /**
     * 过滤查询条件
     * @param $post 查询数据
     * @return array 过滤后的查询数据
     */
    function filterFormSearch($post)
    {
        $exp = array(
            'website_name' => 1,
            'website_domain' => 1,
            'register_id' => 1,
            'register_pass' => 1,
            'website_memberId' => 1,
        );
        return array_filter(array_intersect_key($post,$exp));

    }

}
