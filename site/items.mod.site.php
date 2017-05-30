<?    
 class Items{
      
    function viewItemsModPage()
    {
        foreach ($_REQUEST as $key=>$val)
        {
            // echo $key." - ".$val."<br />";
            $str="$".$key."=\$val;";
            eval($str);
        }

        $seodata=explode("/",$seolink);
        //$lang=$seodata[0];
        $mod=$seodata[0];  //echo $mod." - \$mod<br />";
        $rub=$seodata[1];  //echo $rub." - \$rub<br />";
        $item = $seodata[2]; //echo $item." - \$item<br />";
        $item_1 = $seodata[3]; //echo $item." - \$item<br />"; 

 /*       $iddata=explode("-",$seodata[1]);
        $id=$iddata[0];  //echo $id." - \$id<br />";
        $item=$iddata[1];  //echo $item." - \$item<br />";
*/        
        $itemdata=explode("-",$seodata[2]);
        if($itemdata[1] == "")
        {
            $item_id = ""; 
            $item=$itemdata[0]; // echo $item." - \$item<br />";
        }
        else
        {
            $item_id = $itemdata[0];;
            $item=$itemdata[1]; // echo $item." - \$item<br />";
        }
        
        
        $_SESSION['rub'] = $rub;
        
        if($rub)
        {
            if(!$item)
            {
                $category_id = Items::getCategoryId($rub);
                $page = Items::viewCategoryList($category_id);
            }
            else
            {   
                $category_id = Items::getSubCategoryId($item);
                $page = Items::viewItemsList($category_id);
            }
            if($item_id !== "")
            {
                $page = Items::viewItem($item_id);
            }
        }
//Sys::varDump($page);
        return $page;
    }

    function getCategoryId($rub)
    {
        $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="`items_category`";
        $where="`seolink`='{$rub}' AND `parent`='0'";
        $row = $db->origSelectSQL("", $from, $where, "", "1");
//Sys::varDump($row);
        $_SESSION['category_id'] = $row['0']['id'];
        return $row['0']['id']; 
    }
    
    function getSubCategoryId($rub)
    {
        $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="`items_category`";
        $where="`seolink`='{$rub}' AND `parent` = '".$_SESSION['category_id']."'";
        $row = $db->origSelectSQL("", $from, $where, "", "1");
//Sys::varDump($row);
        $_SESSION['sub_category_id'] = $row['0']['id'];
        return $row['0']['id']; 
    }
    
    function viewCategoryList($category_id)
    {
      
        
        $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="`items_category`";
        $where="`parent`='{$category_id}' AND `showing`='Y'";
        $row = $db->origSelectSQL("", $from, $where, "`caption`", "");
//Sys::varDump($row);
        $tpl_art->assign('list', $row);
        
        
        $page["content"]=$tpl_art->get('items_category_list');
        
        $page["meta"]["title"]="";
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
    
    function viewItemsList($id)
    {
         $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="`items`";
        $where="`showing`='Y' AND `category`='{$id}' ";
        $row = $db->origSelectSQL("", $from, $where, "`caption`", "");
//SYS::varDump($row);        
        $caption_page = Items::getCategory($row[0]['category_id']);
        
        $tpl_art->assign('list', $row);
        $tpl_art->assign('caption_page', $caption_page);
        
        $page["content"]=$tpl_art->get('items_list');
        
        //$page["meta"]["title"]=$caption_page;
//        $page["meta"]["description"]=$caption_page;
//        if($row !== 0)
//            {
//                $txt = new Text;
//                foreach($row as $k=>$v)
//                {
//                     $page["meta"]["keywords"] .= $txt->cut($v['meta_t_'.$_SESSION['lang']]).", ";
//                }    
//            }
        
        return $page; 
    }
    
    function viewItem($item_id)
    {
       
        $tpl_art = new SiteTpl;    


        
        $db =new mysql;
        $from="`items`";
        $where="`id`='{$item_id}'";
        $row = $db->origSelectSQL("", $from, $where, "", "1");
// Запись в БД (добавление 1 к счетчику просмотров)
$where = "";        
$where['id'] = $row['0']['id'];
$count  = $row['0']['counts'];
$arr_value['counts'] = $count+'1';
$db->updateSQL ("items", $arr_value, $where);
// /Запись в БД         
        $tpl_art->assign('item_data', $row[0]);
        
        $page["content"]=$tpl_art->get('item_single');
        
        $page["meta"]["title"]=$row[0]["meta_t"];
        $page["meta"]["keywords"]=$row[0]["meta_k"];
        $page["meta"]["description"]=$row[0]["meta_d"];

        
        return $page; 
    }
    
    
    function getCategory($category_id)
    {
        $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="`items_category`";
        $where="`id`='{$category_id}' AND 1=1";
        $row = $db->origSelectSQL("", $from, $where, "", "1");
//Sys::varDump($row);
        return $row[0]['caption_'.$_SESSION['lang']]; 
    }
    
    function viewItemsListAll()
    {
        //$_SESSION['ip'] = getenv("REMOTE_ADDR");
        $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="`items`";
        $where="`showing`='Y' AND 1=1";
        $row = $db->origSelectSQL("", $from, $where, "`caption`", "");
        
        $tpl_art->assign('list', $row);
        
        $page["content"]=$tpl_art->get('items_list_all');
        
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
    
    function getImageList($directory)
    {
        $dir = new DirectoryItems;
        $dir->dirItems($directory);

        foreach($dir->filearray as $img){
        $imageList[]="/{$directory}{$img}";
        }
        
        return $imageList;
    }
    
    
    
    function addToBD($rub)
    {
        $rub=urldecode($rub);
        //$rub = iconv('UTF-8', 'Windows-1251', $rub);
        
        $db =new mysql;
        $from="`items`";
        $where="`seolink`='{$rub}'";
        $row = $db->origSelectSQL("", $from, $where, "", "");
        
        
        $where = "";        
        $where['id'] = $row['0']['id'];
        $arr_value['counts'] = $row['0']['counts']+1;
        $db->updateSQL ("mr_health", $arr_value, $where);
        
        //return $rub;
        
        
    }
}







?>