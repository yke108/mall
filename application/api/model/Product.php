<?php

namespace app\api\model;

use think\Model;
use think\Db;

class Product extends Model
{
	/*商品详情页获取商品信息*/
	public function getProductDetail($product_id) {
		$info = Db::table('product')->alias('p')->join('__PRODUCT_DESCRIPTION__ pd', 'pd.product_id=p.product_id')->where(['p.product_id'=> 1])->find();
		if (empty($info) || empty($info['product_status'])) {
			return false;
		}

		//获取详情数据
		$result = [
			'product_id'=> $info['product_id'],
			'product_name'=> $info['product_name'],
			'product_price'=> $info['product_price'],
			'sale_price'=> $info['sale_price'],
			'product_status'=> $info['product_status'],
			'sale_number'=> $info['sale_number'],
			'product_video'=> $info['product_video'],
			'product_images'=> explode(',', $info['product_images']),
			'product_description'=> $info['product_description'],
			'filter_list'=> [],
			'poa_list'=> [],
		];

		if ($info['filter_ids']) {
			//规格数据
			$filter_ids = explode(',', $info['filter_ids']);
			$ret = Db::table('product_filter')->field('filter_id,filter_name')->where("filter_id in ({$info['filter_ids']})")->select();
			$list = [];
			foreach ($ret as $key => $value) {
				$list[$value['filter_id']] = $value;
			}
			foreach ($filter_ids as $filter_id) {
				$filter_item = $list[$filter_id];
				//规格值列表
				$ret = Db::table('product_filter_value')->field('filter_value_id,filter_value')->where(['filter_id'=> $filter_id])->select();
				$filter_item['value_list'] = $ret;
				$result['filter_list'][] = $filter_item;
			}

			//poa数据product_poa_filter
			$ret = Db::table('product_poa')->alias('pp')->join('__PRODUCT_POA_FILTER__ ppf', 'pp.poa_id=ppf.poa_id')->where(['pp.product_id'=> 1])->select();
			$list = [];
			foreach ($ret as $value) {
				$list[$value['poa_id']][] = $value;
			}
			foreach ($list as $poa_id => $value) {
				$filter_value_ids = [];
				foreach ($value as $v) {
					$filter_value_ids[] = $v['filter_value_id'];
				}
				sort($filter_value_ids);
				$key = implode('-', $filter_value_ids);
				$result['poa_list'][$key] = ['poa_id'=> $poa_id, 'sale_price'=> $v['sale_price']];
			}
		}


		return $result;
	}
}