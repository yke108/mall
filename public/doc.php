<?php
header("Content-Type: text/html;charset=utf-8");
?>

<p>
首页接口:{{url}}/api/index/index
<p>
<p>
首页推荐:{{url}}/api/index/recommend<br>参数:page,size
<p>
<p>
商品详情:{{url}}/api/product/index<br>参数:product_id
<p>
<p>
分类列表:{{url}}/api/category/catList<br>参数:pid
<p>
<p>
分类商品列表:{{url}}/api/category/productList<br>参数:cat_id,page,size
<p>