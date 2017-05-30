<?php
class Vote 
{
    function admVote() 
    {
        $tpl = new Tpl;
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }
        
if($ext=="ajax"){
	
//$filewrite=fopen("../bugs/bug.txt","w+");
//$paper.=$_FILES["file"]["name"]."\n";
//fwrite($filewrite,$paper);
//$paper="";
//fclose($filewrite);

if(isset($_FILES["file"]) && trim($_FILES["file"]["name"])!= ""){	
$mess=$_FILES["file"]["name"];
$testName = explode( ".",$_FILES["file"]["name"] );

$imgnamebig="big-".Text::cirilToLatin($testName[0]).".".$testName[1];
$imgnamesmall="small-".Text::cirilToLatin($testName[0]).".".$testName[1];


$mess=Vote::loadImage($_FILES["file"]);

if($mess=="OK"){
	
$imgdir = "/site/design/img/vote/";

$GLOBALS['_RESULT'] = array(
  "image" => $imgdir.$imgnamebig,
  "image_small" => $imgdir.$imgnamesmall,    
  "mess" => $mess,
); 
}}


	
}else{
        
        
        $tpl->assign('mod_name',"Отзывы");
        
        if($com=="view")
        {
            $row=Vote::VoteListData();
            $tpl->assign('listing',$row);
            $tpl->assign('type_name',"Обзор");
            $c_cont=$tpl->get("vote-list");    
        }
        if($com=="add")
        {
            $tpl->assign('type_name',"Создать");
            $c_cont=$tpl->get("vote-add-edit");    
        }
        if($com=="edit")
        {
            $vote_data=Vote::voteData($id);
            $tpl->assign('vote_data',$vote_data);
            $tpl->assign('type_name',"Редактировать");
            $c_cont=$tpl->get("vote-add-edit");    
        }
        if($com=="update")
        {   
             $db = new mysql;
             $table = "mr_otzivi";
             $arr_value['var1_content'] = trim($var1_content);
             $arr_value['var2_content'] = trim($var2_content);
             $arr_value['var1_img'] = $image1;
             $arr_value['var2_img'] = $image2;             
             
             $arr_value['date'] = mktime();
             if($showing == "")$showing="N";
             $arr_value['showing'] = $showing;

            if($id != "")
            {    
                $where['id'] = $id;
                $res = $db->updateSQL ($table, $arr_value, $where);
               
            }
            else
            {                      
                $id = $db->insertSQL ($arr_value, $table);    
            }
            $loc_url=str_replace("com=update","com=view",PAGE_URL);
            $loc_url=str_replace("&id={$id}","",$loc_url);
            header("Location: {$loc_url}");
        }
        if($com=="delete")
        {
            $from="mr_otzivi";    
            $where['id']=$id;    
            $db = new mysql;
            $res = $db->deleteSQL ($from, $where);
            $loc_url=str_replace("com=delete","com=view",PAGE_URL);
            $loc_url=str_replace("&id={$id}","",$loc_url);
            header("Location: {$loc_url}");
        }
        
        return $c_cont;
}
    }

    function Navigate()
    {
        $tpl=new AdmTpl;
        $vote_navigate=$tpl->get('vote-navigate');

        return $vote_navigate;
    }

    function voteData($id)
    {
        $select="";
        $from="mr_otzivi";
        $where["id"]=$id;

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, "", 1);

        return $row[0];
    }

    function VoteListData()
    {
        $select="";
        $from="mr_otzivi";
        $where ="1=1";
        $sortby="`id` DESC";

        $db = new mysql;
        $row = $db->origSelectSQL($select, $from, $where, $sortby,"0,70");

        return $row;
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
$cur=array("jpg","bmp","gif","png");
if($testName[1]!="jpg"){
$mess="Проблема: Файл ".$arr["name"]." не разрешённый тип (jpg, bmp, gif, png)";
return $mess;
}
    

if($arr['type']=="")	{
        $mess="Проблема: Файл не файл изображения: ".$arr['type']."<br>";
        return $mess;
        }

//$real=SITE_PATH."/product-image/".$prodid.".".$testName[1];

$uploaddir = SITE_PATH."/site/design/img/vote/";
$imfile=Text::cirilToLatin($testName[0]).".jpg";
$bigimfile="big-".$imfile;
$smallimfile="small-".$imfile;
@mkdir($uploaddir);
$uploadfile = $uploaddir.$bigimfile;
$smalluploadfile = $uploaddir.$smallimfile;


//if (copy($arr['tmp_name'], $uploadfile)){
	
GD::imageResize($uploadfile,$arr['tmp_name'],600,400,100);
//GD::imageResize($smalluploadfile,$arr['tmp_name'],150,100,85);
//var_dump($arr);

return "OK";
//}
}
}
?>