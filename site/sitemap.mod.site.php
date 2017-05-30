<?	
 class Sitemap{
 	 
    function viewSitemapModPage()
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }

        $seodata=explode("/",$seolink);
        $rub=$seodata[0];    $_SESSION['rub'] = $seodata[0];
        $item=$seodata[1];

        $iddata=explode("-",$seodata[1]);
        $id=$iddata[0];
       
        $tpl = new SiteTpl;
       
        $page_list = Sitemap::getPage();
        //$health_cat_list = Sitemap::getHealthCat();
        //$health_item_list = Sitemap::getHealthItem();
        //$faq_cat_list = Sitemap::getFaqCat();
        //$apparatus_item_list = Sitemap::getApparatusItem();
        
        $tpl->assign('page_list',$page_list);
        //$tpl->assign('health_cat_list',$health_cat_list);
        //$tpl->assign('health_item_list',$health_item_list);
        //$tpl->assign('faq_cat_list',$faq_cat_list);
        //$tpl->assign('apparatus_item_list',$apparatus_item_list);
        //$tpl->assign('faq_item_list',$faq_item_list);
        
        $page['meta']['title'] = "Карта сайта";
        $page['meta']['description'] = "Карта сайта";
        $page['meta']['keywords'] = "Карта сайта";
        
        $page["content"]=$tpl->get('sitemap');
        return $page;	 
     }
     
     
    function getPage()
    {
        $db =new mysql;
        $from="mr_nav";
        $where="`showing`='Y'";
        $page_list = $db->origSelectSQL("seolink,type,caption", $from, $where, "`caption`");
        
        return $page_list;
    }

    function getHealthCat()
    {
        $db =new mysql;
        $from="mr_health_category";
        $where="`showing`='Y'";
        $health_cat_list = $db->origSelectSQL("id,seolink,caption", $from, $where, "`caption`");
        
        return $health_cat_list;
    }

    function getHealthItem()
    {
        $db =new mysql;
        $from="mr_health";
        $where="`showing`='Y'";
        $health_item_list = $db->origSelectSQL("category,seolink,caption", $from, $where, "`caption`");
        
        return $health_item_list;
    }
    
    function getFaqCat()
    {
        $db =new mysql;
        $from="mr_faq_category";
        $where="`showing`='Y'";
        $faq_cat_list = $db->origSelectSQL("id,seolink,caption", $from, $where, "`caption`");
        
        return $faq_cat_list;  
    }

    function getFaqItem()
    {
        $db =new mysql;
        $from="mr_faq";
        $where="`showing`='Y'";
        $faq_item_list = $db->origSelectSQL("category_id,name,city", $from, $where, "`date` DESC");
        
        return $faq_item_list;
    }
    
    function getApparatusItem()
    {
        $db =new mysql;
        $from="mr_apparatus";
        $where="`showing`='Y'";
        $page_list = $db->origSelectSQL("id,type,caption_short", $from, $where, "`caption_short`");
        
        return $page_list;    
    }

}
?>