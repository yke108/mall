<?php

namespace app\api\model;

use think\Model;
use think\Db;

class User extends Model
{
	/*获取用户信息*/
	public function getUserId($openid) {
		$userInfo = Db::table('user')->field('user_id')->where(['openid'=> $openid])->find();
		return !empty($userInfo) ? $userInfo['user_id'] : 0;
	}

	/*获取用户信息*/
	public function getUserInfo($user_id) {
		$userInfo = Db::table('user')->where(['user_id'=> $user_id])->find();
		return $userInfo;
	}

	/*获取用户信息*/
	public function addUser($data) {
		$data['reg_time'] = time();
		Db::table('user')->insert($data);
		$user_id = Db::name('user')->getLastInsID();
		return $user_id;
	}
}