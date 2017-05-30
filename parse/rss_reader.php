
<?

error_reporting(E_ALL | E_STRICT) ;
ini_set('display_errors', 'On');
	
include_once(getenv("DOCUMENT_ROOT")."/kernel/kernel.php");

$rss="rssreader/price.xml";
$rub="autortnews";

function rssReader($rss, $rub){
//$filename = "rssreader/".$rub."_".str_replace("/","_",str_replace("http://","",$rss)).".rss";
$filename = "rssreader/price.xml";
$filetimer = "rssreader/timer.txt";
$modif=time()- @filemtime ("$filename");
$timer=time()- @filemtime ("$filetimer");
//echo "Modif : ".$modif."<br>";
if(!file_exists($filename) || $modif > "18")
{
	if(isset($rss)){
	$rss = file_get_contents($rss);
	
	$handle = fopen ("$filename", "w");
	fwrite($handle, $rss);
	fclose($handle);
	$handle = fopen ("$filetimer", "w");
	fwrite($handle, time());
	fclose($handle);
	}	
	
}
if(!file_exists($filename) || $modif > "18")
{
$RSS = simplexml_load_file($filename);
$db=New mysql;

foreach ($RSS->channel->item as $item) 
{
	$item->description = str_replace("&","",$item->description); 
    $item->detailtext = str_replace("&","",$item->detailtext); 
    
	$item->title = str_replace("&","",$item->title); 
	$item->title = str_replace("nbsp;"," ",$item->title); 
	$item->title = str_replace("quot;","",$item->title); 

   // $item->title = iconv("UTF-8","WINDOWS-1251",$item->title);	
	//$item->description = iconv("UTF-8","WINDOWS-1251",$item->description); 
	//$item->detailtext = iconv("UTF-8","WINDOWS-1251",$item->detailtext); 
    $imag=$item->enclosure;
    	
	$zamena=array("ndash;","hellip;");
    $caption=$item->title;
    $short_text =str_replace("'","`",str_replace($zamena,"",$item->description));    
    $full_text=str_replace("'","`",str_replace($zamena,"",$item->detailtext));
    $news_date=date("Y-m-d H:i:s",strtotime($item->pubDate));
    $img=$imag["url"];
    

	$caption=str_replace("'", "", $caption);
	$caption=trim(str_replace("`", "", $caption));
	
	$short_text =str_replace("'", "", $short_text );
	$short_text =trim(str_replace("`", "", $short_text ));
	$short_text=trim(str_replace("ldquo;", "«", $short_text));	
	$short_text=trim(str_replace("rdquo;", "»", $short_text));
	
	$full_text=str_replace("'", "", $full_text);
	$full_text=trim(str_replace("`", "", $full_text));
	$full_text=trim(str_replace("ldquo;", "«", $full_text));	
	echo $full_text=trim(str_replace("rdquo;", "»", $full_text));	

	$query = "INSERT INTO `rss_news` (caption ,img, short_text, full_text, news_date, rub) VALUES ('$caption' ,'$img' , '$short_text', '$full_text', '$news_date', '$rub')";
	//$db->origSQL($query);
	}
}
}

function clearTable(){
$db=New mysql;
$query = "TRUNCATE TABLE `rss_news`";
//$db->origSQL($query); 
}

function killOutLink($text){
$text=stripslashes($text);

preg_match_all('|<a.*href=(.*)>(.*)</a>|U',$text, $links);
foreach($links[0] as $key=>$val){
$linka=stripslashes($val);

preg_match('|href=\"(.*)\"|U',$linka, $matches3);
$matches3[0]=str_replace('"','',$matches3[0]);
$matches3[0]=str_replace('href=','',$matches3[0]);
@$host = $matches3[0];

preg_match('|>(.*)<|U',$linka, $textlink);
$textlink[0]=str_replace('>','',$textlink[0]);
$textlink[0]=str_replace('<','',$textlink[0]);

preg_match("/^(http:\/\/)?([^\/]+)/i",$host, $matches);
@$host = $matches[2];

preg_match("/[^\/]+\.[^\.\/]+$/", $host, $matches);
@$url=$matches[0];

$text=str_replace($linka,''.$textlink[0].'',$text);	
}

return $text;
}


clearTable();
rssReader($rss, $rub);

$from="art_article";
$where="full_text NOT LIKE '%рубл%'";
$db = new mysql;
$row = $db->origSelectSQL("", $from, $where,  "date_create DESC","","date_create");

if($row!=NULL){foreach($row as $key=>$val){

$fromo="art_article";
$whereo="date_create = '".$val["date_create"]."'";
$db = new mysql;
$rowo = $db->origSelectSQL("",$fromo,$whereo,"date_create DESC","0,1");

if($rowo!=NULL){$mozhno=0;}else{$mozhno=1;}
$arr_value['category_id']=17;
$arr_value['date_create']=$val["date_create"];
$arr_value['web_link']=$web_link;
//$arr_value['caption']=Text::coolKav($val["caption"]);;
$arr_value['caption']=$val["caption"];
$arr_value['short_text']=$val["short_text"];
$arr_value['full_text']=strip_tags($val["full_text"],"<p><div><br>")."<div>Источник: <a href=\"http://autorating.ru/\" target=\"_blank\">autorating.ru</a></div>";
$arr_value['image']=$val["img"];
$arr_value['image_small']=$image_small;
if($gallery_id!=0){$arr_value['gallery_id']=$gallery_id;}
$arr_value['smi']=$smi;
$arr_value['seo']=Text::cirilToLatin($arr_value['caption']);
$arr_value['meta_title']=$meta_title!=''?$meta_title:$arr_value['caption'];
$arr_value['meta_description']=$meta_description!=''?$meta_description:$arr_value['short_text'];
$arr_value['meta_keywords']=$meta_keywords;
$arr_value['see']=$see;
$arr_value['enabled']=1;
$nowdata=getdate(time());
$arr_value['pos']=isset($pos)?$pos:$nowdata[0];

var_dump($arr_value);

$table="art_article";

if($mozhno==1){
$db = new mysql;
//$id = $db->insertSQL ($arr_value, $table);
}
	
}}

?>