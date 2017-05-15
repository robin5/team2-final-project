<?php
//NOTES FOR TONE API
//version: 1.01
//date: 4/30/17
//see WATSON TONE TUTORIAL 
//https://www.ibm.com/watson/developercloud/doc/tone-analyzer/getting-started.html
//https://console.ng.bluemix.net/services/tone_analyzer/734b0905-a171-4015-89b3-f6a476930063/?paneId=credentials&env_id=ibm%3Ayp%3Aus-south&noCache=true
//https://watson-api-explorer.mybluemix.net/apis/tone-analyzer-v3?cm_mc_uid=89571857007614924234303&cm_mc_sid_50200000=1493316345&cm_mc_sid_52640000=1493316345#!/tone/GetTone

//GET
function callAPI ($method, $url, $credentials, $data=false){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_USERPWD, $credentials);

// allow for localhost
 	curl_setopt($curl, CURLOPT_URL, $url);
 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

 	$result = curl_exec($curl);
 	curl_close($curl);
 	return $result; //JSON
}

function getTone ($feedback){
	$data= $feedback;
	$credentials= '751ee35e-bd33-4c65-85a0-ae6baad1d03f : j8bVuMIj46JA';
	//param to review whole paragraph not by sentence
	$revewAll='&sentences=false';
	$url= "https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2016-05-19&text=".urlencode($data).$revewAll;

	//CALL CURL function to process feedback data
	$jdata = json_decode(CallAPI('GET', $url, $credentials),true);

	//number of categories in array (0-2)
	$countTC=count($jdata['document_tone']['tone_categories']);

	//number of tone scores under aeach category
	$zcount='';

	echo '<div class ="results">';
	for ($i=0; $i <$countTC ; $i++) { 
		//list of categories (category_id or category_name)
		echo '<div class ="tone">';  
		echo "<h3>".$jdata['document_tone']['tone_categories'][$i]['category_name']."</h3>";

		//num elements in the tones array 
		$zcount=count($jdata['document_tone']['tone_categories'][$i]['tones']);

		//list of scores per category
		echo "<ul>";
		for ($k=0; $k <$zcount ; $k++) { 
			//echo "<p>category tone scores k</p>";
			//process tone result scores
			$score =$jdata['document_tone']['tone_categories'][$i]['tones'][$k]['score'];
			$tone=$jdata['document_tone']['tone_categories'][$i]['tones'][$k]['tone_name'];

			//format output for results
			$score=round(($score * 100), 2)."%";
			echo '<li>'.$tone.": ".$score."</li>";
		}//end tone score loop (k)	
		echo "</ul>";
	} //end category_name (i) loop
	echo '</div">';//tone
	echo '</div">';//results
	////
	//SHOULD DO A RETURN HERE??
	//return $jd_data
} //END callGet

//text data (feedback) from form
$dataIn= $_GET['feedback'];
//var_dump($dataIn); //TEST

//SAMPLE TEXT TEST
//$dataIn="Hi Team, I know the times are difficult! Our sales have been disappointing for the past three quarters for our data analytics product suite. We have a competitive data analytics product suite in the industry. But we need to do our job selling it!";

//process the form text
getTone($dataIn);
?>
