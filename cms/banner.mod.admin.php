<?php
class Banner {

function admBanner() {
foreach ($_REQUEST as $key=>$val){
$str="$".$key."=\$val;";
eval($str);}

$tpl=new AdmTpl;
$tpl->assign('mod_name',"Баннеры");

if($ext=="ajax"){
	

if(isset($_FILES["file"]) && trim($_FILES["file"]["name"])!= ""){	
$mess=$_FILES["file"]["name"];
$mess=Banner::loadImage($_FILES["file"]);
if($mess=="OK"){
	
$testName = explode( ".",$_FILES['file']['name']);
$bantype=$testName[1];
$banfile=Text::cirilToLatin($testName[0]).".".$testName[1];

$imgdir = "/site/design/img/banner/";

$GLOBALS['_RESULT'] = array(
  "banfile"     => $imgdir.$banfile,
  "bantype"     => $bantype,       
  "mess"   => $mess,
); 
}}


	
}else{

if($type=="category"){
	
if($com=="view"){

if($event!=""){Banner::Event($event,'banner_category',$id);}

$row=Banner::catListData();

$tpl->assign('listing',$row);
$c_cont=$tpl->get("category-list");	

}

if($com=="add"){

$c_cont=$tpl->get("category-add-edit");	

}

if($com=="edit"){
	
$cat_data=Banner::catData($id);
$tpl->assign('cat_data',$cat_data);
$c_cont=$tpl->get("category-add-edit");	

}

if($com=="update"){
	
$arr_value['parent_id']=isset($parent_id)?$parent_id:0;
$arr_value['type']=$type;
$arr_value['location']=$location;
$arr_value['caption']=$caption;
$arr_value['seo']=$seo!=""?$seo:Text::cirilToLatin($arr_value['caption']);
$arr_value['date']=isset($date) && $date!=""?$date:date("Y-m-d H:i:s");
$arr_value['meta_title']=$meta_title;
$arr_value['meta_description']=$meta_description;
$arr_value['meta_keywords']=$meta_keywords;
$arr_value['enabled']=$enabled;
$nowdata=getdate(time());
$arr_value['pos']=isset($pos)?$pos:$nowdata[0];

$table="banner_category";

if($id!=""){
	
$where['id']=$id;
$db = new mysql;
$res = $db->updateSQL ($table, $arr_value, $where);

}else{

$db = new mysql;
$id = $db->insertSQL ($arr_value, $table);	
	
}
$loc_url=str_replace("com=update","com=edit",PAGE_URL);
$loc_url=str_replace("&id=".$id."","",$loc_url);
$loc_url=$loc_url."&id=".$id."&upd=ok";

header("Location: ".$loc_url."");

}

if($com=="delete"){
	
$from="banner_category";	
$where['id']=$id;	
$db = new mysql;
$res = $db->deleteSQL ($from, $where);
$loc_url=str_replace("com=delete","com=view",PAGE_URL);
$loc_url=str_replace("&id=".$id."","",$loc_url);
header("Location: ".$loc_url."");
}

}

if($type=="article"){
	
if($com=="view"){

$row=Banner::artListData($category_id);
$parent_data=Banner::catData($category_id);

$tpl->assign('parent_data',$parent_data);
$tpl->assign('artlist',$row);
$c_cont=$tpl->get("banner-list");
}	

if($com=="add"){
	
$parent_list=Banner::catListData();
$tpl->assign('parent_list',$parent_list);

$c_cont=$tpl->get("banner-add-edit");	

}

if($com=="edit"){
	
$art_data=Banner::artData($id);
$tpl->assign('art_data',$art_data);	
	
$parent_list=Banner::catListData();
$tpl->assign('parent_list',$parent_list);

$parent_data=Banner::catData($category_id);
$tpl->assign('parent_data',$parent_data);

$c_cont=$tpl->get("banner-add-edit");	

}

if($com=="update"){
$arr_value['category_id']=$category_id;
$arr_value['name']=$name;
$arr_value['location']=$location;
$arr_value['caption']=$caption;
$arr_value['link']=$link;
$arr_value['file']=$banfile;
$arr_value['type']=$bantype;
$arr_value['date']=date("Y-m-d H:i:s");
$arr_value['enabled']=$enabled;

$table="banner_zone";

if($id!=""){
	
$where['id']=$id;
$db = new mysql;
$res = $db->updateSQL ($table, $arr_value, $where);

}else{

$db = new mysql;
$id = $db->insertSQL ($arr_value, $table);		
}

$filewrite=fopen("../jump/banner-data/".$arr_value['name'].".txt","w+");
$paper.=$arr_value['link']."\n";
fwrite($filewrite,$paper);
$paper="";
fclose($filewrite);
chmod("../jump/banner-data/".$arr_value['name'].".txt",0777);


$loc_url=str_replace("com=update","com=edit",PAGE_URL);
$loc_url=str_replace("&id=".$id."","",$loc_url);
$loc_url=$loc_url."&category_id=".$category_id."&id=".$id."&upd=ok";

header("Location: ".$loc_url."");

}

if($com=="delete"){
	
$from="banner_zone";	
$where['id']=$id;	
$db = new mysql;
$res = $db->deleteSQL ($from, $where);
$loc_url=str_replace("com=delete","com=view",PAGE_URL);
$loc_url=str_replace("&id=".$id."","",$loc_url);
header("Location: ".$loc_url."");
}

}


// $c_cont.=$tpl->get("request");
 return $c_cont;
}
}


function Navigate(){
	
$row=Banner::catListData();

$tpl=new AdmTpl;
$tpl->assign('category',$row);
$art_navigate=$tpl->get('banner-navigate');

return $art_navigate;
}

function catListData($parent=0){
	
$select="";
$from="banner_category";
$where["parent_id"]=$parent;
$sortby="pos DESC";

$db = new mysql;
$row = $db->selectSQL($select, $from, $where, $sortby);

return $row;
}


function catData($id){
	
$select="";
$from="banner_category";
$where["id"]=$id;

$db = new mysql;
$row = $db->selectSQL($select, $from, $where, "", 1);

return $row[0];
}

function artListData($category_id){
	
$select="";
$from="banner_zone";
$where["category_id"]=$category_id;
$sortby="name ASC";

$db = new mysql;
$row = $db->selectSQL($select, $from, $where, $sortby,"0,70");

return $row;
}
function artData($id){
	
$select="";
$from="banner_zone";
$where["id"]=$id;

$db = new mysql;
$row = $db->selectSQL($select, $from, $where, "", 1);

return $row[0];
}

function loadImage($arr){
foreach ($_REQUEST as $key=>$val){
$str="$".$key."=\"".$val."\";";
eval($str);}

$error="";

if($arr['error']>0){
	
$error.="Проблема с загрузкой файла $name<br>";

switch($arr['error'])
{
    case 1: $error.="Размер файла больше возможного<br>"; break;
    case 2: $error.="Размер файла больше обозначенного в форме<br>"; break;
    case 3: $error.="Загружена только часть файла<br>"; break;
    case 4: $error.="Файл не загружен<br>"; break;
}

  return false;
}

$testName = explode( ".",$arr["name"] );
$cur=array("jpg","gif","swf");
if($testName[1]!="jpg"&&$testName[1]!="gif"&&$testName[1]!="swf"){
$mess="Проблема: Файл ".$arr["name"]." не разрешённый тип (jpg, bmp, gif, png)";
return $mess;
}


if($arr['type']=="")	{
        $mess="Проблема: Файл не файл изображения: ".$arr['type']."<br>";
        return $mess;
        }


$uploaddir = SITE_PATH."/site/design/img/banner/";
//$uploaddir = SITE_PATH."/banner/".$testName[1]."/";
$imfile=Text::cirilToLatin($testName[0]).".".$testName[1];
@mkdir($uploaddir);
$uploadfile = $uploaddir.$imfile;



if(copy($arr['tmp_name'], $uploadfile)){
chmod($uploadfile, 0777);	
//var_dump($arr);

return "OK";
}
}


function Event($event,$table,$id){
$from=$table;
$where="id={$id}";
$db = new mysql;
$row = $db->origSelectSQL("id,pos", $from, $where,"pos ASC","0,1");

$pos_start=$row[0]["pos"];
$id_start=$row[0]["id"];

if($event=='moveDown'){
$from2=$table;	
$where2="pos<{$pos_start}";
$db = new mysql;
$row2 = $db->origSelectSQL("id,pos", $from2, $where2,"pos DESC","0,1");
$pos_end=$row2[0]["pos"];
$id_end=$row2[0]["id"];
}

if($event=='moveUp'){
$from2=$table;	
$where2="pos>{$pos_start}";
$db = new mysql;
$row2 = $db->origSelectSQL("id,pos", $from2, $where2,"pos ASC","0,1");
$pos_end=$row2[0]["pos"];
$id_end=$row2[0]["id"];
}
	
if($pos_end!=""){
$arr_value1['pos']=$pos_end;
$arr_value2['pos']=$pos_start;
$whereu1['id']=$id_start;
$whereu2['id']=$id_end;
$db = new mysql;
$db->updateSQL ($from, $arr_value1, $whereu1);
$db->updateSQL ($from, $arr_value2, $whereu2);
}
//return $res;
}

}
?>