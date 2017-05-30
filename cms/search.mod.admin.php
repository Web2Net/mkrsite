<?php
class Search 
{

    function admSearch() 
    {
        $tpl = new Tpl;
        foreach ($_REQUEST as $key=>$val)
        {
            $str="$".$key."=\$val;";
            eval($str);
        }
        
        $tpl->assign('mod_name',"Запросы");
        
        if($com=="view")
        {
            $row=Search::searchList();
            //if($_SESSION['level']<9)
//            {
//                unset($row[0]);
//            }
            $tpl->assign('listing',$row);
            $tpl->assign('type_name',"Обзор");
            $c_cont=$tpl->get("search-list");
        }
        
        return $c_cont;
    }

    function Navigate()
    {
        //$row=Creator::catListData();

        $tpl=new AdmTpl;
        $tpl->assign('category',$row);
        $art_navigate=$tpl->get('search-navigate');

        return $art_navigate;
    }

    function searchList()
    {
        $select="";
        $from="search";
        $where ="1=1";
        $sortby="`count` DESC";

        $db = new mysql;
        $row = $db->origSelectSQL($select, $from, $where, $sortby,"0,70");

        return $row;
    }

}
?>