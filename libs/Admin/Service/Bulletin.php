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
     * 插入数据
     * @param $input
     * @return array|bool
     */
    function insert($input)
    {
        if(!$input = $this->filterInput($input)){
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
    function update($input,$id)
    {
        if(empty($id)&&(!is_numeric($id))){
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
        $condition = array('bulletin_id' => $id);
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
        return $this->getDetailById($id);
    }


    /**
     * 过滤公告提交内容
     * @param $data
     * @return mixed
     */
    function filterInput($data)
    {
        $filters = array(
            'bulletin_channel' => array(new Zend_Filter_Int(),new Zend_Filter_Digits(),new Zend_Filter_StringTrim()),
            'bulletin_access' => array(new Zend_Filter_Int(),new Zend_Filter_Digits(),new Zend_Filter_StringTrim()),
            'bulletin_title' => array(new Zend_Filter_StripNewlines(),new Zend_Filter_HtmlEntities(),new Zend_Filter_StringTrim()),
            'bulletin_author' => array(new Zend_Filter_StripNewlines(),new Zend_Filter_HtmlEntities(),new Zend_Filter_StringTrim()),
            'bulletin_content' => array(new Zend_Filter_StripNewlines(),new Zend_Filter_HtmlEntities(),new Zend_Filter_StringTrim()),
        );
        $validators = array(
            'bulletin_channel' => array(new Zend_Validate_Int()),
            'bulletin_access' => array(new Zend_Validate_Int()),
            'bulletin_title' => array(new Zend_Validate_StringLength(array('max' => '64','encoding' => 'utf-8'))),
            'bulletin_author' => array(new Zend_Validate_StringLength(array('max' => '64','encoding' => 'utf-8'))),
            'bulletin_content' => array(new Zend_Validate_StringLength(array('max' => '512','encoding' => 'utf-8'))),
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
        $channelId = array();
        $modelBulletin = new Diana_Model_Bulletin();
        if($countBulletin = $modelBulletin->getCountByCondition(null,$condition)){
            $rowsBulletin = $modelBulletin->getRowsByCondition(null,$condition,$order,$pagesize,$offset);
            foreach($rowsBulletin as $rowBulletin){
                $channelId[] = $rowBulletin['bulletin_channel'];
            }
            $channelId = array_filter(array_unique($channelId));
            $modelBulletinChannel = new Diana_Model_BulletinChannel();
            if($rowsBulletinChannel = $modelBulletinChannel->getRowsById(null,$channelId)){
                foreach($rowsBulletin as &$rowBulletin){
                    foreach($rowsBulletinChannel as $rowBulletinChannel){
                        if($rowBulletin['bulletin_channel'] == $rowBulletinChannel['channel_id']){
                            $rowBulletin['bulletin_channelLabel'] = $rowBulletinChannel['channel_label'];
                        }
                    }
                }
            }
        }
        return array('total' => $countBulletin,'rows' => $rowsBulletin);
    }


    /**
     * 获取详细的公告资料
     *
     * @param unknown_type $id
     * @param int $type 应用场景，1前台，2后台
     */
    function getDetailById($id,$type = 1)
    {
        $modelBulletin = new Diana_Model_Bulletin();
        if(!$rowBulletin = $modelBulletin->getRowById(null,$id)){
            $this->setMsgs('错误的ID');
            return false;
        }
        if($rowBulletin['bulletin_access'] !== $type){
            $this->setMsgs('错误的应用场景');
            return false;
        }
        $modelBulletinContent = new Diana_Model_BulletinContent();
        if($rowBulletinContent = $modelBulletinContent->getRowById(null,$id)){
            $rowBulletin = array_merge($rowBulletin,$rowBulletinContent);
        }
        $modelBulletinChannel = new Diana_Model_BulletinChannel();
        if(!$rowBulletinChannel = $modelBulletinChannel->getRowsById($rowBulletin['bulletin_channel'])){
            $rowBulletin = array_merge($rowBulletin,$rowBulletinChannel);
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
            'bulletin_channel',
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