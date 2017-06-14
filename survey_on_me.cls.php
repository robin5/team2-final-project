<?php
require_once('includes/Database/TeamInstanceFactory.php');
require_once('includes/Database/QuestionResponseFactory.php');

class SurveyOnMe {

	public static function injectQuestionAnswers($reviewee, $questions, $users) {
	
		$userIndex = 0;
		$buttonIndex = 0;

		$showReviewer = ($reviewee != $_SESSION['userId']);
		if ($showReviewer) {
				/***********/
					echo "<div class=\"txt-summary\">";
						echo "<div><button id=\"btn-summary\" class=\"resp-button\" onclick=\"getAreaTxt('btn-summary','txt-summary','tone-summary')\">Click to See Tone Summary</button></div>";
						echo"<div id=\"tone-summary\" class=\"tone-summary\"></div>";
					echo "</div>";
				/***********/
		}
		
		foreach($questions as $question) {
			echo "<div class=\"question\">";
			echo "<div class=\"resp-grade\">";
			echo "<div id=\"question\">{$question['text']}</div>";
			echo "</div>";

			foreach($users as $user) {
				echo "<div class=\"clear-question\"></div>";
				$userIndex++;

				$text = "";
				$grade = "---";
				if (false !== ($response = QuestionResponsefactory::getResponse($question['question_id'], $reviewee, $user['user_id']))) {
					$text = $response['text'];
					$grade = $response['grade'];
					if (empty($grade)) {
						$grade = "---";
					}
				}
				echo "<div class=\"resp-grade\">";
				if ($showReviewer) {

				//User names shown only for Reviewers and Instuctor if viewer is the Reviewer
				echo "<br>{$user['first_name']} {$user['last_name']}&nbsp;&nbsp;-&nbsp;&nbsp;"; 

				//Show tone UI if viewer is the reviewer (#showRevier)
				echo "<div>Grade: <span style=\"background: white;\">&nbsp;{$grade}&nbsp;</span></div><br>";
				echo "</div>";
				echo "<textarea id=\"txt-q{$buttonIndex}\" class=\"ta-response\" cols=\"80\" rows=\"5\">{$text}</textarea>";
				
				echo "<button id=\"btn-q{$buttonIndex}\" class=\"resp-button\" onclick=\"getAreaTxt('btn-q{$buttonIndex}','txt-q{$buttonIndex}','tone-q{$buttonIndex}')\">Review</button>";
				echo "<div id=\"tone-q{$buttonIndex}\" class=\"resp-tone\"></div>";

				}else{
					//Viewre is not reviewer hide names and tone UI
				echo "<div>Grade: <span style=\"background: white;\">&nbsp;{$grade}&nbsp;</span></div><br>";
				echo "</div>";
				echo "<textarea id=\"txt-q{$buttonIndex}\" class=\"ta-response\" cols=\"80\" rows=\"5\">{$text}</textarea>";
				}// end if viewer is reviewer
				$buttonIndex++;

				}// for each users
			} // for each questions

	}
}
