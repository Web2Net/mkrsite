<?php
	
class GD {
	
function imageResize($outfile,$infile,$neww,$newh,$quality) {

    $im=imagecreatefromjpeg($infile);
    $k1=$neww/imagesx($im);
    //echo "<br>";
    $k2=$newh/imagesy($im);
    $k=$k1>$k2?$k1:$k2;
    //$k=$k1;

   $w=intval(imagesx($im)*$k);
   $h=intval(imagesy($im)*$k);
   //$w=$neww;
   //$h=$newh;  

    $im1=imagecreatetruecolor($w,$h);
    imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));

    imagejpeg($im1,$outfile,$quality);
    imagedestroy($im);
    imagedestroy($im1);

    }

}

?>