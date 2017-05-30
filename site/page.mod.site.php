<?    
class Page
{
    function viewPageModPage()
    {
        foreach ($_REQUEST as $key=>$val)
        {
//  echo $key." - ".$val."<br />";
            $str="$".$key."=\$val;";
            eval($str);
        }
        $seodata=explode("/",$seolink);
        $mod=$seodata[0];  //echo $mod." - \$mod<br />";
        $rub=$seodata[1];  //echo $rub." - \$rub<br />";
        
        $iddata=explode("-",$seodata[1]);
        $id=$iddata[0];  //echo $id." - \$id<br />";
        
        $mod=urldecode($mod);
        $rub=urldecode($rub);
             
        $page = Page::viewPage($mod);
//Sys::varDump($page['content_page']);       
                           
        $tpl_art = new SiteTpl;
        $tpl_art->assign('mod', $mod);    
        $tpl_art->assign('page', $page);
       
        $page["content"] = $tpl_art->get('page');
        
        return $page;
    }

    function viewPage($mod)
    {
//запрет на использование правой кнопки мыши 
$some_meta = "<script type='text/javascript'>
<!--
function click() {
if (event.button==2) {
alert(\"Правая кнопка мыши запрещена\");
}
}
document.onmousedown=click
// -->
</script>";
// /   
       
        $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="`page`";
        
        $where="`seolink`='{$mod}'";
        $row = $db->origSelectSQL("", $from, $where, "", "");

       // $tpl_art->assign('page', $row[0]);
        
        $page["meta"]["title"]=$row[0]["meta_t"];
        $page["meta"]["keywords"]=$row[0]["meta_k"];
        $page["meta"]["description"]=$row[0]["meta_d"];
if($mod == "Лицензии")$page["meta"]["some_meta"]=$some_meta;
        
        $page["content"] = $row[0]; 

//Sys::varDump($page);
        return $page; 
    }

}
?>