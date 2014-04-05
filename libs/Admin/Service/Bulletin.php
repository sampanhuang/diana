<?php
/**
 * 输出验证码
 *
 */
class Admin_Service_Bulletin extends Admin_Service_Abstract
{
    var $captchaObj;
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 公告删除
     * @param $input
     * @return bool|int
     */
    function delete($input)
    {
        $bulletinId = $input['bulletin_id'];
        if(empty($bulletinId)){
            $this->setMsgs('缺少参数 - bulletin_id');
            return false;
        }
        $condition = array('bulletin_id' => $bulletinId);
        $modelBulletin = new Diana_Model_Bulletin();
        if(!$rowsAffectedOfBulletin = $modelBulletin->delData($condition)){
            $this->setMsgs('删除失败-1');
            return false;
        }
        $modelBulletinContent = new Diana_Model_BulletinContent();
        if(!$rowsAffectedOfBulletinContent = $modelBulletinContent->delData($condition)){
            $this->setMsgs('删除失败-2');
            return false;
        }
        $this->setMsgs('成功删除'.$rowsAffectedOfBulletin.'条纪录');
        return $rowsAffectedOfBulletin;
    }

    /**
     * 锁定
     * @param $input
     * @return array|bool
     */
    function lock($input)
    {
        $bulletinId = $input['bulletin_id'];
        $lockTime = $input['lock_time'];
        if(empty($bulletinId)||empty($lockTime)){
            $this->setMsgs('缺少参数 - bulletin_id - lock_time');
            return false;
        }
        $data = array('bulletin_lock_time' => strtotime($lockTime));
        $condition = array('bulletin_id' => $bulletinId);
        $modelBulletin = new Diana_Model_Bulletin();
        if(!$rows = $modelBulletin->saveData(2,$data,$condition)){
            $this->setMsgs('更新失败');
            return false;
        }
        $this->setMsgs('成功更新'.count($rows).'条纪录');
        return $rows;
    }

    /**
     * 解锁
     * @param $input
     * @return array|bool
     */
    function unlock($input)
    {
        $bulletinId = $input['bulletin_id'];
        if(empty($bulletinId)){
            $this->setMsgs('缺少参数 - bulletin_id');
            return false;
        }
        $data = array('bulletin_lock_time' => (DIANA_TIMESTAMP_START - 10));
        $condition = array('bulletin_id' => $bulletinId);
        $modelBulletin = new Diana_Model_Bulletin();
        if(!$rows = $modelBulletin->saveData(2,$data,$condition)){
            $this->setMsgs('更新失败');
            return false;
        }
        $this->setMsgs('成功更新'.count($rows).'条纪录');
        return $rows;
    }

    /**
     * 插入数据
     * @param $input
     * @return array|bool
     */
    function insert($input)
    {
        if(!empty($input['bulletin_access'])){
            $input['bulletin_access'] = array_sum($input['bulletin_access']);
        }
        if(!$input = $this->filterInput($input)){
            $this->setMsgs('格式错误');
            return false;
        }
        $inputOther = array(
            'bulletin_insert_time' => time(),
            'bulletin_insert_manId' => $this->sessionManager['id'],
            'bulletin_insert_manName' => $this->sessionManager['name'],
            'bulletin_insert_manEmail' => $this->sessionManager['email'],
            'bulletin_insert_ip' => $_SERVER['REMOTE_ADDR'],
            'bulletin_update_time' => time(),
            'bulletin_update_manId' => $this->sessionManager['id'],
            'bulletin_update_manName' => $this->sessionManager['name'],
            'bulletin_update_manEmail' => $this->sessionManager['email'],
            'bulletin_update_ip' => $_SERVER['REMOTE_ADDR'],
        );
        $data = array_merge($input,$inputOther);

        unset($data['bulletin_content']);
        $modelBulletinChannel = new Diana_Model_Bulletin();
        if(!$rows = $modelBulletinChannel->saveData(1,$data)){
            $this->setMsgs('数据写入失败');
            return false;
        }
        $bulletinId = $rows[0]['bulletin_id'];
        $dataContent = array(
            'bulletin_id' => $bulletinId,
            'bulletin_time' => time(),
            'bulletin_content' => $input['bulletin_content'],
        );
        $modelBulletinContent = new Diana_Model_BulletinContent();
        if(!$rowsContent = $modelBulletinContent->saveData(1,$dataContent)){
            $this->setMsgs('公告内容保存失败');
            return false;
        }
        return $this->getDetailById($bulletinId);
    }

    /**
     * 更新数据
     * @param $input
     * @param $id
     * @return array|bool
     */
    function update($input)
    {
        if(!empty($input['bulletin_access'])){
            $input['bulletin_access'] = array_sum($input['bulletin_access']);
        }
        $bulletinId = $input['bulletin_id'];
        if(empty($bulletinId)&&(!is_numeric($bulletinId))){
            $this->setMsgs('错误的ID');
            return false;
        }
        if(!$input = $this->filterInput($input)){
            return false;
        }
        $inputOther = array(
            'bulletin_update_time' => time(),
            'bulletin_update_manId' => $this->sessionManager['id'],
            'bulletin_update_manName' => $this->sessionManager['name'],
            'bulletin_update_manEmail' => $this->sessionManager['email'],
            'bulletin_update_ip' => $_SERVER['REMOTE_ADDR'],
        );
        $data = array_merge($input,$inputOther);
        $condition = array('bulletin_id' => $bulletinId);
        unset($data['bulletin_content']);
        $modelBulletinChannel = new Diana_Model_Bulletin();
        if(!$rows = $modelBulletinChannel->saveData(2,$data,$condition)){
            $this->setMsgs('数据写入失败');
            return false;
        }
        $dataContent = array(
            'bulletin_time' => time(),
            'bulletin_content' => $input['bulletin_content'],
        );
        $modelBulletinContent = new Diana_Model_BulletinContent();
        if(!$rowsContent = $modelBulletinContent->saveData(2,$dataContent,$condition)){
            $this->setMsgs('公告内容保存失败');
            return false;
        }
        return $this->getDetailById($bulletinId);
    }


    /**
     * 过滤公告提交内容
     * @param $data
     * @return mixed
     */
    function filterInput($data)
    {
        $filters = array(
            'bulletin_channelId' => array(new Zend_Filter_Int(),new Zend_Filter_Digits(),new Zend_Filter_StringTrim()),
            'bulletin_access' => array(new Zend_Filter_Int(),new Zend_Filter_Digits(),new Zend_Filter_StringTrim()),
            'bulletin_title' => array(new Zend_Filter_StripNewlines(),new Zend_Filter_HtmlEntities(),new Zend_Filter_StringTrim()),
            'bulletin_author' => array(new Zend_Filter_StripNewlines(),new Zend_Filter_HtmlEntities(),new Zend_Filter_StringTrim()),
            'bulletin_content' => array(new Zend_Filter_StripNewlines(),new Zend_Filter_StringTrim()),
        );
        $validators = array(
            'bulletin_channelId' => array(new Zend_Validate_Int()),
            'bulletin_access' => array(new Zend_Validate_Int()),
            'bulletin_title' => array(new Zend_Validate_StringLength(array('max' => '128','encoding' => 'utf-8'))),
            'bulletin_author' => array(new Zend_Validate_StringLength(array('max' => '32','encoding' => 'utf-8'))),
            'bulletin_content' => array(new Zend_Validate_StringLength(array('max' => '65535','encoding' => 'utf-8'))),
        );
        $input = new Zend_Filter_Input($filters, $validators, $data);
        if (!$input->isValid()) {
            $messageInput = $input->getMessages();
            foreach($messageInput as $valMsg){
                $this->setMsgs($valMsg);
            }
            return false;
        }
        return $input->getEscaped();
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
     * 获取分页
     *
     * @param int $page 当前页
     * @param int $pagesize 每页纪录数
     * @param array $condition 查询条件
     * @param array $order 排序方式
     * @return array
     */
    function pageByCondition($page = 1,$pagesize = 1,$condition = array(),$order = null)
    {
        $offset = ($page - 1)*$pagesize;
        if($offset < 0){$offset = 0;}
        //获取频道分类
        $modelBulletinChannel = new Diana_Model_BulletinChannel();
        if($rowsBulletinChannel = $modelBulletinChannel->getRowsByCondition()){
            $tmpBulletinChannelLabel = array();
            $tmpBulletinChannelFather = array();
            foreach($rowsBulletinChannel as $rowBulletinChannel){
                $tmpChannelId = $rowBulletinChannel['channel_id'];
                $tmpChannelFatherId = $rowBulletinChannel['channel_fatherId'];
                $tmpChannelLabel = $rowBulletinChannel['channel_label_'.DIANA_TRANSLATE_CURRENT];
                $tmpBulletinChannelLabel[$tmpChannelId] = $tmpChannelLabel;
                $tmpBulletinChannelFather[$tmpChannelId] = $tmpChannelFatherId;
            }
        }
        $modelBulletin = new Diana_Model_Bulletin();
        if($countBulletin = $modelBulletin->getCountByCondition(null,$condition)){
            $rowsBulletin = $modelBulletin->getRowsByCondition(null,$condition,$order,$pagesize,$offset);
            //获取频道
            foreach($rowsBulletin as &$rowBulletin){
                //锁定状态
                $rowBulletin['bulletin_lock_stat'] = 1;
                if($rowBulletin['bulletin_lock_time'] > DIANA_TIMESTAMP_START){
                    $rowBulletin['bulletin_lock_stat'] = 2;
                }
                //4是www公告,2是client公告,1是admin公告
                $tmpBulletinAccess = array();
                if(in_array($rowBulletin['bulletin_access'],array(1,3,5,7))){
                    $tmpBulletinAccess[] = 'ADMIN';
                }
                if(in_array($rowBulletin['bulletin_access'],array(2,3,6,7))){
                    $tmpBulletinAccess[] = 'CLIENT';
                }
                if(in_array($rowBulletin['bulletin_access'],array(4,5,6,7))){
                    $tmpBulletinAccess[] = 'WWW';
                }
                $rowBulletin['bulletin_accessDomain'] = implode(',',$tmpBulletinAccess);

                //定义频道
                if((!empty($tmpBulletinChannelLabel))&&(!empty($tmpBulletinChannelFather))){
                    $tmpBulletinChannelId = $rowBulletin['bulletin_channelId'];
                    $rowBulletin['bulletin_channelLabel'] = $tmpBulletinChannelLabel[$tmpBulletinChannelId];
                    $rowBulletin['bulletin_channelFatherId'] = $bulletinChannelFatherId = $tmpBulletinChannelFather[$tmpBulletinChannelId];
                    $rowBulletin['bulletin_channelFatherLabel'] = $tmpBulletinChannelLabel[$bulletinChannelFatherId];
                }
            }

        }
        return array('total' => intval($countBulletin),'rows' => $rowsBulletin);
    }

    function getDetail($column,$key)
    {
        if ((empty($column))||(!is_scalar($column))) {
            $this->setMsgs("Invalid Param - 无效的column值");
            return false;
        }
        if ((empty($key))||(!is_scalar($key))) {
            $this->setMsgs("Invalid Param - 无效的key值");
            return false;
        }
        if($column == 'bulletin_id'){
            $detail = $this->getDetailById($key);
        }else{
            $this->setMsgs("Invalid Param - Column ".$column);
            return false;
        }
        return $detail;
    }

    /**
     * 获取详细的公告资料
     *
     * @param unknown_type $id
     */
    function getDetailById($bulletinId)
    {
        $modelBulletin = new Diana_Model_Bulletin();
        if(!$rowBulletin = $modelBulletin->getRowById(null,$bulletinId)){
            $this->setMsgs('错误的ID');
            return false;
        }
        $bulletinChannelId = $rowBulletin['bulletin_channelId'];
        //获取正文内容
        $modelBulletinContent = new Diana_Model_BulletinContent();
        if($rowBulletinContent = $modelBulletinContent->getRowById(null,$bulletinId)){
            $rowBulletin = array_merge($rowBulletin,$rowBulletinContent);
        }
        //获取频道
        $modelBulletinChannel = new Diana_Model_BulletinChannel();
        if($rowsBulletinChannel = $modelBulletinChannel->getRowsByCondition()){
            $tmpBulletinChannelLabel = array();
            $tmpBulletinChannelFather = array();
            foreach($rowsBulletinChannel as $rowBulletinChannel){
                $tmpChannelId = $rowBulletinChannel['channel_id'];
                $tmpChannelFatherId = $rowBulletinChannel['channel_fatherId'];
                $tmpChannelLabel = $rowBulletinChannel['channel_label_'.DIANA_TRANSLATE_CURRENT];
                $tmpBulletinChannelLabel[$tmpChannelId] = $tmpChannelLabel;
                $tmpBulletinChannelFather[$tmpChannelId] = $tmpChannelFatherId;
            }
            $rowBulletin['bulletin_channelLabel'] = $tmpBulletinChannelLabel[$bulletinChannelId];
            $rowBulletin['bulletin_channelFatherId'] = $bulletinChannelFatherId = $tmpBulletinChannelFather[$bulletinChannelId];
            $rowBulletin['bulletin_channelFatherLabel'] = $tmpBulletinChannelLabel[$bulletinChannelFatherId];
        }
        return $rowBulletin;
    }


    /**
     * 过滤
     * @param $input
     * @return bool
     */
    function filterRequestQuery($input)
    {
        if(empty($input)){
            return false;
        }
        if(!empty($input['bulletin_insert_date_min'])){
            $input['bulletin_insert_date_min'] = strtotime($input['bulletin_insert_date_min']);
            unset($input['bulletin_insert_date_min']);
        }
        if(!empty($input['bulletin_insert_date_max'])){
            $input['bulletin_insert_date_max'] = strtotime($input['bulletin_insert_date_max']);
            unset($input['bulletin_insert_date_max']);
        }
        return $input;
    }

    /**
     * 获取查询过滤字段
     * @return array
     */
    function getFilterColumnsForQuery()
    {
        return array(
            'bulletin_id',
            'bulletin_channelId',
            'bulletin_access',
            'bulletin_click_min',
            'bulletin_click_max',
            'bulletin_top',
            'bulletin_title_like',
            'bulletin_author',
            'bulletin_insert_time_min',
            'bulletin_insert_time_max',
            'bulletin_update_time_min',
            'bulletin_update_time_max',
        );
    }

    /**
     * 获取排序字段
     * @return array 排序字段
     */
    function getFilterColumnsForOrder()
    {
        return array(
            'bulletin_top',
            'bulletin_insert_time',
            'bulletin_update_time',
            'bulletin_click',
        );
    }

}