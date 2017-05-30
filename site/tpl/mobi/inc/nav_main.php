<div class="both br_2"></div>
<div>
<?if($_SESSION['navigate']['main'] !== 0){foreach($_SESSION['navigate']['main'] as $k=>$row)
  {
      if($row['seolink'] == "Главная")
      {?>
    <div class="bg_nav_bottom">
        <a href="/" id="mainsite">
            <p style="padding-top:10px;"><nobr><?=$row['caption']?></nobr></p>
            <!--<img src="<?=PATH_IMG_MOBI?>/arrow_right.gif" alt="<?=$row['caption']?>" class="right" />-->
            </a>
    </div>
    <div class="both br_2"></div>
<?    }else{?>
    <div class="bg_nav_bottom">
        <a href="/<?=$row['seolink']?>/" id="<?=$row['name']?>">
            <p style="padding-top:10px;"><nobr><?=$row['caption']?></nobr></p>
            <!--<img src="<?=PATH_IMG_MOBI?>/arrow_right.gif" alt="<?=$row['caption']?>" class="right" />-->
        </a>
    </div>  
    <div class="both br_1"></div>
<?}}}?>
</div>
<div class="both"></div>