<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\Db;
use think\Request;

class Cart extends Base
{
    /*加入购物车*/
    public function addCart() {
        $request = request();
        $product_id = $request->request('product_id', 0, 'intval');
        $poa_id = $request->request('poa_id', 0, 'intval');
        $quantity = $request->request('quantity', 0, 'intval');
        if ($product_id < 1 || $poa_id < 1 || $quantity < 1) {
            exit($this->formatApiData([], '参数错误', 401));
        }
        $result = [
            'cart_number'=> $quantity
        ];
        return $this->formatApiData($result);
    }
}
