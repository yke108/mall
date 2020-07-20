<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\Db;
use think\Request;

class User extends Base
{
	/*商品详情*/
    public function setSession()
    {
        $request = Request::instance();
        $ret = $request->request();
        foreach ($ret as $key => $value) {
            session($key, $value);
        }
        return $this->formatApiData($ret);
    }

    /*商品详情*/
    public function getSession()
    {
        $ret = \think\Session::get();
        $ret['cookie'] = isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : '';
        return $this->formatApiData($ret);
    }
}
