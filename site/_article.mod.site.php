<?    
 class Article{
      
    function viewArticleModPage()
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }

        $seodata=explode("/",$seolink);
        $rub=$seodata[0];   

$rub=urldecode($rub);
        
        $_SESSION['rub'] = $rub;
        

        $iddata=explode("-",$seodata[1]);
        $id=$iddata[0];

        if($id!=""&&$id!="page")
        {    
            $art_html = Article::viewArticle($id);
        }
        else
        {
            if($rub == "Продажа" || $rub == "Услуги"){$limit = "100";}else{$limit="5";}
            if($id=="page")
            {
                $page=$iddata[1];
            }
            if($id=="")
            {
                $page=1;
            } 
            if(($id==""||$id=="page")&&$rub!="")
            {  
                
                $art_html = Article::viewList($rub,'list',$limit,$page);    
            }
            else
            {
                $art_html = Article::viewList("mainart",'list',$limit,$page);    
            }
        }
        return $art_html;     
     }
     
     
    function viewArticle($id){

    $tpl_art = new SiteTpl;    

    $db =new mysql;
    $from="art_article";
    $where="id={$id}";
    $row = $db->origSelectSQL("", $from, $where, "date_create DESC");

    $arr_value['see']=$row[0]["see"]+1;
    $table="art_article";
    $whereup['id']=$row[0]["id"];
    $db = new mysql;
    $res = $db->updateSQL ($table, $arr_value, $whereup);

    $row[0]["see"]=$arr_value['see'];
    $tpl_art->assign('article', $row[0]);

    $db =new mysql;
    $from_cat="mr_nav";
    $where_cat="id='".$row[0]["category_id"]."'";
    $row_cat = $db->origSelectSQL("", $from_cat, $where_cat, "date DESC");
    $tpl_art->assign('article_cat', $row_cat[0]);
    echo $rub;
    if($_SESSION['rub'] == "Услуги" || $_SESSION['rub'] == "Продажа")
    {
         $art_html["content"]=$tpl_art->get('service_sale_single');
    }
    else
    {
        $art_html["content"]=$tpl_art->get('art_article');
    }

    $art_html["meta"]["title"]=$row[0]["meta_title"];
    $art_html["meta"]["keywords"]=$row[0]["meta_keywords"];
    $art_html["meta"]["description"]=$row[0]["meta_description"];

    return $art_html;

    }


    function viewList($category,$style='list',$hm=3,$page=1)
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }

        $seodata=explode("/",$seolink);
        $rub=$seodata[0];

        //if($rub=="Услуги"){$rub="service";}   
        
        $tpl_art = new SiteTpl;    

        if($category=="mainart")
        {
            if($page==1)
            {
                $start=0;
            }
            else
            {
                $start=($page-1)*$hm;
            }

            $db=new mysql;
            $from="art_article";
            $where="enabled=1";
            $artlist = $db->origSelectSQL("", $from, $where, "date_create DESC",$start.",".$hm);
            $count_artlist = $db->origSelectSQL("COUNT(*)", $from, $where, "date_create DESC");

        }
        else
        {
            $db =new mysql;
            $from_cat="mr_nav";
            $where_cat="seolink='{$rub}'";
            $rowcat = $db->origSelectSQL("", $from_cat, $where_cat, "date DESC");
                
            if($page==1)
            {
                $start=0;
            }
            else
            {
                $start=($page-1)*$hm;
            }

            $db =new mysql;
            $from="art_article";
            $where="category_id='{$rowcat[0]['id']}' AND enabled=1";
            
            if($rub == "Продажа"){$sort = "pos";}
            else{$sort = "date_create DESC";}
            
            $artlist = $db->origSelectSQL("", $from, $where, $sort,$start.",".$hm);
            $count_artlist = $db->origSelectSQL("COUNT(*)", $from, $where, "date_create DESC");
        }
        if($artlist!=NULL)
        {
            foreach($artlist as $key=>$val)
            {
                $db =new mysql;
                $from_cat="mr_nav";
                $where_cat="id={$val['category_id']}";
                $row_cat = $db->origSelectSQL("seolink", $from_cat, $where_cat, "date DESC","0,1");
                $artlist[$key]['article_cat']=$row_cat[0]["seolink"];
            }
        }

        $tpl_art->assign('rub',$category);
        $tpl_art->assign('mod',$rowcat[0]['seo']);
        $tpl_art->assign('list',$artlist);
        $tpl_art->assign('count_b',$count_artlist[0]["COUNT(*)"]);
        $tpl_art->assign('page',$page);              
        $tpl_art->assign('pages',ceil($count_artlist[0]["COUNT(*)"]/$hm));   

        if($rub == "Услуги" || $rub == "Продажа")
        {
             $art_data["content"]=$tpl_art->get('service_sale_list');
        }
        else
        {
             $art_data["content"]=$tpl_art->get('art_list');
        }
        
        
        $art_data["meta"]["title"] = SITE_NAME;
        $art_data["meta"]["description"] = SITE_NAME;
        $art_data["meta"]["keywords"] = SITE_NAME;
        
        return $art_data;
    }

    function catList()
    {
        $from_cat="mr_nav";
        $where_cat="enabled=1";
        $db =new mysql;
        $row_cat = $db->origSelectSQL("", $from_cat, $where_cat, "pos DESC");

        return $row_cat;
    }
}
?>