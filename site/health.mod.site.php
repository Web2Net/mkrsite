<?    
 class Health{
      
    function viewHealthModPage()
    {
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

        if($rub && $rub !== "demo")
        {
            if(!$item)
            {
                $page = Health::viewHealthList($rub);
            }
            else
            {
                if($item == "demo")
                {
                    $page = Health::viewHealthDemo($itemdata[1]);    
                }
                else
                {
                     $page = Health::viewHealth($item);
                }
                
            }
        }
        elseif($rub == "demo")
        {
            $page = Health::viewDemoList();
        }
        else
        {
           $page = Health::viewCatList(); 
        }
//Sys::varDump($page);
        return $page;
    }

    function viewCatList()
    {
        //$_SESSION['ip'] = getenv("REMOTE_ADDR");
        $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="`mr_health_category`";
        $where="`showing`='Y' AND 1=1";
        $row = $db->origSelectSQL("", $from, $where, "`caption`", "");
//Sys::varDump($row);
        $tpl_art->assign('list', $row);
        
        $page["content"]=$tpl_art->get('health_category_list');
        
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
    
    function viewHealthListAll()
    {
        //$_SESSION['ip'] = getenv("REMOTE_ADDR");
        $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="`mr_health`";
        $where="`showing`='Y' AND 1=1";
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
    
    function viewHealthList($rub)
    {
        //$_SESSION['ip'] = getenv("REMOTE_ADDR");
        $tpl_art = new SiteTpl;    

        $rub=urldecode($rub);
        //$rub = iconv('UTF-8', 'Windows-1251', $rub);
        
        $db =new mysql;
        $from="`mr_health`";
        $where="`category_name`='{$rub}' AND `showing`='Y'";
        $row = $db->origSelectSQL("", $from, $where, "`caption`", "");
        
        $tpl_art->assign('list', $row);
        
        $page["content"]=$tpl_art->get('health_list');
        
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
    
    function viewHealth($rub)
    {
        //$_SESSION['ip'] = getenv("REMOTE_ADDR");
        $tpl_art = new SiteTpl;    

        $rub=urldecode($rub);
        //$rub = iconv('UTF-8', 'Windows-1251', $rub);
        
        $db =new mysql;
        $from="`mr_health`";
        $where="`seolink`='{$rub}'";
        $row = $db->origSelectSQL("", $from, $where, "", "1");
// Запись в БД (добавление 1 к счетчику просмотров)
$where = "";        
$where['id'] = $row['0']['id'];
$count  = $row['0']['counts'];
$arr_value['counts'] = $count+'1';
$db->updateSQL ("mr_health", $arr_value, $where);
// /Запись в БД         
        $tpl_art->assign('list', $row[0]);
        
        $page["content"]=$tpl_art->get('health');
        
        $page["meta"]["title"]=$row[0]["meta_t"];
        $page["meta"]["keywords"]=$row[0]["meta_k"];
        $page["meta"]["description"]=$row[0]["meta_d"];

        
        return $page; 
    }
    
    function viewHealthDemo($rub)
    {
        //$_SESSION['ip'] = getenv("REMOTE_ADDR");
        $tpl_art = new SiteTpl;    

        $rub=urldecode($rub);
        //$rub = iconv('UTF-8', 'Windows-1251', $rub);
        
        $db =new mysql;
        $from="`mr_health`";
        $where="`seolink`='{$rub}'";
        $row = $db->origSelectSQL("", $from, $where, "", "1");
// Запись в БД (добавление 1 к счетчику просмотров)
$where = "";        
$where['id'] = $row['0']['id'];
$count  = $row['0']['counts'];
$arr_value['counts'] = $count+'1';
$db->updateSQL ("mr_health", $arr_value, $where);
// /Запись в БД         
        $tpl_art->assign('list', $row[0]);
        
        $page["content"]=$tpl_art->get('health_demo');
        
        $page["meta"]["title"]=$row[0]["meta_t"];
        $page["meta"]["keywords"]=$row[0]["meta_k"];
        $page["meta"]["description"]=$row[0]["meta_d"];

        
        return $page; 
    }

    function addToBD($rub)
    {
        $rub=urldecode($rub);
        //$rub = iconv('UTF-8', 'Windows-1251', $rub);
        
        $db =new mysql;
        $from="`mr_health`";
        $where="`seolink`='{$rub}'";
        $row = $db->origSelectSQL("", $from, $where, "", "");
        
        
        $where = "";        
        $where['id'] = $row['0']['id'];
        $arr_value['counts'] = $row['0']['counts']+1;
        $db->updateSQL ("mr_health", $arr_value, $where);
        
        //return $rub;
        
        
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
        $where="`showing`='Y' AND `demo_enable` = '1'";
        $health_item_list = $db->origSelectSQL("category,seolink,caption,demo_price", $from, $where, "`caption`");
        
        return $health_item_list;
    }
    
    function viewDemoList()
    {
        $tpl = new SiteTpl;
            
        $health_cat_list = Health::getHealthCat();
        $health_item_list = Health::getHealthItem();  
        $tpl->assign('health_cat_list',$health_cat_list);
        $tpl->assign('health_item_list',$health_item_list);
        
        $page["content"]=$tpl->get('health_demo_list');
        
        if($health_cat_list !== 0)
        {
            $txt = new Text;
            foreach($health_cat_list as $k=>$v)
            {
                 $page["meta"]["keywords"] .= $txt->cut($v['caption']).", ";
                 $page["meta"]["description"] .= $txt->cut($v['caption']).", ";
            }    
        }
        
        return $page;
    }
    
}







?>