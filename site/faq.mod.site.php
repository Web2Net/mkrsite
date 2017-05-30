<?
class Faq
{
 	function viewFaqModPage()
    {
	    foreach ($_REQUEST as $key=>$val)
        {
            $key." - ".$val."<br />";
            $str="$".$key."=\$val;";
            eval($str);
        }

        $seodata = explode("/",$seolink);
        $mod=$seodata[0]; //echo $mod." - \$mod<br />";
        $rub = $seodata[1];  //echo $rub." - \$rub<br />";

        $category_data = explode("-",$seodata[1]);
        $category_id = $category_data[0]; //echo $category_id." - \$category_id<br />";
        $category = $category_data[1];   //echo $category." - \$category<br />";
        
        $iddata = explode("-",$seodata[2]);
        $id = $iddata[0];  //echo $id." - \$id<br />";
        

        if(isset($_POST['send_qustion']))
        {   
            
            if($_REQUEST['form__name'] == "" || $_REQUEST['form__email'] == "" || $_REQUEST['form__question'] == "")
            {
                $mess = "Заполните правильно форму";
                $tpl_art = new SiteTpl;
                $tpl_art->assign('mess', $mess);
                
                $faq_html["content"]=$tpl_art->get('faq_form');
            }
            else
            {
                foreach($_REQUEST as $key=>$val)
                {
                    $var = explode("__",$key);
                    if($var['0'] == "form")
                    {   
                        $val = Text::wordBreak($val,45, " ");
                        $arr_value[$var['1']] = Text::cutString($val);
                    }
                }
                $arr_value['ip'] = $_SERVER['REMOTE_ADDR'];
                $arr_value['date'] = date("Y-m-d H:i");
              
                 $db = new mysql;
                 $table = "faq";
                 $id = $db->insertSQL ($arr_value, $table);
                
                 $send = Faq::sendEmail();
                
                 $tpl_art = new SiteTpl;
                 $faq_html["content"]=$tpl_art->get('faq_sps');
            }
            
            
            
       }
       else
       {
            if($rub == "")
            {
                $faq_html = Faq::viewFaqCategory();
            }
            elseif($rub == "new")
            {
                $faq_html = Faq::viewFaqForm();
            }
            else
            {
                if($id == "")
                {
                    $page="1";
                    $faq_html = Faq::viewFaqList($category_id,5,$page);
                }
                else
                {
                    if($id !== "page")
                    {
                        $faq_html = Faq::viewFaq($id);
                    }
                    else
                    {
                       $page=$iddata[1];
                       $faq_html = Faq::viewFaqList($category_id,5,$page);
                    }
                }
                
            }
       }
        return $faq_html;
}
     
    function viewFaqForm()
    {
        $tpl_art = new SiteTpl;
        $page["content"]=$tpl_art->get('faq_form');
        return $page;
    }
     
	function viewFaq($id)
    {
        //$_SESSION['ip'] = getenv("REMOTE_ADDR");
        $tpl_art = new SiteTpl;	

        $db =new mysql;
        $from="faq";
        $where="id={$id}";
        $row = $db->origSelectSQL("", $from, $where, "", 1);
        
        $tpl_art->assign('faq', $row);
         
        $page["content"]=$tpl_art->get('faq');
        
        $page["meta"]["title"] = $row[0]["question"];
        $page["meta"]["keywords"] = $row[0]["meta_k"];
        $page["meta"]["description"] = $row[0]["meta_d"];
        
        //var_dump($page);
        return $page; 
    }
    
    function viewFaqCategory()
    {
        //$_SESSION['ip'] = getenv("REMOTE_ADDR");
        $tpl_art = new SiteTpl;    

        $db =new mysql;
        $from="mr_faq_category";
        $where="`showing`='Y'";
        $row = $db->origSelectSQL("", $from, $where, "`order`");
        
        $tpl_art->assign('faq_category', $row);
         
        $page["content"]=$tpl_art->get('faq_category');
        
        $page["meta"]["title"] = $row[0]["caption"];
        $page["meta"]["keywords"] = $row[0]["meta_k"];
        $page["meta"]["description"] = $row[0]["meta_d"];
        
        //var_dump($page);
        return $page; 
    }
    
    function viewFaqList($category_id,$hm=3,$page=1)
    {   
        foreach ($_REQUEST as $key=>$val)
        {
            $key." - ".$val."<br />";
            $str="$".$key."=\$val;";
            eval($str);
        }

        $seodata = explode("/",$seolink);
        $mod=$seodata[0]; //echo $mod." - \$mod<br />";
        $rub = $seodata[1];  //echo $rub." - \$rub<br />";

        $category_data = explode("-",$seodata[1]);
        $category_id = $category_data[0]; //echo $category_id." - \$category_id<br />";
        $category = $category_data[1];   //echo $category." - \$category<br />";
        
        $iddata = explode("-",$seodata[2]);
        $id = $iddata[0]; // echo $id." - \$id<br />";
        
        $tpl_art = new SiteTpl;    
         if($page==1)
        {
            $start=0;
        }
        else
        {
            $start=($page-1)*$hm;
        }

        $db =new mysql;
        $from="`faq`";
        $where="`showing`='Y' AND `category_id`='{$category_id}'";
        $faq_list = $db->origSelectSQL("", $from, $where, "`date` DESC",$start.",".$hm);
        $count_faq = $db->origSelectSQL("COUNT(*)", $from, $where, "date DESC");

        $tpl_art->assign('rub',"faq");
        $tpl_art->assign('category_id',$category_data[0]);
        $tpl_art->assign('category',$category_data[1]);
        $tpl_art->assign('list',$faq_list);
        $tpl_art->assign('count_b',$count_faq[0]["COUNT(*)"]);
        $tpl_art->assign('page',$page);              
        $tpl_art->assign('pages',ceil($count_faq[0]["COUNT(*)"]/$hm));
        
        $art_data["content"]=$tpl_art->get('faq_list');
        $art_data["meta"]["title"]="Вопрос/ответ";
        
        return $art_data;
    }
    
    function checkEmail($em)
    { 
          $em = filter_var($em, FILTER_VALIDATE_EMAIL);
          if($em)
          {
              return $em;
          }
          else
          {
              return false;
          }
     }                              //Content-Type: text/plain;
//                                           charset="koi8-r"
//                                           Content-Transfer-Encoding: base64
      
    function sendEmail()             
    {
        //$charset = "windows-1251";
        $charset = "utf-8";
        //$charset = "KOI8-R";
        
        $my_email = EMAIL_BOSS;
        $subject = "Вопрос с сайта";
        
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        
        $txt = new Text;
        $email = $txt->cut($_POST['form__email']);
        $content = 
"IP - ".$_SERVER['REMOTE_ADDR']."<br />\r\n
Имя - {$_POST['form__name']}<br />\r\n

E-mail - {$email}<br />\r\n
<br /><br /><hr />
Вопрос:<br />\r\n".stripcslashes($txt->cut($_POST['form__question']));
                    
        //$content = iconv('UTF-8', 'Windows-1251', $content);

// На случай если какая-то строка письма длиннее 70 символов мы используем wordwrap()
$message = wordwrap($content, 70);
// Отправляем
        $send = mail($my_email, $subject, $message, $headers); 
        if($send)
        {
           //Sys::varDump($send); 
        }
        else
        {
            return false;
        }
        
        
    }
    
    //function sendEmail()
//    {
//        $txt = new Text();
//        $content = "Имя - {$_POST['form__name']}<br />\r 
//                    E-mail - {$_POST['form__email']}<br />\r 
//                    Вопрос:<br />\r".stripcslashes($txt->cut($_POST['form__question']));
//                    
//        $email_address = 'psydema@ukr.net';  
//        mail($email_address,"Вопрос доктору",$content);
//    }
}   
?>