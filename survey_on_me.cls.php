<?php
require_once('includes/Database/TeamInstanceFactory.php');
require_once('includes/Database/QuestionResponseFactory.php');

class SurveyOnMe {

	public static function injectQuestionAnswers($reviewee, $questions, $users) {
	
		$userIndex = 0;
		$buttonIndex = 0;

		$showReviewer = ($reviewee != $_SESSION['userId']);
		foreach($users as $user) {
			foreach($questions as $question) {
			echo "<div class=\"question\">";
			echo "<div class=\"resp-grade\">";
			echo "<p>{$question['text']}</p>";
			echo "</div>";
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
					echo "<br>{$user['first_name']} {$user['last_name']}&nbsp;&nbsp;-&nbsp;&nbsp;";
				}
				echo "Grade: <span style=\"background: white;\">&nbsp;{$grade}&nbsp;</span><br>";
				echo "</div>";
				echo "<textarea id=\"txt-q{$buttonIndex}\" class=\"ta-response\" cols=\"80\" rows=\"5\">{$text}</textarea>";
				
				echo "<button id=\"btn-q{$buttonIndex}\" class=\"resp-button\" onclick=\"getAreaTxt('btn-q{$buttonIndex}','txt-q{$buttonIndex}','tone-q{$buttonIndex}')\">Review</button>";
				echo "<div id=\"tone-q{$buttonIndex}\" class=\"resp-tone\"></div>";
				$buttonIndex++;
			} // for each questions
			echo "<div class=\"clear-question\"></div></div>";
			$userIndex++;
		}// for each users
	}
}
