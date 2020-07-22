<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\Db;
use think\Request;
use app\api\service\Weixin;

class User extends Base
{
    /*初始化*/
    public function _initialize() {
        parent::_initialize();

        $action = Request::instance()->action();
        if ($action != 'login' && empty($this->user_id)) {
            exit($this->formatApiData([], '请登录', 201));
        }
    }

	/*登录*/
    public function login() {
        $request = Request::instance();
        $code = $request->request('code');
        $nickname = $request->request('nickname');
        $avatar_url = $request->request('avatar_url');
        if (empty($code)) {
            exit($this->formatApiData([], 'code不能为空', 401));
        }
        if (empty($nickname)) {
            exit($this->formatApiData([], 'nickname不能为空', 401));
        }
        if (empty($avatar_url)) {
            exit($this->formatApiData([], '头像不能为空', 401));
        }

        $Weixin = new Weixin();
        $openid = $Weixin->getOpenId($code);$openid = 123456789;
        if (empty($openid)) {
            exit($this->formatApiData([], 'code已失效', 401));
        }

        $User = model('user');
        $user_id = $User->getUserId($openid);
        //注册
        if (empty($user_id)) {
            $addData = [
                'openid'=> $openid,
                'nickname'=> $nickname,
                'avatar_url'=> $avatar_url,
                'openid'=> $openid,
            ];
            $user_id = $User->addUser($addData);
        }
        session('user_id', $user_id);

        return $this->formatApiData();
    }

    /*退出登录*/
    public function logout() {
        session('user_id', null);
        return $this->formatApiData();
    }

    /*加入收藏*/
    public function addWish() {
        $product_id = request()->request('product_id', 0, 'intval');
        if ($product_id < 1) {
            exit($this->formatApiData([], '商品ID不能为空', 401));
        }

        $productInfo = model('product')->getProductInfo($product_id, 'product_id,sale_price');
        if (empty($productInfo)) {
            exit($this->formatApiData([], '商品不存在', 401));
        }

        $where = ['user_id'=> $this->user_id, 'product_id'=> $product_id];
        $is_exist = Db::table('user_wish')->field('id')->where($where)->find();
        if (empty($is_exist)) {
            $data = [
                'user_id'=> $this->user_id,
                'product_id'=> $product_id,
                'sale_price'=> $productInfo['sale_price'],
                'add_time'=> time(),
            ];
            Db::table('user_wish')->insert($data);
        }

        return $this->formatApiData();
    }
}
