<?
//error_reporting(E_ALL | E_STRICT) ;
//ini_set('display_errors', 'On');
session_start();
$_SESSION["admin_name"]="admin";

include_once("../kernel/kernel.php");
include_once ("page.mod.admin.php");
include_once ("users.mod.admin.php");
include_once ("faq.mod.admin.php");
include_once ("items.mod.admin.php");
include_once ("search.mod.admin.php");
include_once ("article.mod.admin.php");
include_once ("nav.mod.admin.php");
include_once ("cloud.mod.admin.php");
include_once ("dump.mod.admin.php");
include_once ("banner.mod.admin.php");
include_once ("award.mod.admin.php");
include_once ("vote.mod.admin.php");
include_once ("feedback.mod.admin.php");
include_once ("apparatus.mod.admin.php");
include_once ("price.mod.admin.php");
include_once ("filter.mod.admin.php");
include_once ("works.mod.admin.php");

include_once ("sender.mod.admin.php");
include_once ("contacts.mod.admin.php");

//include_once ("subscribe.mod.admin.php");


foreach ($_REQUEST as $key=>$val){
$str="$".$key."=\$val;";
eval($str);
}

if(getenv("QUERY_STRING")==""){
    $loc_url=ADM_SITE_URL."/?mod=article&type=category&com=view";
    header("Location: ".$loc_url."");
}

if($ext=="ajax"){
    
include_once SITE_PATH."/lib/JsHttpRequest/lib/JsHttpRequest/JsHttpRequest.php";
//$JsHttpRequest =& new JsHttpRequest("windows-1251");
$JsHttpRequest =& new JsHttpRequest("utf-8");

header("Content-type: text/plain; charset=".CHARSET."");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

}
        //?mod=article&type=category&com=view
if($mod=='page')
{
    $c_cont=Page::admPage();
}
else if($mod=='dump')
{
    $c_cont=Dump::admDump();
}
else if($mod=='price')
{
    $c_cont=Price::admPrice();
}
else if($mod=='items')
{
    $c_cont=Items::admItems();
}
else if($mod=='users'){
$c_cont=Users::admUsers();
}
else if($mod=='faq'){
$c_cont=Faq::admFaq();
}
else if($mod=='article'){
$c_cont=Article::admArticle();
}
else if($mod=='subscribe'){
$c_cont=Subscribe::admSubscribe();
}
else if($mod=='search'){
$c_cont=Search::admSearch();
}
else if($mod=='nav'){
$c_cont=Nav::admNav();
}
else if($mod=='cloud'){
$c_cont=Cloud::admCloud();
}
else if($mod=='banner'){
$c_cont=Banner::admBanner();
}
else if($mod=='award'){
$c_cont=Award::admAward();
}
else if($mod=='vote'){
$c_cont=Vote::admVote();
}
else if($mod=='feedback'){
$c_cont=Feedback::admFeedback();
}
else if($mod=='sender'){
$c_cont=Sender::admSender();
}
else if($mod=='contacts'){
$c_cont=Contacts::admContacts();
}
else if($mod=='filter'){
    $c_cont=Filter::admFilter();
}
else if($mod=='works'){
    $c_cont=Works::admWorks();
}

if($ext!="ajax"&&$ext!="tab")
{   
    $l_cont.=Article::Navigate();
    $l_cont.=Faq::Navigate();
    //$l_cont.=Feedback::Navigate();
    //$l_cont.=Cloud::Navigate();
    //$l_cont.=Items::Navigate(); 
    $l_cont.=Nav::Navigate();
    $l_cont.=Page::Navigate();
    //$l_cont.=Banner::Navigate();
    //$l_cont.=Apparatus::Navigate();   
   // $l_cont.=Award::Navigate();
    $l_cont.=Search::Navigate();
    $l_cont.=Users::Navigate();
    //$l_cont.=Vote::Navigate();
    
    
    //$l_cont.=Opinion::Navigate();

$tpl = new AdmTpl;

$tpl->assign('c_cont', $c_cont); 
$tpl->assign('l_cont', $l_cont);

$tpl->display('index');

}else if($ext=="tab"){
$tpl = new AdmTpl;

$tpl->assign('c_cont', $c_cont);

$tpl->display('tab');

}

?>
