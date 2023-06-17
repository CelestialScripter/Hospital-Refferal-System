<?php 

      // latitude and longitude of Two Points 
      $latitude1 = 19.017656 ; 
      $longitude1 = 72.856178; 
      $latitude2 = 40.7127; 
      $longitude2 = -74.0059;  
      
      //Converting to radians
      $longi1 = deg2rad($longitude1); 
      $longi2 = deg2rad($longitude2); 
      $lati1 = deg2rad($latitude1); 
      $lati2 = deg2rad($latitude2); 
              
      //Haversine Formula 
      $difflong = $longi2 - $longi1; 
      $difflat = $lati2 - $lati1; 
              
      $val = pow(sin($difflat/2),2)+cos($lati1)*cos($lati2)*pow(sin($difflong/2),2); 
              
      $res1 =3936* (2 * asin(sqrt($val))); //for miles
    $res2 =6378.8 * (2 * asin(sqrt($val))); //for kilometers
              
      //display distance in miles
      print_r('Distance:'.$res1.' '.'miles '.'OR '.$res2.' '.' kilometers'); 
    
?>