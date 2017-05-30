<?
class Works
{
    static function admWorks()
    {
        $tpl = new Tpl;
        
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }
        if($ext=="ajax")
        {
	    if(isset($_FILES["file"]) && trim($_FILES["file"]["name"])!= "")
            {	
                $mess=$_FILES["file"]["name"];
                $testName = explode( ".",$_FILES["file"]["name"] );
                $file_name=$id.".".$testName[1];
                $mess=Work::loadFile($_FILES["file"]);
                if($mess=="OK")
                {
	                $file_dir = "/cms/work/doc/";
                        $GLOBALS['_RESULT'] = array(
                                                "file" => $file_dir.$file_name,
                                                "mess" => $mess,
                                                ); 
                }
            }
        }
        else
        {
            if($com == "view")
            {
                if($type == "all"){$row=Works::getList("`archiv` = 'N'");}
                if($type == "nworks"){$row=Works::getList("`zdelano` = 'N' AND `archiv` = 'N'");}
                if($type == "yworks"){$row=Works::getList("`zdelano` = 'Y' AND `archiv` = 'N'");}
                if($type == "ydoc"){$row=Works::getList("`doc` = 'Y' AND `archiv` = 'N'");}
                if($type == "ndoc"){$row=Works::getList("`doc` = 'N' AND `archiv` = 'N'");}
                if($type == "sn"){$row=Works::getList("`sn` <> '' AND `archiv` = 'N'");}
                if($type == "archiv"){$row=Works::getList("`archiv` = 'Y'");}
                if($type == "nal_y"){$row=Works::getList("`nal` = 'Y' AND `archiv` = 'N'");}
                if($type == "nal_n"){$row=Works::getList("`nal` = 'N' AND `archiv` = 'N'");}
                if($type == "n_otgryzka"){$row=Works::getList("`otgryzka` = 'N' AND `archiv` = 'N'");}
                if($type == "prioritet"){$row=Works::getList("`prioritet` = 'Y' AND `archiv` = 'N'");}
                if($type == "srochno_y"){$row=Works::getList("`srochno` = 'Y' AND `doc` = 'N' AND `archiv` = 'N'");}
// Поиск по Клиенту				
				$post_client = $_POST['client'];
                if($type == "client"){$row=Works::getList("`client` LIKE '%{$post_client}%'");}
// /Поиск по Клиенту	              
                if($type == "calendar")
                {
                    $calend = explode(" ",$calendar_date);
                    if(strlen($calend[0]) == 1){$calend[0] = "0".$calend[0];}
                    $d = $calend[0]; $m = $calend[1]; $y = $calend[2];
                    $cal_date = $y."-".$m."-".$d;
                
                    $calend_1 = explode(" ",$calendar_date_1);
                    if(strlen($calend_1[0]) == 1){$calend_1[0] = "0".$calend_1[0];}
                    $d_1 = $calend_1[0]; $m_1 = $calend_1[1]; $y_1 = $calend_1[2];
                    $cal_date_1 = $y_1."-".$m_1."-".$d_1;
                
                    $row=Works::getList("`date_create` = '{$cal_date}'");
                }
/*				if($type == "client"){
					//exit("client");
					$row=Works::getClientList($_POST['client']);
					
				}
*/            
                $tpl->assign('listing',$row);
                $c_cont=$tpl->get("works-list"); 
            }
            elseif($com=="add")
            {
	        $row=Works::getWork($id);
                $tpl->assign('art_data',$row);
                if($type == "duble")
	        {
	            $c_cont=$tpl->get("works-add-edit-duble"); // Создает новую запись на основании существующей, меняя id и дату
	        }
	        else
	        {
	            $c_cont=$tpl->get("works-add-edit");
	        }
            	
        }
        elseif($com=="edit")
        {
	    $row=Works::getWork($id);
            $tpl->assign('art_data',$row);
            $c_cont=$tpl->get("works-add-edit");	
        }
        elseif($com=="update")
        {
            foreach($_REQUEST as $key=>$val)
            {
                $var = explode("__",$key);
                if($var['0'] == "form")
                {
                    $arr_value[$var['1']] = mysql_real_escape_string($val);
                }
            }
//SYS::varDump($arr_value);           
            $content_letter = $arr_value['client']."<br />".$arr_value['content']."<br />".$arr_value['neispravnost'];             

            $arr_value['date_create']=isset($arr_value['date_create']) && $arr_value['date_create']!=""?$arr_value['date_create']:date("Y-m-d")." ".$_SESSION['username'];
            
			$arr_value['zdelano'] = Text::checkBoxProcess($arr_value['zdelano']);
			if($arr_value['zdelano'] == "Y" && $arr_value['date_zdelano'] == ""){
				$arr_value['date_zdelano'] = date("Y-m-d")." ".$_SESSION['username'];
				//Works::sendSimplyMail($arr_value['id'],"1",$content_letter);
			}            
            
			$arr_value['doc'] = Text::checkBoxProcess($arr_value['doc']);
			if($arr_value['doc'] == "Y" && $arr_value['date_doc'] == ""){
				$arr_value['date_doc'] = date("Y-m-d")." ".$_SESSION['username'];
				//Works::sendSimplyMail($arr_value['id'],"2",$content_letter);
			}
			
			$arr_value['srochno'] = Text::checkBoxProcess($arr_value['srochno']);
            if($arr_value['srochno'] == "Y" && $arr_value['date_srochno'] == ""){
				$arr_value['date_srochno'] = date("Y-m-d")." ".$_SESSION['username'];
				//Works::sendSimplyMail($arr_value['id'],"2",$content_letter);
			}
			
			$arr_value['otgryzka'] = Text::checkBoxProcess($arr_value['otgryzka']);
            if($arr_value['otgryzka'] == "Y" && $arr_value['date_otgryzka'] == ""){
				$arr_value['date_otgryzka'] = date("Y-m-d")." ".$_SESSION['username'];
				//Works::sendSimplyMail($arr_value['id'],"3",$content_letter);
			}
            
			$arr_value['pay'] = Text::checkBoxProcess($arr_value['pay']);
            if($arr_value['pay'] == "Y" && $arr_value['date_pay'] == ""){
				$arr_value['date_pay'] = date("Y-m-d")." ".$_SESSION['username'];
				//Works::sendSimplyMail($arr_value['id'],"4",$content_letter);
			}
            
            
//SYS::varDump($arr_value,__FILE__,__LINE__);

            $table="works";
            if($arr_value['id']!="")
            {
/*           
                if($arr_value['zdelano'] == ""){$arr_value['zdelano'] = "N"; $arr_value['date_zdelano'] = "";Works::sendSimplyMail($arr_value['id'],"-1");}
                if($arr_value['doc'] == ""){$arr_value['doc'] = "N"; $arr_value['date_doc'] = "";Works::sendSimplyMail($arr_value['id'],"-2");}
                if($arr_value['otgryzka'] == ""){$arr_value['otgryzka'] = "N"; $arr_value['date_otgryzka'] = "";Works::sendSimplyMail($arr_value['id'],"-3");}
                if($arr_value['pay'] == ""){$arr_value['pay'] = "N"; $arr_value['date_pay'] = "";Works::sendSimplyMail($arr_value['id'],"-4");}
 */           
            //    unset($arr_value['ingener']);
	        $where['id']=$arr_value['id'];
                $db = new mysql;
                $res = $db->updateSQL ($table, $arr_value, $where);
            }
            else
            {
                $db = new mysql;
                $id = $db->insertSQL ($arr_value, $table);
                //Works::sendSimplyMail($id,"0",$content_letter);	
	    }
            $loc_url=str_replace("com=update","com=view",PAGE_URL);
            $loc_url=$loc_url."&type=all&upd=ok";

            header("Location: ".$loc_url."");

//header("Location: ".PAGE_REF."");
        }
        elseif($com=="delete")
        {
	    $from="works";	
            $where['id']=$id;	
            $db = new mysql;
            $res = $db->deleteSQL ($from, $where);
            $loc_url=str_replace("com=delete","com=view",PAGE_URL);
            $loc_url=$loc_url."&type=all&upd=ok";
            
            //Works::sendSimplyMail($id,"-0");
            
            header("Location: ".$loc_url."");
        }
        elseif($com == "archiv" || $com == "otgryzka" || $com == "doc" || $com == "pay" || $com == "zdelano" || $com == "nal" || $com == "viezd" || $com == "srochno")
        {
            $table="works";
            $where['id']=$id;
            $arch[$com] = "Y";
            
//            if($com == "zdelano"){Works::sendSimplyMail($id,"1",$content_letter);}
//            if($com == "doc"){Works::sendSimplyMail($id,"2",$content_letter);}
//            if($com == "otgryzka"){Works::sendSimplyMail($id,"3",$content_letter);}
//            if($com == "pay"){Works::sendSimplyMail($id,"4",$content_letter);}
            //if($com == "archiv"){Works::sendSimplyMail($id,"5",$content_letter);}
            
            if($com !== "nal" && $com !== "viezd")
            {
                $arch['date_'.$com] = date("Y-m-d")." ".$_SESSION['username'];
            }
//SYS::varDump($arch,__FILE__,__LINE__);
            $db = new mysql;
            $res = $db->updateSQL ($table, $arch, $where);	
            header("Location: ".PAGE_REF."");
         }
        else
        {
            $c_cont=$tpl->get("works-list");
        }
        }
        return $c_cont;
    }
    
    
    static function getList($var)
    {
        $select="";
        $from="works";
        //$where["parent_id"]=$parent;
        $where = "{$var}";
        if($var == "`doc` = 'N' AND `archiv` = 'N'" || $var == "`srochno` = 'Y' AND `doc` = 'N' AND `archiv` = 'N'"){
            $sortby="`client`";
        }
		else{
            $sortby="`id` DESC";
        }

        $db = new mysql;
        $row = $db->origSelectSQL($select, $from, $where, $sortby);
        
//SYS::varDump($row);
        return $row;       
    }
    
    static function getWork($id)
    {
        $select="";
        $from="works";
        $where = "`id` = '{$id}'";

        $db = new mysql;
        $row = $db->origSelectSQL($select, $from, $where, "");
//SYS::varDump($row);
        return $row[0];       
    }
    
    static function getClients($id)
    {
        $select="";
        $from="clients";
        $where = "`id` = '{$id}'";
        $sortby="`caption`";

        $db = new mysql;
        $row = $db->origSelectSQL($select, $from, $where, $sortby);
//SYS::varDump($row);
        return $row[0];
    }
	
	static function getClientList($caption) // Выборка клиентов по именам, названиям
    {
        $select="";
        $from="works";
        $where = "`client` LIKE '%{$caption}%'";
        //$sortby="`client`";

        $db = new mysql;
        $row = $db->origSelectSQL($select, $from, $where, $sortby);
//SYS::varDump($row);
        return $row[0];
    }
    
    static function decodeCalendar($str) 
    { 

    $arr = array( 
    'Янв' => '01', 
    'Фев' => '02', 
    'Мар' => '03', 
    'Апр' => '04', 
    'Май' => '05', 
    'Июн' => '06', 
    'Июл' => '07', 
    'Авг' => '08', 
    'Сен' => '09', 
    'Окт' => '10', 
    'Ноя' => '11', 
    'Дек' => '12'
    ); 
    $key = array_keys($arr);
    $val = array_values($arr);
    $transl = str_replace($key,$val,trim($str)); 

    return strtolower($transl); 
    }
    
    function loadFile($arr)
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\"".$val."\";";
            eval($str);
        }

        $error="";

        if($arr['error']>0)
        {
	    $error.="РџСЂРѕР±Р»РµРјР° СЃ Р·Р°РіСЂСѓР·РєРѕР№ С„Р°Р№Р»Р° $name<br>";
            switch($arr['error'])
            {
                case 1: $error.="Р Р°Р·РјРµСЂ С„Р°Р№Р»Р° Р±РѕР»СЊС€Рµ РІРѕР·РјРѕР¶РЅРѕРіРѕ<br>"; break;
                case 2: $error.="Р Р°Р·РјРµСЂ С„Р°Р№Р»Р° Р±РѕР»СЊС€Рµ РѕР±РѕР·РЅР°С‡РµРЅРЅРѕРіРѕ РІ С„РѕСЂРјРµ<br>"; break;
                case 3: $error.="Р—Р°РіСЂСѓР¶РµРЅР° С‚РѕР»СЊРєРѕ С‡Р°СЃС‚СЊ С„Р°Р№Р»Р°<br>"; break;
                case 4: $error.="Р¤Р°Р№Р» РЅРµ Р·Р°РіСЂСѓР¶РµРЅ<br>"; break;
            }
            return false;
        }

        $testName = explode( ".",$arr["name"] );
        $cur=array("jpg","bmp","gif","png");
        if($testName[1]!="jpg")
        {
            $mess="РџСЂРѕР±Р»РµРјР°: Р¤Р°Р№Р» ".$arr["name"]." РЅРµ СЂР°Р·СЂРµС€С‘РЅРЅС‹Р№ С‚РёРї (jpg, bmp, gif, png)";
            return $mess;
        }
        if($arr['type']=="")	
        {
            $mess="РџСЂРѕР±Р»РµРјР°: Р¤Р°Р№Р» РЅРµ С„Р°Р№Р» РёР·РѕР±СЂР°Р¶РµРЅРёСЏ: ".$arr['type']."<br>";
            return $mess;
        }

        //$real=SITE_PATH."/product-image/".$prodid.".".$testName[1];

        $uploaddir = SITE_PATH."/cms/work/doc/";
        $imfile=Text::cirilToLatin($testName[0]).".$testName[1])";
        $smallimfile="small-".$imfile;
        @mkdir($uploaddir);
        $uploadfile = $uploaddir.$imfile;
        $smalluploadfile = $uploaddir.$smallimfile;


    //if (copy($arr['tmp_name'], $uploadfile)){
	    
    //GD::imageResize($uploadfile,$arr['tmp_name'],300,200,85);
    //GD::imageResize($smalluploadfile,$arr['tmp_name'],150,100,85);
    //var_dump($arr);

    return "OK";
    //}
    }
    
    static function sendSimplyMail($id,$mess,$cont)
    {
        $to  = "Виталий <you@mkr.com.ua>, " ; 
        $to .= "Александр <alex@mkr.com.ua>, " ;
//        $to .= "Ирина <irina@mkr.com.ua>, " ; 


        if($mess == "0")
        {
            $subject = "Заявка № {$id}";
            $title = "Создана новая заявка № {$id}";
            $content = "Создана новая заявка № {$id}.<br />{$_SESSION['username']}.<br />{$cont}";
        }
        elseif($mess == "-0")
        {
            $subject = "Заявка № {$id}";
            $title = "Заявка № {$id} УДАЛЕНА";
            $content = "Заявка № {$id} УДАЛЕНА.<br />{$_SESSION['username']}.";
        }
        elseif($mess == "1")
        {
            $subject = "Заявка № {$id}";
            $title = "Заявка № {$id} сделана";
            $content = "Урааа! Закончил работу над заявкой № {$id}.<br />{$_SESSION['username']}.<br />{$cont}";
            $to .= "Ирина <irina@mkr.com.ua>, " ;
        }
        elseif($mess == "-1")
        {
            $subject = "Заявка № {$id}";
            $title = "Заявка № {$id} переведена в рязряд НЕ СДЕЛАНО";
            $content = "Заявка № {$id} переведена в рязряд НЕ СДЕЛАНО.<br />{$_SESSION['username']}.";
        }
        elseif($mess == "2")
        {
            $subject = "Заявка № {$id}";
            $title = "Документы по заявке № {$id} выписаны";
            $content = "Документы по заявке № {$id} выписаны.<br />{$_SESSION['username']}.<br />{$cont}";
        }
        elseif($mess == "-2")
        {
            $subject = "Заявка № {$id}";
            $title = "Документы по заявке № {$id} переведены в рязряд НЕ ВЫПИСАНЫ";
            $content = "Документы по заявке № {$id} переведены в рязряд НЕ ВЫПИСАНЫ.<br />{$_SESSION['username']}.";
        }
        elseif($mess == "3")
        {
            $subject = "Заявка № {$id}";
            $title = "Заявка № {$id} отгружена";
            $content = "Заявка № {$id} отгружена.<br />{$_SESSION['username']}.<br />{$cont}";
        }
        elseif($mess == "-3")
        {
            $subject = "Заявка № {$id}";
            $title = "Заявка № {$id} переведена в рязряд НЕ ОТГРУЖЕНА";
            $content = "Заявка № {$id} переведена в рязряд НЕ ОТГРУЖЕНА.<br />{$_SESSION['username']}.";
        }
        elseif($mess == "4")
        {
            $subject = "Заявка № {$id}";
            $title = "Проплатили заявку № {$id}";
            $content = "Проплатили заявку № {$id}.<br />{$_SESSION['username']}.<br />{$cont}";
        }
        elseif($mess == "-4")
        {
            $subject = "Заявка № {$id}";
            $title = "Оплата заявки № {$id} переведена в рязряд НЕ ОПЛАЧЕНО";
            $content = "Оплата заявки № {$id} переведена в рязряд НЕ ОПЛАЧЕНО.<br />{$_SESSION['username']}.";
        }
        elseif($mess == "5")
        {
            $subject = "Заявка № {$id}";
            $title = "Всем спасибо. Заявка № {$id} отправилась в архив";
            $content = "Всем спасибо. Заявка № {$id} отправилась в архив.<br />{$_SESSION['username']}.<br />{$cont}";
        }
        elseif($mess == "-5")
        {
            $subject = "Заявка № {$id}";
            $title = "Заявка № {$id} отправилась из архива в работу";
            $content = "Заявка № {$id} отправилась из архива в работу.<br />{$_SESSION['username']}.";
        }
        else
        {
            $subject = "{$_SESSION['username']} ковыряется в заявках № {$id}";
            $title = "{$_SESSION['username']} ковыряется в заявках № {$id}";
            $content = "{$_SESSION['username']} ковыряется в заявке № {$id}.";
        }
         

        $message = " 
        <html> 
            <head> 
                <title>{$title}</title> 
            </head> 
            <body> 
                <p>{$content}</p> 
            </body> 
       </html>"; 

       $headers  = "Content-type: text/html; charset=utf8 \r\n"; 
       $headers .= "From: МКР-Сервис. <service@mkr.com.ua>\r\n"; 

       mail($to, $subject, $message, $headers);
    }

    
}

?>
