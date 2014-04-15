<?php
/**
 * 前端菜单
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-10-24
 * Time: 下午9:59
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_FriendLink extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_FriendLink();
    }

    function updateForClick($linkId,$isFirst = null)
    {
        $data = array(
            'link_click_total' => new Zend_Db_Expr('link_click_total + 1'),
            'link_click_last_time' => time(),
        );
        if($isFirst){
            $data['link_click_first_time'] = time();
        }
        $condition = array(
            'link_id' => $linkId,
        );
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 获取有效的友情链接
     * @param null $refresh 是否刷新
     * @param $fatherId 父ID
     * @return array
     */
    function getRowsWithEnable($refresh = null)
    {
        $condition = array("link_enabel_time" => time());
        return $this->getRowsByCondition($refresh,$condition);
    }

    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("link_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

}
