<?php
class Betweentimes
{
    function differentLogo()
    {
        $date_now = date("md"); // Дата в виде МесяцДень 
        
        // Новый год
        if(($date_now>"1220")&&($date_now < "0120")){$img_logo = "logo_NY.jpg";}
        // 8 марта
        elseif(($date_now>"0301")&&($date_now < "0312")){$img_logo = "logo_8march.jpg";}
        // 9 мая
        elseif(($date_now>"0503")&&($date_now < "0518")){$img_logo = "logo_9may.jpg";}
        
        else{$img_logo = "logo.jpg";}
        
        return  $img_logo;
    }
}
?>
