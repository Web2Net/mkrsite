<?php
class Nav 
{

    function admNav() 
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
                $mess=Nav::loadImage($_FILES["file"]);
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
                if($com=="view")
                {
                    $row=Nav::artListData($showing, $order, $strim);
                    $tpl->assign('listing',$row);
                    $c_cont=$tpl->get("nav-list");    
                }
                if($com=="add")
                {
                    $c_cont=$tpl->get("nav-add-edit");    
                }
                if($com=="edit")
                {
                    $cat_data=Nav::catData($id);
                    $tpl->assign('cat_data',$cat_data);
                    $c_cont=$tpl->get("nav-add-edit");    
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
                    $arr_value['type']=$type;
                    $arr_value['date_create']=isset($dt) && $dt!=""?$dt:date("Y-m-d H:i:s");
                   
                   if($form_showing == "1")
                    {
                        $arr_value['showing']="Y"; 
                    }
                    else
                    {
                        $arr_value['showing']="N";
                    }
                    
//                    $arr_value['seolink'] = Text::cirilToCirilSeolink(Text::cutForCirilicSeolink($form__caption));
                      //$arr_value['name'] = Text::cirilToLatin($form__caption);
                      $arr_value['name'] = "page";
                      $arr_value['seolink'] = Text::cutForCirilicSeolink($form__caption);
//Sys::varDump($arr_value);

                    $table = "nav";
                    
                    if($id!="")
                    {
                        $db = new mysql;
                        $where['id'] = $id;
                        $res = $db->updateSQL ($table, $arr_value, $where); 
                    }                 
                    else
                    {   
                        $db = new mysql;
                        $id = $db->insertSQL ($arr_value, $table);    
                    }
                    $loc_url=str_replace("com=update","com=view",PAGE_URL);
                    $loc_url=str_replace("&id=".$id."","",$loc_url);
                    $loc_url=$loc_url."&id=".$id."&upd=ok";

unset($_SESSION['navigate']);
                    
                    header("Location: ".$loc_url."");

                }
                if($com=="delete")
                {
                    $from="nav";    
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

    function artListData($showing, $order, $strim)
    {
        $select="";
        $from="nav";
        $showing = "0";
        
        $where["parent_id"]= $showing;
        //$sortby="`caption`";
        if($order == "" && $order == NULL)
        {
            $order = "location";
        }
        if($strim == "" && $strim == NULL)
        {
            $strim = "";
        }
        
        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, $order." ".$strim,"0,70");

        return $row;
    }
    
    function catData($id)
    {
        $select="";
        $from="`nav`";
        $where["id"]=$id;

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, "", 1);

        return $row[0];
    }
    
    function Navigate()
    {
        $row=Nav::catListData();

        $tpl=new AdmTpl;
        $tpl->assign('category',$row);
        $art_navigate=$tpl->get('nav-navigate');

        return $art_navigate;
    }

    function catListData($type=0)
    {
        $select="";
        $from="`nav`";
        //$where["`type`"]=0;
        //$order="`order_show`";

        $query = "SELECT * FROM {$from}";
        
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

 ///////////////////////////////////////   

    

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

            $uploaddir = SITE_PATH."/site/design/img/health/";
            $imfile="{$testName[0]}.".$testName[1];
            $smallimfile="{$testName[0]}-small.".$testName[1];
            @mkdir($uploaddir);
            $uploadfile = $uploaddir.$imfile;
            $smalluploadfile = $uploaddir.$smallimfile;


            //if (copy($_FILES['userfile']['tmp_name'], $uploadfile)){
                
            GD::imageResize($uploadfile,$arr['tmp_name'],120,120,75);
            GD::imageResize($smalluploadfile,$arr['tmp_name'],50,50,75);
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