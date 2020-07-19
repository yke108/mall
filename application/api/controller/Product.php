<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\Db;
use think\Request;
use app\api\model\Product as modelProduct;

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

        $modelProduct = new modelProduct();
        $result = $modelProduct->getProductDetail($product_id);

        if (empty($result)) {
            return $this->formatApiData([], '商品不存在', 401);
        }

        return $this->formatApiData($result);

    }
}
