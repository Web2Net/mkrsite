<?	
 class Banner{
 	 
function banBrand($banname){

$from="banner_zone";
$where="name='{$banname}' AND enabled=1";

$db =new mysql;
$row = $db->origSelectSQL("", $from, $where, "name ASC","0,1");
//var_dump($row);
$tpl = new SiteTpl;
$tpl->assign('banner', $row[0]);
$banhtml=$tpl->get('ban_brand');

return $banhtml;
}

function banTop(){

$from="banner_zone";
$where="`location`='top' AND `enabled`='1'";

$db =new mysql;
$row = $db->origSelectSQL("", $from, $where, "`name` ASC","");

$tpl = new SiteTpl;
$tpl->assign('banner', $row);
$banhtml=$tpl->get('ban_top');

return $banhtml;
}

function banBottom(){

$from="banner_zone";
$where="`location`='bottom' AND `enabled`='1'";

$db =new mysql;
$row = $db->origSelectSQL("", $from, $where, "`name` ASC","");

$tpl = new SiteTpl;
$tpl->assign('banner_bottom', $row);
$banhtml=$tpl->get('ban_bottom');

return $banhtml;
}


}
?>