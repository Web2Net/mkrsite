<?

class Price
{
    function admPrice()
    {
        $tpl = new Tpl;
        
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }
        
        if($com == "upload")
        {
            Price::decodeFile(SITE_URL."/price/price_all.csv");
            $old_price = Price::getOldKodFromDB();
            $change_price = Price::changeRecordsInDB();
            
            $tpl->assign('change_price',$change_price);
            $c_cont=$tpl->get("price-uploaded");   
//SYS::varDump($change_price);
        }
        else
        {
            $c_cont=$tpl->get("price");
        }
        

        return $c_cont; 
    }
    
    function decodeFile($file)
    {
        header('Content-type: text/html; charset=utf-8');
        if(!setlocale(LC_ALL, 'ru_RU.utf8')) setlocale(LC_ALL, 'en_US.utf8');
        //if(setlocale(LC_ALL, 0) == 'C') die('Не поддерживается ни одна из перечисленных локалей (ru_RU.utf8, en_US.utf8)');

        $handle = fopen('php://memory', 'w+');
        fwrite($handle, iconv('windows-1251', 'utf-8', file_get_contents($file)));
        rewind($handle);
        //while (($row = @fgetcsv($handle, 1024, ';')) !== false) //print_r($row);
        fclose($handle);        
    }
    
    function getOldKodFromDB()
    {
        $select="kod";
        $from="items";
        $where = "1=1";

        $db = new mysql;
        $row = $db->origSelectSQL($select, $from, $where, "", "");

        return $row;
            
    }
    
    function changeRecordsInDB()
    {
        $fd = fopen(SITE_URL."/price/price_all.csv", "r");
        while (($values = fgetcsv($fd, 1024, ";")) !== FALSE)                        
        {
//SYS::varDump($values);           
        // Ноутбуки    
            if($values[15] == "10187"){$category_id = "2";} // Acer
            elseif($values[15] == "17950"){$category_id = "3";}  // Apple
            elseif($values[15] == "10200"){$category_id = "4";}  // Asus
            elseif($values[15] == "10298"){$category_id = "5";} // Dell
            elseif($values[15] == "10314"){$category_id = "6";}  // Fujitsu
            elseif($values[15] == "10336"){$category_id = "7";}  // HP
            elseif($values[15] == "10715"){$category_id = "8";}  // Lenovo
            elseif($values[15] == "10929"){$category_id = "9";} // Samsung
            elseif($values[15] == "10953"){$category_id = "10";}  // Sony
            elseif($values[15] == "10962"){$category_id = "11";} // Toshiba
        // Планшеты    
            elseif($values[15] == "65112"){$category_id = "30";} // Acer
            elseif($values[15] == "75468"){$category_id = "31";} //Amazon
            elseif($values[15] == "63190"){$category_id = "32";}   //Apple
            elseif($values[15] == "80060"){$category_id = "34";}  //BlackBerry
            elseif($values[15] == "56211"){$category_id = "35";}   //Archos
            elseif($values[15] == "75307"){$category_id = "36";} //Asistance
            elseif($values[15] == "67287"){$category_id = "37";} //Asus
            elseif($values[15] == "63193"){$category_id = "38";}  //COBY
            elseif($values[15] == "65911"){$category_id = "39";}  //Dell
            elseif($values[15] == "66370"){$category_id = "40";}  //Globex                                       
            elseif($values[15] == "63194"){$category_id = "41";}  //GoClever
            elseif($values[15] == "63192"){$category_id = "42";}  //Impression
            elseif($values[15] == "70186"){$category_id = "43";}    //@Lux
            elseif($values[15] == "63196"){$category_id = "44";}   //Miotex                                                                      
            elseif($values[15] == "82388"){$category_id = "45";}  //PocketBook
            elseif($values[15] == "59042"){$category_id = "46";}  //ViewSonic
            elseif($values[15] == "63195"){$category_id = "47";}   //Samsung
        // МФУ
            elseif($values[15] == "49585"){$category_id = "49";} // Brother        
            elseif($values[15] == "1660"){$category_id = "50";} // Canon
            elseif($values[15] == "81612"){$category_id = "51";} // Dell                 
            elseif($values[15] == "1675"){$category_id = "52";} // Epson                
            elseif($values[15] == "1685"){$category_id = "53";} // HP
            elseif($values[15] == "48469"){$category_id = "54";} // Konica Minolta
            elseif($values[15] == "1744"){$category_id = "55";} // Samsung
            elseif($values[15] == "1756" || $values[15] == "56221"){$category_id = "56";} // Xerox
            elseif($values[15] == "39573"){$category_id = "57";} // Panasonic
            elseif($values[15] == "55658"){$category_id = "58";} // Kyocera
        // Принтеры    
            elseif($values[15] == "61558"){$category_id = "60";} // Brother        
            elseif($values[15] == "11929"){$category_id = "61";} // Canon
            elseif($values[15] == "81608"){$category_id = "62";} // Dell                 
            elseif($values[15] == "11955"){$category_id = "63";} // Epson                
            elseif($values[15] == "11980"){$category_id = "64";} // HP            
            elseif($values[15] == "12028"){$category_id = "65";} // OKI
            //if($values[15] == "48469"){$category_id = "54";} // Konica Minolta
            elseif($values[15] == "12270"){$category_id = "66";} // Samsung
            elseif($values[15] == "12280"){$category_id = "67";} // Xerox
            //if($values[15] == "39573"){$category_id = "57";} // Panasonic
            elseif($values[15] == "55670"){$category_id = "68";} // Kyocera
        // Флеш память USB    
            elseif($values[15] == "31504"){$category_id = "81";} // Super Talent 4gb
            elseif($values[15] == "31505"){$category_id = "82";} // Super Talent 8gb
            elseif($values[15] == "31506"){$category_id = "83";} // Super Talent 16gb
            elseif($values[15] == "19499"){$category_id = "71";} // Corsair 
            elseif($values[15] == "31491"){$category_id = "84";} // Transcend 4gb
            elseif($values[15] == "31492"){$category_id = "85";} // Transcend 8gb
            elseif($values[15] == "31493"){$category_id = "86";} // Transcend 16gb
            elseif($values[15] == "31494"){$category_id = "87";} // Transcend 32gb
            elseif($values[15] == "66874"){$category_id = "88";} // Transcend 64gb 
            elseif($values[15] == "31496"){$category_id = "89";} // Kingston 4gb 
            elseif($values[15] == "31497"){$category_id = "90";} // Kingston 8gb
            elseif($values[15] == "31498"){$category_id = "91";} // Kingston 16gb
            elseif($values[15] == "44049"){$category_id = "92";} // Kingston 32gb
            elseif($values[15] == "31500"){$category_id = "93";} // Silicon Power 4gb
            elseif($values[15] == "31501"){$category_id = "94";} // Silicon Power 8gb
            elseif($values[15] == "31502"){$category_id = "95";} // Silicon Power 16gb
            elseif($values[15] == "38506"){$category_id = "96";} // Silicon Power 32gb
            elseif($values[15] == "31710"){$category_id = "75";} // Toshiba 
            elseif($values[15] == "72425"){$category_id = "76";} // GoodRam
            elseif($values[15] == "72589"){$category_id = "77";} // Platinet
            elseif($values[15] == "64188"){$category_id = "78";} // ZaNa Design
            elseif($values[15] == "74842"){$category_id = "79";} // Autodrive
            elseif($values[15] == "78124"){$category_id = "80";} // Emtec
            
            else{$category_id = "99999";}
         
            //$values[0] = iconv("windows-1251", "utf-8",$values[0]);
            //echo $values[0]."<br />";
//            $values[1] = iconv("windows-1251", "utf-8",$values[1]);
//            $values[2] = iconv("windows-1251", "utf-8",$values[1]);
//            $values[4] = iconv("windows-1251", "utf-8",$values[4]);
//            $values[16] = iconv("windows-1251", "utf-8",$values[16]);
         
            $values = str_replace("'","",$values);
            $values = str_replace("'","\'",$values);  
            $values = str_replace("`","",$values);
             
            $seolink = str_replace(" ","_",$values[16]);
            $seolink = str_replace("-","_",$seolink);
            $seolink = str_replace("/","_",$seolink);
            $seolink = str_replace("\"","",$seolink); 
            $caption = $values[16];   


 
            
           
            if(//($values[0] == "Ноутбуки" || 
//                $values[0] == "Планшеты" || 
//                $values[0] == "Многофункциональные устройства" ||
//                $values[0] == "Принтеры" ||
//                $values[0] == "Флеш память USB" 
//                ) && 
                $values[5] !== "")
            {
                $uniq_kod = mysql::checkUniqRow("items","kod",$values[3]);
//SYS::varDump($uniq_kod);
                if(!$uniq_kod)
                {
                    $query = "UPDATE `items` SET
                    `category`='{$category_id}',
                    `seolink` = '{$seolink}',
                    `price_dialer`='{$values[5]}',
                    `price_opt` = '{$values[6]}',
                    `price_opt2` = '{$values[7]}',
                    `price_mass` = '{$values[8]}',
                    `present_lvov` = '{$values[9]}',
                    `present_kiev` = '{$values[9]}'
                    WHERE `kod` = '{$values[3]}'
                    ";
                    
                    $res = mysql_query($query);
                    if(!$res)
                    {
                       echo mysql_error();
                       exit("Что-то с Query.");
                    }
                    $in[] = "Обновлено : {$values[0]} - {$values[1]} - {$values[3]}";

                }
                
                
                else
                {   
                    // Новая позиция
                    $query = "INSERT INTO `items` (
                    type, 
                    category, 
                    category_name, 
                    seolink, 
                    meta_t, 
                    meta_k, 
                    meta_d, 
                    caption, 
                    content_short, 
                    content, 
                    image_title, 
                    brend, 
                    price_dialer, 
                    price_opt, 
                    price_opt2, 
                    price_mass, 
                    present_lvov, 
                    present_kiev, 
                    garantie, 
                    kod, 
                    article)
                    VALUES (
                    '{$values[0]}', 
                    '{$category_id}', 
                    '{$values[1]}', 
                    '{$seolink}', 
                    '{$values[4]}', 
                    '{$values[4]}', 
                    '{$values[4]}', 
                    '{$caption}', 
                    '{$values[4]}', 
                    '{$values[4]}', 
                    '{$values[4]}', 
                    '{$values[1]}', 
                    '{$values[5]}', 
                    '{$values[6]}', 
                    '{$values[7]}', 
                    '{$values[8]}', 
                    '{$values[9]}', 
                    '{$values[10]}', 
                    '{$values[11]}', 
                    '{$values[3]}', 
                    '{$values[2]}'
                    )";   
                    
                    $res = mysql_query($query);
                    if(!$res)
                    {
                       echo mysql_error();
                       exit("Что-то с Query.");
                    }
                    $in[] = "{$values[0]} - {$values[1]} - {$values[3]}";

                }
                
            }

        }
        //var_dump($arr);
        fclose($fd);
        return $in;
    }
   
}

?>