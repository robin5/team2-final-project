<?php
require_once('includes/Database/TeamInstanceFactory.php');
require_once('includes/Database/QuestionResponseFactory.php');

class SurveyOnMe {

	public static function injectQuestionAnswers($reviewee, $questions, $users) {
	
		echo "<div>";
		
		$showReviewer = ($reviewee != $_SESSION['userId']);
		
		foreach($questions as $question) {
			echo "<div class=\"question\">";
			echo "<div class=\"resp-grade\">";
			echo "<p>{$question['text']}</p>";
			echo "</div>";
			foreach($users as $user) {
				
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
				echo "<textarea class=\"ta-response\" rows=\"5\">{$text}</textarea><br>";
			}
			echo "</div>";
		}
		echo "</div>";
	}
}
