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
//                $art_html = Article::viewList("mainart",'list',$limit,$page);    // Если в logic.php - $page_data=Main::viewMainModPage();
                $art_html = Article::viewList("mainart",'list',$limit,$page);      // Если в logic.php - $page_data=Article::viewArticleModPage(); 
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
    $from_cat="nav";
    $where_cat="id='".$row[0]["category_id"]."'";
    $row_cat = $db->origSelectSQL("", $from_cat, $where_cat, "date_create DESC");
    $tpl_art->assign('article_cat', $row_cat[0]);
    echo $rub;
    if($_SESSION['rub'] == "Услуги" || $_SESSION['rub'] == "Продажа")
    {
         $art_html["content"]=$tpl_art->get('service_sale_single');
    }
    elseif($_SESSION['rub'] == "Производители" || $_SESSION['rub'] == "Портфолио_сайты")
    {
         $art_html["content"]=$tpl_art->get('brand_single');
    }
    elseif($_SESSION['rub'] == "Офис_под_ключ")
    {
         $art_html["content"]=$tpl_art->get('brand_single');
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
            $where="`category_id`='1' AND `enabled`='1'";
            $artlist = $db->origSelectSQL("", $from, $where, "date_create DESC",$start.",".$hm);
            $count_artlist = $db->origSelectSQL("COUNT(*)", $from, $where, "date_create DESC");

        }
        else
        {
            $db =new mysql;
            $from_cat="nav";
            $where_cat="seolink='{$rub}'";
            $rowcat = $db->origSelectSQL("", $from_cat, $where_cat, "date_create DESC");
                
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
           
            if($rub == "Новости" || $rub == "Полезности" || $rub == "Статьи"){$sort = "date_create DESC";}
            elseif($rub == "Производители"){$sort = "caption"; $hm="999";}
            else{$sort = "pos";}
            
            $artlist = $db->origSelectSQL("", $from, $where, $sort,$start.",".$hm);
            $count_artlist = $db->origSelectSQL("COUNT(*)", $from, $where, "date_create DESC");
        }
        if($artlist!=NULL)
        {
            foreach($artlist as $key=>$val)
            {
                $db =new mysql;
                $from_cat="nav";
                $where_cat="id={$val['category_id']}";
                $row_cat = $db->origSelectSQL("seolink", $from_cat, $where_cat, "date_create DESC","0,1");
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
        elseif($_SESSION['rub'] == "Производители")
        {
             $art_data["content"]=$tpl_art->get('brand_list');
        }
        elseif($_SESSION['rub'] == "Портфолио_сайты")
        {
             $art_data["content"]=$tpl_art->get('portfolio_list');
        }
        elseif($_SESSION['rub'] == "Офис_под_ключ")
        {
             $art_data["content"]=$tpl_art->get('portfolio_list');
        }
        elseif($category=="mainart") // Главная
        {
             $art_data["content"]=$tpl_art->get('art_list_main_page');
        }
        else
        {
             $art_data["content"]=$tpl_art->get('art_list');
        }
// Получение meta        
        foreach($artlist as $k=>$v)
        {
            $m_k .= $v['caption']." ";
            $m_d .= $v['meta_description'].". ";
        }
        $m_k_arr = explode(" ",$m_k);
        $m_k_arr = array_unique($m_k_arr);
        foreach($m_k_arr as $k=>$v)
        {
             if(strlen($v)<='3'){unset($v);}
             $m_k_arr .= $v.", ";
        }
        //$m_k = implode(",",$m_k_arr);
        $m_k = TEXT::cutStringForMetaKey($m_k_arr);
        //$m_k = TEXT::parseHtml($m_k);
        $m_d = TEXT::cutStringForMetaKey($m_d);
        $m_d = TEXT::cutProbels($m_d);
// /Получение meta
        
        if($_SESSION['rub'] == "Портфолио_сайты")
        {
            $art_data["meta"]["title"] = "Создание сайтов. Портфолио.";
        }
        elseif($_SESSION['rub'] == "Новости")
        {
            $art_data["meta"]["title"] = "Новости";
        }
        elseif($_SESSION['rub'] == "Полезности")
        {
            $art_data["meta"]["title"] = "Полезности. Это может быть полезно";
        }
        else
        {
            $art_data["meta"]["title"] = SITE_NAME;
        }
        $art_data["meta"]["description"] = $m_d;
        $art_data["meta"]["keywords"] = $m_k;
        
        return $art_data;
    }

    function catList()
    {
        $from_cat="nav";
        $where_cat="enabled=1";
        $db =new mysql;
        $row_cat = $db->origSelectSQL("", $from_cat, $where_cat, "pos DESC");

        return $row_cat;
    }
}
?>