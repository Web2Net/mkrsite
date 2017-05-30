<?php
class Users 
{

    function admUsers() 
    {
        $tpl = new Tpl;
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }
        
        $tpl->assign('mod_name',"Пользователи");
        
        if($com=="view")
        {
            $row=Users::usersListData();
            if($_SESSION['level']<9)
            {
                unset($row[0]);
            }
            $tpl->assign('listing',$row);
            $tpl->assign('type_name',"Обзор");
            $c_cont=$tpl->get("users-list");
        }
        if($com=="add")
        {
            $tpl->assign('type_name',"Создать");
            $c_cont=$tpl->get("users-add-edit");
        }
        if($com=="edit")
        {
            $cat_data=Users::catData($id);
            $tpl->assign('cat_data',$cat_data);
            $tpl->assign('type_name',"Редактировать");
            $c_cont=$tpl->get("users-add-edit");
        }
        if($com=="update")
        {   
            if($password == $password_1)
            {
                if(get_magic_quotes_gpc()) 
                { 
                    $username = stripslashes($username);
                    $password = stripslashes($password);
                }
                $username = mysql_real_escape_string(trim($username));
                $password = mysql_real_escape_string(trim($password));
                
                $arr_value['username'] = $username;
                $arr_value['password'] = md5($password);
            
                $db = new mysql;
                $table = "users";

                if($id != "")
                {
                    $oldPass = Users::checkOldPassword($password_old, $id);
                    if($oldPass)
                    {
                         $where['id'] = $id;
                         $res = $db->updateSQL ($table, $arr_value, $where);
                    }
                }
                else
                {
                    $id = $db->insertSQL ($arr_value, $table);    
                }
                $loc_url=str_replace("com=update","com=edit",PAGE_URL);
                $loc_url=str_replace("&id=".$id."","",$loc_url);
                $loc_url=$loc_url."&id=".$id."&upd=ok";

                header("Location: ".$loc_url."");
            }
        }
        if($com=="delete")
        {
            $from="users";    
            $where['id']=$id;    
            $db = new mysql;
            $res = $db->deleteSQL ($from, $where);
            $loc_url=str_replace("com=delete","com=view",PAGE_URL);
            $loc_url=str_replace("&id={$id}","",$loc_url);
            header("Location: {$loc_url}");
        }
        return $c_cont;
    }

    function Navigate()
    {
        //$row=Creator::catListData();

        $tpl=new AdmTpl;
        $tpl->assign('category',$row);
        $art_navigate=$tpl->get('users-navigate');

        return $art_navigate;
    }

    function catListData($type=0)
    {
        $select="";
        $from="`users`";
        $where["`type`"]=0;
        $sortby="`order`";

        $query = "SELECT * FROM `mr_catalog`";
        
        $res = mysql_query($query);
        while ($result = mysql_fetch_assoc ($res))
        {
            if($result['type'] == "1")
            {
                $query1 = "SELECT * FROM `mr_catalog` WHERE `parent`='{$result['id']}'";
                $res1 = mysql_query($query1);
                while ($result1 = mysql_fetch_assoc ($res1))
                {
                     $category[] = $result1;
                }
            }
            $category[] = $result;
        }
        return $category;
    }

    function catData($id)
    {
        $select="";
        $from="users";
        $where["id"]=$id;

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, "", 1);

        return $row[0];
    }

    function usersListData()
    {
        $select="";
        $from="users";
        $where ="1=1";
        $sortby="`level` DESC";

        $db = new mysql;
        $row = $db->origSelectSQL($select, $from, $where, $sortby,"0,70");

        return $row;
    }
    function artData($id)
    {
        $select="";
        $from="users";
        $where["id"]=$id;

        $db = new mysql;
        $row = $db->selectSQL($select, $from, $where, "", 1);

        return $row[0];
    }

     function checkOldPassword($password, $user_id)
     {
         $query = "SELECT `password` FROM `users` WHERE `id`='{$user_id}' LIMIT 1";
         $db = new mysql;
         $res = $db->simplySQL($query);
         foreach($res as $k=>$v)
         {
            if(md5($password) == $v)
            {
                return true; 
            }
            else
            {
                return false;
            }
         }
         
         
         
     }

    

}
?>