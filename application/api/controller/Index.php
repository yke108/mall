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
    			],
    		],
    		[
    			'type'=> 2,
    			'title'=> '',
    			'data'=> [
    				[
    					'title'=> '商城新开张，优惠多多，戳戳戳我看详情。',
	    				'content'=> '<p>尊敬的客户:</p><p>商城新开张，快来砸单哦！</p><p>&nbsp;&nbsp;&nbsp;&nbsp;1、单件包邮（秒杀）</p><p>&nbsp;&nbsp;&nbsp;&nbsp;2、每天发布6件秒杀商品</p><p>&nbsp;&nbsp;&nbsp;&nbsp;3、满返活动</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A：满100元返10元券；</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B：满200元返25元券；</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C：满300元返40元券；</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;消费累计满500元，返80元券哦！</p>',
	    				'add_time'=> '2020-07-21 09:00:00',
	    			],[
    					'title'=> '分享送积分',
	    				'content'=> '<p>成功分享一次送10积分</p>',
	    				'add_time'=> '2020-07-19 09:00:00',
	    			],
    			],
    		],
    		[
    			'type'=> 3,
    			'title'=> '',
    			'data'=> $cat_list,
    		],
    		[
    			'type'=> 4,
    			'title'=> '',
    			'data'=> [
    				'banner_image'=> 'https://dcdn.it120.cc/2019/12/29/2e79921a-92b3-4d1d-8182-cb3d524be5fb.png',
	    			'banner_url'=> 'https://baidu.com',
    			],
    		],
    		[
    			'type'=> 5,
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
