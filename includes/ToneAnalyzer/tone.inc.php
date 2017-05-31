

<?php
//test
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



/// SETUP WATSON API & Get JSON///
  function getTone ($feedback){
    //API
  	$data= $feedback;
    //Watson username and password
   $credentials = '95adcd29-4b80-4fa3-8ee7-1604ba4da748 : E3N7oVOwp4eG';
  	//review all text for tone, not by sentence
  	$reviewAll ='&sentences=false';

///////// LOCAL TEST  /////////
//    $url="sampledata.json";
//    $data = file_get_contents($url);
//    $tone_data = json_decode(($data),true);
    //print_r ($data);
/////////          /////////

    //API path to Watson service
 	$url= "https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2016-05-19&text=".urlencode($data).$reviewAll;

  	//CALL CURL callAPI & convert json to array
  	$tone_data = json_decode(CallAPI('GET', $url, $credentials),true);

    //Process resuts and return in table to response.php
    processTone ($tone_data);

    //var_dump($tone_data); //TEST to see JSON returned from Watson
    //print_r($tone_data); //TEST 
  } //END processTone function

  ////PROCESS the JSON RESULTS from WATSON and format in HTML
  function processTone ($tone_data){

   //number of tone categories
    $numCategories=count($tone_data['document_tone']['tone_categories']);
    //Number of tone scores in each Tone array in categories
    $numTones='';

    echo '<table class="tbl-analyze"><tbody>';
    //echo '<table id="analysis-all" class="tbl-analyze"><tbody>';
    echo '<tr>';

    //this loop access the tone array and the name of the catagory
    for ($i=0; $i <$numCategories ; $i++) { 
      //list of categories (category_id or category_name)
    	echo "<td><b>".$tone_data['document_tone']['tone_categories'][$i]['category_name']."</b><br>";

      //list of scores per category
    	//Number of elements in the tones array 
    	$numTones=count($tone_data['document_tone']['tone_categories'][$i]['tones']);

     //echo "<br>";
    	for ($k=0; $k <$numTones ; $k++) {
          //Tone
          $tone=$tone_data['document_tone']['tone_categories'][$i]['tones'][$k]['tone_name'];

          //Score
    		  $score =$tone_data['document_tone']['tone_categories'][$i]['tones'][$k]['score'];

          //FORMAT score to % & round up (1.05%)
          $score=round(($score * 100), 2)."%";

          //output tone name and score value
    		  echo $tone.": ".$score."<br>";
    	 }//end tone score loop (k)
      echo "</td>";
    } //end category_name (i) loop
    echo '</tr">';//tone

   echo '</tbody></table">';//results
   //echo '<button class="btn-hide" onclick=> Hide </button>'; //TEST
  } //END getTone function
////

//GET DATA FROM analyzeTone function in RESPONSE.php
$dataIn= $_GET['feedback'];
//var_dump($dataIn); //TEST

//TEST -SAMPLE TEXT- Use test API seprate from areatext
//$dataIn="Hi Team, I know the times are difficult! Our sales have been disappointing for the past three quarters for our data analytics product suite. We have a competitive data analytics product suite in the industry. But we need to do our job selling it!";
//TEST

//PROCESS data/text from response.php.
getTone($dataIn);
?>
