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
            if($values[15] == "13192"){$category_id = "9B";} // AMD
            elseif($values[15] == "13275"){$category_id = "9C";} // Intel
            elseif($values[15] == "54935"){$category_id = "9D";} //  ASRock
            elseif($values[15] == "7886"){$category_id = "9M";} //  Asus
            elseif($values[15] == "22964"){$category_id = "9E";} // Biostar 
            elseif($values[15] == "7927"){$category_id = "97";} // Gigabyte 
        
            else{$category_id = "99999";}
        
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
                    `connect`='86-{$category_id}',
                    `price`='{$values[5]}',
                    `price_new` = '{$values[8]}',
                    `style` = '{$values[9]}',
                    `volume` = '{$values[9]}'
                    WHERE `1Cid` = '{$values[3]}'
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
                              1Cid,
                              brand,
                              serial,
                              name,
                              description,
                              full_text,
                              harakter,
                              instruction,
                              price,
                              newprice,
                              connect)
                    VALUES (
                    '{$values[3]}',  
                    '{$values[1]}',
                    '{$values[3]}',
                    '{$values[4]}',
                    '{$values[4]}',
                    '{$values[4]}',
                    '{$values[4]}',
                    '{$values[4]}', 
                    '{$values[5]}', 
                    '{$values[8]}', 
                    '{$category_id}'
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