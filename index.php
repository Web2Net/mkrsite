<?php
//error_reporting(E_ALL | E_STRICT) ;
//ini_set('display_errors', 'On');

//Считываем текущее время
$mtime = microtime();
//Разделяем секунды и миллисекунды
$mtime = explode(" ",$mtime);
//Составляем одно число из секунд и миллисекунд
$mtime = $mtime[1] + $mtime[0];
//Записываем стартовое время в переменную
$tstart = $mtime; 
//---------------------------------------------


include_once ("kernel/cfg.php");
include_once ("site/logic.php");


//----------------------------------------------
//Делаем все то же самое, чтобы получить текущее время
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
//Записываем время окончания в другую переменную
$tend = $mtime;
//Вычисляем разницу
$totaltime = ($tend - $tstart);
//Выводим не экран
//echo "<div style='text-align:center;font-size:11px;'>".$totaltime."</div>"; 

?>