<?php
class Subscribe 
{
    function admSubscribe() 
    {
        $tpl = new Tpl;
        
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }
        
        $tpl->assign('mod_name',"Подписчики");
        if($type == ""){$type = "Y";}
        if($type == "Y")
        {
            $tpl->assign('type_name',"Активные");
        }
        elseif($type == "N")
        {
            $tpl->assign('type_name',"Отписавщиеся");
        }
        
                if($com=="view")
                {
                    $row=Subscribe::artListData($type);
                    $tpl->assign('listing',$row);
                    $c_cont=$tpl->get("subscribe-list");	
                }
                if($com=="add")
                {
                    $c_cont=$tpl->get("subscribe-add-edit");	
                }
                if($com=="edit")
                {
	                $cat_data=Subscribe::catData($id);
                    $tpl->assign('cat_data',$cat_data);
                    $c_cont=$tpl->get("subscribe-add-edit");	
                }
                if($com=="update")
                {   //echo $cat;
                    //$cat = explode("-",$cat);
	                //$arr_value['id_group']=$cat[0];
                    //$arr_value['name']=$cat[1];
                    
                    $arr_value['name']=$name;
                    $arr_value['email']=$email;
                    $arr_value['active']=$type;
                    
                    if($showing == "1")
                    {
                        $arr_value['active']="Y"; 
                    }
                    else
                    {
                        $arr_value['active']="N";
                    }
                    
                    $table="mdpls_subscribe";

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
                    $loc_url=str_replace("&id=".$id."","",$loc_url);
                    $loc_url=$loc_url."&id=".$id."&upd=ok";

                    header("Location: ".$loc_url."");

                }
                if($com=="delete")
                {
	                $from="mdpls_subscribe";	
                    $where['id']=$id;	
                    $db = new mysql;
                    $res = $db->deleteSQL ($from, $where);
                    $loc_url=str_replace("com=delete","com=view",PAGE_URL);
                    $loc_url=str_replace("&id={$id}","",$loc_url);
                    header("Location: {$loc_url}");
                }
                return $c_cont;
        
    }

    function Navigate()
    {
       //$row=Subscribe::catSubscribeData();

        $tpl=new AdmTpl;
        //$tpl->assign('subscribe',$row);
        $art_navigate=$tpl->get('subscribe-navigate');

        return $art_navigate;
    }

    function catListData($type)
    {
        $select="";
        $from="`mdpls_subscribe`";
        $where["`active`"]=$type;
        $sortby="`id`";

        $query = "SELECT * FROM `{$from}";
        
        $res = mysql_query($query);
        while ($result = mysql_fetch_assoc ($res))
        {
            if($result['type'] == "1")
            {
                $query1 = "SELECT * FROM `mdpls_catalog` WHERE `parent`='{$result['id']}'";
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
        $from="mdpls_subscribe";
        $where["id"]=$id;

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, "", 1);

        return $row[0];
    }

    function artListData($type)
    {
        $select="";
        $from="mdpls_subscribe";
        $where["active"]= $type;
        $sortby="`name`";

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, $sortby,"0,70");

        return $row;
    }

    function artData($id)
    {
        $select="";
        $from="mdpls_catalog";
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

            $uploaddir = SITE_PATH."/img/cuisine/".$category_id."/";
            $imfile=$testName[0].".jpg";
            $smallimfile="small-".$imfile;
            @mkdir($uploaddir);
            $uploadfile = $uploaddir.$imfile;
            $smalluploadfile = $uploaddir.$smallimfile;


            //if (copy($_FILES['userfile']['tmp_name'], $uploadfile)){
	            
            GD::imageResize($uploadfile,$arr['tmp_name'],120,120,75);
            GD::imageResize($smalluploadfile,$arr['tmp_name'],120,120,75);
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