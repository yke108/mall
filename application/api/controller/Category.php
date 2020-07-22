<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\Db;
use think\Request;

class Category extends Base
{
	/*商品详情*/
    public function catList()
    {
        $request = Request::instance();
        $pid = $request->request('pid', 0, 'intval');
        $result = Db('category')
            ->field('cat_id,cat_name')
            ->where(['pid'=> $pid, 'is_delete'=> 0])
            ->order('sort asc')
            ->select();

        return $this->formatApiData($result);

    }

    /*商品详情*/
    public function productList()
    {
        $request = Request::instance();
        $page = $request->request('page', 1, 'intval');
        $size = $request->request('size', 10, 'intval');
        $page = $page > 0 ? $page : 1;
        $size = $size > 0 ? $size : 1;
        $cat_id = $request->request('cat_id', 0, 'intval');
        if ($cat_id < 1) {
            return $this->formatApiData([], '参数不合法', 401);
        }

        $ret = model('product')->categoryProductList($cat_id, $page, $size);

        $result = [
            'list'=> $ret['list'],
            'page'=> $page,
            'size'=> $size,
            'total'=> ceil($ret['count']/$size),
        ];

        return $this->formatApiData($result);

    }
}
