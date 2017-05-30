<form enctype="multipart/form-data" action="" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
Отправить этот файл: <input name="userfile" type="file" /><br>
<input type="submit" name="upl_base" value="Загрузить" />
</form>
<hr />
<?php
if(isset($_REQUEST['upl_base']))
{
    $uploaddir = "e-mail_base";
    $uploadfile = $uploaddir."/". basename($_FILES['userfile']['name']);
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
    {
         echo "<center><h2>Загрузили.</h2></center>\n";
         echo "<meta http-equiv=refresh content=0; url='{$_SERVER['PHP_SELF']}'>";
    } 
    else 
    {
        echo "<center><h2>Произошла ошибка. Попробуйте еще раз.</h2></center>\n";
    }
}   
?>