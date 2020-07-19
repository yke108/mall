CREATE TABLE `category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `cat_name` varchar(100) NOT NULL DEFAULT '' COMMENT '分类名称',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `cat_image` varchar(255) NOT NULL DEFAULT '' COMMENT '分类图片',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0-普通，1-首页',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`cat_id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类';

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `product_name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `product_image` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `product_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '原价',
  `sale_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '销售价',
  `product_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '商品状态',
  `sale_number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `filter_ids` varchar(100) NOT NULL DEFAULT '' COMMENT '规格ID：多个逗号隔开',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表';

CREATE TABLE `product_description` (
  `product_id` int(11) NOT NULL COMMENT '商品ID',
  `product_description` text COMMENT '商品描述',
  `product_video` varchar(255) NOT NULL DEFAULT '' COMMENT '商品视频',
  `product_images` varchar(1000) NOT NULL DEFAULT '' COMMENT '商品图片，多张逗号隔开',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表';

CREATE TABLE `product_category` (
  `product_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `cat_path` varchar(255) NOT NULL DEFAULT '' COMMENT '分类链:a123a-a456a-a456a',
  PRIMARY KEY (`product_id`,`cat_id`),
  KEY `cat_id` (`cat_id`),
  FULLTEXT KEY `cat_path` (`cat_path`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品分类';

CREATE TABLE `product_filter` (
  `filter_id` int(11) NOT NULL AUTO_INCREMENT,
  `filter_name` varchar(50) NOT NULL DEFAULT '' COMMENT '规格名称',
  `sort` smallint(5) unsigned DEFAULT '0' COMMENT '排序',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`filter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='规格';

CREATE TABLE `product_filter_value` (
  `filter_value_id` int(11) NOT NULL AUTO_INCREMENT,
  `filter_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '规格ID',
  `filter_value` varchar(50) NOT NULL DEFAULT '' COMMENT '规格值',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`filter_value_id`),
  KEY `filter_id` (`filter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='规格值';

CREATE TABLE `product_poa` (
  `poa_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `sale_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '销售价',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`poa_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品POA';

CREATE TABLE `product_poa_filter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poa_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'poa ID',
  `filter_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '规格ID',
  `filter_name` varchar(50) NOT NULL DEFAULT '' COMMENT '规格名称',
  `filter_value_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '规格值ID',
  `filter_value` varchar(50) NOT NULL DEFAULT '' COMMENT '规格值',
  PRIMARY KEY (`id`),
  KEY `poa_id` (`poa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品POA规格列表';
