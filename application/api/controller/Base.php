<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Db;

class Base extends Controller {

    /*用户ID*/
    protected $user_id = null;
    
    /*初始化*/
    public function _initialize() {
        $this->user_id = session('user_id');
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