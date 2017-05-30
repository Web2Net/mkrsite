<?php
class Faq {

    function admFaq() 
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }

        $tpl=new AdmTpl;
        
        if($com=="view" &&$id=="" && $category == "question")
        {
            $page_html=Faq::FaqList($category);
        }
        if($com=="view" && $id=="" && $category == "answer")
        {
            $page_html=Faq::catListData();
        }
        if($com=="view" && isset($id) && $id!=="" && $category == "answer")
        {
            $page_html=Faq::AnswerList($id);  
        }
        if($com=="view" && $see == "all")
        {
            $page_html = Faq::getAllFaq();
        }
        if($com=="edit"&&$id!="")
        {
            $page_html=Faq::editFaq($id);
                
        }
        if($com=="update"&&$id!="")
        {
            $page_html=Faq::updateFaq($id);
        }
        if($com=="delete"&&$id!="")
        {
            $page_html=Faq::deleteFaq($id);    
        }
        return $page_html;

    }

    function categoryList()
    {
        $tpl=new AdmTpl;
        $db = new mysql;
        
        $select="";
        $from="faq_category";
        $where["showing"]="Y";
        $order="`caption`";

        $category_list = $db->selectSQL($select, $from, $where, $order);
        return $category_list;
    }
    
    function catListData()
    {
        $tpl=new AdmTpl;
        $db = new mysql;
        
        
        $select="";
        $from="faq_category";
        $where["showing"]="Y";
        $order="`caption`";

        $row = $db->selectSQL($select, $from, $where, $order);
        $tpl->assign('category_list',$row);
        $html=$tpl->get("faq-category-list");
        return $html;
    }
    
    function FaqList($category){
        
    $tpl=new AdmTpl;
    $tpl->assign('mod_name',"Вопрос/ответ");

    if($category=="question"){
    $tpl->assign('cat_name',"Новые вопросы");
        
    $select="";
    $from="faq";
    $where="showing='N'";  //$where="showing='N' || `category_id` = '0'";
    $sortby="date DESC";
    $db = new mysql;
    $row = $db->origSelectSQL($select, $from, $where, $sortby);
    }
    if($category=="answer"){
    $tpl->assign('cat_name',"Обработанные вопросы");
        
    $select="";
    $from="faq";
    $where="showing='Y'";
    $sortby="date DESC";
    $db = new mysql;
    $row = $db->origSelectSQL($select, $from, $where, $sortby);
    }
    $tpl->assign('list',$row);
    $html=$tpl->get("faq-list");    
    return $html;
    }
    
    function AnswerList($id)
    {
        $tpl=new AdmTpl;
        
        $tpl->assign('cat_name',"Обработанные вопросы");
            
        $select="";
        $from="faq";
        $where="`category_id` = '{$id}'";
        $sortby="date DESC";
        $db = new mysql;
        $row = $db->origSelectSQL($select, $from, $where, $sortby);
        
        $tpl->assign('list',$row);
        $html=$tpl->get("faq-list");    
        return $html;
    }

    function editFaq($id){

    $tpl=new AdmTpl;

    $select="";
    $from="faq";
    $where="id={$id}";
    $sortby="";
    $db = new mysql;
    $row = $db->origSelectSQL($select, $from, $where, $sortby,"0,1");

    $tpl->assign('mdata',$row[0]);
    $parent_list = Faq::categoryList();    
    $tpl->assign('parent_list',$parent_list);
    //$directory = "/img/brides/".$row[0]['email']."/small/";          
    //$smallPhotoList=Faq::showSmallPhoto($directory);
    //$tpl->assign('smallPhotoList', $smallPhotoList);

    $html=$tpl->get("faq-edit");    
    return $html;
    }

    function updateFaq($id)
    {
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }

        $arr_value['date']=$date;
        $arr_value['name']=$name;
        $arr_value['city']=$city;
        $arr_value['email']=$email;
        $arr_value['question']=$question;
        $arr_value['answer']=$answer;
        $arr_value['category_id']=$category_id;

        if($enabled==1){$arr_value['showing']='Y';}else{$arr_value['showing']='N';}

        $table="faq";

        $where['id']=$id;
        $db = new mysql;
        $res = $db->updateSQL ($table, $arr_value, $where);

        $loc_url=str_replace("com=update","com=view",PAGE_URL);
       //loc_url=str_replace("&id=".$id."","",$loc_url);
        //$loc_url=$loc_url;

        header("Location: ?mod=faq&com=view&category=question");
    }

    function deleteFaq($id){
        
    $from="faq";    
    $where['id']=$id;    
    $db = new mysql;
    $res = $db->deleteSQL ($from, $where);
    $loc_url=str_replace("com=delete","com=view",PAGE_URL);
    $loc_url=str_replace("&id=".$id."","",$loc_url);
    header("Location: ".$loc_url."");
    }

    function Navigate(){

    $tpl=new AdmTpl;
    $art_navigate=$tpl->get('faq-navigate');

    return $art_navigate;
    }
    
    function getAllFaq()
    {
        $tpl=new AdmTpl;
         
        $select="";
        $from="faq";
        $where="1 = 1";
        $sortby="`date` DESC";
        $db = new mysql;
        $row = $db->origSelectSQL($select, $from, $where, $sortby);
        
        $tpl->assign('list',$row);
        $html=$tpl->get("faq-list");    
        return $html; 
    }
}
?>