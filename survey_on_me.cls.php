<?php
require_once('includes/Database/TeamInstanceFactory.php');
require_once('includes/Database/QuestionResponseFactory.php');

class SurveyOnMe {

	public static function injectQuestionAnswers($reviewee, $questions, $users) {
	
		echo "<div>";
		
		foreach($questions as $question) {
			
			echo "<p>{$question['text']}</p>";
			foreach($users as $user) {
				
				$text = "";
				$grade = "---";
				if (false !== ($response = QuestionResponsefactory::getResponse($question['question_id'], $reviewee, $user['user_id']))) {
					$text = $response['text'];
					$grade = $response['grade'];
				}
				echo "<br>{$user['first_name']} {$user['last_name']}&nbsp;&nbsp;-&nbsp;&nbsp;";
				echo "Grade: <span style=\"background: white;\">&nbsp;{$grade}&nbsp;</span><br>";
				echo "<textarea cols=\"80\" rows=\"5\">{$text}</textarea><br>";
			}
		}
		echo "</div>";
	}
}
