<?php
/**
 * API
 * User: sampan
 * Date: 14-3-3
 * Time: 下午11:48
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_Api extends Diana_Service_Abstract
{

    function __construct()
    {

    }

    /**
     * @param $user
     */
    function getPrivateKey($user)
    {

    }

    /**
     * 生成本地公钥
     * @param $api API接口
     * @param $user 用户名
     * @param $privateKey 私钥
     * @param $time 服务器时间
     */
    function makePublicKeyOfLocal($api,$user,$privateKey,$remoteTime)
    {
        return md5($api,$user,$privateKey,$remoteTime);
    }

}