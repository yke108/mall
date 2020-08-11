<?php

namespace app\api\model;

use think\Model;
use think\Db;

class Product extends Model
{
	/*商品详情页获取商品信息*/
	public function getProductDetail($product_id) {
		$info = Db::table('product')->alias('p')->join('__PRODUCT_DESCRIPTION__ pd', 'pd.product_id=p.product_id')->where(['p.product_id'=> $product_id])->find();
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
			'product_description'=> htmlspecialchars_decode($info['product_description']),
			'filter_list'=> [],
			'poa_list'=> [],
		];

		if ($info['filters']) {
			$filters = json_decode($info['filters'], true);//{"1":[1,2,3],"2":[4,5,6]}
			$filter_ids = array_keys($filters);
			//规格数据
			$ret = Db::table('product_filter')->field('filter_id,filter_name')->where("filter_id in (".implode(',', $filter_ids).")")->select();
			$temp = [];
			foreach ($ret as $key => $value) {
				$temp[$value['filter_id']] = $value;
			}
			foreach ($filters as $filter_id => $filter_value_ids) {
				$filter_item = $temp[$filter_id];
				//规格值列表
				$value_list = Db::table('product_filter_value')->field('filter_value_id,filter_value')->where("filter_value_id in (".implode(',', $filter_value_ids).")")->select();
				$filter_item['value_list'] = $value_list;
				$result['filter_list'][] = $filter_item;
			}

			//poa数据product_poa_filter
			$ret = Db::table('product_poa')->alias('pp')->join('__PRODUCT_POA_FILTER__ ppf', 'pp.poa_id=ppf.poa_id')->field('pp.poa_id,pp.sale_price,ppf.filter_value_id')->where(['pp.product_id'=> $product_id])->select();
			$temp = [];
			foreach ($ret as $value) {
				$temp[$value['poa_id']][] = $value;
			}
			foreach ($temp as $poa_id => $value) {
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

	/*获取分类商品列表*/
	public function categoryProductList($cat_id=0, $page=1, $size=20) {

		$count = Db::table('product')->alias('p')
			->join('__PRODUCT_CATEGORY__ pc', 'p.product_id=pc.product_id')
			->field('p.product_id,p.product_name,p.product_image,p.product_price,p.sale_price')
			->where("p.product_status=1 AND MATCH (pc.cat_path) AGAINST ('a{$cat_id}a' IN BOOLEAN MODE)")
			->count();

		$list = [];
		if ($count) {
			$list = Db::table('product')->alias('p')
			->join('__PRODUCT_CATEGORY__ pc', 'p.product_id=pc.product_id')
			->field('p.product_id,p.product_name,p.product_image,p.product_price,p.sale_price')
			->where("p.product_status=1 AND MATCH (pc.cat_path) AGAINST ('a{$cat_id}a' IN BOOLEAN MODE)")
			->page($page,$size)
			->select();
		}

		return ['list'=> $list, 'count'=> $count];

	}

	/*商品详情页获取商品信息*/
	public function getProductInfo($product_id, $field="*") {
		return Db::table('product')->field($field)->where(['product_id'=> $product_id])->find();
	}
}