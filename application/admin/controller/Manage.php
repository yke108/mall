<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\View;
use think\Request;
use think\Db;

/**
* 管理设置
*/
class Manage extends Base {
	/**
	 * 系统设置
	 */
    public function system() {
        $request = Request::instance();
        if ($request->isPost()) {
            $system_maintenance = input('post.system_maintenance', 0, 'intval');
            $announcement = input('post.announcement', '', 'htmlspecialchars,trim');
            $announcement_url = input('post.announcement_url', '', 'htmlspecialchars,trim');
            $cs_qq = input('post.cs_qq', '', 'htmlspecialchars,trim');
            $cs_wx = input('post.cs_wx', '', 'htmlspecialchars,trim');
            $rate = input('post.rate', '', 'htmlspecialchars,floatval');
            if (!in_array($system_maintenance, [0,1]) || empty($announcement) || empty($announcement_url) || empty($cs_qq) || empty($cs_wx) || $rate >= 1 || $rate <= 0) {
                $this->ajaxOutput('参数错误');
            }

            Db::table('config')->where(['config_sign'=>'rate'])->update(['config_value'=> $rate]);
            Db::table('config')->where(['config_sign'=>'system_maintenance'])->update(['config_value'=> $system_maintenance]);
            Db::table('config')->where(['config_sign'=>'announcement'])->update(['config_value'=> $announcement]);
            Db::table('config')->where(['config_sign'=>'announcement_url'])->update(['config_value'=> $announcement_url]);
            Db::table('config')->where(['config_sign'=>'cs_qq'])->update(['config_value'=> $cs_qq]);
            Db::table('config')->where(['config_sign'=>'cs_wx'])->update(['config_value'=> $cs_wx]);
            $this->addAtionLog("修改系统配置");
            $this->ajaxOutput('保存设置成功', 1, url('Manage/system'));
        } else {
            $systemInfo = Db::table('config')->select();
            $list = [];
            foreach ($systemInfo as $key => $value) {
                $list[$value['config_sign']] = $value['config_value'];
            }
            $view = View::instance();
            $view->assign('list', $list);
            return $view->fetch();
        }
    }

    /**
	 * 充值提现
	 */
    public function cash() {
        if ($request->isPost()) {
            $free_draw_times = I('post.free_draw_times', 0, 'intval');
            $min_recharge = I('post.min_recharge', 0, 'floatval');
            $max_recharge = I('post.max_recharge', 0, 'floatval');
            $min_draw = I('post.min_draw', 0, 'floatval');
            $max_draw = I('post.max_draw', 0, 'floatval');
            $water_times = I('post.water_times', 0, 'intval');
            $draw_fee = I('post.draw_fee', 0, 'floatval');
            if ($free_draw_times < 1 || empty($min_recharge) || empty($max_recharge) || empty($min_draw) || empty($max_draw) || $water_times < 0 || $draw_fee >= 1 || $draw_fee < 0) {
                $this->ajaxOutput('参数错误');
            }
            M('config')->where(['config_sign'=>'free_draw_times'])->save(['config_value'=> $free_draw_times]);
            M('config')->where(['config_sign'=>'min_recharge'])->save(['config_value'=> $min_recharge]);
            M('config')->where(['config_sign'=>'max_recharge'])->save(['config_value'=> $max_recharge]);
            M('config')->where(['config_sign'=>'min_draw'])->save(['config_value'=> $min_draw]);
            M('config')->where(['config_sign'=>'max_draw'])->save(['config_value'=> $max_draw]);
            M('config')->where(['config_sign'=>'water_times'])->save(['config_value'=> $water_times]);
            M('config')->where(['config_sign'=>'draw_fee'])->save(['config_value'=> $draw_fee]);
            $redis = redisCache();
            $redis->delete(CacheEnum::CONFIG);
            $systemInfo = M('config')->select();
            $config = [];
            foreach ($systemInfo as $key => $value) {
                $config[$value['config_sign']] = $value['config_value'];
            }
            $redis->set(CacheEnum::CONFIG, json_encode($config));
            $this->addAtionLog("修改充值提现配置");
            $this->ajaxOutput('保存设置成功', 1, U('Manage/cash'));
        } else {
            $systemInfo = M('config')->select();
            $list = [];
            foreach ($systemInfo as $key => $value) {
                $list[$value['config_sign']] = $value['config_value'];
            }
            $this->assign('list', $list);
            $this->display();
        }
    }

    /**
	 * 第三方支付
	 */
    public function threePay() {
        if ($request->isPost()) {
            $online_pay = I('post.online_pay', 0, 'intval');
            $pay_url = I('post.pay_url', '', 'htmlspecialchars,trim');
            $pay_method = I('post.pay_method', '', 'htmlspecialchars,trim');
            $app_id = I('post.app_id', '', 'htmlspecialchars,trim');
            $app_secret = I('post.app_secret', '', 'htmlspecialchars,trim');
            $store_id = I('post.store_id', '', 'htmlspecialchars,trim');
            if (!in_array($online_pay, [0,1]) || empty($pay_url) || empty($pay_method) || empty($app_id) || empty($app_secret) || empty($store_id)) {
                $this->ajaxOutput('参数错误');
            }
            M('config')->where(['config_sign'=>'online_pay'])->save(['config_value'=> $online_pay]);
            M('config')->where(['config_sign'=>'pay_url'])->save(['config_value'=> $pay_url]);
            M('config')->where(['config_sign'=>'pay_method'])->save(['config_value'=> $pay_method]);
            M('config')->where(['config_sign'=>'app_id'])->save(['config_value'=> $app_id]);
            M('config')->where(['config_sign'=>'app_secret'])->save(['config_value'=> $app_secret]);
            M('config')->where(['config_sign'=>'store_id'])->save(['config_value'=> $store_id]);
            $redis = redisCache();
            $redis->delete(CacheEnum::CONFIG);
            $systemInfo = M('config')->select();
            $config = [];
            foreach ($systemInfo as $key => $value) {
                $config[$value['config_sign']] = $value['config_value'];
            }
            $redis->set(CacheEnum::CONFIG, json_encode($config));
            $this->addAtionLog("修改第三方支付配置");
            $this->ajaxOutput('保存设置成功', 1, U('Manage/threePay'));
        } else {
            $systemInfo = M('config')->select();
            $list = [];
            foreach ($systemInfo as $key => $value) {
                $list[$value['config_sign']] = $value['config_value'];
            }
            $this->assign('list', $list);
            $this->display();
        }
    }

    /**
	 * 银行卡列表
	 */
    public function bank() {
        $set_bank_card = M('set_bank_card');
        $list = $set_bank_card->where(['is_delete'=>0])->order('id desc')->select();
        $this->assign('list', $list);
        $this->display();
    }

    /**
	 * 停用/启动银行卡
	 */
    public function changeBankStatus() {
    	if ($request->isPost()) {
    		$id = I('post.id', 0, 'intval');
    		$is_default = I('post.is_default', 0, 'intval');
    		$is_default = in_array($is_default, [0,1]) ? $is_default : 1;
	        $set_bank_card = M('set_bank_card');
	        $bankInfo = $set_bank_card->where(['id'=>$id])->find();
	        if (empty($bankInfo)) {
	            $this->ajaxOutput('银行卡不存在');
	        }
	        if ($is_default == 0) {
	        	if (!$set_bank_card->where(['is_default'=>1, 'is_delete'=>0, 'id'=>['neq',$id]])->count()) {
	        		$this->ajaxOutput('不能停用所有银行卡');
	        	}
	        }
	        $set_bank_card->where(['id'=>$id])->save(['is_default'=>$is_default]);
	        $msg = $is_default == 1 ? "启动" : "停用";
            $this->addAtionLog("{$msg}银行卡{$bankInfo['account_number']}（{$bankInfo['real_name']}）");
	        $this->ajaxOutput($msg.'成功', 1, U('Manage/bank'));
    	}
    }

    /**
	 * 添加银行卡
	 */
    public function addBank() {
    	if ($request->isPost()) {
    		$account_number = I('post.account_number', '', 'htmlspecialchars,trim');
    		$bank_name = I('post.bank_name', '', 'htmlspecialchars,trim');
    		$real_name = I('post.real_name', '', 'htmlspecialchars,trim');
    		$branch_bank = I('post.branch_bank', '', 'htmlspecialchars,trim');
    		if (empty($account_number) || empty($bank_name) || empty($real_name) || empty($branch_bank)) {
    			$this->ajaxOutput('参数错误');
    		}
    		$set_bank_card = M('set_bank_card');
	        $set_bank_card->add([
	        	'account_number'=> $account_number,
	        	'bank_name'=> $bank_name,
	        	'real_name'=> $real_name,
	        	'branch_bank'=> $branch_bank,
	        	'is_default'=> 1,
	        ]);
            $this->addAtionLog("添加银行卡{$account_number}（{$real_name}）");
	        $this->ajaxOutput("添加成功", 1, U('Manage/bank'));
    	} else {
    		$this->display();
    	}
    }

    /**
	 * 编辑银行卡
	 */
    public function editBank() {
    	if ($request->isPost()) {
    		$id = I('post.id', 0, 'intval');
    		$account_number = I('post.account_number', '', 'htmlspecialchars,trim');
    		$bank_name = I('post.bank_name', '', 'htmlspecialchars,trim');
    		$real_name = I('post.real_name', '', 'htmlspecialchars,trim');
    		$branch_bank = I('post.branch_bank', '', 'htmlspecialchars,trim');
    		if ($id < 1 || empty($account_number) || empty($bank_name) || empty($real_name) || empty($branch_bank)) {
    			$this->ajaxOutput('参数错误');
    		}
    		$set_bank_card = M('set_bank_card');
    		$bankInfo = $set_bank_card->where(['id'=>$id])->find();
	        if (empty($bankInfo)) {
	            $this->ajaxOutput('银行卡不存在');
	        }
	        $set_bank_card->where(['id'=>$id])->save([
	        	'account_number'=> $account_number,
	        	'bank_name'=> $bank_name,
	        	'real_name'=> $real_name,
	        	'branch_bank'=> $branch_bank,
	        ]);
            $this->addAtionLog("修改银行卡{$account_number}（{$real_name}）");
	        $this->ajaxOutput("修改成功", 1, U('Manage/bank'));
    	} else {
    		$id = I('get.id');
    		$set_bank_card = M('set_bank_card');
    		$bankInfo = $set_bank_card->where(['id'=>$id])->find();
	        if (empty($bankInfo)) {
	            $this->ajaxOutput('银行卡不存在');
	        }
	        $this->assign('bankInfo', $bankInfo);
    		$this->display();
    	}
    }

    /**
	 * 删除银行卡
	 */
    public function delBank() {
    	if ($request->isPost()) {
    		$id = I('post.id');
	        $set_bank_card = M('set_bank_card');
	        $bankInfo = $set_bank_card->where(['id'=>['in',$id]])->find();
	        if (empty($bankInfo)) {
	            $this->ajaxOutput('银行卡不存在');
	        }
        	if (!$set_bank_card->where(['is_default'=>1, 'is_delete'=>0, 'id'=>['not in',$id]])->count()) {
        		$this->ajaxOutput('不能删除所有使用中银行卡');
        	}
	        $set_bank_card->where(['id'=>['in',$id]])->save(['is_delete'=>1]);
            $this->addAtionLog("删除银行卡{$bankInfo['account_number']}（{$bankInfo['real_name']}）");
	        $this->ajaxOutput("删除成功", 1, U('Manage/bank'));
    	}
    }

    /**
	 * 公告列表
	 */
    public function notice() {
        $notice = M('notice');
        $list = $notice->order('id desc')->select();
        $this->assign('list', $list);
        $this->display();
    }

    /**
	 * 启动公告
	 */
    public function changeNoticeStatus() {
    	if ($request->isPost()) {
    		$id = I('post.id', 0, 'intval');
	        $notice = M('notice');
	        $noticeInfo = $notice->where(['id'=>$id])->find();
	        if (empty($noticeInfo)) {
	            $this->ajaxOutput('公告不存在');
	        }
	        $notice->where(['status'=>1])->save(['status'=>0]);
	        $notice->where(['id'=>$id])->save(['status'=>1]);
	        sendToAll(CodeEnum::PUSH_NOTICE, ['notice'=> $noticeInfo['content']]);
            $this->addAtionLog("启动公告：{$noticeInfo['content']}");
	        $this->ajaxOutput('启动成功', 1, U('Manage/notice'));
    	}
    }

    /**
	 * 添加公告
	 */
    public function addNotice() {
    	if ($request->isPost()) {
    		$content = I('post.content', '', 'htmlspecialchars,trim');
    		if (empty($content)) {
    			$this->ajaxOutput('参数错误');
    		}
    		$notice = M('notice');
	        $notice->add([
	        	'content'=> $content,
	        	'user_name'=> session('cs_name'),
	        	'status'=> 0,
	        	'add_time'=> time(),
	        	'update_time'=> time(),
	        ]);
            $this->addAtionLog("添加公告：{$content}");
	        $this->ajaxOutput("添加成功", 1, U('Manage/notice'));
    	} else {
    		$this->display();
    	}
    }

    /**
	 * 编辑公告
	 */
    public function editNotice() {
    	if ($request->isPost()) {
    		$id = I('post.id', 0, 'intval');
    		$content = I('post.content', '', 'htmlspecialchars,trim');
    		if ($id < 1 || empty($content)) {
    			$this->ajaxOutput('参数错误');
    		}
    		$notice = M('notice');
    		$noticeInfo = $notice->where(['id'=>$id])->find();
	        if (empty($noticeInfo)) {
	            $this->ajaxOutput('银行卡不存在');
	        }
	        $notice->where(['id'=>$id])->save([
	        	'content'=> $content,
	        	'update_time'=> time(),
	        ]);
	        if ($noticeInfo['status'] == 1) {
	        	sendToAll(CodeEnum::PUSH_NOTICE, ['notice'=> $content]);
	        }
            $this->addAtionLog("修改公告：{$content}");
	        $this->ajaxOutput("修改成功", 1, U('Manage/notice'));
    	} else {
    		$id = I('get.id');
    		$notice = M('notice');
    		$noticeInfo = $notice->where(['id'=>$id])->find();
	        if (empty($noticeInfo)) {
	            $this->ajaxOutput('公告不存在');
	        }
	        $this->assign('noticeInfo', $noticeInfo);
    		$this->display();
    	}
    }

    /**
	 * 删除公告
	 */
    public function delNotice() {
    	if ($request->isPost()) {
    		$id = I('post.id');
	        $notice = M('notice');
	        $bankInfo = $notice->where(['id'=>['in',$id]])->find();
	        if (empty($bankInfo)) {
	            $this->ajaxOutput('公告不存在');
	        }
        	if ($notice->where(['status'=>1, 'id'=>['in',$id]])->count()) {
        		$this->ajaxOutput('不能删除使用中的公告');
        	}
	        $notice->where(['id'=>['in',$id]])->delete();
            $this->addAtionLog("删除公告：{$bankInfo['content']}");
	        $this->ajaxOutput("删除成功", 1, U('Manage/notice'));
    	}
    }

    /**
	 * 操作日志
	 */
    public function actLog() {
        $controller = input('get.controller','0');
        $type = input('get.type',0,'intval');
        $add_time = input('get.add_time');
        $user_name = input('get.user_name');

        $controllerNameArr = [];
        // 模块
        $controllerList = [];
        $authList = [];
        $controllerList[] = ['name'=>'全部模块','controller'=>'0','selected'=>($controller == '0' ? 'selected' : '')];
        foreach ($this->module as $key => $value) {
            $controllerList[] = [
                'name'=> $value['name'],
                'controller'=> $value['controller'],
                'selected'=> $controller == $value['controller'] ? 'selected' : '',
            ];
            if ($controller == $value['controller']) {
                $authList = $value['list'];
            }
            $controllerNameArr[$value['controller']] = $value['name'];
        }
        // 类型
        $typeList = [];
        $typeList[] = ['name'=>'全部类型','type'=>0,'selected'=>($type == 0 ? 'selected' : '')];
        foreach ($authList as $key => $value) {
            $typeList[] = [
                'name'=> $this->auth[$value]['name'],
                'type'=> $value,
                'selected'=> $type == $value ? 'selected' : '',
            ];
        }
        // 查询条件
        $where = [];
        if (!empty($controller)) {
            $where['controller'] = $controller;
        }
        if (isset($this->auth[$type])) {
            $action_arr = [];
            foreach ($this->auth[$type]['list'] as $key => $value) {
                list($c, $action_arr[]) = explode('-', $value);
            }
            $where['action'] = ['in', $action_arr];
        }
        if (!empty($add_time)) {
            $where['add_time'][] = ['egt', strtotime($add_time)];
            $where['add_time'][] = ['lt', strtotime($add_time) + 86400];
        }
        if (!empty($user_name)) {
            $where['user_name'] = $user_name;
        }
        $admin_action_log = db('admin_action_log');
        $count = $admin_action_log->where($where)->count();
        $pageInfo = $this->setPage($count);
        $list = $admin_action_log->where($where)->order('id desc')->limit($pageInfo['limit'])->select();
        foreach ($list as $key => $value) {
            $list[$key]['controller_name'] = $controllerNameArr[$value['controller']];
            $list[$key]['type_name'] = '';
            foreach ($this->auth as $k => $v) {
                if (in_array($value['controller'].'-'.strtolower($value['action']), $v['list'])) {
                    $list[$key]['type_name'] = $v['name'];
                    break;
                }
            }
        }

        $view = View::instance();
        $view->assign('controllerList', $controllerList);
        $view->assign('typeList', $typeList);
        $view->assign('controller', $controller);
        $view->assign('type', $type);
        $view->assign('add_time', $add_time);
        $view->assign('user_name', $user_name);
        $view->assign('list', $list);
        $view->assign('pageInfo', $pageInfo);
    	return $view->fetch();
    }

    /**
	 * 系统账号
	 */
    public function user() {
        $user_name = I('get.user_name');
        $where = ['is_delete'=> 0];
        if (!empty($user_name)) {
            $where['_complex'] = ['user_name'=>$user_name,'nickname'=>$user_name,'_logic'=>'or'];
        }
        $count = M('cs_user')->where($where)->count();
        $pageInfo = setPage($count);
        $list = M('cs_user')->where($where)->limit($pageInfo['limit'])->order('user_id desc')->select();
        $admin_login_log = M('admin_login_log');
        foreach ($list as $key => $value) {
            $login_ip = $admin_login_log->where(['user_id'=>$value['user_id'],'type'=>2])->order('id desc')->getField('ip');
            $list[$key]['login_ip'] = $login_ip;
            // 权限
            $arr = [];
            foreach ($this->module as $k1 => $v1) {
                $temp_arr = array_intersect(explode(',', $value['auth']), $v1['list']);
                if (!empty($temp_arr)) {
                    $module_auth_arr = [];
                    foreach ($temp_arr as $k2 => $v2) {
                        $module_auth_arr[] = $this->auth[$v2]['name'];
                    }
                    $arr[] = ['name'=> $v1['name'], 'auth'=> implode('，', $module_auth_arr)];
                }
            }
            $list[$key]['auth'] = !empty($arr) ? json_encode($arr) : '';
        }
        $this->assign('user_name', $user_name);
        $this->assign('list', $list);
        $this->assign('pageInfo', $pageInfo);
    	$this->display();
    }

    /**
     * 添加账号
     */
    public function addUser() {
        if ($request->isPost()) {
            $user_name = I('post.user_name', '', 'htmlspecialchars,trim');
            $nickname = I('post.nickname', '', 'htmlspecialchars,trim');
            $password = I('post.password', '', 'htmlspecialchars,trim');
            $repassword = I('post.repassword', '', 'htmlspecialchars,trim');
            $auths = I('post.auths', '', 'htmlspecialchars,trim');
            if (empty($user_name) || empty($nickname) || empty($password) || empty($repassword) || empty($auths)) {
                $this->ajaxOutput('参数错误');
            }
            if ($password != $repassword) {
                $this->ajaxOutput('确认密码不一致');
            }
            if (strlen($password) < 4 || strlen($password) > 16) {
                $this->ajaxOutput('登陆密码请输入4~16个字符');
            }
            $cs_user = M('cs_user');
            // 判断是否有同名
            if ($cs_user->where(['user_name'=> $user_name])->count()) {
                $this->ajaxOutput('该帐号已存在');
            }
            $cs_user->add([
                'user_name'=> $user_name,
                'nickname'=> $nickname,
                'password'=> md5($password),
                'nickname'=> $nickname,
                'auth'=> $auths,
                'is_delete'=> 0,
                'add_time'=> time(),
            ]);
            $this->addAtionLog("添加系统账号{$user_name}");
            $this->ajaxOutput("添加成功", 1, U('Manage/user'));
        } else {
            foreach ($this->module as $key => $value) {
                foreach ($value['list'] as $k => $v) {
                    $this->module[$key]['auth'][] = ['id'=>$v,'name'=>$this->auth[$v]['name']];
                }
            }
            $this->assign('module', $this->module);
            $this->display();
        }
    }

    /**
     * 编辑账号
     */
    public function editUser() {
        if ($request->isPost()) {
            $nickname = I('post.nickname', '', 'htmlspecialchars,trim');
            $password = I('post.password', '', 'htmlspecialchars,trim');
            $auths = I('post.auths', '', 'htmlspecialchars,trim');
            $user_id = I('post.user_id', '0', 'intval');
            if (empty($nickname) || empty($auths) || $user_id < 1) {
                $this->ajaxOutput('参数错误');
            }
            $userInfo = M('cs_user')->where(['user_id'=> $user_id])->find();
            if (empty($userInfo)) {
                $this->ajaxOutput('帐号不存在');
            }
            $save = [
                'nickname'=> $nickname,
                'auth'=> $auths,
            ];
            if (!empty($password)) {
                if (strlen($password) < 4 || strlen($password) > 16) {
                    $this->ajaxOutput('登陆密码必须4~16个字符');
                }
                $save['password'] = md5($password);
            }
            M('cs_user')->where(['user_id'=> $user_id])->save($save);
            $this->addAtionLog("修改系统账号{$userInfo['user_name']}");
            $this->ajaxOutput("修改成功", 1, U('Manage/user'));
        } else {
            $user_id = I('get.user_id',0,'intval');
            $cs_user = M('cs_user');
            $userInfo = $cs_user->where(['user_id'=>$user_id, 'is_delete'=>0])->find();
            if (empty($userInfo)) {
                $this->error('账号不存在');
            }
            $userInfo['auth'] = explode(',', $userInfo['auth']);
            foreach ($this->module as $key => $value) {
                foreach ($value['list'] as $k => $v) {
                    $this->module[$key]['auth'][] = ['id'=>$v,'name'=>$this->auth[$v]['name']];
                }
            }
            $this->assign('module', $this->module);
            $this->assign('userInfo', $userInfo);
            $this->assign('auth', $this->auth);
            $this->display();
        }
    }

    /**
     * 删除账号
     */
    public function delUser() {
        if ($request->isPost()) {
            $user_id = I('post.user_id');
            $cs_user = M('cs_user');
            $userInfo = $cs_user->where(['user_id'=>['in',$user_id], 'is_delete'=>0])->find();
            if (empty($userInfo)) {
                $this->ajaxOutput('帐号不存在');
            }
            if (in_array($this->csUserInfo['user_id'], explode(',', $user_id))) {
                $this->ajaxOutput('不能删除自己');
            }
            $cs_user->where(['user_id'=>['in',$user_id], 'is_delete'=>0])->save(['is_delete'=>1]);
            $this->addAtionLog("删除系统账号{$userInfo['user_name']}");
            $this->ajaxOutput("删除成功", 1, U('Manage/user'));
        }
    }
}