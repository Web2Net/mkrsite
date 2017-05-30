<?php
  class Graphics
  {
      function getRandColor($begin,$end)
      {
          if($begin == "" || $begin > "255")$begin = "0";
          if($end == "" || $end > "255")$end = "255";
          $color = dechex(rand($begin,$end)).dechex(rand($begin,$end)).dechex(rand($begin,$end));
          if(strlen($color)=="3")
          {
              $color = $color."000";
          }
          if(strlen($color)=="4")
          {
              $color = $color."00";
          }
          if(strlen($color)=="5")
          {
              $color = $color."0";
          }
          return "#".$color;
          
          
      }
  }
?>
