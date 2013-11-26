<?php
/**
 * 会员菜单
 * User: sampan
 * Date: 13-7-19
 * Time: 上午12:04
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_MemberMenu extends Www_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }


    /**
     * 确认他是否有确认载入当前URL
     * @param $tree 资源数
     * @param $module 模块
     * @param $controller 控制器
     * @param $action 选项
     * @return bool
     */
    function check($tree,$module,$controller,$action)
    {
        $temp = array();
        if(empty($tree[$module]['son'][$controller]['son'][$action])){
            return false;
        }
        return true;
    }
    /**
     * @param array $tree 树状权限
     * @return array|bool 菜单
     */
    function makeMenusByTree(array $tree)
    {
        if((empty($tree))||(!is_array($tree))){
            return false;
        }
        $menus = $tree;
        foreach($tree as $moduleKey => $rowModule){
            if((!empty($rowModule['son']))&&(is_array($rowModule['son']))){
                foreach($rowModule['son'] as $controllerKey => $rowController){
                    if((!empty($rowController['son']))&&(is_array($rowController['son']))){
                        foreach($rowController['son'] as $actionKey => $rowAction){
                            if($rowAction['menu_show'] == 1){
                                $menus[$moduleKey]['son'][$controllerKey]['son'][$actionKey]['menu_link'] = '/'.implode('/',array($moduleKey,$controllerKey,$actionKey));
                            }else{
                                unset($menus[$moduleKey]['son'][$controllerKey]['son'][$actionKey]);
                            }
                        }
                    }
                    if(count($menus[$moduleKey]['son'][$controllerKey]['son']) ==  0){
                        unset($menus[$moduleKey]['son'][$controllerKey]);
                    }elseif(count($menus[$moduleKey]['son'][$controllerKey]['son']) == 1){
                        $tmpAction = current($menus[$moduleKey]['son'][$controllerKey]['son']);
                        $menus[$moduleKey]['son'][$controllerKey]['menu_link'] = $tmpAction['menu_link'];
                    }
                }
            }
            if(count($menus[$moduleKey]['son']) ==  0){
                unset($menus[$moduleKey]);
            }elseif(count($menus[$moduleKey]['son']) == 1){
                $tmpController = current($menus[$moduleKey]['son']);
                $menus[$moduleKey]['menu_link'] = $tmpController['menu_link'];
            }
        }
        return $menus;
    }

    /**
     * 获取所有菜单
     * @return array|bool
     */
    function makeTree()
    {
        //提取原始数据
        $modelMemberMenu = new Diana_Model_MemberMenu();
        if(!$rows = $modelMemberMenu->getRows()){
            $this->setMsgs('数据获取失败');
            return false;
        }
        return $this->formatTree($rows);
    }

    /**
     * 通过ID生成菜单数
     * 本方法中出现了三次的重复循环，估计要挨骂了，口下留情啊，赶时间，没空想太多
     * @param array $menuId 菜单ID
     */
    function makeTreeByIds($menuIds)
    {
        if(empty($menuIds)){
            return false;
        }
        if(is_scalar($menuIds)){
            $menuIds = explode(',',$menuIds);
        }
        if(!is_array($menuIds)){
            return false;
        }
        $menuIds = array_filter(array_unique($menuIds));
        $tree = array();
        //提取原始数据
        $modelMemberMenu = new Diana_Model_MemberMenu();
        if(!$rows = $modelMemberMenu->getRowsById(null,$menuIds)){
            $this->setMsgs('数据获取失败');
            return false;
        }
        return $this->formatTree($rows);
    }

    /**
     * 格式化树
     * @param $rowsMenu
     */
    function formatTree($rows)
    {
        $tree = array();
        if(empty($rows)||(!is_array($rows))){
            $this->setMsgs('错误的参数');
            return false;
        }
        //ID与键值对应关系表
        $arrId2Key = array();
        $moduleIds = array();
        //过滤获取一级菜单
        foreach($rows as $row){
            $tmpId = $row['menu_id'];
            $tmpFatherId = $row['menu_fatherId'];
            $tmpKey = $row['menu_key'];
            $arrId2Key[$tmpId] = $tmpKey;
            if(empty($tmpFatherId)){
                $moduleIds[] = $tmpId;
                if(empty($tree[$tmpId])){//如果不存在就直接初始化
                    $tree[$tmpKey] = $row;
                }
            }
        }
        $controllerIds = array();
        //过滤获取二级菜单
        foreach($rows as $row){
            $tmpId = $row['menu_id'];
            $tmpFatherId = $row['menu_fatherId'];
            $tmpKey = $row['menu_key'];
            if((!empty($tmpFatherId))&&(in_array($tmpFatherId,$moduleIds))){
                $tree[$arrId2Key[$tmpFatherId]]['son'][$tmpKey] = $row;
                $controllerIds[$tmpFatherId][] = $tmpId;
            }
        }
        //过滤三级菜单
        foreach($controllerIds as $moduleId => $valueControllerIds){
            foreach($rows as $row){
                $tmpFatherId = $row['menu_fatherId'];
                $tmpKey = $row['menu_key'];
                if((!empty($tmpFatherId))&&(in_array($tmpFatherId,$valueControllerIds))){
                    if(empty($tree[$arrId2Key[$moduleId]]['son'][$arrId2Key[$tmpFatherId]]['menu_link_frist'])){
                        $tree[$arrId2Key[$moduleId]]['son'][$arrId2Key[$tmpFatherId]]['menu_link_frist'] = implode('/',array($arrId2Key[$moduleId],$arrId2Key[$tmpFatherId],$tmpKey));
                    }
                    $tree[$arrId2Key[$moduleId]]['son'][$arrId2Key[$tmpFatherId]]['son'][$tmpKey] = $row;
                }
            }
        }
        return $tree;
    }

    /**
     * 通过ID获取菜单详细信息
     * @param $id 流水号
     */
    function getDetailById($id)
    {
        $modelMemberMenu = new Diana_Model_MemberMenu();
        if(!$rows = $modelMemberMenu->getRowsById(null,$id)){
            return false;
        }
        $row = $rows[0];
        if(!empty($row['menu_fatherId'])){
            if($rowsFather = $modelMemberMenu->getRowsById(null,$row['menu_fatherId'])){
                $row['menu_father'] = $rowsFather[0];
            }
            if(!empty($row['menu_father']['menu_fatherId'])){
                if($rowsGrandfather = $modelMemberMenu->getRowsById(null,$row['menu_father']['menu_fatherId'])){
                    $row['menu_father']['menu_father'] = $rowsGrandfather[0];
                }
            }
        }
        return $row;
    }

    /**
     * 获取详细信息
     * @param $id
     * @return array|bool
     */
    function selectById($id){
        $modelMemberMenu = new Diana_Model_MemberMenu();
        if(!$rows = $modelMemberMenu->getRowsById(null,$id)){
            return false;
        }
        $row = $rows[0];
        if(!empty($row['menu_fatherId'])){
            if($rowsFather = $modelMemberMenu->getRowsById(null,$row['menu_fatherId'])){
                $row['menu_father'] = $rowsFather[0];
            }
            if(!empty($row['menu_father']['menu_fatherId'])){
                if($rowsGrandfather = $modelMemberMenu->getRowsById(null,$row['menu_father']['menu_fatherId'])){
                    $row['menu_father']['menu_father'] = $rowsGrandfather[0];
                }
            }
        }
        return $row;
    }

    /**
     * 通过上级ID获取所有下级ID的数据
     */
    function selectByFather($fatherId = 0)
    {
        $modelMemberMenu = new Diana_Model_MemberMenu();
        if(!$rows = $modelMemberMenu->getRowsByFather(null,$fatherId)){
            return false;
        }
        return $rows;
    }

}