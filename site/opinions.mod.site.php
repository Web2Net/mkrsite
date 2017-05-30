<?    
class Opinions
{
    function viewOpinionsModPage()
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

$rub=urldecode($rub);
        
        $iddata=explode("-",$seodata[1]);
        $id=$iddata[0];  //echo $id." - \$id<br />";

        $page = Opinions::viewList($rub,'list',36,$page);
//var_dump($page);        

        $tpl_art = new SiteTpl;    
        $tpl_art->assign('page', $page);
        $page["content"] = $tpl_art->get('opinions');  
        
        return $page;
    }

     
    function viewList($category,$style='list',$hm=3,$page=1)
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }

        $tpl_art = new SiteTpl;    

            $start=0;
            $db =new mysql;
            $from="`mr_opinions`";
            $where="`showing`='Y'";
            $order = "`date` DESC";
            $limit = "6";
            $memb_list = $db->origSelectSQL("", $from, $where, $order, $limit);

            $tpl_art->assign('rub',$category);
            $tpl_art->assign('list',$memb_list);
            $page["content"]=$tpl_art->get('opinions');
            
        
            //var_dump($memb_list);
            
            return $page;
    }
    
    

}
?>