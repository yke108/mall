<?php

namespace app\api\service;

class Weixin {

    private $appid = 'APPID';
    
    private $appsecret = 'APPSECRET';

    /*获取openid*/
    public function getOpenid($code=null) {	
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->appsecret}&js_code={$code}&grant_type=authorization_code";
        $ret = curlRequst($url, [], 'GET', 3);

        $openid = isset($ret['openid']) ?: '';
        return $openid;
    }

}