<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-19
 * Time: 下午8:10
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_WebsiteKeyword extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
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
        $modelWebsiteKeyword = new Diana_Model_WebsiteKeyword();
        if(!$rowsWebsiteKeyword = $modelWebsiteKeyword->getRowsByCondition(null,$condition,$order,$counter,0)){
            return false;
        }
        return $rowsWebsiteKeyword;
    }

    /**
     * @param $key 关键字
     * @param $page 当前页
     * @param $pagesize 每页的纪录数
     */
    function pageByCondition($page = 1,$pagesize = 1,$condition = array(),$order = null)
    {

        $offset = ($page - 1)*$pagesize;
        if($offset < 0){
            $offset = 0;
        }
        $modelWebsiteKeyword = new Diana_Model_WebsiteKeyword();
        if($countWebsiteKeyword = $modelWebsiteKeyword->getCountByCondition(null,$condition)){
            $rowsWebsiteKeyword = $modelWebsiteKeyword->getRowsByCondition(null,$condition,$order,$pagesize,$offset);
        }
        return array('total' => $countWebsiteKeyword,'rows' => $rowsWebsiteKeyword);
    }

    /**
     * 搜索统计
     * @param $keyword
     * @return bool
     */
    function search($keyword)
    {
        if(empty($keyword)){
            $this->setMsgs('关键字不能为空');
            return false;
        }
        //保存
        $modelWebsiteKeyword = new Diana_Model_WebsiteKeyword();
        if(!$rowsWebsiteKeyword = $modelWebsiteKeyword->saveLabel($keyword)){
            $this->setMsgs('无效关键字');
            return false;
        }
        //得到ID
        $keywordId  = array();
        foreach($rowsWebsiteKeyword as $rowWebsiteKeyword){
            $keywordId[] = $rowWebsiteKeyword['keyword_id'];
        }
        //更新
        if(!$modelWebsiteKeyword->addCount($keywordId)){
            $this->setMsgs('更新失败');
            return false;
        }

        $modelWebsiteTrendSearch = new Diana_Model_WebsiteTrendSearch();
        if(!$modelWebsiteTrendSearch->update(1,$keywordId)){
            $this->setMsgs('搜索动态更新失败');
            return false;
        }
        return true;

    }
}
