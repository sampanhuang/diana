<?php
/**
 * 菜单
 * User: sampan
 * Date: 13-7-19
 * Time: 上午12:04
 * To change this template use File | Settings | File Templates.
 */
class Admin_Service_ManagerMenu extends Admin_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 插入数据
     *  @param $input
     */
    function insert($input)
    {
        //确认外部参数是否正确
        if(!$input = $this->checkInputWithMenu($input)){
            return false;
        }
        $input['menu_insert_man'] = $input['menu_update_man'] = $this->sessionManager['id'];
        $modelManagerMenu = new Diana_Model_ManagerMenu();
        if(!$rowsManagerMenu = $modelManagerMenu->insert($input)){
            $this->setMsgs('保存失败');
            return false;
        }
        return $rowsManagerMenu[0];
    }

    /**
     * 更新数据
     * @param $input
     * @param $menuId
     */
    function update($input,$menuId)
    {
        if(empty($menuId)||empty($input)){
            $this->setMsgs('参数menuId不能为空');
            return false;
        }
        //确认外部参数是否正确
        if(!$input = $this->checkInputWithMenu($input)){
            return false;
        }
        $input['menu_update_man'] = $this->sessionManager['id'];
        $modelManagerMenu = new Diana_Model_ManagerMenu();
        if(!$rowsManagerMenu = $modelManagerMenu->update($input,$menuId)){
            $this->setMsgs('保存失败');
            return false;
        }
        return $rowsManagerMenu[0];
    }

    /**
     * 通过ID删除菜单
     * @param $menuId 菜单ID
     */
    function deleteById($menuId)
    {
        if(empty($menuId)){
            $this->setMsgs("参数不能为空！");
            return false;
        }
        //包含有下级菜单的不能删除，只允许一级一级删除
        $modelManagerMenu = new Diana_Model_ManagerMenu();
        if($rowsMenuSon = $modelManagerMenu->getRowsByFather(null,$menuId)){
            $this->setMsgs('无法删除包含有下级的菜单！请先删除他的下级！');
            return false;
        }
        if(!$rowsAffected = $modelManagerMenu->delData(array('menu_id' => $menuId))){
            $this->setMsgs('无法删除指定的菜单，也许他们已被删除');
            return false;
        }
        return $rowsAffected;
    }

    /**
     * 确认外部参数是否正确
     * @param $data 外部参数
     */
    function checkInputWithMenu($data)
    {
        $filters = array(
            'menu_label_zh-cn'   => array(new Zend_Filter_StringTrim()),
            'menu_label_zh-tw'   => array(new Zend_Filter_StringTrim()),
            'menu_label_en-us'   => array(new Zend_Filter_StringTrim()),
            'menu_fatherId'   => array(new Zend_Filter_Int()),
            'menu_link'   => array(new Zend_Filter_StringToLower()),
            'menu_show'   => array(new Zend_Filter_Int()),
            'menu_order'   => array(new Zend_Filter_Int()),
        );
        $validators = array(
            'menu_label_zh-cn'   => array(new Zend_Validate_StringLength(4,64)),
            'menu_label_zh-tw'   => array(new Zend_Validate_StringLength(4,64),'allowEmpty' => true),
            'menu_label_en-us'   => array(new Zend_Validate_StringLength(4,64),'allowEmpty' => true),
            'menu_fatherId'   => array(new Zend_Validate_Int()),
            'menu_link'   => array(new Zend_Validate_StringLength(8,256),'allowEmpty' => true),
            'menu_show'   => array(new Zend_Validate_Int()),
            'menu_order'   => array(new Zend_Validate_Int()),
        );
        $input = new Zend_Filter_Input($filters, $validators, $data);
        if ($input->isValid()) {
            return $input->getEscaped();
        }else{
            $messageInput = $input->getMessages();
            foreach($messageInput as $valMsg){
                $this->setMsgs($valMsg);
            }
            return false;
        }
    }



    /**
     * 为combo tree提供数据
     * 用于添加与编辑菜单时的上级菜单
     */
    function makeComboTree($tree)
    {
        if((empty($tree))||(!is_array($tree))){
            return false;
        }
        $treeGird = array();
        foreach($tree as $moduleId => $rowModule){
            $treeGird[$moduleId]['id'] = $rowModule['menu_id'];
            $treeGird[$moduleId]['text'] = $rowModule['menu_label_'.DIANA_TRANSLATE_CURRENT];
            if((!empty($rowModule['son']))&&(is_array($rowModule['son']))){
                $treeGird[$moduleId]['state'] = 'closed';
                foreach($rowModule['son'] as $controllerId => $rowController){
                    $treeGird[$moduleId]['children'][$controllerId]['id'] = $rowController['menu_id'];
                    $treeGird[$moduleId]['children'][$controllerId]['text'] = $rowController['menu_label_'.DIANA_TRANSLATE_CURRENT];
                }
            }
            if(count($treeGird[$moduleId]['children']) >  0){
                $treeGird[$moduleId]['children'] = array_values($treeGird[$moduleId]['children']);
            }
        }
        $treeGird = array_values($treeGird);
        return array(array('id' => 0,'text' => 'root','children' => $treeGird));
    }

    /**
     * 为tree grid提供数据
     */
    function makeTreeGrid($tree)
    {
        if((empty($tree))||(!is_array($tree))){
            return false;
        }
        $treeGird = array();
        foreach($tree as $moduleId => $rowModule){
            $treeGird[$moduleId] = $rowModule;
            $treeGird[$moduleId]['menu_label'] = $rowModule['menu_label_'.DIANA_TRANSLATE_CURRENT];
            if((!empty($rowModule['son']))&&(is_array($rowModule['son']))){
                $treeGird[$moduleId]['state'] = 'closed';
                foreach($rowModule['son'] as $controllerId => $rowController){
                    $treeGird[$moduleId]['children'][$controllerId] = $rowController;
                    $treeGird[$moduleId]['children'][$controllerId]['menu_label'] = $rowController['menu_label_'.DIANA_TRANSLATE_CURRENT];
                    if((!empty($rowController['son']))&&(is_array($rowController['son']))){
                        $treeGird[$moduleId]['children'][$controllerId]['state'] = 'closed';
                        foreach($rowController['son'] as $actionId => $rowAction){
                            $treeGird[$moduleId]['children'][$controllerId]['children'][$actionId] = $rowAction;
                            $treeGird[$moduleId]['children'][$controllerId]['children'][$actionId]['menu_label'] = $rowAction['menu_label_'.DIANA_TRANSLATE_CURRENT];
                        }
                    }
                    if(count($treeGird[$moduleId]['children'][$controllerId]['children']) >  0){
                        $treeGird[$moduleId]['children'][$controllerId]['children'] = array_values($treeGird[$moduleId]['children'][$controllerId]['children']);
                    }
                }
            }
            if(count($treeGird[$moduleId]['children']) >  0){
                $treeGird[$moduleId]['children'] = array_values($treeGird[$moduleId]['children']);
            }
        }
        $treeGird = array_values($treeGird);
        return $treeGird;
    }

    /**
     * 确认他是否有权限载入当前URL
     * @param $tree 资源数
     * @param $module 模块
     * @param $controller 控制器
     * @param $action 选项
     * @return bool
     */
    function check($tree,$module,$controller,$action)
    {
        foreach($tree as $moduleId => $rowMoudle){
            if(!empty($rowMoudle['son'])){
                foreach($rowMoudle['son'] as $controllerId => $rowController){
                    if(!empty($rowController['son'])){
                        foreach($rowController['son'] as $actionId => $rowActon){
                            $tmpUrl = parse_url($rowActon['menu_link']);
                            if(strtolower(trim($tmpUrl['path'])) == strtolower(trim(implode('/',array($module,$controller,$action))))){
                                return true;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * 生成左侧菜单
     * @param array $tree
     * @return array|bool
     */
    function makeMenusByTreeForEasyui(array $tree)
    {
        if((empty($tree))||(!is_array($tree))){
            return false;
        }
        $menus = array();
        foreach($tree as $moduleId => $rowModule){
            if((!empty($rowModule['son']))&&(is_array($rowModule['son']))){
                $menus[$moduleId]['id'] = $rowModule['menu_id'];
                $menus[$moduleId]['text'] = $rowModule['menu_label_'.DIANA_TRANSLATE_CURRENT];
                $menus[$moduleId]['state'] = 'closed';
                $menus[$moduleId]['attributes']['url'] = '';
                foreach($rowModule['son'] as $controllerId => $rowController){
                    if((!empty($rowController['son']))&&(is_array($rowController['son']))){
                        $menus[$moduleId]['children'][$controllerId]['id'] = $rowController['menu_id'];
                        $menus[$moduleId]['children'][$controllerId]['text'] = $rowController['menu_label_'.DIANA_TRANSLATE_CURRENT];
                        $menus[$moduleId]['children'][$controllerId]['state'] = 'closed';
                        $menus[$moduleId]['children'][$controllerId]['attributes']['url'] = '';
                        foreach($rowController['son'] as $actionId => $rowAction){
                            if($rowAction['menu_show'] == 1){
                                $menus[$moduleId]['children'][$controllerId]['children'][$actionId]['id'] = $rowAction['menu_id'];
                                $menus[$moduleId]['children'][$controllerId]['children'][$actionId]['text'] = $rowAction['menu_label_'.DIANA_TRANSLATE_CURRENT];
                                $menus[$moduleId]['children'][$controllerId]['children'][$actionId]['attributes']['url'] = '/'.$rowAction['menu_link'];
                                $menus[$moduleId]['children'][$controllerId]['children'][$actionId]['attributes']['show'] = $rowAction['menu_show'];
                                $menus[$moduleId]['children'][$controllerId]['children'][$actionId]['attributes']['nav'] = implode('/',array($rowModule['menu_label_'.DIANA_TRANSLATE_CURRENT],$rowController['menu_label_'.DIANA_TRANSLATE_CURRENT],$rowAction['menu_label_'.DIANA_TRANSLATE_CURRENT]));
                            }else{
                                unset($menus[$moduleId]['son'][$controllerId]['son'][$actionId]);
                            }
                        }
                    }
                    if(count($menus[$moduleId]['children'][$controllerId]['children']) ==  0){
                        unset($menus[$moduleId]['children'][$controllerId]);
                    }else{
                        $menus[$moduleId]['children'][$controllerId]['children'] = array_values($menus[$moduleId]['children'][$controllerId]['children']);
                    }
                }
            }
            if(count($menus[$moduleId]['children']) ==  0){
                unset($menus[$moduleId]);
            }else{
                $menus[$moduleId]['children'] = array_values($menus[$moduleId]['children']);
            }
        }
        $menus = array_values($menus);
        return $menus;
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
        foreach($tree as $moduleId => $rowModule){
            if((!empty($rowModule['son']))&&(is_array($rowModule['son']))){
                foreach($rowModule['son'] as $controllerId => $rowController){
                    if((!empty($rowController['son']))&&(is_array($rowController['son']))){
                        foreach($rowController['son'] as $actionId => $rowAction){
                            if($rowAction['menu_show'] == 1){
                                $menus[$moduleId]['son'][$controllerId]['son'][$actionId]['menu_link'] = '/'.$rowAction['menu_link'];
                            }else{
                                unset($menus[$moduleId]['son'][$controllerId]['son'][$actionId]);
                            }
                        }
                    }
                    if(count($menus[$moduleId]['son'][$controllerId]['son']) ==  0){
                        unset($menus[$moduleId]['son'][$controllerId]);
                    }elseif(count($menus[$moduleId]['son'][$controllerId]['son']) == 1){
                        $tmpAction = current($menus[$moduleId]['son'][$controllerId]['son']);
                        $menus[$moduleId]['son'][$controllerId]['menu_link'] = $tmpAction['menu_link'];
                    }
                }
            }
            if(count($menus[$moduleId]['son']) ==  0){
                unset($menus[$moduleId]);
            }elseif(count($menus[$moduleId]['son']) == 1){
                $tmpController = current($menus[$moduleId]['son']);
                $menus[$moduleId]['menu_link'] = $tmpController['menu_link'];
            }
        }
        //print_r($menus);
        return $menus;
    }

    /**
     * 获取所有菜单
     * @return array|bool
     */
    function makeTree()
    {
        //提取原始数据
        $modelManagerMenu = new Diana_Model_ManagerMenu();
        if(!$rows = $modelManagerMenu->getRows()){
            $this->setMsgs('数据获取失败');
            return false;
        }
        return $this->formatTree($rows);
    }

    /**
     * 通过ID生成菜单数
     * 
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
        $fatherMenuIds = array();
        $grandfatherMenuIds = array();
        $tree = array();
        //提取原始数据-第一层的
        $modelManagerMenu = new Diana_Model_ManagerMenu();
        if(!$rows = $modelManagerMenu->getRowsById(null,$menuIds)){
            $this->setMsgs('数据获取失败');
            return false;
        }
        //获取第二层ID
        foreach($rows as $row){
            if(!empty($row['menu_fatherId'])){
                $fatherMenuIds[] =$row['menu_fatherId'];
            }
        }
        $menuIds = array_merge($menuIds,$fatherMenuIds);
        //获取第一层ID
        if($rowsFather = $modelManagerMenu->getRowsById(null,$fatherMenuIds)){
            foreach($rowsFather as $rowFather){
                if(!empty($rowFather['menu_fatherId'])){
                    $grandfatherMenuIds[] =$rowFather['menu_fatherId'];
                }
            }
            $menuIds = array_merge($menuIds,$grandfatherMenuIds);
        }
        $menuIds = array_filter(array_unique($menuIds));
        //再重新取一次
        if(!$rowsMenu = $modelManagerMenu->getRowsById(null,$menuIds)){
            $this->setMsgs('数据获取失败');
            return false;
        }
        return $this->formatTree($rowsMenu);
    }

    /**
     * 格式化树
     * 本方法中出现了三次的重复循环，估计要挨骂了，口下留情啊，赶时间，没空想太多
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
        //$arrId2Key = array();
        $moduleIds = array();//模块ID
        $controllerIds = array();//控制器ID
        //过滤获取一级菜单
        foreach($rows as $row){
            $tmpId = $row['menu_id'];
            $tmpFatherId = $row['menu_fatherId'];
            //$arrId2Key[$tmpId] = $tmpKey;
            if(empty($tmpFatherId)){
                $moduleIds[] = $tmpId;
                if(empty($tree[$tmpId])){//如果不存在就直接初始化
                    $tree[$tmpId] = $row;
                }
            }
        }        
        //过滤获取二级菜单
        foreach($rows as $row){
            $tmpId = $row['menu_id'];
            $tmpFatherId = $row['menu_fatherId'];
            //$tmpKey = $row['menu_key'];
            if((!empty($tmpFatherId))&&(in_array($tmpFatherId,$moduleIds))){
                $tree[$tmpFatherId]['son'][$tmpId] = $row;
                $controllerIds[$tmpFatherId][] = $tmpId;
            }
        }
        //过滤三级菜单
        foreach($controllerIds as $moduleId => $valueControllerIds){
            foreach($rows as $row){
            	$tmpId = $row['menu_id'];
                $tmpFatherId = $row['menu_fatherId'];
                //$tmpKey = $row['menu_key'];
                if((!empty($tmpFatherId))&&(in_array($tmpFatherId,$valueControllerIds))){
                    $tree[$moduleId]['son'][$tmpFatherId]['son'][$tmpId] = $row;
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
        if(empty($id)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        $modelManagerMenu = new Diana_Model_ManagerMenu();
        if(!$rows = $modelManagerMenu->getRowsById(null,$id)){
            $this->setMsgs('无效的参数');
            return false;
        }
        $row = $rows[0];
        if(!empty($row['menu_fatherId'])){
            if($rowsFather = $modelManagerMenu->getRowsById(null,$row['menu_fatherId'])){
                $row['menu_father'] = $rowsFather[0];
            }
            if(!empty($row['menu_father']['menu_fatherId'])){
                if($rowsGrandfather = $modelManagerMenu->getRowsById(null,$row['menu_father']['menu_fatherId'])){
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
        $modelManagerMenu = new Diana_Model_ManagerMenu();
        if(!$rows = $modelManagerMenu->getRowsById(null,$id)){
            return false;
        }
        $row = $rows[0];
        if(!empty($row['menu_fatherId'])){
            if($rowsFather = $modelManagerMenu->getRowsById(null,$row['menu_fatherId'])){
                $row['menu_father'] = $rowsFather[0];
            }
            if(!empty($row['menu_father']['menu_fatherId'])){
                if($rowsGrandfather = $modelManagerMenu->getRowsById(null,$row['menu_father']['menu_fatherId'])){
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
        $modelManagerMenu = new Diana_Model_ManagerMenu();
        if(!$rows = $modelManagerMenu->getRowsByFather(null,$fatherId)){
            return false;
        }
        return $rows;
    }

}