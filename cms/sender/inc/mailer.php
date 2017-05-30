<?php
if(isset($_POST['img_top']) && $_POST['img_top'] !== "")
{
   $img_top = $_POST['img_top']; 
}
else
{
   $img_top = "default/shapka.gif"; 
}

$subject = $_POST['mail_subject'];
$content = stripcslashes($_POST['full_text']);

//$charset = "windows-1251";
$charset = CHARSET;

$url_master  = SITE_URL;
$width       = "800";
$height      = "150";
$title       = SITE_NAME;
$adress      = "";
$from        = EMAIL_OFFICE;
$unsubscribe = EMAIL_UNSUBCRIBE;

include "sender/inc/message_cfg.php";


if(isset($_POST['mail_to']) && $_POST['mail_to'] !== "")
{
    //$filename = "{$GLOBALS['PATH_EMAIL']}/{$_POST['mail_to']}";
//    $listemail = file($filename);
    
    $filename = SITE_PATH."/cms/sender/e-mail_base/{$_POST['mail_to']}";
    $listemail = file($filename);

//////////////////////////////       
$my_email = EMAIL_OFFICE;
//$my_email = "psydema@ukr.net";

$EOL = "\r\n";

$headers = "Content-Type: text/html; charset={$charset}{$EOL}";
//$headers .= "Reply-To: psydema@ukr.net{$EOL}";
$headers .= "From: {$my_email}{$EOL}";
$headers .= "Reply-To: {$my_email}{$EOL}";
$headers .= "Return-Path: {$my_email}{$EOL}";
$headers .= "Errors-To: {$my_email}{$EOL}";
$headers .= "Disposition-Notification-To: {$my_email}{$EOL}{$EOL}";
$header.="Subject: {$subject}";
$header.="Content-type: text/html; {$charset}{$EOL}";
$msg = "<HTML>{$EOL}";
$msg .= "<HEAD>{$EOL}";
$msg .= "</HEAD>{$EOL}";
$msg .= "<BODY>{$EOL}";
$msg .= $top;
$msg .= $content_head;
$msg .= $bottom;
$msg .= "</BODY>{$EOL}";
$msg .= "</HTML>";

$attach = ""; 
  if (!empty($_FILES['mail_file']['tmp_name'])) 
  { 
     $path = $_FILES['mail_file']['name']; 
     if (copy($_FILES['mail_file']['tmp_name'], $path)) 
     {
         $attach = $path;
     }  
  }  
function send_mail($mail_to, $subject, $html, $path)   
{
     if ($path) 
     {  
         $fp = fopen($path,"r");   
         if (!$fp)   
         {
            echo "Cannot open file";   
            exit();   
         }   
         $file = fread($fp, filesize($path));   
         fclose($fp);
     }  

        $name = $_FILES['mail_file']['name']; 
        $EOL = "\n";                                               
        $boundary     = "--".md5(uniqid(time()));
         
        $headers    = "MIME-Version: 1.0;{$EOL}";   
        $headers   .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"{$EOL}";  
        $headers   .= "From: ".EMAIL_OFFICE."\r\n\r\n";
        $headers   .= "Reply-To: ".EMAIL_OFFICE."\r\n\r\n";  
        $multipart  = "--{$boundary}{$EOL}";   
        $multipart .= "Content-Type: text/html; charset={$charset}{$EOL}";   
        $multipart .= "Content-Transfer-Encoding: base64{$EOL}";   
        $multipart .= $EOL;
        $multipart .= chunk_split(base64_encode($html));   
        $multipart .=  "{$EOL}--{$boundary}{$EOL}";   
        $multipart .= "Content-Type: application/octet-stream; name=\"{$name}\"{$EOL}";   
        $multipart .= "Content-Transfer-Encoding: base64{$EOL}";   
        $multipart .= "Content-Disposition: attachment; filename=\"{$name}\"{$EOL}";   
        $multipart .= $EOL;
        $multipart .= chunk_split(base64_encode($file));   
        $multipart .= "{$EOL}--{$boundary}--{$EOL}";   
             if(!mail($mail_to, $subject, $multipart, $headers))   
             {
                 return False; 
             }  
             else 
             { 
                  return True; 
             } 
             exit;  
}

foreach($listemail as $mail_to) 
{
   if(empty($attach))
   {   
      mail($mail_to, $subject, $msg, $headers);  
   }
   else 
   {  
      send_mail($mail_to, $subject, $msg, $attach);
   }
}  
  


$date = date("Y-m-d H:i");
$message = stripslashes($msg);
$query = "INSERT INTO message 
              (`date`, `subject`, `message`, `attach`, `email_bd`)
          VALUES
              ('{$date}', '{$subject}', '{$content}', '{$attach}', '{$_POST['mail_to']}')";
//echo "QUERY - {$query}";
//$db->mySQLQuery($query);

echo "<center><div style='padding: 0px; margin: 10px; width: 800px; border: 1px solid black;'>{$msg}</div></center>";
echo "<div style='border-top: 1px solid red;'></div>"; 
echo "<div style='padding: 10px; margin: 10px; text-align: left;'>Рассылка завершена.</div>";
echo "<div style='padding: 10px; margin: 10px; text-align: left;'><a href='' onclick='window.history.back(0);'>Назад</a></div>"; 
}
else
{
    echo "<script> alert('Не выбрана база e-mail...')</script>";
   
     

}

 
?>
