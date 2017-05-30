<?php
class Sender
{
    function admSender() 
    {
        $tpl = new AdmTpl;
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
                $mess=Sender::loadImage($_FILES["file"]);
                if($mess=="OK")
                {
                    $imgdir = "/img/sender/";
                    $GLOBALS['_RESULT'] = array
                    (
                        "image"     => $imgdir.$_FILES['file']['name'],
                        "image_small"     => $imgdir."".$_FILES['file']['name'],    
                        "mess"   => $mess
                    ); 
                }
            }
        }
        else
        {
                if($com=="view")     
                {
                    $filearray = Sender::getEmailBaseList(PATH_EMAIL_BASE);
                }
                if($com=="add")
                {
                    //$page = Sender::uploadBase();    
                }
                if($com=="edit")
                {
                    $cat_data=Cuisine::catData($id);
                    $tpl->assign('cat_data',$cat_data);
                    $c_cont=$tpl->get("cuisine-add-edit");    
                }
                if($com=="update")
                {   //echo $cat;
                    $cat = explode("-",$cat);
                    $arr_value['id_group']=$cat[0];
                    $arr_value['name']=$cat[1];
                    
                    //$arr_value['type']=$type;
                    $arr_value['caption_ru']=$caption;
                    $arr_value['on_main_page']=$on_main_page;
                    $arr_value['seo']=$seo!=""?$seo:Text::cirilToLatin($arr_value['caption_ru']);
                    $arr_value['date']=isset($date) && $date!=""?$date:date("Y-m-d H:i:s");
                    $arr_value['title_ru']=$meta_title;
                    $arr_value['meta_description']=$meta_description;
                    $arr_value['meta_keywords']=$meta_keywords;
                    $arr_value['output']=$output;
                    $arr_value['price']=$price;
                    $arr_value['content_full_ru']=$content_full_ru;
                    if($on_main_page == "1")
                    {
                        $arr_value['on_main_page']="Y"; 
                    }
                    else
                    {
                        $arr_value['on_main_page']="N";
                    }
                    if($enabled == "1")
                    {
                        $arr_value['showing_ru']="Y"; 
                    }
                    else
                    {
                        $arr_value['showing_ru']="N";
                    }
                    
                    $nowdata=getdate(time());
                    //$arr_value['pos']=isset($pos)?$pos:$nowdata[0];

                    $table="steak_cuisine";

                    if($id!="")
                    {
                        $where['id']=$id;
                        $db = new mysql;
                        $res = $db->updateSQL ($table, $arr_value, $where);
                    }
                    else
                    {
                        $db = new mysql;
                        $id = $db->insertSQL ($arr_value, $table);    
                    }
                    $loc_url=str_replace("com=update","com=edit",PAGE_URL);
                    $loc_url=str_replace("&id=".$id."","",$loc_url);
                    $loc_url=$loc_url."&id=".$id."&upd=ok";

                    header("Location: ".$loc_url."");

                }
                if($com=="delete")
                {
                    //$dir = SITE_PATH."/cms/sender/e-mail_base/";
                    $filearray = Sender::getEmailBaseList(PATH_EMAIL_BASE);
                    foreach($filearray as $key => $value)
                    {
                        if($_GET['name'] == $value)
                        {
                            unlink(PATH_EMAIL_BASE."/{$value}");
                           
                            $loc_url=str_replace("com=delete","com=view",PAGE_URL);
                            $loc_url=str_replace("&name={$value}","",$loc_url);
                            header("Location: {$loc_url}");
                        }
                    }   
                }
          }
          $tpl->assign('filearray',$filearray);
          $tpl->assign('count_row_email_file',$count_row_email_file); 
          
          
          $page=$tpl->get("sender");
          
          return $page;
    }
    
    function senderOffice()
    {
        $tpl = new AdmTpl;
        //$tpl->assign('listing',"jhjkj");
        $page=$tpl->get("sender");
        
        return $page;
    }   
    
    function getEmailBaseList($dir)
    {   
        $filearray = DirectoryItems::dirItems($dir);
        $filearray = DirectoryItems::getOnlyType($filearray,"txt");
        return $filearray;
        //SYS::varDump($filearray);
    }
    
    function getCountRowEmailFile($file)
    {
        $count_row_email_file = DirectoryItems::countFileLine(PATH_EMAIL_BASE."/{$file}");
        return $count_row_email_file;
    }
    
    function uploadFile($uploaddir)
    {
        $uploadfile = $uploaddir."/". basename($_FILES['userfile']['name']);
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
        {
             sender::addEmailToBD($_FILES['userfile']['name']);
             echo "<center><h2>Загрузили.</h2></center>\n";
             echo "<meta http-equiv=refresh content=2; url='{$_SERVER['PHP_SELF']}'>";
        } 
        else 
        {
            echo "<center><h2>Произошла ошибка. Попробуйте еще раз.</h2></center>\n";
        }
    }

    function addEmailToBD($file)
    {   
        $path_file = PATH_EMAIL_BASE."/".$file;
        $fp = fopen($path_file, 'r');
if ($fp) 
{
while (!feof($fp))
{
$mytext = fgets($fp, 1000);
echo $mytext."<br />";
}
}
else echo "Ошибка при открытии файла";
fclose($fp); 
        //Sys::varDump($fp);   
    }
    
    function getMessageData()
    {
        $tpl=new AdmTpl;
        if(isset($_GET['tepl']) && $_GET['tepl'] !== "")
        {
            $query = "SELECT * FROM `message` WHERE `id`='{$_GET['tepl']}'";
            $res = mysql_query($query);
            while($row = mysql_fetch_array($res))
            {
               $subject = $row['subject'];
               $htmlCode = $row['message'];
            }
            $tpl->assign('subject',$subject);
            $tpl->assign('htmlCode',$htmlCode);
        }
        
    }
    
          
}
?>