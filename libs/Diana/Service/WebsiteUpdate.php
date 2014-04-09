<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-9-7
 * Time: 上午7:50
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_WebsiteUpdate extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }


    function postUpdate($websiteId,$data)
    {
        //确认提交是否正确
        if(!$data = $this->checkUpdateParam($websiteId,$data)){
            return false;
        }


    }

    /**
     * 将更新纪录写入
     * @param $websiteId 网站ID
     * @param $data 更新提交内容
     */
    function writeUpdate($websiteId,$data)
    {
        $modelWebsiteUpdate = new Diana_Model_WebsiteUpdate();

    }

    /**
     * 确认参数
     * @param $websiteId 网站ID
     * @param $data 提交的参数
     * @return array|bool 失败返回false，成功返回过滤后的$data
     */
    function checkUpdateParam($websiteId,$data)
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
        $serviceWebsiteApply = new Diana_Service_WebsiteApply();
        if(!$data = $serviceWebsiteApply->checkApplyParams($data)){
            $this->setMsgs($serviceWebsiteApply->getMsgs());
            return false;
        }
        //网站名称和域名不能重复
        $modelWebsite = new Diana_Model_Website();
        if($modelWebsite->checkName(true,$data['website_name'],$websiteId)){
            $this->setMsgs("当前网站名称【".$data['website_name']."】已经被使用");
            return false;
        }
        if($modelWebsite->checkDoamin(true,$data['website_domain'],$websiteId)){
            $this->setMsgs("当前网站域名【".$data['website_domain']."】已经被使用");
            return false;
        }
        //确认ID是否正确
        if(!$rowsWebsite = $modelWebsite->getRowsById(null,$websiteId)){
            $this->setMsgs("无效的网站ID");
            return false;
        }
        return $data;
    }

}
