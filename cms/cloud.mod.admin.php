<?php
class Cloud 
{

    function admCloud() 
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
                $imgname=Text::cirilToLatin($testName[0]).".".$testName[1];
                $imgnamesmall=Text::cirilToLatin($testName[0])."-small.".$testName[1];
                $mess=Cloud::loadImage($_FILES["file"]);
                if($mess=="OK")
                {
                    $imgdir = "/site/design/img/health/";
                    $GLOBALS['_RESULT'] = array(
                                                "image" => $imgdir.$imgname,
                                                "image_small" => $imgdir.$imgnamesmall,    
                                                "mess" => $mess,
                                                ); 
                }
            }
        }
        else
        {     
                if($com=="view")
                {
                    $row=Cloud::artListData($order, $strim);
                    $tpl->assign('listing',$row);
                    $c_cont=$tpl->get("cloud-list");    
                }
                //if($com=="add")
//                {
//                    $c_cont=$tpl->get("nav-add-edit");    
//                }
//                if($com=="edit")
//                {
//                    $cat_data=Cloud::catData($id);
//                    $tpl->assign('cat_data',$cat_data);
//                    $c_cont=$tpl->get("nav-add-edit");    
//                }
//                if($com=="update")
//                {   
//                  foreach($_REQUEST as $key=>$val)
//                  {
//                      $var = explode("__",$key);
//                      if($var['0'] == "form")
//                      {
//                          $arr_value[$var['1']] = $val;
//                      }
//                  }                             
//                    $arr_value['type']=$type;
//                    $arr_value['dt']=isset($dt) && $dt!=""?$dt:date("Y-m-d H:i:s");
//                   
//                   if($form_showing == "1")
//                    {
//                        $arr_value['showing']="Y"; 
//                    }
//                    else
//                    {
//                        $arr_value['showing']="N";
//                    }
//                    
//                    $arr_value['seolink'] = Text::cirilToCirilSeolink(Text::cutForCirilicSeolink($form__caption));

//Sys::varDump($arr_value);

//                    $table = "mr_nav";
//                    
//                    if($id!="")
//                    {
//                        $db = new mysql;
//                        $where['id'] = $id;
//                        $res = $db->updateSQL ($table, $arr_value, $where); 
//                    }                 
//                    else
//                    {   
//                        $db = new mysql;
//                        $id = $db->insertSQL ($arr_value, $table);    
//                    }
//                    $loc_url=str_replace("com=update","com=view",PAGE_URL);
//                    $loc_url=str_replace("&id=".$id."","",$loc_url);
//                    $loc_url=$loc_url."&id=".$id."&upd=ok";

//                    header("Location: ".$loc_url."");

//                }
//                if($com=="delete")
//                {
//                    $from="mr_nav";    
//                    $where['id']=$id;    
//                    $db = new mysql;
//                    $res = $db->deleteSQL ($from, $where);
//                    $loc_url=str_replace("com=delete","com=view",PAGE_URL);
//                    $loc_url=str_replace("&id={$id}","",$loc_url);
//                    header("Location: {$loc_url}");
//                }


                     return $c_cont;
        }
        
    }


    function artListData($order, $strim)
    {
        $select="";
        $from="mr_health";
        $where["showing"]= "Y";
        $order = "counts DESC";
        
        
        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, $order." ".$strim,"19");

        return $row;
    }
    
    function Navigate()
    {
        $row=Nav::catListData();

        $tpl=new AdmTpl;
        $tpl->assign('category',$row);
        $art_navigate=$tpl->get('cloud-navigate');

        return $art_navigate;
    }

    

}
?>