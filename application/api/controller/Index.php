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
	    			'banner_image'=> 'https://cdn.it120.cc/apifactory/2019/04/09/cfee29650d6ae58a4bb1f84a3d899450.png',
	    			'banner_url'=> 'https://baidu.com',
	    		],[
	    			'banner_image'=> 'https://cdn.it120.cc/apifactory/2019/04/09/6b3136cda73c99453ac93a1c5a9deebf.png',
	    			'banner_url'=> 'https://baidu.com',
	    		],[
	    			'banner_image'=> 'https://cdn.it120.cc/apifactory/2019/04/09/cdb16ac9c66bc211b82bd947452526f4.png',
	    			'banner_url'=> 'https://baidu.com',
	    		],
    		];
    	$result['topBanner'] = $topBanner;

    	//中间部分内容
    	$cat_list = Db::table('category')->field('cat_id,cat_name,cat_image')->where(['type'=> 1, 'is_delete'=>0])->order('sort asc')->select();
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
    				'banner_image'=> 'https://cdn.it120.cc/apifactory/2019/04/09/6b3136cda73c99453ac93a1c5a9deebf.png',
	    			'banner_url'=> 'https://baidu.com',
    			],
    		],
    		[
    			'type'=> 4,
    			'title'=> '爆品推荐',
    			'data'=> [
    				[
    					'product_id'=>1,
    					'product_name'=>'WiFi云标签机',
    					'product_image'=>'https://cdn.it120.cc/apifactory/2019/04/09/6b3136cda73c99453ac93a1c5a9deebf.png',
    					'product_price'=>700,
    					'sale_price'=>600,
    				],[
    					'product_id'=>1,
    					'product_name'=>'打钱机',
    					'product_image'=>'https://cdn.it120.cc/apifactory/2019/04/09/6b3136cda73c99453ac93a1c5a9deebf.png',
    					'product_price'=>800,
    					'sale_price'=>500,
    				],
    			],
    		],
    	];
    	
        return $this->formatApiData($result);
    }

    public function recommend() {
    	$request = Request::instance();
    	$page = $request->post('page', 1, 'intval');
        $page_size = $request->post('page_size', 10, 'intval');
    	$page = $page > 0 ? $page : 1;
    	$page_size = $page_size > 0 ? $page_size : 1;

    	$list = [
    		[
				'product_id'=>1,
				'product_name'=>'WiFi云标签机',
				'product_image'=>'https://cdn.it120.cc/apifactory/2019/04/09/6b3136cda73c99453ac93a1c5a9deebf.png',
				'product_price'=>700,
				'sale_price'=>600,
			],[
				'product_id'=>1001,
				'product_name'=>'打钱机',
				'product_image'=>'https://cdn.it120.cc/apifactory/2019/04/09/6b3136cda73c99453ac93a1c5a9deebf.png',
				'product_price'=>800,
				'sale_price'=>500,
			],
			[
				'product_id'=>1,
				'product_name'=>'WiFi云标签机',
				'product_image'=>'https://cdn.it120.cc/apifactory/2019/04/09/6b3136cda73c99453ac93a1c5a9deebf.png',
				'product_price'=>700,
				'sale_price'=>600,
			],[
				'product_id'=>1001,
				'product_name'=>'打钱机',
				'product_image'=>'https://cdn.it120.cc/apifactory/2019/04/09/6b3136cda73c99453ac93a1c5a9deebf.png',
				'product_price'=>800,
				'sale_price'=>500,
			],
			[
				'product_id'=>1,
				'product_name'=>'WiFi云标签机',
				'product_image'=>'https://cdn.it120.cc/apifactory/2019/04/09/6b3136cda73c99453ac93a1c5a9deebf.png',
				'product_price'=>700,
				'sale_price'=>600,
			],[
				'product_id'=>1,
				'product_name'=>'打钱机',
				'product_image'=>'https://cdn.it120.cc/apifactory/2019/04/09/6b3136cda73c99453ac93a1c5a9deebf.png',
				'product_price'=>800,
				'sale_price'=>500,
			],
    	];

    	$result = [
    		'list'=> $list,
    		'page'=> $page,
    		'page_size'=> $page_size,
    		'page_total'=> 8,
    	];

    	return $this->formatApiData($result);
    }
}
