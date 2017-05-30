<?php
  $dat = "2011-06-19";
  
  $ddat = explode("-", $dat);
  $year = $ddat[0];
  $mounth = $ddat[1];
  $day = $ddat[2];
  
  
//  $mkdate = mktime($day,$mounth,$year);
    $mkdate = mktime(0,0,0,$mounth,$day,$year);
  
  $mkdate;
  $mkdate1 = date("Y-m-d",$mkdate);
   
?>
