<?php	
class Shopping
{
	
    function viewShoppingPage()
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }

        $tpl_page = new SiteTpl;

        if($param=='logonvalid')
        {
	    if($logonemail!="")
            {
                $filewrite=fopen("".date("d-m-Y",time()).".txt","a+");
                foreach($_REQUEST as $key=>$val)
                {
                    $paper.=$key."=".$val."&";
                }
                $paper.=USER_IP."\n";
                fwrite($filewrite,$paper);
                fclose($filewrite);
                unset($paper);

                $selectu="";
                $fromu="shop_user";
                $whereu="email = '".trim($logonemail)."'";
                $db =new mysql;
                $ulist= $db->origSelectSQL($selectu, $fromu, $whereu, "");

                if($ulist!=NULL)
                {
                    $GLOBALS['_RESULT'] = array(
                                                 'valid' => '1'
                                                );
                }
                else
                {
                    $GLOBALS['_RESULT'] = array(
                                                 'valid' => 'Пользователь с таким e-mail не зарегистрирован. Пожалуйста, зарегистрируйтесь.'
                                                ); 	
                }
            }
        }

        if($param=="index")
        {
            $shopping_list=Shopping::listShoppingData();	
            $tpl_page->assign('shopping_list', $shopping_list);
            $shopping_html=$tpl_page->get('shopping-list');
            $page["content"]=$shopping_html;
            $page["meta"]["title"]="Ваша корзина";	
         }
         if($param=="login"){
$tpl_sh = new SiteTpl;	
$shopping_list=Shopping::listShoppingData();
$tpl_sh->assign('shopping_list', $shopping_list);
$sh_list=$tpl_sh->get('shopping-list');

$tpl_page->assign('sh_list', $sh_list);
$shopping_html=$tpl_page->get('shopping-login');
$page["content"]=$shopping_html;
$page["meta"]["title"]="Оформление заказа";	
}
if($param=="order"){
if($com_u=="register"){
$userid=Shopping::insertUser();
}
if($com_u=="logon"){
$selectu="";
$fromu="shop_user";
$whereu="email = '".trim($logonemail)."'";
$db =new mysql;
$ulist= $db->origSelectSQL($selectu, $fromu, $whereu, "");
$userid=$ulist[0]["id"];
}
$tpl_sh = new SiteTpl;
$shopping_list=Shopping::listShoppingData();
Shopping::updateShopping($shopping_list,$userid);
$tpl_sh->assign('shopping_list', $shopping_list);
$sh_list=$tpl_sh->get('shopping-list');

$tpl_page->assign('sh_list', $sh_list);
$shopping_html=$tpl_page->get('shopping-order');
$page["content"]=$shopping_html;
$page["meta"]["title"]="Ваша покупка";

}


return $page;

}


function updateShopping($shopping_list,$user){
if($shopping_list!=NULL){
foreach($shopping_list as $key=>$val){
$from="shop_shopping";
$where['product']=$val['1Cid'];
$where['date']=$val['date'];
$arr_value['zakaz']=$user;
$arr_value['zakaz_date']=date("Y-m-d H:i:s");
$db = new mysql;
$res = $db->updateSQL ($from, $arr_value, $where);
}}
return $res;
}
	
	
function listShoppingData(){
	
$select="";
$from="shop_shopping";
$where="ip='".USER_IP."' AND zakaz IS NULL";
$pid =new mysql;
$slist= $pid->origSelectSQL($select, $from, $where, "");
unset($where);

if($slist!=NULL){
foreach($slist as $key=>$val){
$select="";
$from="shop_product_1";
$where['1Cid']=$val["product"];
$prod =new mysql;
$product= $prod->selectSQL($select, $from, $where, "");
$product[0]["pcount"]=$val["pcount"];
$cena=$product[0]["newprice"]!=0?$product[0]["newprice"]:$product[0]["newprice"];
$product[0]["allprice"]=intval($product[0]["pcount"])*intval($cena);
$product[0]["date"]=$val["date"];
unset($where);
$shoppinglist[]=$product[0];

}}

return $shoppinglist;
}

function shoppingPlus(){	
foreach ($_REQUEST as $key=>$val){
$str="$".$key."=\$val;";
eval($str);}
include_once SITE_PATH."/lib/JsHttpRequest/lib/JsHttpRequest/JsHttpRequest.php";

$JsHttpRequest =& new JsHttpRequest("utf-8");
header("Content-type: text/plain; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

$filewrite=fopen("bot-".date("d-m-Y",time()).".txt","a+");
foreach($_REQUEST as $key=>$val){
$paper.=$key."=".$val."\n";
}
$paper.=USER_IP."\n";
fwrite($filewrite,$paper);
fclose($filewrite);
unset($paper);

if($prodid!="" && $pcount!=""){
$arr_value['ip']=USER_IP;
$arr_value['product']=$prodid;
$arr_value['pcount']=$pcount;
$arr_value['date']=date("Y-m-d H:i:s");

$table="shop_shopping";
$db = new mysql;
$res = $db->insertSQL ($arr_value, $table);	
}
$strkol=Shopping::shoppingCountText();
$GLOBALS['_RESULT'] = array(
  "q"     => $strkol,
  "r"     => '<strong style="color:forestgreen">Товар добавлен в корзину</a>',    
); 
}

function shoppingMinus(){	
foreach ($_REQUEST as $key=>$val){
$str="$".$key."=\$val;";
eval($str);}
include_once SITE_PATH."/lib/JsHttpRequest/lib/JsHttpRequest/JsHttpRequest.php";

$JsHttpRequest =& new JsHttpRequest("utf-8");
header("Content-type: text/plain; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);


$from="shop_shopping";
$where['ip']=USER_IP;
$where['product']=$prodid;
$where['date']=$shoppingdate;
$db = new mysql;
$res = $db->deleteSQL ($from, $where);

$strkol=Shopping::shoppingCountText();
$shopping_list=Shopping::listShoppingData();
if($shopping_list!=NULL){
$totalprice=0;
foreach($shopping_list as $key=>$val){
$totalprice=$val["allprice"]+$totalprice;
}}
$GLOBALS['_RESULT'] = array(
  "q"     => $strkol,
  "r"     => $totalprice,    
); 
}


function shoppingCountText(){

$select="COUNT(*)";
$from="shop_shopping";
$where="ip='".USER_IP."' AND zakaz IS NULL";;
$pid =new mysql;
$cs= $pid->origSelectSQL($select, $from, $where, "");

$chego=array("товар","товара","товаров");
$kol=$cs[0]["COUNT(*)"];
if($kol<2){$strkol=$kol." ".$chego[0];}else if($kol>1&&$kol<5){$strkol=$kol." ".$chego[1];}else{$strkol=$kol." ".$chego[2];}
if($kol!=0){
$shoppingcounttext="<a href='".SITE_URL."/shop/shopping/index.html' title='Перейти в корзину'>В корзине</a><br> ".$strkol;
}else{
$shoppingcounttext="Ваша корзина пуста";
}
$_SESSION["shopcounttext"]=$shoppingcounttext;
return $shoppingcounttext;
//echo USER_IP;
}


function insertUser(){
	
foreach ($_REQUEST as $key=>$val){
$str="$".$key."=\$val;";
eval($str);}

$table="shop_user";
$arr_value['name']=$regname;
$arr_value['phone']=$regphone;
$arr_value['email']=$regemail;
$arr_value['city']=$regcity;
$arr_value['comment']=$regcomment;
$arr_value['regdate']=date("Y-m-d H:i:s");

$db = new mysql;
$res = $db->insertSQL ($arr_value, $table);
return $res;
}

}
?>
