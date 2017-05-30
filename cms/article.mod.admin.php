<?php
class Article 
{
    function admArticle() 
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }
        $tpl=new AdmTpl;
        $tpl->assign('mod_name',"Статьи");

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
	                $imgdir = "/site/design/img/art/";
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
            if($type=="category")
            {    
                if($com=="view")
                {
	                if($event!="")
                    {
                        Article::Event($event,'art_category',$id);
                    }
                    $row=Article::catListData();
                    $tpl->assign('listing',$row);
                    $c_cont=$tpl->get("category-list");	
                    
                }
                if($com=="add")
                {
                    $c_cont=$tpl->get("category-add-edit");	
                }
                if($com=="edit")
                {
	                $cat_data=Article::catData($id);
                    $tpl->assign('cat_data',$cat_data);
                    $c_cont=$tpl->get("category-add-edit");	
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
	                $from="art_category";	
                    $where['id']=$id;	
                    $db = new mysql;
                    $res = $db->deleteSQL ($from, $where);
                    $loc_url=str_replace("com=delete","com=view",PAGE_URL);
                    $loc_url=str_replace("&id=".$id."","",$loc_url);
                    header("Location: ".$loc_url."");
                }
            }
            if($type=="article")
            {
	            if($com=="view")
                {
                    $row=Article::artListData($category_id);
                    $parent_data=Article::catData($category_id);

                    $tpl->assign('parent_data',$parent_data);
                    $tpl->assign('artlist',$row);
                    $c_cont=$tpl->get("article-list");
                }	
                if($com=="add")
                {
	                $parent_list=Article::catListData();
                    $tpl->assign('parent_list',$parent_list);

                    $c_cont=$tpl->get("article-add-edit");	
                }
                if($com=="edit")
                {
	                $art_data=Article::artData($id);
                    $tpl->assign('art_data',$art_data);	
	                    
                    $parent_list=Article::catListData();
                    $tpl->assign('parent_list',$parent_list);

                    $parent_data=Article::catData($category_id);
                    $tpl->assign('parent_data',$parent_data);

                    $c_cont=$tpl->get("article-add-edit");	
                }
                if($com=="update")
                {
                    
                    $arr_value['category_id']=$category_id;
                    $arr_value['type']=$rubrica; 
                    $arr_value['date_create']=isset($date_create) && $date_create!=""?$date_create:date("Y-m-d H:i:s");
                    
                    $web_link = Text::cutForCirilicSeolink($caption);
                   
                    $arr_value['web_link']=Text::cirilToCirilSeolink($web_link);

                    $arr_value['source']=$source;
                    
                    //$arr_value['web_link']=str_replace(" ","_",$web_link);
                    
                    $arr_value['ilike']=$ilike;
                    $arr_value['caption']=str_replace('"','&quot;',$caption);
                    $arr_value['short_text']=str_replace('"','&quot;',$short_text);
                    $arr_value['full_text']=$full_text;
                    $arr_value['video']=$video;
                    $arr_value['author_name']=$_SESSION['username'];
                    $arr_value['text_autor']=$text_autor;
                    $arr_value['foto_autor']=$foto_autor;
                    $arr_value['image']=$image;
                    $arr_value['image_small']=$image_small;
                    if($gallery_id!=0){$arr_value['gallery_id']=$gallery_id;}
                    $arr_value['smi']=$smi;
                    if($category_id!=5){
                    $arr_value['seo']=Text::cirilToLatin($arr_value['caption']);
                    }
                    $arr_value['meta_title']=$meta_title!=''?str_replace('"','&quot;',$meta_title):$arr_value['caption'];
                    $arr_value['meta_description']=$meta_description!=''?str_replace('"','&quot;',$meta_description):$arr_value['short_text'];
                    $arr_value['meta_keywords']=$meta_keywords;
                    //$arr_value['see']=$see;
                    $arr_value['enabled']=$enabled;
                    $arr_value['show_on_main']=$show_on_main;
                    $arr_value['date_create']=$date_create;
                    $arr_value['date_change']=date("Y-m-d H:i");
                    $arr_value['pos']=$pos;
                    $arr_value['source_id']=$source_id;

                    $table="art_article";
//SYS::varDump($arr_value);
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
                    $loc_url=$loc_url."&category_id=".$category_id."&id=".$id."&seo={$arr_value['type']}&upd=ok";

                    header("Location: ".$loc_url."");

                }
                if($com=="delete")
                {
	                $from="art_article";	
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

    function Navigate()
    {
	    $row=Article::catListData();

        $tpl=new AdmTpl;
        $tpl->assign('category',$row);
        $art_navigate=$tpl->get('article-navigate');

        return $art_navigate;
    }

    function catListData($parent=0)
    {
	    $select="";
        $from="nav";
        //$where["parent_id"]=$parent;
        $where = "`parent_id` = '0' AND `type` = 'category'";
        $sortby="`caption`";

        $db = new mysql;
        $row = $db->origSelectSQL($select, $from, $where, $sortby);
//SYS::varDump($row);
        return $row;
    }

    function catData($id)
    {
	    $select="";
        $from="nav";
        $where["id"]=$id;

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, "", 1);

        return $row[0];
    }

    function artListData($category_id)
    {
	    $select="";
        $from="art_article";
        $where["category_id"]=$category_id;
        $sortby="date_create DESC";

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, $sortby,"0,70000");

        return $row;
    }
    
    function artData($id)
    {
	    $select="";
        $from="art_article";
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
	    
    $error.="РџСЂРѕР±Р»РµРјР° СЃ Р·Р°РіСЂСѓР·РєРѕР№ С„Р°Р№Р»Р° $name<br>";

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
    if($testName[1]!="jpg"){
    $mess="РџСЂРѕР±Р»РµРјР°: Р¤Р°Р№Р» ".$arr["name"]." РЅРµ СЂР°Р·СЂРµС€С‘РЅРЅС‹Р№ С‚РёРї (jpg, bmp, gif, png)";
    return $mess;
    }


    if($arr['type']=="")	{
            $mess="РџСЂРѕР±Р»РµРјР°: Р¤Р°Р№Р» РЅРµ С„Р°Р№Р» РёР·РѕР±СЂР°Р¶РµРЅРёСЏ: ".$arr['type']."<br>";
            return $mess;
            }

    //$real=SITE_PATH."/product-image/".$prodid.".".$testName[1];

    $uploaddir = SITE_PATH."/site/design/img/art/";
    $imfile=Text::cirilToLatin($testName[0]).".jpg";
    $smallimfile="small-".$imfile;
    @mkdir($uploaddir);
    $uploadfile = $uploaddir.$imfile;
    $smalluploadfile = $uploaddir.$smallimfile;


    //if (copy($arr['tmp_name'], $uploadfile)){
	    
    GD::imageResize($uploadfile,$arr['tmp_name'],300,200,85);
    GD::imageResize($smalluploadfile,$arr['tmp_name'],150,100,85);
    //var_dump($arr);

    return "OK";
    //}
    }


    function Event($event,$table,$id)
    {
        $from=$table;
        $where="id={$id}";
        $db = new mysql;
        $row = $db->origSelectSQL("id,pos", $from, $where,"pos ASC","0,1");

        $pos_start=$row[0]["pos"];
        $id_start=$row[0]["id"];
        if($event=='moveDown')
        {
            $from2=$table;	
            $where2="pos<{$pos_start}";
            $db = new mysql;
            $row2 = $db->origSelectSQL("id,pos", $from2, $where2,"pos DESC","0,1");
            $pos_end=$row2[0]["pos"];
            $id_end=$row2[0]["id"];
        }
        if($event=='moveUp')
        {
            $from2=$table;	
            $where2="pos>{$pos_start}";
            $db = new mysql;
            $row2 = $db->origSelectSQL("id,pos", $from2, $where2,"pos ASC","0,1");
            $pos_end=$row2[0]["pos"];
            $id_end=$row2[0]["id"];
        }
	    if($pos_end!="")
        {
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
