
<html>
<head>
  <meta charset="utf-8">
  <title>Multiband</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script>
  </script>
  <link rel="stylesheet" href="style.css" media="screen" title="no title" charset="utf-8">
  <style>
  #hasil{
    float: left;
    margin-right: 10px;
    margin-bottom: 10px;
    width: 285px;
    height: 200px;
  }
  #gbsatelit{
    
    margin-right: 25px;
    margin-left: 25px;
    margin-top: 25px;
    width: 32px;
    height: 32px;
  }

  #gbsatelitBesar{
    
    margin-right: auto;
    margin-left: auto;
    margin-top: 25px;
    display: block;
    width: 500px;
    height: 500px;
  }
 

  </style>
</head>
<body>
  <header>
    <!--img class="blogtitle" src="icon.png"--><br><br><br><br>
    <h5 class="subtitle">Multiband Image Clustering With K-Means</h5>

    <!--form  method="post" action="" enctype="multipart/form-data">
        
            <label class="fileContainer">
               Pilih Gambar
               
                <input type="file" id="gambar" name="gambar"/>
               
            </label>
           <br>
           <br>

          
         
    </form-->
  
  </header>
  <nav>
  </nav>
  <section class="mainpart">
    <main>
   
         
          
          
          <?php

                 $datatxt=array();
                 $f = fopen("datatxtgbsatelit.txt", "r");
                 $indexdata=0; 
                 while(!feof($f)) { 
                  
                  $datatxt[$indexdata]=fgets($f);
                  $indexdata++;
                  
                 }
                 fclose($f);
               //  print_r($datatxt);
               //  echo count($datatxt);

            

              
              $nilaigray=array();
          
           for($hehe = 0; $hehe <count($datatxt); $hehe++) {
            
              $index=0;
              $im = imagecreatefromgif(trim("satelit/".$datatxt[$hehe]));
              $w = imagesx($im);
              $h = imagesy($im);

            //  echo "<br><br><br><br>";
             //  echo "Lebar Gambar ke ".($hehe+1)."=".$w."<br>";
            //   echo "Tinggi Gambar ke ".($hehe+1)."=".$h;

              for($y = 0; $y < $h; $y++) {
                      for($x = 0; $x < $w; $x++) {
                          $rgb = imagecolorat($im, $x, $y);
                          $r = ($rgb >> 16) & 0xFF;
                          $g = ($rgb >> 8) & 0xFF;
                          $b = $rgb & 0xFF;
                          $nilaigray[$index][$hehe]=(int)(($r+$g+$b)/3);
                          
                          $index++;
                      }

               }  
         //   echo "<br>Jumlah isi array warna grayscale gambar ke ".($hehe+1)."= ".count($nilaigray)."<br>";
         

          }
        //  print_r($nilaigray);
              
              echo "<br><br><br>";
            //mulai KMEANS
            $k=3;


              $centroid2 = array();
              for($i=0;$i<$k;$i++){
                for($j=0;$j<count($datatxt);$j++){
                  $centroid2[$i][$j]=0;   
                }
              }
              
              $max=array();
              $min=array();
              for($j=0;$j<count($datatxt);$j++){
                $max[$j]=0;
                $min[$j]=100;
              }



              for($i=0;$i<count($nilaigray);$i++){
                for($j=0;$j<count($datatxt);$j++){
                    if($nilaigray[$i][$j]>$max[$j]){
                      $max[$j]=$nilaigray[$i][$j];
                    }
                    if($nilaigray[$i][$j]<$min[$j]){
                      $min[$j]=$nilaigray[$i][$j];
                    }

                }
              }
              
            
              $centroid = array();
                  for($i=0;$i<$k;$i++){
                    for($j=0;$j<count($datatxt);$j++){
                      $centroid[$i][$j]=0;  
                    }
                  }

              
                  


              function frand($min, $max, $decimals = 0) {
                $scale = pow(1, $decimals);
                return mt_rand($min * $scale, $max * $scale) / $scale;
              }     


              for($i=0;$i<$k;$i++){
                
                for($j=0;$j<count($datatxt);$j++){

                  $centroid[$i][$j]=frand($min[$j],$max[$j],1);
                }
                
              }

               echo "Centroid awal:<br>";
              for($i=0;$i<$k;$i++){
                echo "centroid ".($i+1)." = ";
                for($j=0;$j<count($datatxt);$j++){

                  echo $centroid[$i][$j].", ";
                }
                echo "<br>";

              }
               echo "<br><br>";
              
              $temp=array($k);
              for($i=0;$i<$k;$i++){
                $temp[$i]=0;
              }

                do{
                 for($i=0;$i<count($nilaigray);$i++){ //ini di looping sebanyak 1024


                  for($j=0;$j<$k;$j++){  //misal k=3 maka nilaigray dihitung selisihnya pada masing masing centroid.
                    for($hehe=0;$hehe<count($datatxt);$hehe++){
                      $temp[$j]=$temp[$j]+pow(($centroid[$j][$hehe]-$nilaigray[$i][$hehe]) , 2);
                    }
                    $temp[$j]=sqrt($temp[$j]);
                  }

                  // setelah mendapatkan temp maka dicek lebih dekat ke cluster mana
                  for($j=0;$j<$k;$j++){
                    if($j==0){
                      $minim=$temp[$j];
                        $nilaigray[$i][count($datatxt)]=$j;
                      }
                      else{
                        
                        if($temp[$j]<$minim){
                        $minim=$temp[$j];
                         $nilaigray[$i][count($datatxt)]=$j;
                                      
                        }
                      }
                  }
                
                  //jika sudah nilaigray yang akhir maka waktunya mengecek apakah centroid lama dan baru sama
                  if($i==(sizeof($nilaigray)-1)){
                    $centroid2=$centroid;
                    for($c=0;$c<sizeof($centroid);$c++){
                      $jumlahnya=array(count($datatxt));
                      for($hehe=0;$hehe<count($datatxt);$hehe++){
                        $jumlahnya[$hehe]=0;;
                      }
                      $pembagi=0;
                     for($a=0;$a<sizeof($nilaigray);$a++){

                      if($nilaigray[$a][count($datatxt)]==$c){
                        for($hehe=0;$hehe<count($datatxt);$hehe++){
                          $jumlahnya[$hehe]=$jumlahnya[$hehe]+$nilaigray[$a][$hehe];
                        }
                        
                        $pembagi++;
                      }
                     }
                     if($pembagi==0){
                      for($hehe=0;$hehe<count($datatxt);$hehe++){
                        $jumlahnya[$hehe]=0;
                      }
                
                      for($hehe=0;$hehe<count($datatxt);$hehe++){
                        $centroid[$c][$hehe]=$jumlahnya[$hehe];
                      }
                      
                     }
                     else{

                        for($hehe=0;$hehe<count($datatxt);$hehe++){
                          //mendapatkan rata2nya
                          $jumlahnya[$hehe]=(float)($jumlahnya[$hehe]/$pembagi);
                        }
                        for($hehe=0;$hehe<count($datatxt);$hehe++){
                          $centroid[$c][$hehe]=$jumlahnya[$hehe];
                        }
                    
                     }
                    }
                  }
                 }

                   

                }while($centroid!=$centroid2);

                echo "Centroid akhir:<br>";
                for($i=0;$i<$k;$i++){
                  echo "centroid ".($i+1)." = ";
                  for($j=0;$j<count($datatxt);$j++){

                    echo (int)($centroid[$i][$j]).", ";
                  }
                  echo "<br>";

                }

                echo "<br><br><br>";
            //    print_r($nilaigray);

            $ix=0;
            $gd = imagecreatetruecolor($w, $h);
            $red = imagecolorallocate($gd, 255, 0, 0); 
            $green = imagecolorallocate($gd, 0, 255, 0); 
            $blue = imagecolorallocate($gd, 0, 0, 255);
            $white = imagecolorallocate($gd, 255, 255, 255); 
            $black = imagecolorallocate($gd, 0, 0, 0);  
                  for($y = 0; $y < $h; $y++) {
                                  for($x = 0; $x < $w; $x++) {

                                        if($nilaigray[$ix][count($datatxt)]==0){
                                           imagesetpixel($gd, $x,$y, $blue);
                                        }
                                        else if($nilaigray[$ix][count($datatxt)]==1){
                                           imagesetpixel($gd, $x,$y, $green);
                                        }
                                        else if($nilaigray[$ix][count($datatxt)]==2){
                                           imagesetpixel($gd, $x,$y, $red);
                                        }
                                         else if($nilaigray[$ix][count($datatxt)]==3){
                                           imagesetpixel($gd, $x,$y, $black);
                                        }
                                         else if($nilaigray[$ix][count($datatxt)]==4){
                                           imagesetpixel($gd, $x,$y, $white);
                                        }

                                      

                                     $ix++;
                                  }

                           }  


            imagejpeg($gd, 'hasilgabungan.jpg');
            imagedestroy($gd);

            echo "
            <img id='gbsatelit' src='satelit/gb1.gif'>
            <img id='gbsatelit' src='satelit/gb2.gif'>
            <img id='gbsatelit' src='satelit/gb3.gif'>
            <img id='gbsatelit' src='satelit/gb4.gif'>
            <img id='gbsatelit' src='satelit/gb5.gif'>
            <img id='gbsatelit' src='satelit/gb7.gif'>
            <img id='gbsatelit' src='hasilgabungan.jpg'>
            <img id='gbsatelitBesar' src='hasilgabungan.jpg'>";


          ?>

   </main>
</section>
<div class="cls"></div>
<footer>
  <div class="mainpart">
    <p>
      &copy; 2016 Multiband Image Clustering
    </p>
  </div>
</footer>
</body>
</html>