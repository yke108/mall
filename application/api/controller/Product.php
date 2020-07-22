<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\Db;
use think\Request;

class Product extends Base
{
	/*商品详情*/
    public function index()
    {
        $request = Request::instance();
        $product_id = $request->request('product_id', 0, 'intval');
        if ($product_id < 1) {
            return $this->formatApiData([], '参数不合法', 401);
        }

        $Product = model('product');
        $result = $Product->getProductDetail($product_id);

        if (empty($result)) {
            return $this->formatApiData([], '商品不存在', 401);
        }

        //是否加入收藏
        $is_wish =false;
        if ($this->user_id) {
            $ret = Db::table('user_wish')->field('id')->where(['user_id'=> $this->user_id, 'product_id'=> $product_id])->find();
            $is_wish = $ret ? true : false;
        }
        $result['is_wish'] = $is_wish;

        return $this->formatApiData($result);

    }
}
