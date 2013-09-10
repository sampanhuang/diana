<?php
/**
 * 网站删除申请
 * 只有后台在删除的时候才需要申请，前台用户在删除他自己的网站时，不需要申请
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-1
 * Time: 下午9:07
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_WebsiteApplyDelete extends Diana_Service_Abstract
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
    function postApply($websiteId,$source,$sourceId)
    {
        //各项参数不能为空
        if(empty($websiteId)||empty($source)||empty($sourceId)){
            $this->setMsgs("各项参数不能为空");
            return false;
        }
        if(!in_array($source,array(1,2))){
            $this->setMsgs("source 只能是1或2");
            return false;
        }
        if(!is_numeric($sourceId)){
            $this->setMsgs("sourceId 必须是数字");
            return false;
        }
        //判断$sourceId是否有效
        if($source == 1){//前台用户
            $modelMember = new Diana_Model_Member();
            if(!$rowsMember = $modelMember->getRowsById(null,$sourceId)){
                $this->setMsgs('无效的参数 $sourceId');
                return false;
            }
        }elseif($source == 2){//后台用户
            $modelManager = new Diana_Model_Manager();
            if(!$rowsManager = $modelManager->getRowsById(null,$sourceId)){
                $this->setMsgs('无效的参数 $sourceId');
                return false;
            }
        }
        //判断是否
        $modelWebsite = new Diana_Model_Website();
        if(!$rowsWebsite = $modelWebsite->getRowsById(null,$websiteId)){
            $this->setMsgs('参数错误 - websiteId');
            return false;
        }
        //过滤出可以删除的用户
        $modelWebsiteApplyDelete = new Diana_Model_WebsiteApplyDelete();
        $rowsWebsiteApplyDelete = array();
        foreach($rowsWebsite as $rowWebsite){
            if(($source == 1)&&($rowsWebsite['website_memberId'] <> $sourceId)){//前台用户需要判断权限
                continue;
            }
            if($tmpRowsWebsiteApplyDelete = $modelWebsiteApplyDelete->postApply($rowWebsite['website_id'],$rowWebsite,$source,$sourceId)){
                $rowsWebsiteApplyDelete = array_map($rowsWebsiteApplyDelete,$tmpRowsWebsiteApplyDelete);
            }
        }
        return $rowsWebsiteApplyDelete;
    }

    /**
     * 同意申请
     * @param $applyId
     * @return array|bool
     */
    function accedeApply($applyId)
    {
        //确认申请单ID是否正确，并取出申请单
        if(!$tmpCountWebsiteApply = $this->checkApplyId($applyId)){
            return false;
        }
        if(!is_array($applyId)){
            $applyId = explode(',',$applyId);
        }
    }

    /**
     * 拒绝删除申请
     * @param $applyId
     * @return bool
     */
    function demurApply($applyId,$reply = null,$managerId)
    {
        //参数不能为空
        if(empty($applyId)||empty($managerId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        //确认拒绝申请的ID
        if(!$tmpCountWebsiteApply = $this->checkApplyId($applyId)){
            return false;
        }
        //确认reply
        if((!empty($applyId))&&(!is_scalar($reply))){
            $this->setMsgs('错误的删除备注');
            return false;
        }
        //确认manager
        $modelManager = new Diana_Model_Manager();
        if(!$rowsManager = $modelManager->getRowsById(null,$managerId)){
            $this->setMsgs('无效的参数 managerId - '.$managerId);
            return false;
        }
        $managerName = $rowsManager[0]['manager_name'];
        $managerEmail = $rowsManager[0]['manager_email'];
        //更新状态
        $modelWebsiteApplyDelete = new Diana_Model_WebsiteApplyDelete();
        if($rowsWebsiteApply = $modelWebsiteApplyDelete->updatePass($applyId,2,$reply)){
            $this->setMsgs('状态更新失败');
            return false;
        }
        foreach($rowsWebsiteApply as $rowWebsiteApply){
            $tmpRrowWebsite = json_decode($rowWebsiteApply['website_row'],true);
            if($rowWebsiteApply['delete_source'] == 2){
                if($rowsManager = $modelManager->getRowsById(null,$rowWebsiteApply['delete_sourceId'])){
                    $msgSubject = '你申请删除网站【'.$tmpRrowWebsite['website_name'].'】的申请被【'.$managerName.' - '.$managerEmail.' 】拒绝';
                    $msgContent = '';
                }
            }
        }
        //向管理员发送邮件拒绝删除申请的提醒
        $msgSubject = "你删除'.$tmpCountWebsiteApply.'个网站的申请被【'.$managerName.' - '.$managerEmail.' 】拒绝";
        $msgContent = "";
        return $rowsWebsiteApply;
    }

    /**
     * 确认申请单ID是否正确
     * @param $applyId
     * @return array|bool
     */
    function checkApplyId($applyId)
    {
        if(empty($applyId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        $modelWebsiteApplyDelete = new Diana_Model_WebsiteApplyDelete();
        if(!$countWebsiteApply = $modelWebsiteApplyDelete->getCountById(null,$applyId)){
            $this->setMsgs('无效的参数');
            return false;
        }
        return $countWebsiteApply;
    }

    function pageByCondition($page = 1,$pagesize = 1,$condition = array(),$order = null)
    {

        $offset = ($page - 1)*$pagesize;
        if($offset < 0){
            $offset = 0;
        }
        $modelWebsiteApplyDelete = new Diana_Model_WebsiteApplyDelete();
        if($countWebsiteApply = $modelWebsiteApplyDelete->getCountByCondition(null,$condition)){
            if($rowsWebsiteApply = $modelWebsiteApplyDelete->getRowsByCondition(null,$condition,null,$pagesize,$offset)){
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

    function detailById($applyId)
    {
        if(empty($applyId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        if(!is_numeric($applyId)){
            $this->setMsgs('参数类型错误');
            return false;
        }
        //获取网站信息
        $modelWebsiteApplyDelete = new Diana_Model_WebsiteApplyDelete();
        if(!$rowsWebsiteApply = $modelWebsiteApplyDelete->getRowsById(null,$applyId)){
            return false;
        }
        $rowWebsiteApply = $rowsWebsiteApply[0];

        //获取网站详情
        $modelWebsiteApplyDeleteIntro = new Diana_Model_WebsiteApplyDeleteIntro();
        if($rowsWebsiteIntro = $modelWebsiteApplyDeleteIntro->getIntroById(null,$applyId)){
            $rowWebsiteApply['website_intro'] = $rowsWebsiteIntro[$applyId];
        }
        //获取归属人信息
        $modelMember = new Diana_Model_Member();
        if($rowsMember = $modelMember->getRowsById(null,$rowWebsiteApply['website_memberId'])){
            $rowWebsite['website_memberName'] = $rowsMember[0]['website_memberName'];
            $rowWebsite['website_memberEmail'] = $rowsMember[0]['website_memberEmail'];
            $rowWebsite = array_merge($rowWebsiteApply,$rowsMember[0]);
        }
        return $rowWebsite;
    }

    //删除申请
    function deleteById($applyId)
    {
        if(empty($applyId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        //删除条件
        $condition = array("apply_id" => $applyId);
        //删除主体内容
        $modelWebsiteApplyDelete = new Diana_Model_WebsiteApplyDelete();
        if(!$rowsWebsiteApply = $modelWebsiteApplyDelete->getRowsById(null,$applyId)){
            $this->setMsgs('错误的流水号');
            return false;
        }
        if(!$rowsWebsiteApply->delData($condition)){
            $this->setMsgs('主体删除失败');
            return false;
        }
        //删除网站简介
        $modelWebsiteApplyDeleteIntro = new Diana_Model_WebsiteApplyDeleteIntro();
        if($rowsWebsiteApplyIntro = $modelWebsiteApplyDeleteIntro->getRowsById(null,$applyId)){
            if(!$modelWebsiteApplyDeleteIntro->delData($condition)){
                foreach($rowsWebsiteApply as $rowWebsiteApply){
                    $modelWebsiteApplyDelete->saveData(1,$rowWebsiteApply);
                }
                $this->setMsgs('简介删除失败');
                return false;
            }
        }
        return true;
    }

}
