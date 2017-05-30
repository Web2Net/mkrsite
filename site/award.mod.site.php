<?
class Award
{
     function viewAwardModPage()
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $key." - ".$val."<br />";
            $str="$".$key."=\$val;";
            eval($str);
        }

        $seodata = explode("/",$seolink);
        $mod = $seodata[0];  //echo $mod." - \$mod<br />";
        $rub = $seodata[1];  //echo $rub." - \$rub<br />";

        $iddata = explode("-",$seodata[2]);
        $id = $iddata[0];  // $id." - \$id<br />";

        $page = Award::viewAwardListAll('list',36,$page);
        if(isset($rub) && $rub !== "")
        {   
            $page = Award::viewAwardList($rub,36,$page);
        }
        if(isset($id) && $id !== "")
        {
            $page = Award::viewAward($id);
        }
        
       
        
          
//$page['meta']['faq_css'] = "<link rel='stylesheet' type='text/css' href='/site/design/faq.css' />\n";
//$page['meta']['faq_script'] = "\n";

        
    return $page;
}

    function viewAward($id)
    {
        //$_SESSION['ip'] = getenv("REMOTE_ADDR");
        $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="mr_award";
        $where="id={$id}";
        $row = $db->origSelectSQL("", $from, $where, "", 1);
        
        $tpl_art->assign('award', $row);

// Вывод маленьких фоток
$directory = "site/design/img/award/".$row[0]['id']."/small/";          
$smallPhotoList=Award::showSmallPhoto($directory);
// END  
$tpl_art->assign('smallPhotoList', $smallPhotoList);
         
        $page["content"]=$tpl_art->get('award');
        
        $page["meta"]["title"] = $row[0]["caption"];
        $page["meta"]["keywords"] = $row[0]["caption"];
        $page["meta"]["description"] = $row[0]["caption"];
        
        //var_dump($page);
        return $page; 
    }
    
    function viewAwardListAll($style='list',$hm=3,$page=1)
    {
      
        $tpl_art = new SiteTpl;    
            $start=0;
            $db =new mysql;
            $from="`mr_award`";
            $where="`enable`='1'";
            $memb_list = $db->origSelectSQL("", $from, $where, "`id` DESC",$start.",".$hm);

            

            $tpl_art->assign('rub',$category);
            $tpl_art->assign('list',$memb_list);
            $tpl_art->assign('title_page',"Награды автора и клиники");
            $art_data["content"]=$tpl_art->get('award_list');
            $art_data["meta"]["title"]="Награды автора и клиники";
            $art_data["meta"]["keywords"]="Награды автора и клиники";
            $art_data["meta"]["description"]="Награды автора и клиники";
            
            
//            }
            return $art_data;
    }
    
    function viewAwardList($type,$hm=3,$page=1)
    {
        $tpl_art = new SiteTpl;    
            $start=0;
            $db =new mysql;
            $from="`mr_award`";
            $where="`enable`='1' AND `type` = '{$type}'";
            $list = $db->origSelectSQL("", $from, $where, "`id` DESC",$start.",".$hm);

            $tpl_art->assign('rub',$category);
            $tpl_art->assign('list',$list);
           
            if($type == "author")
            {
                $art_data["meta"]["title"]="Награды автора";
                $tpl_art->assign('title_page',"Награды автора");    
            }
            if($type == "clinic")
            {
                $art_data["meta"]["title"]="Награды клиники";
                $tpl_art->assign('title_page',"Награды клиники");        
            }
            
            $art_data["content"]=$tpl_art->get('award_list');
            
            return $art_data;
    }
    
    function showSmallPhoto($directory)
    {
        $dir = new DirectoryItems;
        $dir->dirItems($directory);

        foreach($dir->filearray as $img){
        $smallPhotoList[]="/{$directory}{$img}";
        }
        return $smallPhotoList;
    }
}   
?>