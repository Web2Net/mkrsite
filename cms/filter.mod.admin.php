<?php
class Filter {

    static function admFilter() 
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }
        $tpl=new AdmTpl;
        $tpl->assign('mod_name',"Фильтры");

        if($ext=="ajax")
        {
	        if(isset($_FILES["file"]) && trim($_FILES["file"]["name"])!= "")
            {	
                $mess=$_FILES["file"]["name"];
                $testName = explode( ".",$_FILES["file"]["name"] );
                $imgname=Text::cirilToLatin($testName[0]).".".$testName[1];
                $imgnamesmall="small-".Text::cirilToLatin($testName[0]).".".$testName[1];
                $mess=Article::loadImage($_FILES["file"]);
                if($mess=="OK")
                {
	                $imgdir = "/site/design/img/filter/";
                    $GLOBALS['_RESULT'] = array(
                                                "image" => $imgdir.$imgname,
                                                "image_small" => $imgdir.$imgnamesmall,    
                                                "mess" => $mess,
                                                ); 
                }
            }
        }
        else
        {
            if($type=="brand")
            {    
                if($com=="view")
                {
                    $row=Filter::brandList();
                    $tpl->assign('listing',$row);
                    $c_cont=$tpl->get("brand-list");	
                }
                if($com=="add")
                {
                    $c_cont=$tpl->get("brand-add-edit");	
                }
                if($com=="edit")
                {
	            $brand_data=Filter::brandData($id);
                    $tpl->assign('brand_data',$brand_data);
                    $c_cont=$tpl->get("brand-add-edit");	
                }
                if($com=="update")
                {

                    
                    $arr_value['parent_id']=isset($parent_id)?$parent_id:0;
                    $arr_value['type']=$rubrica;
                    $arr_value['caption']=$caption;
                    $arr_value['seo']=$seo!=""?$seo:$caption;
                    
                    $web_link = Text::cutForCirilicSeolink($caption);
                    //$arr_value['web_link']=Text::cirilToCirilSeolink($web_link);
                    
                    $arr_value['date_create']=isset($date_create) && $date_create!=""?$date_create:date("Y-m-d H:i:s");
                    $arr_value['meta_title']=$meta_title;
                    $arr_value['meta_description']=$meta_description;
                    $arr_value['meta_keywords']=$meta_keywords;
                    $arr_value['enabled']=$enabled;
                    $arr_value['pos']=$pos;
                    
                    
                    $nowdata=getdate(time());

                    $table="nav";

                    if($id != "")
                    {
	                    $where['id']=$id;
                        $db = new mysql;
                        $res = $db->updateSQL ($table, $arr_value, $where);
                    }
                    else
                    {
                        $arr_value['pos']=isset($pos)?$pos:$nowdata[0];
                        $db = new mysql;
                        $id = $db->insertSQL ($arr_value, $table);	
	                }
                    $loc_url=str_replace("com=update","com=edit",PAGE_URL);
                    $loc_url=str_replace("&id=".$id."","",$loc_url);
                    $loc_url=$loc_url."&id=".$id."&upd=ok";

                    header("Location: ".$loc_url."");
                }
                if($com=="delete")
                {
	            $from="items_filter";	
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

    static function Navigate()
    {
	$row=Filter::catListData();
     
        $tpl=new AdmTpl;
        $tpl->assign('category',$row);
        $art_navigate=$tpl->get('items-filter-navigate');

        return $art_navigate;
    }

    static function brandList()
    {
	$select="";
        $from="items_filter";
        $where["PARENTID"]="86";
        $sortby="`DESCR`";

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, $sortby);

        return $row;
     }

     static function brandData($id)
     {
         $select="";
         $from="items_filter";
         $where["id"]=$id;

         $db = new mysql;
         $row = $db->selectSQL($select, $from, $where, "", 1);
//SYS::varDump($row);
         return $row[0];
     }
/*
function artListData($category_id){
	
$select="";
$from="art_article";
$where["category_id"]=$category_id;
$sortby="date DESC";

$db = new mysql;
$row = $db->selectSQL($select, $from, $where, $sortby);

return $row;
}

function artData($id){
	
$select="";
$from="art_article";
$where["id"]=$id;

$db = new mysql;
$row = $db->selectSQL($select, $from, $where, "", 1);

return $row[0];
}
*/
function loadImage($arr){
foreach ($_REQUEST as $key=>$val){
$str="$".$key."=\"".$val."\";";
eval($str);}

$error="";

if($arr['error']>0){
	
$error.="Ïðîáëåìà ñ çàãðóçêîé ôàéëà $name<br>";

switch($arr['error'])
{
    case 1: $error.="Ðàçìåð ôàéëà áîëüøå âîçìîæíîãî<br>"; break;
    case 2: $error.="Ðàçìåð ôàéëà áîëüøå îáîçíà÷åííîãî â ôîðìå<br>"; break;
    case 3: $error.="Çàãðóæåíà òîëüêî ÷àñòü ôàéëà<br>"; break;
    case 4: $error.="Ôàéë íå çàãðóæåí<br>"; break;
}

  return false;
}

$testName = explode( ".",$arr["name"] );
$cur=array("jpg","bmp","gif","png");
if($testName[1]!="jpg"){
$mess="Ïðîáëåìà: Ôàéë ".$arr["name"]." íå ðàçðåøžííûé òèï (jpg, bmp, gif, png)";
return $mess;
}


if($arr['type']=="")	{
        $mess="Ïðîáëåìà: Ôàéë íå ôàéë èçîáðàæåíèÿ: ".$arr['type']."<br>";
        return $mess;
        }

//$real=SITE_PATH."/product-image/".$prodid.".".$testName[1];

$uploaddir = SITE_PATH."/userfiles/image/".$category_id."/";
$imfile=$testName[0].".jpg";
$smallimfile="small-".$imfile;
@mkdir($uploaddir);
$uploadfile = $uploaddir.$imfile;
$smalluploadfile = $uploaddir.$smallimfile;


//if (copy($_FILES['userfile']['tmp_name'], $uploadfile)){
	
GD::imageResize($uploadfile,$arr['tmp_name'],520,520,75);
GD::imageResize($smalluploadfile,$arr['tmp_name'],200,200,100);
//var_dump($arr);

return "OK";
}


function Event($type,$id){
	$from=$r['table'];
	$where['type']=$r['type'];
	if(isset($r['parent'])&&$r['parent']!=''){
	$where['parent']=$r['parent'];		
    }
	$data=viewInfo($from,$where);
	//var_dump($data);	
	foreach($data as $x=>$v){ 
	if($v['id']==$r['id']){
    $p = $x;	
    }
    //echo $x." => ".$v."<br>";
    }
    $data1=$data[$p];	
	if($r['event']=='moveDown'){
	$p2=$p+1;
		if($p2<count($data)){
		$data2=$data[$p2];
		}
	}
	if($r['event']=='moveUp'){
	$p2=$p-1;
		if($p2>=0){
		$data2=$data[$p2];
		}
	}	
if(isset($data[$p2]['pos'])){
$arr_value1['pos']=$data2['pos'];
$arr_value2['pos']=$data1['pos'];
$where1['id']=$data1['id'];
$where2['id']=$data2['id'];
$db = new mysql;
$db->updateSQL ($from, $arr_value1, $where1);
$db->updateSQL ($from, $arr_value2, $where2);
}
//return $res;
}

}
?>
