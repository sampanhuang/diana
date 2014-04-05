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
     * 获取
     * @param $id
     * @param string $order
     * @return array|bool
     */
    function getLabelById($id,$order = 'keyword_count_enter_desc')
    {
        $condition = array('keyword_id' => $id);
        $label = array();
        $modelWebsiteKeyword = new Diana_Model_WebsiteKeyword();
        if(!$rowsWebsiteKeyword = $modelWebsiteKeyword->getRowsByCondition(null,$condition,$order)){
            return false;
        }
        foreach($rowsWebsiteKeyword as $rowWebsiteKeyword){
            $label[$rowWebsiteKeyword['keyword_id']] = $rowWebsiteKeyword['keyword_label'];
        }
        return $label;
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
     * 获取查询过滤字段
     * @return array
     */
    function getFilterColumnsForQuery()
    {
        return array(
            'keyword_id',
            'keyword_label',
            'keyword_count_enter_min',
            'keyword_count_enter_max',
        );
    }

    /**
     * 获取排序字段
     * @return array 排序字段
     */
    function getFilterColumnsForOrder()
    {
        return array(
            'keyword_update_time',
            'keyword_insert_time',
            'keyword_count_enter',
        );
    }
    /**
     * 生成数据
     * @param $params
     * @return array
     */
    function makeDataGird($params)
    {
        $page = $params['page']?$params['page']:1;
        $pageSize = $params['rows']?$params['rows']:DIANA_DATAGRID_PAGESIZE_ADMIN;
        $tmpCondition = $this->filterColumns(array($params),$this->getFilterColumnsForQuery());
        $order = implode('_',array($params['order_by_1'],$params['order_by_2']));
        return $this->pageByCondition($page,$pageSize,$tmpCondition[0],$order);
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
