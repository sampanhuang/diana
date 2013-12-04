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
        //判断这个网站名是否已经被注册
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
        if(!$rowsWebsiteApply = $modelWebsiteApplyRegister->postApply($memberId,$data['website_name'],$data['website_domain'],$data['website_tag'],$data['website_categoryId'],$data['website_areaId'])){
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
     * @param $registerId
     * @return array|bool
     */
    function accedeApply($registerId)
    {
        //确认申请单ID是否正确，并取出申请单
        if(!$rowsWebsiteApply = $this->checkApplyId($registerId)){
            return false;
        }
        if(!is_array($registerId)){
            $registerId = explode(',',$registerId);
        }
        //获取他们的简介
        $modelWebsiteApplyRegisterInstro = new Diana_Model_WebsiteApplyRegisterIntro();
        if(!$optionsWebsiteApplyInstro = $modelWebsiteApplyRegisterInstro->getIntroById(null,$registerId)){
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
            if($rowWebsiteApply['register_pass'] == 0){
                $tmpApplyId = $rowWebsiteApply['register_id'];
                $checkName[$tmpApplyId] = $rowWebsiteApply['website_name'];
                $checkDomain[$tmpApplyId] = $rowWebsiteApply['website_domain'];
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
        if($rowsWebsiteByDoamin = $modelWebsite->getRowsByDomain(true,$checkDomain)){
            foreach($rowsWebsiteByDoamin as $rowWebsiteByDoamin){
                $existWebsiteDomain[] = $rowWebsiteByDoamin['website_domain'];
            }
        }
        //剔除已经存在的纪录，避免重复提交
        foreach($tmpInsertData as $keyApplyId => $valApply){
            if(in_array($valApply['website_name'],$existWebsiteName)){
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
                    $modelWebsiteIntro->saveIntro($tmpRowsWebsite[0]['website_id'],$optionsWebsiteApplyInstro[$tmpRowsWebsite[0]['website_applyId']]);
                    if(!$serviceWebsiteTag->updateWebsiteTag($tmpRowsWebsite[0]['website_id'],$tmpRowsWebsite[0]['website_tag'].",".$tmpRowsWebsite[0]['website_name'].",".$tmpRowsWebsite[0]['website_domain'])){
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
     * @param $registerId
     * @return bool
     */
    function demurApply($registerId)
    {
        if(!$rowsWebsiteApply = $this->checkApplyId($registerId)){
            return false;
        }
        $modelWebsiteApplyRegister = new Diana_Model_WebsiteApplyRegister();
        $modelWebsiteApplyRegister->updatePass($registerId,2);
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

    function pageByCondition($page = 1,$pagesize = 1,$condition = array(),$order = null)
    {

        $offset = ($page - 1)*$pagesize;
        if($offset < 0){
            $offset = 0;
        }
        $modelWebsiteApplyRegister = new Diana_Model_WebsiteApplyRegister();
        if($countWebsiteApply = $modelWebsiteApplyRegister->getCountByCondition(null,$condition)){
            if($rowsWebsiteApply = $modelWebsiteApplyRegister->getRowsByCondition(null,$condition,null,$pagesize,$offset)){
                $memberIds = array();
                foreach($rowsWebsiteApply as $rowWebsiteApply){
                    if(!empty($rowWebsite['website_memberId'])){
                        $memberIds[$rowWebsite['website_memberId']] = $rowWebsite['website_memberId'];
                    }
                }
                $modelMember = new Diana_Model_Member();
                if($rowsMember = $modelMember->getRowsById(null,$memberIds)){
                    foreach($rowsWebsiteApply as &$rowWebsite){
                        foreach($rowsMember as $rowMember){
                            if($rowWebsite['website_memberId'] == $rowMember['member_id']){
                                $rowWebsite['website_memberName'] = $rowMember['member_name'];
                                $rowWebsite['website_memberEmail'] = $rowMember['member_email'];
                                break;
                            }
                        }
                    }
                }
            }
        }
        return array('total' => $countWebsiteApply,'rows' => $rowsWebsiteApply);
    }

    function detailById($registerId)
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
        $rowWebsiteApply = $rowsWebsiteApply[0];
        //获取网站详情
        $modelWebsiteApplyRegisterIntro = new Diana_Model_WebsiteApplyRegisterIntro();
        if($rowsWebsiteIntro = $modelWebsiteApplyRegisterIntro->getIntroById(null,$registerId)){
            $rowWebsiteApply['website_intro'] = $rowsWebsiteIntro[$registerId];
        }
        //获取归属人信息
        $modelMember = new Diana_Model_Member();
        if($rowsMember = $modelMember->getRowsById(null,$rowWebsiteApply['website_memberId'])){
            $rowWebsite['website_memberName'] = $rowsMember[0]['website_memberName'];
            $rowWebsite['website_memberEmail'] = $rowsMember[0]['website_memberEmail'];
            $rowWebsiteApply = array_merge($rowWebsiteApply,$rowsMember[0]);
        }
        return $rowWebsiteApply;
    }

    //删除申请
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
        if(!$rowsWebsiteApply->delData($condition)){
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
        return true;
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
        );
        return array_filter(array_intersect_key($post,$exp));

    }

}
