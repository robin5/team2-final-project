<?php
require_once('includes/Database/TeamInstanceFactory.php');
require_once('includes/Database/QuestionResponseFactory.php');

class Responses {

	public static function injectQuestionAnswers($surveyId, $reviewer, $questions, $users) {
	
		$userIndex = 0;
		$buttonIndex = 0;
		foreach($users as $user) {

			$submissionId = SurveyCompleteFactory::getSubmissionId($surveyId, $user['user_id'], $reviewer);
			if ($submissionId == 1) {
				$disabledTone = "";
			} else {
				$disabledTone = "disabled";
			}
			
			echo "<div id=\"user-{$userIndex}\" class=\"question\">";

			/*********** SUMMARY TONE PER USER ********************************/
			echo "<div id=\"txt-summary-{$userIndex}\" class=\"summary\">";
			echo "<hr>";
			echo "<div> <button id=\"btn-summary-{$userIndex}\" class=\"resp-button\" onclick=\"getAreaTxt('btn-summary-{$userIndex}','txt-summary-{$userIndex}','tone-summary-{$userIndex}')\" {$disabledTone}>Show Tone Summary</button></div>";
			echo "<div id=\"tone-summary-{$userIndex}\" class=\"tone-summary\"></div>";
			echo "</div>"; //END OF txt summary div
			/*********** END SUMMARY TONE ********************************/

			echo "<div class=\"resp-grade\" class=\"tone-summary\">";
			//echo "<p>{$user['first_name']} {$user['last_name']} ({$user['user_name']})</p>";
			echo "</div>";
			
			foreach($questions as $question) {
				
				$text = "";
				$grade = "---";
				$textclass = "ta-response";
				
				if ($submissionId == 1) {
					if (false !== ($response = QuestionResponsefactory::getResponse($question['question_id'], $user['user_id'], $reviewer))) {
						$text = $response['text'];
						$grade = $response['grade'];
					}
					if (empty($text)) {
						$text = "Nothing submitted!";
					}
					if (empty($grade)) {
						$grade = "---";
					}
				} else if ($submissionId == 2) {
					$text = "-- Requested resubmission --";
					$textclass = "ta-response-redo";
				} else if ($submissionId == 0) {
					$text = "-- Not yet Submitted --";
					$textclass = "ta-response-none";
				}
				//GRADE
				echo "<div class=\"resp-grade\">";
				echo "Grade: <span style=\"background: white;\">&nbsp;{$grade}&nbsp;</span>&nbsp;&nbsp;{$question['text']}<br>";
				echo "</div>";
				
				echo "<textarea id=\"txt-q{$buttonIndex}\" class=\"{$textclass}\">{$text}</textarea>";

				echo "<button id=\"btn-q{$buttonIndex}\" class=\"resp-button\" class=\"iconbutton\"  onclick=\"getAreaTxt('btn-q{$buttonIndex}','txt-q{$buttonIndex}','tone-q{$buttonIndex}')\" value=\"Tone\" {$disabledTone}>Tone Details</button>";

				//TONE
				echo "<div id=\"tone-q{$buttonIndex}\" class=\"resp-tone\"></div>";
				$buttonIndex++;
			} // for each questions


			echo "<div class=\"clear-question\"></div></div>";
			$userIndex++;
		} // for each users
	}

	public static function injectUserTabs($users) {
		
		echo "<div id=\"div-resp-student-select\">";
		echo "<strong>";
		$index = 0;
		foreach($users as $user) {
			if ($index) {
				echo "&nbsp;|&nbsp;";
			}
			echo "<span id=\"sp-tab-{$index}\" data-index=\"{$index}\" class=\"sp-tab\" onclick=\"tabClick('{$index}');\">{$user['first_name']} {$user['last_name']}</span>";
			$index++;
		}
		echo "</strong>";
		echo "</div>";
	}
	
	public static function injectUserRedoSection($surveyId, $instanceId, $reviewer, $users) {

		$index = 0;
		$numUsers = count($users);
		$numSubmissions = 0;

		for($i = 0; $i < $numUsers; $i++) {
			$users[$i]['submission_id'] = SurveyCompleteFactory::getSubmissionId($surveyId, $users[$i]['user_id'], $reviewer);
			if (1 == $users[$i]['submission_id']) {
				$numSubmissions++;
			}
		}
		
		if ($numSubmissions > 0) {
			echo "<div id=\"div-user-redo\">";
			echo "<form action=\"dashboard.php\" method=\"POST\"><fieldset><legend>Check user who should redo surveys and press \"Redo Surveys\" button</legend><br>";
			
			echo "<input id=\"reviewer\" type=\"hidden\" name=\"reviewer\" value=\"{$reviewer}\">";
			echo "<input id=\"instance-id\" type=\"hidden\" name=\"instance-id\" value=\"{$instanceId}\">";
			
			foreach($users as $user) {

				if ($user['submission_id'] == 1) {
					echo "<input id=\"redo-input{$index}\" type=\"checkbox\" name=reviewees[{$index}] value=\"{$user['user_id']}\">";
					echo " {$user['first_name']} {$user['last_name']}<br>";
					$index++;
				}
			}
			echo "<br><button type=\"submit\" name=\"action\" value=\"redo-survey\">Redo Surveys</fieldset>";
			echo "</div>";
		}
		
	}
	
}