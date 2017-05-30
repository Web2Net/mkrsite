<?php
//require_once "/home/_mirkiev.com/req/class/CheckDataRequest.class.php";
 
////////////////////
class Email
{
    
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
     }
     
     function sendMail($name, $subject, $url, $to, $from) 
     {
       $letter_tpl = file_get_contents('letter.txt'); // считываем из файла шаблон письма
       $date = date("d.m.Y", time()); // форматируем текущую дату
       $searches = array("/\{name\}/", "/\{url\}/", "/\{date\}/");
       $replacements = array($name, $url, $date);
       // подставляем данные в шаблон
       $letter = preg_replace($searches, $replacements, $letter_tpl);
       $from = convert_cyr_string($from, "w","k"); // перекодируем поле 'от кого'
       $letter = convert_cyr_string($letter, "w","k"); // перекодируем тело письма
       // составляем заголовки - очень важно!
       $headers  = "Content-type: text/plain; charset=koi8-r\r\n";
       $headers .= "From: ".$from."\r\n";
       $subject = '=?koi8-r?B?'.base64_encode(convert_cyr_string($subject, "w","k")).'?=';
       if (mail($to, $subject, $letter, $headers)) {
          return true;
       }else {
          return false;
       }
    }
     
     
     
     function send_mime_mail($name_from, // имя отправителя
                             $email_from, // email отправителя
                             $name_to, // имя получателя
                             $email_to, // email получателя
                             $data_charset, // кодировка переданных данных
                             $send_charset, // кодировка письма
                             $subject, // тема письма
                             $body // текст письма
                            ) 
     {
          $to = Email::mime_header_encode($name_to, $data_charset, $send_charset).'<'.$email_to.'>';
          $subject = Email::mime_header_encode($subject, $data_charset, $send_charset);
          $from =  Email::mime_header_encode($name_from, $data_charset, $send_charset).'<'.$email_from.'>';
          if($data_charset != $send_charset) 
          {
             $body = iconv($data_charset, $send_charset, $body);
          }
          $headers = "From: {$from}\r\n";
          $headers .= "Content-type: text/plain; charset={$send_charset}\r\n";
          $headers .= "Mime-Version: 1.0\r\n";

          return mail($to, $subject, $body, $headers);
     }

     function mime_header_encode($str, $data_charset, $send_charset) 
     {
          if($data_charset != $send_charset) 
          {
              $str = iconv($data_charset, $send_charset, $str);
          }
          return '=?'.$send_charset.'?B?'.base64_encode($str).'?=';
     }
  
     
}
?>
