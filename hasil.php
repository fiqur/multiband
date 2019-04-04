<?php
session_start();
unset ($_SESSION['hasilarraynya']);
$gd = imagecreatetruecolor(200, 200);
 $red = imagecolorallocate($gd, 255, 0, 0); 
			for($y = 0; $y < 200; $y++) {
                      for($x = 0; $x < 200; $x++) {
                         
                           imagesetpixel($gd, $x,$y, $red);
                      }

               }  


imagejpeg($gd, 'simpletext.jpg');
imagedestroy($gd);

?>