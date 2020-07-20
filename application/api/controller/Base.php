<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Db;

class Base extends Controller {
    
    /*初始化*/
    public function _initialize() {
        if (isset($_COOKIE['PHPSESSID'])) {
            session_id($_COOKIE['PHPSESSID']);
        }
        session_start();
    }

    /*输出*/
    protected function formatApiData($data=[], $msg='success', $code=200) {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];
        return json_encode($result);
    }
}