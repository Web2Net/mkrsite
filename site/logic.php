<?
session_start();//Стартуем сессию, шоб всегда було :)

include_once(SITE_PATH."/kernel/kernel.php"); //инклудим ядро (библиотеки повторяющихся функций, конфиг и дефайны)

foreach ($_REQUEST as $key=>$val)//делаем из массива всех постов и гетов переменные, $_REQUEST["seolink"] становится $seolink, просто для удобства обращения
{
    $str="$".$key."=\$val;";
    eval($str);
}

$seodata=explode("/",$seolink); //распарсиваем $seolink (всё что после домена, это команды, первая команда после домена и до "/", $mod = какому модулю адресуются последующие команды) 
$mod=$seodata[0];     
$rub=$seodata[1];

include_once ("mandatory.mod.site.php");  // Пытаюсь собрать все, что ОБЯЗАТЕЛЬНО выполняется..
$page_data = Mandatory::mandatoryElements();
                                                  
include_once ("main.mod.site.php");
include_once ("page.mod.site.php");
include_once ("faq.mod.site.php");
include_once ("opinions.mod.site.php");
include_once ("article.mod.site.php");
include_once ("items.mod.site.php");
include_once ("search.mod.site.php"); 
include_once ("sitemap.mod.site.php");
include_once ("banner.mod.site.php");
include_once ("vote.mod.site.php");
include_once ("feedback.mod.site.php");
include_once ("errors.mod.site.php");

include_once ("shopping.mod.site.php"); // Магазин

$tpl = new SiteTpl; 

if(getenv("QUERY_STRING")==""||$mod=="") //Что выводить когда главная
{
    //$page_data=Main::viewMainModPage();
    $page_data=Article::viewArticleModPage();
    $content=$page_data["content"];
    $meta=$page_data["meta"];
//    $meta["title"]=SITE_NAME;
//    $meta["description"]=SITE_NAME;
//    $meta["keywords"]=SITE_NAME;
}
if(getenv("QUERY_STRING")!=""||$mod!="") //Что выводить когда НЕ главная
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($mod == "mobi" || $mod == "pc")  // Переключалка мобильной/стандартной версии
    {
        header("Location: ".$_SERVER['HTTP_REFERER']."");    
    }
    if($mod=="search") // Поиск
    {
        $page_data=Search::viewSearchModPage();
        $content=$page_data["content"];
        $meta=$page_data["meta"];
    }
    else if($mod=="Карта_сайта")  //Карта сайта
    {   
        $page_data=Sitemap::viewSitemapModPage();
        $content=$page_data["content"];
        $meta=$page_data["meta"];
    }
    else if($mod=="каталог")  //товарs
    {   
        $page_data=Items::viewItemsModPage();
        $content=$page_data["content"];
        $meta=$page_data["meta"];
    }
    else // Ошибки
    {
        $page_data=Errors::viewErrorsModPage();
        $content=$page_data["content"];
        $meta=$page_data["meta"];
    }
//////////////////
//  Блок, для определения, какоe значение $mod работает с модулем PAGE
//////////////////
    $db =new mysql;
    $select = "`seolink`";
    $from="`nav`";
    $where="`modul`='page'";
    $mod_for_page = $db->origSelectSQL($select, $from, $where, "", "");
    if(count($mod_for_page) > 1)
    {
        foreach($mod_for_page as $k=>$v)
        {   
            if($mod == $v['seolink'])
            {   
                $page_data=Page::viewPageModPage();
                $content=$page_data["content"];
                $meta=$page_data["meta"];
            }
        }
    }
// /Блок, для определения, какоe значение $mod работает с модулем PAGE
    
//////////////////
//  Блок, для определения, какоe значение $mod работает с модулем Article  
//////////////////
    $db =new mysql;
    $select = "`seolink`";
    $from="`nav`";
    $where="`modul`='article'";
    $mod_for_articles = $db->origSelectSQL($select, $from, $where, "", "");
    if(count($mod_for_articles) > 1)
    {
        foreach($mod_for_articles as $k=>$v)
        {   
            if($mod == $v['seolink'])
            {   
                $page_data=Article::viewArticleModPage();
                $content=$page_data["content"];
                $meta=$page_data["meta"];
            }
        }
    }
//  /Блок, для определения, какоe значение $mod работает с модулем Article
    $tpl->assign('content', $content);
    $content=$tpl->get('page_second');
}

$tpl->assign('mod', $mod);   //отдали ему $content и $meta и заодно пусть $mod знает... ну и $rub до кучи))
$tpl->assign('rub', $rub); 
$tpl->assign('content', $content);
$tpl->assign('meta', $meta);
 
//if($_SESSION['ismobile'] !== FALSE) //отключаеи показ банеров в на мобильных дивайсах
//{
//$banbrand1=Banner::banBrand("branding-1");
$bantop1=Banner::banTop();
$banbott=Banner::banBottom();
//$tpl->assign('banbrand1', $banbrand1);
$tpl->assign('bantop1', $bantop1);
$tpl->assign('banbott', $banbott);
//}
 
$tpl->display('index');//вывели на экран шаблон страницы, предварительно отработав с переданными в шаблон переменными
 

unset($GLOBALS); //удаление всего массива $GLOBALS, для освобождения памяти 


//$tpl->display('request');  //темплейт с запросами, для отладки 

?>