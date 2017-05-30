<?php

/**
 * $Id: test.cropinterface.php 49 2006-11-29 14:35:46Z Andrew $
 *
 * [Description]
 *
 * Example file for class.cropinterface.php.
 *
 * [Author]
 *
 * Andrew Collington <php@amnuts.com> <http://php.amnuts.com/>
 */

require('class.cropinterface.php');
require(getenv("DOCUMENT_ROOT").'/kernel/kernel.php');

$ci =& new CropInterface(true);

if (isset($_GET['file'])) {
	
    //$ci->loadImage($_GET['file']);
	//$ci->cropToDimensions($_GET['sx'], $_GET['sy'], $_GET['ex'], $_GET['ey']);
    //$ci->showImage('jpg', 75);
    

$cc =& new CropCanvas();
    $cc->loadImage($_GET['file']);
    $cc->cropToDimensions($_GET['sx'], $_GET['sy'], $_GET['ex'], $_GET['ey']);
    $cc->saveImage($_GET["file"], 90);
    $cc->flushImages();
    
    GD::imageResize(str_replace("big","small",$_GET["file"]),$_GET["file"],150,100,85);
    
	?><html>
<body style="margin:0;padding:0px;">
		<script>
//	window.parent.location.href='<?=SITE_URL?>/register/step3.html';
	//window.parent.document.getElementById("seeImage").src = '<?=ADM_SITE_URL?>/design/noimage.jpg';
	window.parent.document.getElementById("crop").style.display = 'none';
	window.parent.document.getElementById("seeImage").style.display = 'block';		
	window.parent.document.getElementById("seeImage").src = '<?=str_replace(getenv("DOCUMENT_ROOT"),"",$_GET["file"]);?>';	
	
	</script>
</body>
</html>
		<?
	exit;

}else{

?>

<html>

<body style="margin:0;padding:0px;">

<div>
<center>
<?php

$ci->setCropAllowResize(true);
$ci->setCropTypeDefault(ccRESIZEANY);
$ci->setCropTypeAllowChange(true);
$ci->setCropSizeDefault('3:2');
$ci->setCropPositionDefault(ccCENTRE);
$ci->setCropMinSize(10, 10);
$ci->setExtraParameters(array('test' => '1', 'fake' => 'this_var'));
$ci->setCropSizeList(array(
        '200x200' => '200 x 200 pixels',
        '320x240' => '320 x 240 pixels',
        '2:3'     => '2x3 portrait',
        '5:3'     => '3x5 landscape',
        '8:10'    => '8x10 portrait',
        '10:8'    => '8x10 landscape',
        '4:3'     => 'TV screen',
        '16:9'    => 'Widescreen',
        '2/2'     => 'Half size',
        '4/2'     => 'Quater width and half height'
        ));
$ci->setMaxDisplaySize('300x200');
$filec=getenv("DOCUMENT_ROOT").$_GET["img"];
$ci->loadInterface($filec);

?>


<?php $ci->loadJavascript(); ?>
</center>
</div>
</body>
</html>
<?}?>