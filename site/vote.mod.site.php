<?	
 class Vote{
 	 
    function viewVoteModPage()
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

        $iddata=explode("-",$seodata[1]);
        $id=$iddata[0];  //echo $id." - \$id<br />";
         
        if($rub == "archives")
        {
            $page = Vote::viewList($rub,'list',30,$page);
        }
        else
        {
            //$page = Vote::showingVoteButton($id_vote);
            
            if(is_numeric($id))
            {
                $page = Vote::viewVote($id);
            }
            else
            {
                $page = Vote::viewList($rub,'list',30,$page);    
            }
        }
        if(isset($submit))
        {   
            $page = Vote::clickVote($id_vote, $variant);
            header('Location:'.PAGE_URL.'');  
        }      
        return $page;
    }

    function viewLastVote()
    {
        $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="mr_otzivi";
        $where="`showing`='Y'";
        $row = $db->origSelectSQL("", $from, $where, "`date` DESC", "");
        
        $tpl_art->assign('vote_list', $row[0]);
        $page["content"]=$tpl_art->get('vote_list');
        $page["meta"]["title"]="";
        $page["meta"]["description"]="Отзывы больных о лечении в клинике доктора Василевича";
        $page["meta"]["keywords"]="Отзывы больных о лечении в клинике доктора Василевича";
        
        
        return $page;
    }
    
	function viewVote($id)
    {
        $tpl_art = new SiteTpl;	

        $db =new mysql;
        $from="mr_otzivi";
        $where="id={$id}";
        $row = $db->origSelectSQL("", $from, $where, "date DESC", "1");
        
        $tpl_art->assign('vote', $row[0]);
        $page["content"]=$tpl_art->get('vote');
        $page["meta"]["title"]="{$row[0]["var1_content"]} или {$row[0]["var2_content"]}";
        $keywords1 = explode(" ",$row[0]["var1_content"]); 
        $keywords2 = explode(" ",$row[0]["var2_content"]);  
        $keywords = array_merge($keywords1,$keywords2);    
        foreach($keywords as $val)
        {
           $page["meta"]["keywords"] .= "{$val}, ";
        }
        
        $page["meta"]["description"]="Отзывы больных о лечении в клинике доктора Василевича";
        return $page; 
    }

    function viewList($category,$style='list',$hm=3,$page=1)
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }
        $seodata=explode("/",$seolink);
        $mod=$seodata[0];
        $rub=$seodata[1];
        
        $tpl_art = new SiteTpl; 
           
       // if($category==0)
//        {
            $page==1;$start=0;//else{$start=($page-1)*$hm;}
            $db =new mysql;
            $from="`mr_otzivi`";
            $where="`showing`='Y'";
            $vote_list = $db->origSelectSQL("", $from, $where, "`id` DESC",$start.",".$hm);
            $count_memb = $db->origSelectSQL("COUNT(*)", $from, $where, "`date` DESC");
            
            $tpl_art->assign('rub',$rub);
            $tpl_art->assign('mod',$mod);            
            $tpl_art->assign('vote_list',$vote_list);
            $tpl_art->assign('count_m',$count_memb[0]["COUNT(*)"]);
            $tpl_art->assign('page',$page);              
            $tpl_art->assign('pages',ceil($count_memb[0]["COUNT(*)"]/$hm));                        
            $art_data["content"]=$tpl_art->get('vote_list');
            $art_data["meta"]["title"]="Отзывы больных о лечении в клинике доктора Василевича";
            $art_data["meta"]["description"]="Отзывы больных о лечении в клинике доктора Василевича";
            $art_data["meta"]["keywords"]="Отзывы больных о лечении в клинике доктора Василевича";
//            }
            return $art_data;
    }
    
    function showingVoteButton($id_vote)
    {
        $db = new mysql;
        $table = "`mdpls_ip_vote`"; 
        if(!$db->checkUniqRow($table, "ip", getenv("REMOTE_ADDR")))
        {
            $query = "SELECT `date` FROM {$table} WHERE `ip` = '".getenv("REMOTE_ADDR")."'";
            if($res = mysql_query($query))
            {
                $res = mysql_fetch_row($res);
                foreach($res as $v)
                {   
                    $hours_now = mktime()/3600;
                    $hours_in_sys = $v/3600;
                    $t = round($hours_now - $hours_in_sys);
                    if($t >= 24)
                    {    
                         $_SESSION['butt_vote'] = "N";
                         //Vote::clickVote($id_vote, $variant);
                    }
                }
             }
        }
        else
        {
            $_SESSION['butt_vote'] = "Y";
            //Vote::clickVote($id_vote, $variant);
        }
    }
    
    function clickVote($id_vote, $variant)
    {   
        $db =new mysql;
        $from="mr_otzivi";
        $where="id={$id_vote}";
        $row = $db->origSelectSQL("", $from, $where, "", "1");
        
        $page=$row[0];
        
        if($variant == "1")
        {  
            $vote = $page['var1_vote'];
            $vote_field = "var1_vote";
        }
        else
        {   
            $vote = $page['var2_vote'];
            $vote_field = "var2_vote";
        }      
        $vote = $vote + 1;        // Кол-во голосов + 1
            
         $query = "UPDATE `mr_otzivi` 
                     SET `{$vote_field}`= {$vote}
                     WHERE `id`='{$id_vote}'";
           $ok = mysql_query($query);
           
          $query = "INSERT INTO `mdpls_ip_vote` (`id`, `ip`, `date`) 
                     VALUES ('','".getenv("REMOTE_ADDR")."', '".mktime()."')";
           $ok1 = mysql_query($query);
           $_SESSION['butt_vote'] = "N";
           return $page;
           
    }
    
    

}
?>