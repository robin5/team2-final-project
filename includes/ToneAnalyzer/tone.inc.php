<?php
/// CONTACT WATSON GET TONE SCORES ///
  function callAPI ($method, $url, $credentials, $data=false){
  	$curl = curl_init();
    //Watson username and password.
  	curl_setopt($curl, CURLOPT_USERPWD, $credentials);

    //allow for use on localhost
   	curl_setopt($curl, CURLOPT_URL, $url);
   	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    //Tone Results
   	$result = curl_exec($curl);
   	curl_close($curl);
   	return $result; 
  }


/***************************************************
  /// SETUP WATSON API & Get JSON///
***************************************************/
  function getTone ($feedback){
    //API
  	$data= $feedback;
    //Watson username and password
    $credentials = '95adcd29-4b80-4fa3-8ee7-1604ba4da748 : E3N7oVOwp4eG';
  	//review all text for tone, not by sentence
  	$reviewAll ='&sentences=false';

    ///////// LOCAL JSON TEST FOR TESTING OFFLINE /////////
    //$url="sampledata.json";
    //$data = file_get_contents($url);
    //$tone_data = json_decode(($data),true);
    //print_r ($data);
    ///////////////////////////////////////////


    ///////// API to WATSON  //////////////////
 	  $url= "https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2016-05-19&text=".urlencode($data).$reviewAll;

  	//CALL CURL callAPI & convert json to array
  	$tone_data = json_decode(CallAPI('GET', $url, $credentials),true);
    /////////////////////////////////////////////


    //Process resuts and return in table to response.php  
    processBar ($tone_data);
   // processTone ($tone_data); 

    //var_dump($tone_data); //TEST to see JSON returned from Watson
    //print_r($tone_data); //TEST 
  } //END getTone function
/****************************************************/
/****************************************************/


/************************************************************
  ////PROCESS the JSON RESULTS from WATSON and format in HTML
*************************************************************/
  function processTone ($tone_data){

   //number of tone categories
    $numCategories=count($tone_data['document_tone']['tone_categories']);
    //Number of tone scores in each Tone array in categories
    $numTones='';

    echo '<span class="details" >';
     
    //this loop access the tone array and the name of the catagory
    for ($i=0; $i <$numCategories ; $i++) { 
      echo '<div class="t-results"><table>';
      //list of categories (category_id or category_name)
      echo "<tr><th><b>".$tone_data['document_tone']['tone_categories'][$i]['category_name']."</b></th></tr>";


      //list of scores per category
    	//Number of elements in the tones array 
    	$numTones=count($tone_data['document_tone']['tone_categories'][$i]['tones']);

     echo "</tr><tr>";
    	for ($k=0; $k <$numTones ; $k++) {
          //Tone
          $tone=$tone_data['document_tone']['tone_categories'][$i]['tones'][$k]['tone_name'];

          //Score
    		  $score =$tone_data['document_tone']['tone_categories'][$i]['tones'][$k]['score'];
          //var_dump($score);

          //FORMAT score to % & round up (1.05%)
         $score=round(($score * 100), 2)."%";

         // $score=round($score, 2);
          //var_dump($score);

          //output tone name and score value
    		  //echo "<td>".$tone.": ".($score * 100)."%"."</tr>";
          echo "<td>".$tone.": ".$score."</tr>";


    	 }//end tone score loop (k)
      echo "</table></div>";
      
    } //end category_name (i) loop
    echo "</span>"; /*end Details*/

  } //END getTone function
/****************************************************/
/****************************************************/


/************************************************************
  ////PROCESS bar the JSON RESULTS 
*************************************************************/
  function processBar ($tone_data){

   //number of tone categories
    $numCategories=count($tone_data['document_tone']['tone_categories']);
    //Number of tone scores in each Tone array in categories
    $numTones='';


    echo '<span class="bar" >';
    //this loop access the tone array and the name of the catagory
    for ($i=0; $i <$numCategories ; $i++) { 

      echo '<div class="summaryemotion" >';
 
      //list of categories (category_id or category_name)     
      echo '<div class="cat" >'.$tone_data['document_tone']['tone_categories'][$i]['category_name']."</div>";

      //list of scores per category
      //Number of elements in the tones array 
      $numTones=count($tone_data['document_tone']['tone_categories'][$i]['tones']);

	/** TONES and Scores **/
      for ($k=0; $k <$numTones ; $k++) {
          //Tone
          $tone=$tone_data['document_tone']['tone_categories'][$i]['tones'][$k]['tone_name'];
          //Score
          $score =$tone_data['document_tone']['tone_categories'][$i]['tones'][$k]['score'];

          $x=round($score, 2);
          //FORMAT score to % & round up (1.05%)
         $score=round(($score * 100), 0)."%";
          
          //var_dump($score);

          /***Tone Likely check ***/
          if ($x >= .5) { //likely Present
            if ($x>= .75) { //echo"> .75 = is present ";
               echo'<div class="meter-label-likely">'.$tone.'</div>'; 
               //bar
                echo'<div class="meter-present">';
                  echo "<span style=\"width:".$score."\">".$score."</span>";
                echo '</div>'; //end meter
                ////////////////
                var_dump($tone.":".$x);
              ////////////////
              } else{ //between .5 and .75
                //echo"> .5 = likely present ";
             	   echo'<div class="meter-label-likely">'. $tone.'</div>'; 
    	           //bar
                 echo'<div class="meter-likely">';
    	           echo "<span style=\"width:".$score."\">".$score."</span></div>"; //end meter

              } //end >.75

  	     } else {  //echo"< .5 = not likely present";
  		    echo'<div class="meter-label">'.$tone.'</div>';
  	      echo"<div class=\"meter\"><span style=\"width:".$score."\">".$score."</span></div>"; //end meter
         }//end else/if .5
        
/********end score *********/


       }//end tone score loop (k)
      echo "</div>"; /*end category*/

      
    } //end category_name (i) loop
    echo "</div>"; /*end summaryemotion*/
    echo"</span>";/*end Bar*/

  

  } //END getTone function
/****************************************************/
/****************************************************/


/************************************************************
//GET DATA FROM analyzeTone function in RESPONSE.php
*************************************************************/
$dataIn= $_GET['feedback'];
//var_dump($dataIn); //TEST

///////// //TEST -SAMPLE TEXT // /////////////////
/////////Use test API seprate from areatext /////////

//$dataIn="Hi Team, I know the times are difficult! Our sales have been disappointing for the past three quarters for our data analytics product suite. We have a competitive data analytics product suite in the industry. But we need to do our job selling it!";
///////// //END TEST// /////////////////


/************************************************************
//FUNCTION PROCESS data/text from response.php in WATSON
*************************************************************/
getTone($dataIn);
?>
