<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\View;
use think\Db;

class Login extends Controller {

    public function login() {
        $request = Request::instance();
    	if ($request->isPost()) {
            $admin_name = $request->post('admin_name');
            $password = $request->post('password');
    		$captcha_code = $request->post('captcha_code');
    		if (empty($admin_name)) {
                $this->ajaxOutput('用户名不能为空');
    		}
    		if (empty($password)) {
                $this->ajaxOutput('密码不能为空');
    		}
    		if (empty($captcha_code)) {
                $this->ajaxOutput('验证码不能为空');
    		}
            // 验证码验证
            if (!captcha_check($captcha_code)) {
                $this->ajaxOutput('验证码错误');
            }
    		$userInfo = Db::table('admin_user')->where([
                'admin_name' => $admin_name,
                'password'  => md5($password),
    			'is_delete' => 0,
    		])->find();
            if (empty($userInfo)) {
                $this->ajaxOutput('用户名或密码错误');
            }
            session('admin_name', $userInfo['admin_name']);
            $this->ajaxOutput('登录成功', 1, url('Index/index'));
    	} else {
            $view = View::instance();
    		return $view->fetch();
    	}
    }

    /**
	 * 退出登录
	*/
    public function logout() {
        session('admin_name', null);
    	$this->redirect('Login/login');
    }

    private function ajaxOutput($msg, $code=0,  $url='', $data=[]) {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'url'  => $url,
            'data' => $data,
        ];
        exit(json_encode($result));
    }
}