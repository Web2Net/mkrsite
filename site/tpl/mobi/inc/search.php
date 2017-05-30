<?php defined('WEB2NET') or die();?> 
<form method="post" action="/search/" style="display:inline;">
    <div class="left" >
        <input value="Поиск" onblur="if(this.value==''){this.value='';}" onfocus="if(this.value==''){this.value='';}" type="text" name="search_input" maxlength="200" id="search_input" style="border: 1px solid #d91219;width:120px;height:15px" />
    </div> 
     <div class="left">
         <input type="image" src="<?=PATH_IMG_MOBI?>/butt_search.gif" style="height:15px" />
     </div>
</form>