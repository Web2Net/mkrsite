<?php
class Health 
{

    function admHealth() 
    {
        $tpl = new Tpl;
        
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }
        
        
        if($ext=="ajax")
        {
            if(isset($_FILES["file"]) && trim($_FILES["file"]["name"])!= "")
            {    
                $mess=$_FILES["file"]["name"];
                $testName = explode( ".",$_FILES["file"]["name"] );
                $imgname=Text::cirilToLatin($testName[0]).".".$testName[1];
                $imgnamesmall=Text::cirilToLatin($testName[0])."-small.".$testName[1];
                $mess=Health::loadImage($_FILES["file"]);
                if($mess=="OK")
                {
                    $imgdir = "/site/design/img/health/";
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
//            if($type=="cuisine")
//            {
                if($com=="view")
                {
                    $row=Health::artListData($type, $order, $strim);
                    $tpl->assign('listing',$row);
                    $c_cont=$tpl->get("health-list");    
                }
                if($com=="add")
                {
                    $c_cont=$tpl->get("health-add-edit");    
                }
                if($com=="edit")
                {
                    $cat_data=Health::catData($id);
                    $tpl->assign('cat_data',$cat_data);
                    $c_cont=$tpl->get("health-add-edit");    
                }
                if($com=="update")
                {   
$query="SELECT * FROM `mr_health_category` WHERE `name`='{$_GET['type']}' LIMIT 1";
$res = mysql_query($query);
while($row = mysql_fetch_array($res)) 
{
   $seolink = trim($row['seolink']);   
} 
                                                 
                    $arr_value['type']=$type;
                    $arr_value['category']=$category;
                    $arr_value['category_name'] = $seolink;
                    $arr_value['seolink'] =  Text::uncutForCirilicSeolink($caption);
                    $arr_value['counts']=$counts;
                    $arr_value['image']=$image;
                    $arr_value['image_small']=$image_small;
                    $arr_value['image_title']=$image_title;
                    $arr_value['time']=isset($date) && $date!==""?$date:date("Y-m-d H:i:s");
                    $arr_value['meta_t']=$meta_t;
                    $arr_value['meta_d']=$meta_d;
                    $arr_value['meta_k']=$meta_k;
                    $arr_value['caption']=$caption;
                    $arr_value['content']=$content;      
                    $arr_value['demo_caption']=$demo_caption;
                    $arr_value['demo_price']=$demo_price;        
                    $arr_value['demo_content']=$demo_content;
                    
                    if($showing == "1")
                    {
                        $arr_value['showing']="Y"; 
                    }
                    else
                    {
                        $arr_value['showing']="N";
                    }
                    if($demo_enable == "1")
                    {
                        $arr_value['demo_enable']="1"; 
                    }
                    else
                    {
                        $arr_value['demo_enable']="0";
                    }
                    if($demo_main == "1")
                    {
                        $arr_value['demo_main']="1"; 
                    }
                    else
                    {
                        $arr_value['demo_main']="0";
                    }
                    
                    $nowdata=getdate(time());
                    //$arr_value['pos']=isset($pos)?$pos:$nowdata[0];

                    $table="mr_health";

                    if($id!="")
                    {
                        $where['id']=$id;
                        $db = new mysql;
                        $res = $db->updateSQL ($table, $arr_value, $where);
                    }
                    else
                    {
                        $db = new mysql;
                        $id = $db->insertSQL ($arr_value, $table);    
                    }
                    $loc_url=str_replace("com=update","com=view",PAGE_URL);
                    $loc_url=str_replace("&id={$id}","",$loc_url);
                    $loc_url=$loc_url."&category={$category}&id={$id}&upd=ok";

unset($_SESSION);
                    
                    header("Location: ".$loc_url."");

                }
                if($com=="delete")
                {
                    $from="mr_health";    
                    $where['id']=$id;    
                    $db = new mysql;
                    $res = $db->deleteSQL ($from, $where);
                    $loc_url=str_replace("com=delete","com=view",PAGE_URL);
                    $loc_url=str_replace("&id={$id}","",$loc_url);
                    unset($_SESSION);
                    header("Location: {$loc_url}");
                }


                     return $c_cont;
        }
        
    }

    function Navigate()
    {
        $row=Health::catListData();

        $tpl=new AdmTpl;
        $tpl->assign('category',$row);
        $art_navigate=$tpl->get('health-navigate');

        return $art_navigate;
    }

    function catListData($type=0)
    {
        $select="";
        $from="`mr_health_category`";
        //$where["`type`"]=0;
        $sortby="`order`";

        $query = "SELECT * FROM `{$from}`";
        
        $res = mysql_query($query);
        while ($result = @mysql_fetch_assoc ($res))
        {
            if($result['type'] == "1")
            {
                $query1 = "SELECT * FROM `{$from}` WHERE `parent`='{$result['id']}'";
                $res1 = mysql_query($query1);
                while ($result1 = mysql_fetch_assoc ($res1))
                {
                     $category[] = $result1;
                }
            }
            $category[] = $result;
        }
        return $category;
    }

    function catData($id)
    {
        $select="";
        $from="mr_health";
        $where["id"]=$id;

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, "", 1);

        return $row[0];
    }

    function artListData($category_id, $order, $strim)
    {
        $select="";
        $from="mr_health";
        $where["type"]= $category_id;
        //$sortby="`caption`";
        if($order == "" && $order == NULL)
        {
            $order = "caption";
        }
        if($strim == "" && $strim == NULL)
        {
            $strim = "";
        }
        
        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, $order." ".$strim,"0,70");

        return $row;
    }

    function artData($id)
    {
        $select="";
        $from="mr_health";
        $where["id"]=$id;

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, "", 1);

        return $row[0];
    }

    function loadImage($arr)
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\"".$val."\";";
            eval($str);}

            $error="";

            if($arr['error']>0)
            {
                $error.="Проблема с загрузкой файла {$name}<br>";

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
            if($testName[1]!="jpg")
            {
                $mess="Проблема: Файл ".$arr["name"]." не разрешённый тип (jpg, bmp, gif, png)";
                return $mess;
            }
                     
            if($arr['type']=="")    
            {
                $mess="Проблема: Файл не файл изображения: ".$arr['type']."<br>";
                return $mess;
            }

            //$real=SITE_PATH."/product-image/".$prodid.".".$testName[1];

            $uploaddir = SITE_PATH."/site/design/img/health/";
            $imfile="{$testName[0]}.".$testName[1];
            $smallimfile="{$testName[0]}-small.".$testName[1];
            @mkdir($uploaddir);
            $uploadfile = $uploaddir.$imfile;
            $smalluploadfile = $uploaddir.$smallimfile;


            //if (copy($_FILES['userfile']['tmp_name'], $uploadfile)){
                
             GD::imageResize($uploadfile,$arr['tmp_name'],300,200,85);
             GD::imageResize($smalluploadfile,$arr['tmp_name'],150,100,85);
            //var_dump($arr);

            return "OK";
    }

    function Event($type,$id)
    {
        $from=$r['table'];
        $where['type']=$r['type'];
        if(isset($r['parent'])&&$r['parent']!='')
        {
            $where['parent']=$r['parent'];        
        }
        $data=viewInfo($from,$where);
        //var_dump($data);    
        foreach($data as $x=>$v)
        { 
            if($v['id']==$r['id'])
            {
                $p = $x;    
            }
        //echo $x." => ".$v."<br>";
        }
        $data1=$data[$p];    
        if($r['event']=='moveDown')
        {
            $p2=$p+1;
            if($p2<count($data))
            {
                $data2=$data[$p2];
            }
        }
        if($r['event']=='moveUp')
        {
            $p2=$p-1;
            if($p2>=0)
            {
                $data2=$data[$p2];
            }
        }    
        if(isset($data[$p2]['pos']))
        {
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