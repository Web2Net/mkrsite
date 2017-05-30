<?php	
class Shopping{

function shoppingCommand(){
foreach ($_REQUEST as $key=>$val){
$str="$".$key."=\$val;";
eval($str);
}

if($com=="arhview"){
$data=Shopping::shoppingOrder("arhview");
return $data;
}

if($com=="arhiv"){
$arr_value['arhive']=1;
$table="shop_shopping";

$where['zakaz']=$user_id;
$where['zakaz_date']=date("Y-m-d H:i:s",$zakaz_date);
$db = new mysql;
$res = $db->updateSQL ($table, $arr_value, $where);	

header("Location: ".PAGE_REF."");
}

if($com=="delete"){
$from="shop_shopping";	
$where['zakaz']=$user_id;
$where['zakaz_date']=date("Y-m-d H:i:s",$zakaz_date);	
$db = new mysql;
$res = $db->deleteSQL ($from, $where);

header("Location: ".PAGE_REF."");
}


}

function shoppingOrder($com=""){

$select="";
$from="shop_shopping";
if($com=="arhview"){
$where="zakaz IS NOT NULL AND arhive='1'";	
}else{
$where="zakaz IS NOT NULL AND arhive IS NULL";
}
$pid =new mysql;
$order_list= $pid->origSelectSQL($select, $from, $where, $order="zakaz_date DESC", $limit="", $group="zakaz_date");
unset($where);

if($order_list!=NULL){
foreach($order_list as $key=>$val){
$select="";
$from="shop_user";
$where["id"]=$val["zakaz"];
$pid =new mysql;
$ulist= $pid->selectSQL($select, $from, $where, "");
unset($where);
$ulist[0]["zakaz_date"]=$val["zakaz_date"];

$select1="";
$from1="shop_shopping";
$where1["zakaz"]=$val["zakaz"];
$where1["zakaz_date"]=$val["zakaz_date"];
$pid =new mysql;
$olist= $pid->selectSQL($select1, $from1, $where1, "");
unset($where1);

if($olist!=NULL){
foreach($olist as $key1=>$val1){
$select2="";
$from2="shop_product";
$where2['1Cid']=$val1["product"];
$prod =new mysql;
$product= $prod->selectSQL($select2, $from2, $where2, "");
unset($where2);
$product[0]["pcount"]=$val1["pcount"];
$cena=$product[0]["newprice"]!=0?$product[0]["newprice"]:$product[0]["price"];
$product[0]["allprice"]=intval($product[0]["pcount"])*intval($cena);
$product[0]["date"]=$val1["date"];
$product[0]["zakaz_date"]=$val1["zakaz_date"];

$ordlist[]=$product[0];

}}

$ulist[0]["order_list"]=$ordlist;
unset($ordlist);
$user_list[]=$ulist[0];
}}
	
$tpl = new AdmTpl;
$tpl->assign('user_list', $user_list);
$orderlist_html=$tpl->get('shopping-order');

$leftblock=$tpl->get("navigate");
$rightblock=$orderlist_html;

$tpl=new AdmTpl;
$tpl->assign('leftblock',$leftblock);
$tpl->assign('rightblock',$rightblock);

$page=$tpl->get("main-page");

return $page;
}


}
?>