<form enctype="multipart/form-data" action="" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
��������� ���� ����: <input name="userfile" type="file" /><br>
<input type="submit" name="upl_base" value="���������" />
</form>
<hr />
<?php
if(isset($_REQUEST['upl_base']))
{
    $uploaddir = "e-mail_base";
    $uploadfile = $uploaddir."/". basename($_FILES['userfile']['name']);
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
    {
         echo "<center><h2>���������.</h2></center>\n";
         echo "<meta http-equiv=refresh content=0; url='{$_SERVER['PHP_SELF']}'>";
    } 
    else 
    {
        echo "<center><h2>��������� ������. ���������� ��� ���.</h2></center>\n";
    }
}   
?>