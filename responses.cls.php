<?php
require_once('includes/Database/TeamInstanceFactory.php');
require_once('includes/Database/QuestionResponseFactory.php');

class Responses {

	public static function injectQuestionAnswers($reviewer, $questions, $users) {
	
		//echo "<div>";

		$userIndex = 0;
		$buttonIndex = 0;
		foreach($users as $user) {
			echo "<div id=\"user-{$userIndex}\" class=\"question\">";
			echo "<div class=\"resp-grade\">";
			echo "<p>{$user['first_name']} {$user['last_name']} ({$user['user_name']})</p>";
			echo "</div>";
			foreach($questions as $question) {
				
				$text = "";
				$grade = "---";
				if (false !== ($response = QuestionResponsefactory::getResponse($question['question_id'], $user['user_id'], $reviewer))) {
					$text = $response['text'];
					$grade = $response['grade'];
					if (empty($grade)) {
						$grade = "---";
					}
				}
				//echo "<br>{$user['first_name']} {$user['last_name']}&nbsp;&nbsp;-&nbsp;&nbsp;";
				echo "<div class=\"resp-grade\">";
				echo "Grade: <span style=\"background: white;\">&nbsp;{$grade}&nbsp;</span>&nbsp;&nbsp;{$question['text']}<br>";
				echo "</div>";
				echo "<textarea id=\"txt-q{$buttonIndex}\" class=\"ta-response\" cols=\"80\" rows=\"5\">{$text}</textarea>";
				
				echo "<button id=\"btn-q{$buttonIndex}\" class=\"resp-button\" onclick=\"getAreaTxt('btn-q{$buttonIndex}','txt-q{$buttonIndex}','tone-q{$buttonIndex}')\">Review</button>";
				echo "<div id=\"tone-q{$buttonIndex}\" class=\"resp-tone\"></div>";
				$buttonIndex++;
			} // for each questions
			echo "<div class=\"clear-question\"></div></div>";
			$userIndex++;
		} // for each users
		//echo "</div>";
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
	
}