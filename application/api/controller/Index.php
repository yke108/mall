<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\Db;

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
    	];
    	
        return $this->formatApiData($result);
    }
}
