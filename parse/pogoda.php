<?
function startElement($parser, $name, $attrs) {
    global $depth;
       
   // echo str_repeat("&nbsp;", $depth * 3); // отступы
   // echo "<b>Element: $name</b><br>";      // имя элемента
       
    $depth++; // увеличиваем глубину, чтобы браузер показал отступы
       
    foreach ($attrs as $attr => $value) {
       // echo str_repeat("&nbsp;", $depth * 3); // отступы
        // выводим имя атрибута и его значение
       // echo 'Attribute: '.$attr.' = '.$value.'<br>';
        if($attr=="TEMP"){echo $value;}
    }
}

function endElement($parser, $name) {
    global $depth;
       
    $depth--; // уменьшаем глубину
}

$depth = 0;
$file  = "http://www.autodealer.ua/rssreader/pogoda.rss";

$xml_parser = xml_parser_create();

xml_set_element_handler($xml_parser, "startElement", "endElement");

if (!($fp = fopen($file, "r"))) {
    die("could not open XML input");
}

while ($data = fgets($fp)) {
    if (!xml_parse($xml_parser, $data, feof($fp))) {
        echo "<br>XML Error: ";
        echo xml_error_string(xml_get_error_code($xml_parser));
        echo " at line ".xml_get_current_line_number($xml_parser);
        break;
    }
}
$filename = "../rssreader/pogoda.rss";
$modif=time()- @filemtime ("$filename"); 
$rss  = "http://xml.weather.yahoo.com/forecastrss?p=UPXX0016&u=c";
if(!file_exists($filename) || $modif > "1800")
{
	if(isset($rss)){
	$rss = file_get_contents($rss);
	
	$handle = fopen ("$filename", "w");
	fwrite($handle, $rss);
	fclose($handle);
	}	
}

xml_parser_free($xml_parser);
?>