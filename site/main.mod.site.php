<?    
 class Main{
      
function viewMainModPage()
{
    $tpl = new SiteTpl;    
    foreach ($_REQUEST as $key=>$val)
    {
        $str="$".$key."=\$val;";
        eval($str);
    }
    
    //$main_news=Main::dataArtList("news","main",0,1);
    //$main_faq=Main::dataFaqList("faq","main",0,3);
    //$main_art=Main::dataArtList("articles","main",0,1);
    $main_page = Main::getMainPageVariant2();
    //$last_news=Main::dataArtList("новости","main",0,4);
    //$last_faq=Main::dataFaqList("faq","main",0,4);
    //$last_videogid=Main::dataArtList("videogid","main",0,6); //Sys::varDump($last_videogid);  
    //$demo_main=Main::dataDemoList("demo","main",0,3);
    //$last_art=Main::dataArtList("articles","main",0,3);

    //$block_main=Main::artBlock('Главная новость','mainnews','piclist',1);
    //$block_contra=Main::artBlock('Pro-Contra','pro-contra','piclist',1);
    
    //$tpl->assign('main_news',$main_news[0]);
    //$tpl->assign('main_faq',$main_faq[0]);
    //$tpl->assign('main_art',$main_art[0]);
    $tpl->assign('main_page',$main_page);
    //$tpl->assign('last_news',$last_news);
    //$tpl->assign('last_faq',$last_faq);
    //$tpl->assign('last_videogid',$last_videogid);
    //$tpl->assign('demo_main',$demo_main);
    //$tpl->assign('last_art',$last_art);

    $page["content"]=$tpl->get('page_main');
    return $page;
}

function getMainPage()
{
    $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="`page`";
        
        $where="`seolink`='Главная'";
        $row = $db->origSelectSQL("", $from, $where, "", "");

       // $tpl_art->assign('page', $row[0]);
        
        $page["meta"]["title"]=$row[0]["meta_t"];
        $page["meta"]["keywords"]=$row[0]["meta_k"];
        $page["meta"]["description"]=$row[0]["meta_d"];
        
        $page["content"] = $row[0]; 

        //Sys::varDump($page);
        return $page;
}
function getMainPageVariant2()
{
    $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="`art_article`";
        
        $where="`category_id`='1'";
        $row = $db->origSelectSQL("", $from, $where, "", "");

       // $tpl_art->assign('page', $row[0]);
        
        $page["meta"]["title"]=$row[0]["meta_t"];
        $page["meta"]["keywords"]=$row[0]["meta_k"];
        $page["meta"]["description"]=$row[0]["meta_d"];
        
        $page["content"] = $row[0]; 

        //Sys::varDump($page);
        return $page;
}

    function dataArtList($rub,$style,$start,$hm)
    {
        $tpl = new SiteTpl;

        $rub=urldecode($rub);
    //SYS::varDump($rub);    
        $from_cat="art_category";
        $where_cat="seo='{$rub}'";
        $db =new mysql;
        $row_cat = $db->origSelectSQL("", $from_cat, $where_cat, "pos DESC","0,1");
        $category=$row_cat[0];

        if($category!=NULL)
        {
            $from="art_article";
            $where="`enabled`='1' AND `category_id`='{$category['id']}'";
            $db=new mysql;
            $artlist = $db->origSelectSQL("", $from, $where, "`date_create` DESC",$start.",".$hm);

            //$arrCatSeo=Main::arrCatSeo();

            //if($artlist!=NULL){foreach($artlist as $key=>$val){
            //$artlist[$key]["cat_seo"]=$arrCatSeo[$val["category_id"]];
            //}
            //}
        }
        return $artlist;
    }

    function arrCatSeo()
    {
        $from_cat="art_category";
        $where_cat="1=1";
        $db =new mysql;
        $row_cat = $db->origSelectSQL("id,seo", $from_cat, $where_cat, "pos DESC");

        if($row_cat!=NULL)
        {
            foreach($row_cat as $key=>$val)
            {
                $arrCatSeo[$val["id"]]=$val["seo"];
            }
        }
        return $arrCatSeo;
    }

}
?>