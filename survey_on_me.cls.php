<?php
require_once('includes/Database/TeamInstanceFactory.php');
require_once('includes/Database/QuestionResponseFactory.php');

class SurveyOnMe {

	public static function injectQuestionAnswers($reviewee, $questions, $users) {
	
		//echo "<div>";
		//AMY
		$userIndex = 0;
		$buttonIndex = 0;
		//////
		$showReviewer = ($reviewee != $_SESSION['userId']);
		///AMY ////
		foreach($users as $user) {
			//echo "<div id=\"user-{$userIndex}\" class=\"question\">";
			//echo "<div class=\"resp-grade\">";
			//echo "<p>{$user['first_name']} {$user['last_name']} ({$user['user_name']})</p>";
			//echo "</div>";
		//foreach($questions as $question) {
			// echo "<div class=\"question\">";
			// echo "<div class=\"resp-grade\">";
			// echo "<p>{$question['text']}</p>";
			// echo "</div>";
			//foreach($users as $user) {
			foreach($questions as $question) {
			echo "<div class=\"question\">";
			echo "<div class=\"resp-grade\">";
			echo "<p>{$question['text']}</p>";
			echo "</div>";


				
			//foreach($users as $user) {
				
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
				//echo "<textarea class=\"ta-response\" rows=\"5\">{$text}</textarea><br>";
				////AMY
				echo "<textarea id=\"txt-q{$buttonIndex}\" class=\"ta-response\" cols=\"80\" rows=\"5\">{$text}</textarea>";
				
				echo "<button id=\"btn-q{$buttonIndex}\" class=\"resp-button\" onclick=\"getAreaTxt('btn-q{$buttonIndex}','txt-q{$buttonIndex}','tone-q{$buttonIndex}')\">Review</button>";
				echo "<div id=\"tone-q{$buttonIndex}\" class=\"resp-tone\"></div>";
				$buttonIndex++;
			} // for each questions
			//AMY
			echo "<div class=\"clear-question\"></div></div>";
			$userIndex++;
			//echo "</div>";
		}// for each users
		//echo "</div>";
	}
}
