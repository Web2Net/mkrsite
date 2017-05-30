<?php
class Apparatus 
{

    function admApparatus() 
    {
        $tpl = new Tpl;
        
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }
        
        $tpl->assign('mod_name',"Аппаратура");
        if($type == "phizeo"){$tpl->assign('type_name',"Физиотерапия");}
        if($type == "diagnoz"){$tpl->assign('type_name',"Диагност.оборудование");}
        
        
        if($ext=="ajax")
        {
            if(isset($_FILES["file"]) && trim($_FILES["file"]["name"])!= "")
            {    
                $mess=$_FILES["file"]["name"];
                $testName = explode( ".",$_FILES["file"]["name"] );
                $imgname=Text::cirilToLatin($testName[0]).".".$testName[1];
                $imgnamesmall=Text::cirilToLatin($testName[0])."-small.".$testName[1];
                $mess=Apparatus::loadImage($_FILES["file"]);
                if($mess=="OK")
                {
                    $imgdir = "/site/design/img/apparatus/";
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
                    $row=Apparatus::artListData($type, $order, $strim);
                    $tpl->assign('listing',$row);
                    $c_cont=$tpl->get("apparatus-list");	
                }
                if($com=="add")
                {
                    $c_cont=$tpl->get("apparatus-add-edit");	
                }
                if($com=="edit")
                {
	                $cat_data=Apparatus::catData($id);
                    $tpl->assign('cat_data',$cat_data);
                    $c_cont=$tpl->get("apparatus-add-edit");	
                }
                if($com=="update")
                {   
//$query="SELECT * FROM `mr_apparatus_category` WHERE `type`='{$_GET['type']}' LIMIT 1";
//$res = mysql_query($query);
//while($row = mysql_fetch_array($res)) 
//{
//   $seolink = trim($row['seolink']);   
//}

                                                 
                    foreach($_REQUEST as $key=>$val)
                    {
                        $var = explode("__",$key);
                        if($var['0'] == "form")
                        {
                            $arr_value[$var['1']] = $val;
                        }
                    }
//Sys::varDump($arr_value);
                    if($form__showing == "1")
                    {
                        $arr_value['showing']="Y"; 
                    }
                    else
                    {
                        $arr_value['showing']="N";
                    }
                   
                    
                    $nowdata=getdate(time());
                    //$arr_value['pos']=isset($pos)?$pos:$nowdata[0];

                    $table="mr_apparatus";

                    if($form__id!="")
                    {
	                    $where['id']=$form__id;
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
                    $loc_url=$loc_url."&category={$form__category_id}&id={$id}&upd=ok";

unset($_SESSION);
                    
                    header("Location: ".$loc_url."");

                }
                if($com=="delete")
                {
	                $from="mr_apparatus";	
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
        $row=Apparatus::catListData();

        $tpl=new AdmTpl;
        $tpl->assign('category',$row);
        $art_navigate=$tpl->get('apparatus-navigate');

        return $art_navigate;
    }

    function catListData($type=0)
    {
        $select="";
        $from="`mr_apparatus_category`";
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
        $from="mr_apparatus";
        $where["id"]=$id;

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, "", 1);

        return $row[0];
    }

    function artListData($category_id, $order, $strim)
    {
        $select="";
        $from="mr_apparatus";
        $where["type"]= $category_id;
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
        $from="mr_apparatus";
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
	            $error.="РџСЂРѕР±Р»РµРјР° СЃ Р·Р°РіСЂСѓР·РєРѕР№ С„Р°Р№Р»Р° {$name}<br>";

                switch($arr['error'])
                {
                    case 1: $error.="Р Р°Р·РјРµСЂ С„Р°Р№Р»Р° Р±РѕР»СЊС€Рµ РІРѕР·РјРѕР¶РЅРѕРіРѕ<br>"; break;
                    case 2: $error.="Р Р°Р·РјРµСЂ С„Р°Р№Р»Р° Р±РѕР»СЊС€Рµ РѕР±РѕР·РЅР°С‡РµРЅРЅРѕРіРѕ РІ С„РѕСЂРјРµ<br>"; break;
                    case 3: $error.="Р—Р°РіСЂСѓР¶РµРЅР° С‚РѕР»СЊРєРѕ С‡Р°СЃС‚СЊ С„Р°Р№Р»Р°<br>"; break;
                    case 4: $error.="Р¤Р°Р№Р» РЅРµ Р·Р°РіСЂСѓР¶РµРЅ<br>"; break;
                }
                return false;
            }
            $testName = explode( ".",$arr["name"] );
            $cur=array("jpg","bmp","gif","png");
            if($testName[1]!="jpg")
            {
                $mess="РџСЂРѕР±Р»РµРјР°: Р¤Р°Р№Р» ".$arr["name"]." РЅРµ СЂР°Р·СЂРµС€С‘РЅРЅС‹Р№ С‚РёРї (jpg, bmp, gif, png)";
                return $mess;
            }
                     
            if($arr['type']=="")	
            {
                $mess="РџСЂРѕР±Р»РµРјР°: Р¤Р°Р№Р» РЅРµ С„Р°Р№Р» РёР·РѕР±СЂР°Р¶РµРЅРёСЏ: ".$arr['type']."<br>";
                return $mess;
            }

            //$real=SITE_PATH."/product-image/".$prodid.".".$testName[1];

            $uploaddir = SITE_PATH."/site/design/img/apparatus/";
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