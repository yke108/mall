<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\Db;
use think\Request;

class Index extends Base
{
	/*首页*/
    public function index()
    {
    	$result = [];

    	//顶部banner
    	$topBanner = [
    			[
	    			'banner_image'=> 'https://dcdn.it120.cc/2019/12/29/8396f65d-d615-46d8-b2e5-aa41820b9fe5.png',
	    			'banner_url'=> 'https://baidu.com',
	    		],[
	    			'banner_image'=> 'https://dcdn.it120.cc/2019/12/29/daca65ee-4347-4792-a490-ccbac4b3c1d7.png',
	    			'banner_url'=> 'https://baidu.com',
	    		],[
	    			'banner_image'=> 'https://dcdn.it120.cc/2019/12/29/2e79921a-92b3-4d1d-8182-cb3d524be5fb.png',
	    			'banner_url'=> 'https://baidu.com',
	    		],
    		];
    	$result['topBanner'] = $topBanner;

    	//中间部分内容
    	//分类列表
    	$cat_list = Db::table('category')
    		->field('cat_id,cat_name,cat_image')
    		->where(['type'=> 1, 'is_delete'=>0])
    		->order('sort asc')
    		->select();
    	//爆品推荐
    	$hot_list = Db::table('product')
    		->field('product_id,product_name,product_image,product_price,sale_price')
    		->where(['product_status'=> 1])
    		->limit(2)
    		->select();
    	$result['list'] = [
    		[
    			'type'=> 1,
    			'title'=> '',
    			'data'=> [
    				'text'=> '商城新开张，优惠多多，戳戳戳我看详情。',
    				'more_url'=> 'https://baidu.com',
    			],
    		],
    		[
    			'type'=> 2,
    			'title'=> '',
    			'data'=> $cat_list,
    		],
    		[
    			'type'=> 3,
    			'title'=> '',
    			'data'=> [
    				'banner_image'=> 'https://dcdn.it120.cc/2019/12/29/2e79921a-92b3-4d1d-8182-cb3d524be5fb.png',
	    			'banner_url'=> 'https://baidu.com',
    			],
    		],
    		[
    			'type'=> 4,
    			'title'=> '爆品推荐',
    			'data'=> $hot_list,
    		],
    	];
    	
        return $this->formatApiData($result);
    }

    public function recommend() {
    	$request = Request::instance();
    	$page = $request->request('page', 1, 'intval');
        $size = $request->request('size', 10, 'intval');
    	$page = $page > 0 ? $page : 1;
    	$size = $size > 0 ? $size : 1;

    	$where = ['product_status'=> 1];
    	$list = Db::table('product')
    		->field('product_id,product_name,product_image,product_price,sale_price')
    		->where($where)
    		->page($page,$size)
    		->select();

    	$count = Db::table('product')->where($where)->count();

    	$result = [
    		'list'=> $list,
    		'page'=> $page,
    		'size'=> $size,
    		'total'=> ceil($count/$size),
    	];

    	return $this->formatApiData($result);
    }
}
