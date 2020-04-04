<?php

  function Get_Current_Field_Data($Country_Name){
      $data_from_Database  = array();//associative array
      global $dbh2;  
      $sql = "QUEYR";
      $stmt = $dbh2->prepare($sql);
      $stmt->bindValue(":Country", $Country_Name);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($rows as $row) {
       $data_from_Database [$row['ID']] =$row;//adding a couple key-value
      }
      $data = json_encode($data_from_Database); 
      return $data ;
     }
	
  $data_from_Database = json_decode(Get_Current_Field_Data("Norway"),true);
?>

<h2> Fetch Norway Latest Field Data </h2>
<form>
	

</form>


<?php   
  
  // Generate random Ip address
  $randIP = "".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255);

  $url = "https://factpages.npd.no/ReportServer_npdpublic?/FactPages/TableView/field_production_monthly&rs:Command=Render&rc:Toolbar=false&rc:Parameters=f&rs:Format=CSV&Top100=false&IpAddress=".$randIP."&CultureCode=en";

  //unique name of the file created 
  $OutputfieldName = "NorwayConvertedFieldData".time().uniqid().".txt";

  if (($handle1 = fopen($url, "r")) !== FALSE) {
      if (($textfile = fopen($OutputfieldName, "w")) !== FALSE) {
          while (($data = fgetcsv($handle1, 1000, ",")) !== FALSE) {
            if(is_numeric($data[3])){
	            
	            $data[8] = monthQuarter($data[2]);  // passing the month from csv
	            $data[3] = convertOil($data[1],$data[2],$data[3]); // Year,Month, Oil from million  to thousand barrels from csv
	            $data[0] = strtoupper($data[0]); // Field Name fetched from Norway data
	            $data[4] = convertGas($data[1],$data[2],$data[4]); // Converts gas 
					
	            if((float)$data[6] > 0){
              	$data[6] = convertOil($data[1],$data[2],$data[6]); // Year,Month, Oil from million to thousand barrels from csv
              	      
              	}     	
              	$data[5] = (float)$data[3] + (float)$data[6]; // Computing the liquid	  
              foreach ($data_from_Database  as $value) {
                  $ID     = $value['FieldID'];
                  $fiednamed= $value['Field']; //Field Name 
                  $Alias  = $value['Alias'];
                  $Month  = $value['Month'];
                  $Year   = $value['Year'];
                 if(strtoupper($fiednamed)===utf8_encode(strtoupper($data[0]))){
                 $data[7] = $ID;
                }
                else if(strtoupper($Alias)===(strtoupper($data[0]))){
                 $data[7] = $ID;
                }
        
              } 

        }

	        $data[3] = number_format((float)$data[3],5,'.',''); // converting oil into 5 significent e.g : 0.84607
	        $data[4] = number_format((float)$data[4],5,'.',''); // converting gas into 5 significent e.g : 0.84607
	        $data[5] = number_format((float)$data[5],5,'.',''); // converting gas into 5 significent e.g : 0.84607
 			$data[6] = number_format((float)$data[6],5,'.',''); // converting gas into 5 significent e.g : 0.84607
	        
	        $outputData = array($data[7],$data[0], $data[3], $data[4], $data[5], $data[1],$data[2],$data[8]);
	          			//FieldID   //oil        gas          cond         liquid        year        monthQuarter  Month
	        echo "(".$data[7].",".$data[3].",".$data[4].",".$data[6].",".$data[5].",".$data[1].",".$data[8].",".$data[2]."),<br>"; 
	     	$data = "(".$data[7].",".$data[3].",".$data[4].",".$data[6].",".$data[5].",".$data[1].",".$data[8].",".$data[2]."),\n"; 
	     
            fwrite($textfile, $data);
            // fputcsv($handle2, $outputData); 
          }
            fclose($textfile);
      }
      fclose($handle1);
  }
 


  // Converts oil from million barrels into thousand barrel 
  function convertOil($year,$month,$num) {
    $totalDayInMonth = cal_days_in_month(CAL_GREGORIAN,$month,$year);
    $barrelPerDay = ($num * 6.2897) * 1000;  
    $thousandBarrelDay = $barrelPerDay / $totalDayInMonth;
    return $thousandBarrelDay;

  }

  //echo convertGas(1979,5,0.0768); 
   // Converts oil from million barrels into thousand barrel 
  function convertGas($year,$month,$num) {
    if($num == 0 ){
      $num = 0;
      return $num;
    }
    else {
      $totalDayInMonth = cal_days_in_month(CAL_GREGORIAN,$month,$year);
      $convertGasbtocubic = $num*0.0058867406463;
     
      $boe = ($convertGasbtocubic * 100) / 100;
      $boe= $boe / $totalDayInMonth;
      $number = number_format( $boe,  12, '.', '');
      return   $number*1000000 ;
    }
  }


  // Calculates the month quarter based on month 
  function monthQuarter($month){
    $quarter = 0;
     if ($month <= 3){
          $quarter = 1;
        }
      else if ($month >= 4 && $month <= 6){
           $quarter = 2;
      }  
      else if ($month >= 7 && $month <= 9){
           $quarter = 3;
         }
     else if ($month >= 10 && $month <= 12){
           $quarter = 4;
      }
      return $quarter;
  }

?>
