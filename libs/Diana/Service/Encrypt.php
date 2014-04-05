<?php
/**
 * 签名类
 *
 */
class Diana_Service_Encrypt extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 生成加密码
     * 不能为空值，不能小于6位
     * 全都会转换为小写
     *
     * @param string $str 明文
     * @return string
     */
    function makePwd($str)
    {
        if ((empty($str))||(!is_scalar($str))) {
            $this->setMsgs('密码不能为空，且必须为标量');
            return false;
        }
        if (strlen($str) <= 5) {
            $this->setMsgs('密码长度必须大于6位');
            return false;
        }
        return md5($str);
    }

    /**
     * 生成签名
     *
     * @param string $pub 生成签名
     */
    function makeSign($pub)
    {
        if (empty($pub)) {$this->setMsgs('参数不能为空');	return false;}
        if (!is_scalar($pub)) {$this->setMsgs('参数类型错误');return false;}
        $str = $pub.DIANA_JUMPER_PRIVATEKEY;
        return $this->sampanMd5($str);
    }

    /**
     * 自定义加密方式
     *
     * @param string $str
     * @return string
     */
    function sampanMd5($str)
    {
        if ((empty($str))||(!is_scalar($str))) {
            $this->setMsgs('要被加密码的字符串不能为空，且必须为标量');
            return false;
        }
        if (!$this->checkSpace($str)) {
            return false;
        }
        if (!$arr = array_reverse(str_split($str,2))) {
            return false;
        }
        $str = implode('',$arr);
        return md5(strtolower($str));
    }

    /**
     * 不允许包含空格
     *
     * @param string $str
     */
    function checkSpace($str)
    {
        $arr = str_split($str);
        foreach ($arr as $v){
            $v = trim($v);
            if ($v == null || $v == '') {
                $this->setMsgs('要被加密码的字符串中不允许包含空格');
                return false;
            }
        }
        return true;
    }
}