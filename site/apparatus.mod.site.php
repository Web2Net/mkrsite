<?
class Apparatus
{
 	function viewApparatusModPage()
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
        $id = $iddata[0];   //echo $id." - \$id<br />";

        $page = Apparatus::viewApparatusListAll('list',36,$page);
        if(isset($rub) && $rub !== "")
        {   
            $page = Apparatus::viewApparatusList($rub,36,$page);
        }
        if(isset($id) && $id !== "")
        {
            $page = Apparatus::viewApparatus($id);
        }
        
       
        
          
//$page['meta']['faq_css'] = "<link rel='stylesheet' type='text/css' href='/site/design/faq.css' />\n";
//$page['meta']['faq_script'] = "\n";

        
    return $page;
}

	function viewApparatus($id)
    {
        //$_SESSION['ip'] = getenv("REMOTE_ADDR");
        $tpl_art = new SiteTpl;	

        $db =new mysql;
        $from="mr_apparatus";
        $where="id={$id}";
        $row = $db->origSelectSQL("", $from, $where, "", 1);
        
        $tpl_art->assign('apparatus', $row);

// Р’С‹РІРѕРґ РјР°Р»РµРЅСЊРєРёС… С„РѕС‚РѕРє
//$directory = "site/design/img/apparatus/small/";          
//$smallPhotoList=Apparatus::showSmallPhoto($directory);
// END  
//$tpl_art->assign('smallPhotoList', $smallPhotoList);
         
        $page["content"]=$tpl_art->get('apparatus');
        
        $page["meta"]["title"] = $row[0]["meta_t"];
        $page["meta"]["keywords"] = $row[0]["meta_k"];
        $page["meta"]["description"] = $row[0]["meta_d"];
        
        //Sys::varDump($page);
        return $page; 
    }
    
    function viewApparatusListAll($style='list',$hm=3,$page=1)
    {
      
        $tpl_art = new SiteTpl;    
            $start=0;
            $db =new mysql;
            $from="`mr_apparatus`";
            $where="`showing`='Y'";
            $memb_list = $db->origSelectSQL("", $from, $where, "`id`",$start.",".$hm);

            

            $tpl_art->assign('rub',$category);
            $tpl_art->assign('list',$memb_list);
            $tpl_art->assign('title_page',"Аппаратура клиники");
            $art_data["content"]=$tpl_art->get('apparatus_list');
            $art_data["meta"]["title"]="Аппаратура клиники доктора Василевича";
            $art_data["meta"]["keywords"]="Аппаратура клиники доктора Василевича";
            $art_data["meta"]["description"]="Аппаратура клиники доктора Василевича";
            
            
//            }
            return $art_data;
    }
    
    function viewApparatusList($type,$hm=3,$page=1)
    {
        $tpl_art = new SiteTpl;    
            $start=0;
            $db =new mysql;
            $from="`mr_apparatus`";
            $where="`showing`='Y' AND `type` = '{$type}' AND `category_id` > 0";
            $list = $db->origSelectSQL("", $from, $where, "`id`",$start.",".$hm);

            $tpl_art->assign('rub',$category);
            $tpl_art->assign('list',$list);
           
            if($type == "diagnoz")
            {
                $art_data["meta"]["title"]="Диагностическое оборудование клиники";
                $tpl_art->assign('title_page',"Диагностическое оборудование клиники");    
            }
            if($type == "phizeo")
            {
                $art_data["meta"]["title"]="Аппаратная физиотерапия клиники";
                $tpl_art->assign('title_page',"Аппаратная физиотерапия клиники");        
            }
            
            $art_data["content"]=$tpl_art->get('apparatus_list');
            
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