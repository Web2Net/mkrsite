<?    
class Errors
{
    function viewErrorsModPage()
    {
          
                           
        $tpl_art = new SiteTpl;
        
         
         $page["content"] = $tpl_art->get('errors');

        
        

        return $page;
    }

    function viewPage($rub)
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
        $from="`mr_page`";
        
        $where="`seolink`='{$rub}'";
        $row = $db->origSelectSQL("", $from, $where, "", "");

       // $tpl_art->assign('page', $row[0]);
        
        $page["meta"]["title"]=$row[0]["meta_t"];
        $page["meta"]["keywords"]=$row[0]["meta_k"];
        $page["meta"]["description"]=$row[0]["meta_d"];
        if($rub == "Лицензии")$page["meta"]["some_meta"]=$some_meta;
        
        $page["content"] = $row[0]; 

        //Sys::varDump($page);
        return $page; 
    }

}
?>