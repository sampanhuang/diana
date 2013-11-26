<?php
/**
 * 输出验证码
 *
 */
class Diana_Service_Captcha extends Diana_Service_Abstract 
{
	var $captchaObj;
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 更新数据
	 *
	 * @param int $id 流水号
	 * @param int $type 类型
	 * @param int $top 置顶级别
	 * @param string $title 标题
	 * @param string $author 作者
	 * @param string $content 内容
	 * @param string $man email
	 * @return array
	 */
	function update($id,$type,$top,$title,$author,$content,$man)
	{
		$condition = array('bulletin_id' => $id);
		//公告数据
		$dataBulletin = array(
			'bulletin_type' => $type,
			'bulletin_top' => $top,
			'bulletin_title' => $title,
			'bulletin_author' => $author,
			'bulletin_update_time' => time(),
			'bulletin_update_man' => $man,
			'bulletin_update_ip' => $_SERVER['REMOTE_ADDR'],
		);
		$modelBulletin = new Diana_Model_Bulletin();
		if(!$rowsBulletin = $modelBulletin->saveData(2,$dataBulletin,$condition)){
			$this->setMsgs('公告保存失败');
			return false;
		}
		$rowBulletin = $rowsBulletin;
		$dataBulletinContent = array(
			'bulletin_id' => $rowBulletin['bulletin_id'],
			'bulletin_time' => time(),
			'bulletin_content' => $content,
		);
		$modelBulletinContent = new Diana_Model_BulletinContent();
		if(!$rowsBulletinContent = $modelBulletinContent->saveData(2,$dataBulletinContent,$condition)){
			$this->setMsgs('公告内容保存失败');
			return false;
		}
		$rowBulletin = array_merge($rowBulletin,$rowsBulletinContent[0]);
		return $rowBulletin;
	}
	
	/**
	 * 插入数据
	 *
	 * @param string $title 标题
	 * @param string $author 作者
	 * @param string $type 类型
	 * @param string $content 内容
	 * @param string $man 操作人
	 */
	function insert($type,$top,$title,$author,$content,$man)
	{
		//公告数据
		$dataBulletin = array(
			'bulletin_type' => $type,
			'bulletin_top' => $top,
			'bulletin_title' => $title,
			'bulletin_author' => $author,
			'bulletin_insert_time' => time(),
			'bulletin_insert_man' => $man,
			'bulletin_insert_ip' => $_SERVER['REMOTE_ADDR'],
		);
		$modelBulletin = new Diana_Model_Bulletin();
		if(!$rowsBulletin = $modelBulletin->saveData(1,$dataBulletin)){
			$this->setMsgs('公告保存失败');
			return false;
		}
		$rowBulletin = $rowsBulletin;
		$dataBulletinContent = array(
			'bulletin_id' => $rowBulletin['bulletin_id'],
			'bulletin_time' => time(),
			'bulletin_content' => $content,
		);
		$modelBulletinContent = new Diana_Model_BulletinContent();
		if(!$rowsBulletinContent = $modelBulletinContent->saveData(1,$dataBulletinContent)){
			$this->setMsgs('公告内容保存失败');
			return false;
		}
		$rowBulletin = array_merge($rowBulletin,$rowsBulletinContent[0]);
		return $rowBulletin;
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
        $modelBulletin = new Diana_Model_Bulletin();
        if($countBulletin = $modelBulletin->getCountByCondition(null,$condition)){
            $rowsBulletin = $modelBulletin->getRowsByCondition(null,$condition,$order,$pagesize,$offset);
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
		if($rowBulletin['bulletin_type'] !== $type){
			$this->setMsgs('错误的应用场景');
			return false;
		}
		$modelBulletinContent = new Diana_Model_BulletinContent();
		if($rowBulletinContent = $modelBulletinContent->getRowById(null,$id)){
			$rowBulletin = array_merge($rowBulletin,$rowBulletinContent);
		}
		return $rowBulletin;
	}
	
}