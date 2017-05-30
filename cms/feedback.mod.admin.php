<?php
class Feedback 
{
    function admFeedback()
    {
        foreach ($_REQUEST as $key=>$val)
        {
    //echo "{$key} = {$val}<br />";
            $str="$".$key."=\$val;";
            eval($str);
        }
        if($com=="view"&&$id=="")
        {
            $page_html=Feedback::FeedbackList($showing,$type);
        }
        if($com=="edit"&&$id!="")
        {
            $page_html=Feedback::viewFeedback($id,$type);    
        }
        if($com=="update"&&$id!="")
        {
            $page_html=Feedback::updateFeedback($id);    
        }
        if($com=="add")
        {    
            $page_html=Feedback::addFeedback($id);    
        }
        if($com=="delete"&&$id!="")
        {
            $page_html=Feedback::deleteFeedback($id);    
        }
         return $page_html;
    }

    function FeedbackList($showing,$type)
    {
        $tpl=new AdmTpl;
        if($type == "feedback"){$tpl->assign('mod_name',"Запись в клинику");}
        if($type == "feed"){$tpl->assign('mod_name',"Письмо админу");}
        if($type == "adver"){$tpl->assign('mod_name',"Заявка на рекламу");}

        if($showing=="n")
        {
            $tpl->assign('cat_name',"Новые");
            
            $select="";
            $from="mr_feedback";
            $where="`type`='{$type}' AND `showing`='N'";
            $sortby="`id` DESC";
            $db = new mysql;
            $row = $db->origSelectSQL($select, $from, $where, $sortby);
        }
        if($showing=="y")
        {
            $tpl->assign('cat_name',"Архив");
                
            $select="";
            $from="mr_feedback";
            $where="`type`='{$type}' AND `showing`='Y'";
            $sortby="id DESC";
            $db = new mysql;
            $row = $db->origSelectSQL($select, $from, $where, $sortby);
        }
        if($showing=="")
        {
            $tpl->assign('cat_name',"Все");
                
            $select="";
            $from="mr_feedback";
            $where="`type`='{$type}'";
            $sortby="`id` DESC";
            $db = new mysql;
            $row = $db->origSelectSQL($select, $from, $where, $sortby);
        }
//Sys::varDump($row);
        $tpl->assign('list',$row);
        $html=$tpl->get("feedback-list");    
        return $html;
    }

    function viewFeedback($id,$type)
    {
        $tpl=new AdmTpl;

        $select="";
        $from="mr_feedback";
        $where="id={$id}";
        $sortby="";
        $db = new mysql;
        $row = $db->origSelectSQL($select, $from, $where, $sortby,"0,1");
//Sys::varDump($row);
        $tpl->assign('mdata',$row[0]);

       
        if($type == "feedback"){$html=$tpl->get("feedback-edit");}
        if($type == "feed"){$html=$tpl->get("feedback-feed-edit");}
        if($type == "adver"){$html=$tpl->get("feedback-adver-edit");}
            
        return $html;
    }

    function updateFeedback($id)
    {
        $enabled = $_POST['form__showing'];
        
        if($enabled==1)
        {
            $arr_value['showing']='Y';
        }
        else
        {
            $arr_value['showing']='N';
        } 
        
        $table="mr_feedback";
        $where['id']=$id;
        $db = new mysql;
        $res = $db->updateSQL ($table, $arr_value, $where);

        $loc_url = str_replace("com=update","com=edit",PAGE_URL);
        $loc_url = str_replace("&id=".$id."","",$loc_url);
        $loc_url = $loc_url."&id=".$id."&upd=ok";

        header("Location: ".$loc_url."");
    }

    function deleteFeedback($id)
    {
        $from="mr_feedback";    
        $where['id']=$id;    
        $db = new mysql;
        $res = $db->deleteSQL ($from, $where);
        $loc_url=str_replace("com=delete","com=view",PAGE_URL);
        $loc_url=str_replace("&id=".$id."","",$loc_url);
        header("Location: ".$loc_url."");
    }

    function addFeedback($val)
    {
        $tpl=new AdmTpl;
        $tpl->assign('mod_name',"Запись");
        $html=$tpl->get("feedback-add");
        if(isset($_POST['save']))
        {
            Feedback::insertFeedback();
        }
        return $html;
    }

    function insertFeedback()
    {
         $db = new mysql; 
         unset($_POST['save']);
         unset($_POST['img_small']);
         $arr_value = $_POST;
         $arr_value['date'] = mktime();
        
         $table = "`mr_feedback`";
         $db->insertSQL($arr_value, $table);
         
         header("Location: ?mod=feedback&com=view");
    }

    function Navigate()
    {
        $tpl=new AdmTpl;
        $art_navigate=$tpl->get('feedback-navigate');

        return $art_navigate;
    }

    function showSmallPhoto($directory)
    {
        $dir = new DirectoryItems;
        $dir->dirItems(SITE_PATH.$directory);

        foreach($dir->filearray as $img)
        {
            $smallPhotoList[]="{$directory}{$img}";
        }
        return $smallPhotoList;
    }   

}
?>