<?php defined('WEB2NET') or die();?>

<div id="wrap_paginator">
<?if($page>1){?>
        <a href="/<?=$rub?>/page-<?=$page-1?>/" class="pagenum" style="font-size:13px;">« <i>Туда</i></a><?}?>
&nbsp;
<?$sko=1;$page==""?$page=1:$page=$page;
if($page<=7){$i=$sko;}else{$i=$page-6;}
while($sko<11&&$i<=$pages){?> 
<?if($page==$i){?>
        <span class="pagenumnow"><?=$i?></span>
<?}else{?>
        <a href="/<?=$rub?>/<?if($i>1){?>page-<?=$i?>/<?}?>" class="pagenum" style="font-size:11px;"><b><?=$i?></b></a>
<?}?>
<?$sko++;$i++;}?>
&nbsp;
<?if($page<$pages){?>
        <a href="/<?=$rub?>/page-<?=$page+1?>/" class="pagenum" style="font-size:13px;"><i>Сюда</i> »</a><?}?>
    </div>
