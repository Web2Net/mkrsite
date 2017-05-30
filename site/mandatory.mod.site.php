<?
    
class Mandatory
{
    function mandatoryElements()
    {
         if(!isset($_SESSION['navigate']['main']))
         {
            Mandatory::getNavigate("main");
         }
         if(!isset($_SESSION['navigate']['second']))
         {
             Mandatory::getNavigate("second");
         }
//         if(!isset($_SESSION['navigate']['health']))
//         {
//             Mandatory::getNavigateHealth();
//         }
//         if(!isset($_SESSION['clouds']))
//         {
//             Mandatory::getClouds();
//         }
//         if(!isset($_SESSION['hit_mounth']))
//         {
//             Mandatory::getHit();
//         }
//         if(!isset($_SESSION['videogid']))
//         {
//             Mandatory::getVideogid();
//         } 
//         if(!isset($_SESSION['demo_enable']))
//         {
//             Mandatory::getDemo();
//         }
//         if(!isset($_SESSION['award']))
//         {
//             Mandatory::getAward();
//         }
//         if(!isset($_SESSION['apparatus']))
//         {
//             Mandatory::getApparatus();
//         }
         
    }
    
    function getNavigate($val)
    {
        //unset($_SESSION['navigate'][$val]);
        
        $db =new mysql;
        $select = "name, seolink, type,caption";
        $from="`nav`";
        $where="`location`='{$val}' AND `showing`='Y'";
        $_SESSION['navigate'][$val] = $db->origSelectSQL($select, $from, $where, "`order_show`", "");
    }
    
    function getNavigateHealth()
    {
        unset($_SESSION['navigate']['health']);
        
        $db =new mysql;
        $select = "seolink, caption";
        $from="`mr_health_category`";
        $where="`showing`='Y'";
        $_SESSION['navigate']['health'] = $db->origSelectSQL($select, $from, $where, "`caption`", "");
    }
    
    function getVideogid()
    {
        unset($_SESSION['videogid']);
        
        $db =new mysql;
        $select = "id, seo, image_small, caption"; 
        $from="`art_article`";
        $where="`type`='videogid' AND `enabled`='1'";
        $_SESSION['videogid'] = $db->origSelectSQL($select, $from, $where, "`date` DESC", "1"); 
    }
    
    function getDemo()
    {
        unset($_SESSION['demo_enable']);
        
        $db =new mysql;
        $select = "category_name,seolink, demo_caption, demo_price"; 
        $from="`mr_health`";
        $where="`demo_enable`='1' AND `showing`='Y'";
        $_SESSION['demo_enable'] = $db->origSelectSQL($select, $from, $where, "`caption`", ""); 
    }                              
    
    function getAward()
    {
        unset($_SESSION['award']);
        
        $db =new mysql;
        $select = "id,caption,image";
        $from="`mr_award`";
        $where="`enable`='1'";
        $_SESSION['award'] = $db->origSelectSQL($select, $from, $where, "`id` DESC", ""); 
    }
    
    function getHit()
    {   
        $db =new mysql;
        $select = "type,seolink,caption,image_small,counts";
        $from="`mr_health`";
        $where="`showing`='Y'";
        $_SESSION['hit_mounth'] = $db->origSelectSQL($select, $from, $where, "`counts` DESC", "10"); 
    }
    
    function getClouds()
    {   
        $db =new mysql;
        $select = "type,seolink,caption,image_small,counts";
        $from="`mr_health`";
        $where="`showing`='Y'";
        $_SESSION['clouds'] = $db->origSelectSQL($select, $from, $where, "`counts` DESC", "20"); 
    }
    
    function getApparatus()
    {
        $db =new mysql;
        $select = "id,type,caption,image";
        $from="`mr_apparatus`";
        $where="`showing`='Y'";
        $_SESSION['apparatus'] = $db->origSelectSQL($select, $from, $where, "`id` DESC", ""); 
    }
    
    
}
?>