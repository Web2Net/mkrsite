<?php defined('WEB2NET') or die();?> 

<div>
<?if(isset($_SESSION['navigate']['second']) && $_SESSION['navigate']['second'] !== 0){foreach($_SESSION['navigate']['second'] as $k=>$row)
  {?>
    <div class="bg_nav_bottom"><a href="/<?=$row['type']?>/<?=$row['seolink']?>/" id="<?=$row['name']?>"><nobr><?=$row['caption']?></nobr><img src="<?=PATH_IMG_MOBI?>/arrow_right.gif" alt="<?=$row['caption']?>" class="right" /></a></div>  
    <div class="both br_1"></div>
<?}}?>
</div>
<div class="both br_5"></div>
<div class="copyright">
    Copyright © 2004-<?=date("Y")?> "<?=SITE_NAME?>".
</div>