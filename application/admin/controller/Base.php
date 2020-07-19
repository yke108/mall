<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;

class Base extends Controller {

    public function _initialize() {
        $request = Request::instance();
        $is_ajax = $request->isAjax();
        $admin_name = session('admin_name');
        if (empty($admin_name)) {
            if ($is_ajax) {
                $this->ajaxOutput('请登录', 0, url('Login/login'));
            } else {
                echo "<script>window.parent.location.href='".url('Login/login')."'</script>";exit;
            }
        }
        $this->assign('admin_name', $admin_name);
        $adminInfo = Db::table('admin_user')->where(['admin_name'=> $admin_name, 'is_delete'=>0])->find();
        if (empty($adminInfo)) {
            if ($is_ajax) {
                $this->ajaxOutput('帐号不存在', 0, url('Login/login'));
            } else {
                $this->redirect('Login/login');
            }
        }
        $this->adminInfo = $adminInfo;
        if (!$this->checkAuth()) {
            if ($is_ajax) {
                $this->ajaxOutput('权限不够，请联系相关管理员');
            } else {
                echo '权限不够，请联系相关管理员';exit;
            }
        }
    }

    // 输出
    protected function ajaxOutput($msg, $code=0,  $url='', $data=[]) {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'url'  => $url,
            'data' => $data,
        ];
        exit(json_encode($result));
    }

    // 管理员信息
    protected $adminInfo = [];

    // 权限列表
    protected $auth = [
        // 管理设置
        101 => ['name'=>'系统设置','list'=>['Manage-system']],
        102 => ['name'=>'充值提现','list'=>['Manage-cash']],
        103 => ['name'=>'系统账号','list'=>['Manage-user','Manage-adduser','Manage-edituser','Manage-deluser','Manage-user']],
        104 => ['name'=>'公告管理','list'=>['Manage-notice','Manage-changenoticestatus','Manage-addnotice','Manage-editnotice','Manage-delnotice']],
        105 => ['name'=>'第三方支付','list'=>['Manage-threepay']],
        106 => ['name'=>'银行卡管理','list'=>['Manage-bank','Manage-changebankstatus','Manage-addbank','Manage-editbank','Manage-delbank']],
        107 => ['name'=>'操作日志','list'=>['Manage-actlog']],
        // 用户管理
        201 => ['name'=>'代理列表','list'=>['User-agent','User-freezeagent','User-unfreezeagent','User-addagent','User-editagent','User-adminWastebook','User-agentRank']],
        202 => ['name'=>'会员列表','list'=>['User-user','User-freezeuser','User-unfreezeuser','User-adduser','User-edituser','User-userwastebook']],
        203 => ['name'=>'登录日志','list'=>['User-userlog']],
        // 游戏管理
        301 => ['name'=>'房间管理','list'=>['Game-room','Game-onlineuser','Game-editroom']],
        302 => ['name'=>'赛车开奖','list'=>['Game-pk10','Game-betlog']],
        303 => ['name'=>'时时彩开奖','list'=>['Game-ssc','Game-betlog']],
        304 => ['name'=>'快艇开奖','list'=>['Game-xyft','Game-betlog']],
        305 => ['name'=>'实时注单','list'=>['Game-nowbetdetail']],
        306 => ['name'=>'报表查询','list'=>['Game-reportform','Game-reportsheet']],
        // 充值提现
        401 => ['name'=>'充值管理','list'=>['Pay-recharge','Pay-syncrecharge','Pay-bankinfo','Pay-lockoperateuser']],
        402 => ['name'=>'提现管理','list'=>['Pay-draw','Pay-changedrawstatus','Pay-checkdraw']],
    ];

    // 模块列表
    protected $module = [
        ['name'=>'管理设置','controller'=>'Manage','list'=>[101,102,103,104,105,106,107]],
        ['name'=>'用户管理','controller'=>'User','list'=>[201,202,203]],
        ['name'=>'游戏管理','controller'=>'Game','list'=>[301,302,303,304,305,306]],
        ['name'=>'充值提现','controller'=>'Pay','list'=>[401,402]],
    ];

    // 判断访问权限
    private function checkAuth() {
        $controller = Request::instance()->controller();
        $action = Request::instance()->action();
        if ($controller == 'Index') {
            return true;
        }
        if (empty($this->adminInfo['auth'])) {
            return false;
        }
        $auth_id = 0;
        foreach ($this->auth as $key => $value) {
            if (in_array($controller.'-'.$action, $value['list'])) {
                $auth_id = $key;
                break;
            }
        }
        if ($auth_id == 0) {
            return false;
        }
        if (!in_array($auth_id, explode(',', $this->adminInfo['auth']))) {
            return false;
        }
        return true;
    }

    // 添加管理员日志
    protected function addAtionLog($content) {
        if (request()->isPost()) {
            $controller = Request::instance()->controller();
            $action = Request::instance()->action();
            $param = json_encode($_POST, JSON_UNESCAPED_UNICODE);
            Db::table('admin_action_log')->insert([
                'user_name'=> $this->adminInfo['admin_name'],
                'controller'=> $controller,
                'action'=> $param,
                'param'=> $param,
                'content'=> $content,
                'add_time'=> time(),
            ]);
        }  
    }

    /**
     * @desc 管理后台分页
     * @param config_sign
     * @return bool
     */
    protected function setPage($count, $page_size=15, $field="page") {
        $page = input('get.'.$field, 1, 'intval');
        $page_count = ceil($count/$page_size);
        if ($page > $page_count) $page = $page_count;
        if ($page < 1) $page = 1;
        $offset = $page_size * ($page - 1);
        $limit = $offset.','.$page_size;
        return [
            'page'=> $page,
            'page_count'=> $page_count,
            'limit'=> $limit,
        ];
    }
}