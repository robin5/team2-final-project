<?php

class ToneAnalyzer
{
	public static $lastError;

	$tone = [
		'Emotion tone' => ['Anger'=>5.79, 'Disgust'=>14.35, 'Fear'=>1.89, 'Joy'=>36.68, 'Sadness'=>26],
		'Language Tone' => ['Analytical'=>0, 'Confident'=>0, 'Tentative'=>0],
		'Social Tone' => ['Openness'=>26.01, 'Conscientiousness'=>27.45, 'Extraversion'=>54.04, 'Agreeableness'=>59.91, 'Emotional Range'=>27.88]];
	
	
	/*****************************************************
	 * Function: getTone 
	 * Description: Returns watson tone information
	 *****************************************************/
	 
    public static function getTone($text) {
			return $tone;
    }
	
	/*****************************************************
	 * Function: getLastError 
	 * Description: Returns last error encountered
	 *****************************************************/
	 
	public static getLastError() {
		return $lastError;
	}
}