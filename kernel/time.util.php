<?php
	
class Time {
	
function formatMonth($data){
$gdate=getdate(strtotime($data));
//var_dump($gdate);
$months[1] = "января";
$months[2] = "февраля";
$months[3] = "марта";
$months[4] = "апреля";
$months[5] = "мая";
$months[6] = "июня";
$months[7] = "июля";
$months[8] = "августа";
$months[9] = "сентября";
$months[10] = "октября";
$months[11] = "ноября";
$months[12] = "декабря";
$ndata="".$gdate['mday']." ".$months[$gdate['mon']]." ".$gdate['year']."";
return $ndata;
}

function formatMonthOnly($data){
$gdate=getdate(strtotime($data));
//var_dump($gdate);
$months[1] = "января";
$months[2] = "февраля";
$months[3] = "марта";
$months[4] = "апреля";
$months[5] = "мая";
$months[6] = "июня";
$months[7] = "июля";
$months[8] = "августа";
$months[9] = "сентября";
$months[10] = "октября";
$months[11] = "ноября";
$months[12] = "декабря";
$ndata="".$months[$gdate['mon']]."";
return $ndata;
}

function getMonthUA(){

$months["01"] = "Січень";
$months["02"] = "Лютий";
$months["03"] = "Березень";
$months["04"] = "Квітень";
$months["05"] = "Травень";
$months["06"] = "Червень";
$months["07"] = "Липень";
$months["08"] = "Серпень";
$months["09"] = "Вересень";
$months["10"] = "Жовтень";
$months["11"] = "Листопад";
$months["12"] = "Грудень";
return $months;
}

function getMonthRU(){

$months["01"] = "Январь";
$months["02"] = "Февраль";
$months["03"] = "Март";
$months["04"] = "Апрель";
$months["05"] = "Май";
$months["06"] = "Июнь";
$months["07"] = "Июль";
$months["08"] = "Август";
$months["09"] = "Сентябрь";
$months["10"] = "Октябрь";
$months["11"] = "Ноябрь";
$months["12"] = "Декабрь";
return $months;
}

function formatGetTime($data){
$gdate=strtotime($data);
//var_dump($gdate);
$ndata=date("H:i",$gdate);
return $ndata;
}

function formatGetYear($data){
$gdate=strtotime($data);
//var_dump($gdate);
$ndata=date("Y",$gdate);
return $ndata;
}

function formatGetMonth($data){
$gdate=strtotime($data);
//var_dump($gdate);
$ndata=date("m",$gdate);
return $ndata;
}

function formatGetDay($data){
$gdate=strtotime($data);
//var_dump($gdate);
$ndata=date("d",$gdate);
return $ndata;
}

}

?>