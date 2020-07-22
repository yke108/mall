<?php

namespace app\api\service;

class Weixin {

    private $appid = 'wx51b5cd02d562f364';
    
    private $appsecret = '7d33015eda5d349f9d53644c3e7aaeb3';

    /*获取openid*/
    public function getOpenid($code=null) {	
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->appsecret}&js_code={$code}&grant_type=authorization_code";
        $ret = curlRequst($url, [], 'GET', 3);

        $openid = isset($ret['openid']) ?: '';
        return $openid;
    }

}