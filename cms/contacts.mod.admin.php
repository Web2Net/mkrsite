<?php
class Contacts 
{

    function admContacts() 
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
                $imgname=$id.".".$testName[1];
                $imgnamesmall=$id."-small.".$testName[1];
                $mess=Contacts::loadImage($_FILES["file"],$id);
                if($mess=="OK")
                {
                    $imgdir = "/site/design/img/contacts/";
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
                if($com=="view")
                {
                    $row=Contacts::artListData($type, $category, $order, $strim);
                    $tpl->assign('listing',$row);
                    $c_cont=$tpl->get("contacts-list");    
                }
                if($com=="add")
                {
		    $parent_list=Contacts::catListData();
                    $tpl->assign('parent_list',$parent_list);    

                    $c_cont=$tpl->get("contacts-add-edit");
                }
                if($com=="edit")
                {
                    $cat_data=Contacts::catData($id);
                    $tpl->assign('listing',$cat_data);
					
		    $parent_list=Contacts::catListData();
                    $tpl->assign('parent_list',$parent_list);
					
                    $c_cont=$tpl->get("contacts-add-edit");    
                }
                if($com=="update")
                {   
                  foreach($_REQUEST as $key=>$val)
                  {
                      $var = explode("__",$key);
                      if($var['0'] == "form")
                      {
                          $arr_value[$var['1']] = $val;
                      }
                  }                             
                    //$arr_value['type']=$type;
                    //$arr_value['date_create']=isset($date) && $date!=""?$date:date("Y-m-d H:i:s");
                    //echo $seolink;
                    
                    

//Sys::varDump($arr_value);                    
                    $table="contacts";

                    $query="SELECT * FROM `contacts` WHERE `id`='{$id}' LIMIT 1";
                    $res = mysql_query($query);
                    $res = mysql_num_rows($res);
                    if($res>0)
                    {
                        $db = new mysql;
                        $where['id'] = $arr_value['id'];
                        $res = $db->updateSQL ($table, $arr_value, $where); 
                    }                 
                    else
                    {   
                        $db = new mysql;
                        $id = $db->insertSQL ($arr_value, $table);    
                    }

                    //$loc_url=str_replace("com=update","com=view",PAGE_URL);
                   // $loc_url=str_replace("&id=".$id."","",$loc_url);
                    //$loc_url=$loc_url."&id=".$id."&upd=ok";

                    header("Location: ".$loc_url."");

                }
                if($com=="delete")
                {
                    $from="contacts";    
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

    function artListData($type, $category, $order, $strim)
    {
        $select="";
        $from="contacts";
if($_SESSION['level']=="9")
{
        $where = "1=1";
}
else
{
        $where = "`category_id`='МКР'";
}
if($category)
{
		$where = "`category_id`='{$category}'";
} 
       //$sortby="`caption`";
        if($order == "" && $order == NULL)
        {
            $order = "l_name";
        }
        if($strim == "" && $strim == NULL)
        {
            $strim = "";
        }
        
        $db = new mysql;
        $row = $db->OrigSelectSQL($select, $from, $where, $order." ".$strim,"");

        return $row;
    }
    
    function catData($id)
    {
        $select="";
        $from="`contacts`";
        $where["id"]=$id;

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, "", 1);

        return $row[0];
    }
    
    function Navigate()
    {
        $row=contacts::catListData();

        $tpl=new AdmTpl;
        $tpl->assign('category',$row);
        $art_navigate=$tpl->get('contacts-navigate');

        return $art_navigate;
    }

    function catListData()
    {
        $select="category_id";
        $from="contacts";
        //$where["`type`"]=0;
        $sortby="`category_id`";

       $query = "SELECT `{$select}` FROM `{$from}` GROUP BY `{$select}`";
        
        $res = mysql_query($query);
        while ($result = @mysql_fetch_assoc ($res))
        {
//            if($result['type'] == "1")
//            {
//                $query1 = "SELECT * FROM `{$from}` WHERE `category_id`='{$result['id']}'";
//                $res1 = mysql_query($query1);
//                while ($result1 = mysql_fetch_assoc ($res1))
//                {
//                     $category[] = $result1;
//                }
//            }
            $category[] = $result;
        }
        return $category;
    }

    function artData($id)
    {
        $select="";
        $from="contacts";
        $where["id"]=$id;

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, "", 1);

        return $row[0];
    }

    function loadImage($arr,$id)
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\"".$val."\";";
            eval($str);
		}
            $error="";

            if($arr['error']>0)
            {
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

            $uploaddir = SITE_PATH."/site/design/img/contacts/";
            $imfile="{$id}.".$testName[1];
            $smallimfile="{$id}-small.".$testName[1];
            @mkdir($uploaddir);
            $uploadfile = $uploaddir.$imfile;
            $smalluploadfile = $uploaddir.$smallimfile;


            //if (copy($_FILES['userfile']['tmp_name'], $uploadfile)){
            GD::imageResize($uploadfile,$arr['tmp_name'],400,265,85);
            GD::imageResize($smalluploadfile,$arr['tmp_name'],300,150,85);    
            
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
