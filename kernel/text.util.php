<?php
class Text 
{
	function cutStr($str,$cword){
    $nstr='';
    $str_exp = explode(" ", $str);
    for ($a = 0; $a <= $cword; $a++) {
    $nstr.=$str_exp[$a].' ';
    }
    $nstr=trim($nstr);
    if(isset($str_exp[$a+1]))$nstr.='&nbsp;...';

    return $nstr;
    }
    function cutStrBySymbol($text, $start, $limit)
    {
        $text=substr($text,$start,$limit);
        /*если не пустая обрезаем до  последнего  пробела*/
        if(substr($text,strlen($text)-1,1) && strlen($text)==$limit)
        {
            $textret=substr($text,0,strlen($text)-strlen(strrchr($text,' ')));
            if(!empty($textret))
            {
                return $textret;
            }
        }
        return $text;
    }
    function cut($string)
    {
        $string = trim($string);
        $string = htmlspecialchars($string);
        $string = str_replace("'","`",$string);
        return $string;
    }

    function cutString($string)
    {
        $string = trim($string);
        $string = str_replace("\""," ",$string);
        $string = str_replace("'"," ",$string);
        $string = str_replace("`"," ",$string);
        $string = htmlspecialchars($string);
        return $string;
    }
    function cutStringForMetaKey($string)
    {
        $string = trim($string);
        $string = str_replace("\""," ",$string);
        $string = str_replace("'"," ",$string);
        $string = str_replace("`"," ",$string);
        $string = str_replace(" ,",",",$string);
        $string = str_replace(",,",",",$string);
        $string = str_replace(",,,",",",$string);
        $string = str_replace("&","",$string);
        $string = str_replace("quot;","",$string);
        $string = str_replace("\r\n","",$string);
        $string = strip_tags($string);
        $string = htmlspecialchars($string);
        return $string;
    }
    function cutProbels($string)
    {
         while (strpos($string,'  ')!==false)
         {
           $string = str_replace('  ',' ',$string);
         }
         return $string;
    }
    function parseHtml($string)
    {
        $string = trim($string);
        $string = preg_replace("/[^\x20-\xFF]/","",@strval( $string ));
        $string = strip_tags($string,"<span><strong><i><img><a><u><p><ul><ol><li><br><object>
                <embed><param><table><tr><td><th><h1><h2><h3><h4><h5><hr><div><code><pre>" );
        
        $string = mysql_real_escape_string( $string );
        return $string;
    }
    
    function wordBreak($str, $max) 
    { 
        $array_words=explode(' ', $str); 
        $array_words_count=count($array_words); 
        $str=null; 
        for($i=0; $i<$array_words_count; $i++) 
        { 
            $word=$array_words[$i]; 
            if(strlen($word)>$max) $word=chunk_split($word, $max," "); 
            $str.=$word." "; 
        } 
        $str=trim($str); 
        return $str; 
    }
    
    function checkStrEmpty($str)
    {   
        if($str !== "")
        {
            return $str;
        }
        else
        {
            return false;
        }
    } 
    function cirilToLatin($str) 
    { 

    $arr = array( 
    'А' => 'A', 
    'Б' => 'B', 
    'В' => 'V', 
    'Г' => 'G', 
    'Д' => 'D', 
    'Е' => 'E', 
    'Ё' => 'E', 
    'Ж' => 'ZH', 
    'З' => 'Z', 
    'И' => 'I', 
    'Й' => 'Y', 
    'К' => 'K', 
    'Л' => 'L', 
    'М' => 'M', 
    'Н' => 'N', 
    'О' => 'O', 
    'П' => 'P', 
    'Р' => 'R', 
    'С' => 'S', 
    'Т' => 'T', 
    'У' => 'U', 
    'Ф' => 'F', 
    'Х' => 'KH', 
    'Ц' => 'TS', 
    'Ч' => 'CH', 
    'Ш' => 'SH', 
    'Щ' => 'SCH', 
    'Ъ' => '', 
    'Ы' => 'Y', 
    'Ь' => '', 
    'Э' => 'EH', 
    'Ю' => 'U', 
    'Я' => 'JA',
    'І' => 'I',    
    'Ї' => 'YI',
    'Є' => 'YE',
    'Ґ' => 'G',    
    'а' => 'a', 
    'б' => 'b', 
    'в' => 'v', 
    'г' => 'g', 
    'д' => 'd', 
    'е' => 'e', 
    'ё' => 'e', 
    'ж' => 'zh', 
    'з' => 'z', 
    'и' => 'i', 
    'й' => 'y', 
    'к' => 'k', 
    'л' => 'l', 
    'м' => 'm', 
    'н' => 'n', 
    'о' => 'o', 
    'п' => 'p', 
    'р' => 'r', 
    'с' => 's', 
    'т' => 't', 
    'у' => 'u', 
    'ф' => 'f', 
    'х' => 'kh', 
    'ц' => 'ts', 
    'ч' => 'ch', 
    'ш' => 'sh', 
    'щ' => 'sch', 
    'ъ' => '', 
    'ы' => 'y', 
    'ь' => '', 
    'э' => 'eh', 
    'ю' => 'u', 
    'я' => 'ja',
    'і' => 'i',    
    'ї' => 'yi',
    'є' => 'ye',
    'ґ' => 'g',                    
    "'" => '',
    ' ' => '-',
    '+' => 'plus',
    '&#352;' => 's',
    '&euml;' => 'e',            
    '.' => '',
    ',' => '',
    '"' => '',
    '?' => '',
    '!' => '',
    '№' => '-',
    '(' => '',
    ')' => '',
    '«' => '',
    '»' => '',
    '%' => '',
    ' "' => '',
    '\r' => '',
    ':' => '',
    ';' => '',
    '/' => '-',
    '&' => 'and',
    '—' => '',
    '–' => '',    
    '--' => '-',
    "'" => "",
    "“" => "",    
    "”" => "",
    "’" => "",                            
    "'" => ''
    ); 
    $key = array_keys($arr);
    $val = array_values($arr);
    $transl = str_replace($key,$val,trim($str)); 

    return strtolower($transl); 
    }

    function cutForCirilicSeolink($string)
    {                                                        
        $string = trim($string);
        //$string = htmlspecialchars($string);
        $string = str_replace("`","",$string);
        $string = str_replace(",","",$string);
        $string = str_replace("-","_",$string);
        $string = str_replace(" ","_",$string);
        $string = str_replace("'","",$string);
        $string = str_replace("%","_",$string);
        $string = str_replace(":","_",$string); 
        $string = str_replace("\"","",$string);
        $string = str_replace("\\","",$string);
        $string = str_replace("«","",$string);
        $string = str_replace("»","",$string);
        return $string;
    }

    function uncutForCirilicSeolink($string)
    {
        $string = trim($string);
        $string = str_replace(" ","_",$string);
        
        return $string;
    }

    function cirilToCirilSeolink($str) 
    {    
        $arr = array(
        '-' => '_',
        ' ' => '_',
        '.' => '',
        ',' => '',
        '"' => '',
        '?' => '',
        '!' => '',
        '№' => '',
        '(' => '',
        ')' => '',
        '«' => '_',
        '»' => '_',
        '%' => '_',
        ' "' => '_',
        '\r' => '_',
        ':' => '_',
        ';' => '_',
        '/' => '_',
        '&' => '_',
        '—' => '_',
        '–' => '_',    
        '--' => '_',
        '__' => '_',
        "'" => "",
        "“" => "",    
        "”" => "",
        "’" => "",                            
        "'" => '',
        "і" => 'i',
        "?" => '',
        ); 
        $key = array_keys($arr);
        $val = array_values($arr);
        $str = str_replace($key,$val,trim($str)); 
    
        //return strtolower($str); 
        return $str;
    } 

    function alp_data_ru()
    {
        $alphavit_ru = array( 
        'А' => 'r1', 
        'Б' => 'r2', 
        'В' => 'r3', 
        'Г' => 'r4', 
        'Д' => 'r5', 
        'Е' => 'r6', 
        'Ё' => 'r7', 
        'Ж' => 'r8', 
        'З' => 'r9', 
        'И' => 'r10', 
        'Й' => 'r11', 
        'К' => 'r12', 
        'Л' => 'r13', 
        'М' => 'r14', 
        'Н' => 'r15', 
        'О' => 'r16', 
        'П' => 'r17', 
        'Р' => 'r18', 
        'С' => 'r19', 
        'Т' => 'r20', 
        'У' => 'r21', 
        'Ф' => 'r22', 
        'Х' => 'r23', 
        'Ц' => 'r24', 
        'Ч' => 'r25', 
        'Ш' => 'r26', 
        'Щ' => 'r27', 
        'Э' => 'r28', 
        'Ю' => 'r29', 
        'Я' => 'r30',
         );
         return $alphavit_ru;
    }
    function alp_data_en()
    {
        $alphavit_en = array(
        'A' => 'e1',
        'B' => 'e2',
        'C' => 'e3',
        'D' => 'e4',
        'E' => 'e5',
        'F' => 'e6',
        'G' => 'e7',
        'H' => 'e8',
        'I' => 'e9',
        'J' => 'e10',
        'K' => 'e11',
        'L' => 'e12',
        'M' => 'e13',
        'N' => 'e14',
        'O' => 'e15',
        'P' => 'e16',
        'Q' => 'e17',
        'R' => 'e18',
        'S' => 'e19',
        'T' => 'e20',
        'U' => 'e21',
        'V' => 'e22',
        'W' => 'e23',
        'X' => 'e24',
        'Y' => 'e25',
        'Z' => 'e26'
        );
         return $alphavit_en;
    }
    
    function strtolower_utf8($text)
    {
         //$text = mb_convert_case($text, MB_CASE_LOWER, "UTF-8");
         return $text;
    }
    function strtolower_ru($text) // замена function strtolower_utf8($text), если на сервере не присутствовует библиотека mb_string.
    {
         $alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');
         $alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');
         return str_replace($alfavitupper,$alfavitlover,strtolower($text));
    }
	
	static function checkBoxProcess($var){
	if($var == '1'){
		$var = 'Y';
	}
	else{
		$var = 'N';
	}
	return $var;
}
}

?>