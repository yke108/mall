<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\View;

class Index extends Base {
    public function index(){
    	$user_auth = explode(',', $this->adminInfo['auth']);
    	$list = [];
    	foreach ($this->module as $key => $value) {
    		$temp_arr = [];
            foreach ($value['list'] as $k => $v) {
            	if (in_array($v, $user_auth)) {
            		$temp_arr[] = [
		                'id'	=> $v,
		                'name'	=> $this->auth[$v]['name'],
		                'url'	=> url(str_replace('-', '/', $this->auth[$v]['list'][0])),
	                ];
            	}
            }
            if (!empty($temp_arr)) {
            	$list[] = [
            		'name'=> $value['name'],
            		'list'=> $temp_arr,
            	];
            }
        }
        // $rCount = M('recharge')->where("type=1 and sync=0 and (mm_name='' or mm_name='{$this->adminInfo['user_name']}')")->count();
        // $dCount = M('draw_cash')->where(['sync'=>0])->count();
        // $this->assign('rCount', $rCount);
        // $this->assign('dCount', $dCount);
        $view = View::instance();
    	$view->assign('list', $list);
        return $view->fetch();
    }

    public function home() {

        $view = View::instance();

        $view->assign('agent_count', 200);
        $view->assign('user_count', 200);
        $view->assign('online_count', 200);
        $view->assign('recharge_balance', 200);
        $view->assign('agent_draw_balance', 200);
        $view->assign('user_draw_balance', 200);
        $view->assign('total_commission', 200);
        $view->assign('my_commission', 200);

        return $view->fetch();
    }
}