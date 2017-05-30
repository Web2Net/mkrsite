<?php

include_once ("cfg.php");

include_once ("sys.util.php");		
include_once ("time.util.php");
include_once ("text.util.php");
include_once ("gd.util.php");
include_once ("mysql.class.php");
include_once ("template.class.php");
include_once ("DirectoryItems.class.php");
include_once ("CheckMobile.class.php");
include_once ("graphics.class.php");
include_once ("setting.class.php");

include_once ("BetweenTimes.class.php");//Отвечает за изменения чого-либо в зависимости от времени, даты и т.д.

if($_REQUEST['seolink'] == "mobi")
{
    $_SESSION['ismobile'] = "/mobi";
}
if($_REQUEST['seolink'] == "pc")
{
    $_SESSION['ismobile'] = "";
}

if(Setting::setSetting("mobi"))
{
     if(!isset($_SESSION['ismobile']))
    {
        $m=new Mobile_Detect();
        if($m->isMobile()){$_SESSION['ismobile'] = "/mobi";}else{$_SESSION['ismobile'] = "";}
        
    //    $ismobi = checkMobile::isMobile();                                 
    //    if($ismobi !== FALSE)
    //    {
    //        $_SESSION['ismobile'] = "/mobi";
    //    }
    //    else
    //    {
    //        $_SESSION['ismobile'] = "";
    //    }
    }

}

     

class SiteTpl extends Tpl
{
	function SiteTpl ()
	{
       $this->template_dir = SITE_PATH.'/site/tpl'.$_SESSION['ismobile'];
	}
}

class AdmTpl extends Tpl
{
	function AdmTpl ()
	{
		$this->template_dir = SITE_PATH.'/cms/tpl';
	}
}



?>