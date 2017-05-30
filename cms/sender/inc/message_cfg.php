<?php
  $top = '<div style="text-align:left"><a href="'.SITE_URL.'" target="_blank">
        <img src="'.SITE_URL.'/site/design/img/message_shapka.jpg" style="border: 0px;" alt="'.SITE_NAME.'" /></a>
       </div>';
$content_head = '<div style="text-align: justify;">
                 <div style="text-align:center"><h2>'.SITE_NAME.'</h2></div>
                 '.$content.'
                 </div>';
$bottom = '<br /><br /><div style="text-align:center;  border-top:solid 2px #274255;font-size: 8.5pt; font-family: Arial;color:#000;padding:3px;">
           Если это письмо попало к Вам по ошибке или полученная информация  Вам не интересна, 
           просьба сообщить : <a href="mailto:'.EMAIL_UNSUBCRIBE.'">'.EMAIL_UNSUBCRIBE.'</a>. Спасибо.
          </div>';
?>
