<?php
require_once('includes/Database/SurveyInstanceFactory.php');
require_once('includes/Database/TeamFactory.php');
require_once('includes/Database/SurveyFactory.php');
require_once('includes/Database/QuestionResponseFactory.php');
require_once('includes/Database/GradeFactory.php');
require_once('includes/functions.inc.php');

class TakeSurvey {
	
	public static function injectSurveyQuestions($surveyId, $reviewee, $questions, $responses) {
		
		if (false === ($grades = GradeFactory::getGrades())) {
			return;
		}

		$numGrades = count($grades);
		
		// Get survey questions
		if (false !== $questions) {
			
			echo "<form action=\"dashboard.php\" method=\"post\" onsubmit=\"return isValidForm(evt)\">";

		echo "<div class=\"question\">";/*AMY ADDED */
			echo "<div class=\"resp-grade\">";

			echo "<input type=\"hidden\" name=\"survey-id\" value=\"{$surveyId}\">";
			echo "<input type=\"hidden\" name=\"reviewee\" value=\"{$reviewee}\" >";
			
			foreach($questions as $question) {
				
				$index = $question['qs_index'];
				$questionId = $question['question_id'];
				$response = TakeSurvey::findResponse($responses, $questionId);
				
				echo "<p>";
				echo "<input type=\"hidden\" name=\"question-id[{$index}]\" value=\"{$question['question_id']}\">";
				echo "<input type=\"hidden\" name=\"response-id[{$index}]\" value=\"{$response['response_id']}\">";
				

				echo "<div class=\"resp-grade\">";/*amy added*/
				//echo "<label for=\"select-grade-{$index}\">Grade:&nbsp;</label>";
				echo "Grade:&nbsp;";
				echo "<select id=\"select-grade-{$index}\" name=\"grade-id[{$index}]\">";
				echo "<option value=\"0\">---</option>";
				for ($i = 0; $i < $numGrades; $i++) {
					$selected = TakeSurvey::getSelected($response['grade_id'], $grades[$i]['grade_id']);
					echo "<option value=\"{$grades[$i]['grade_id']}\" {$selected}>{$grades[$i]['text']}</option>";
				}
				
				echo "</select>&nbsp;";


				echo "{$question['text']}<br></div>";
				echo "<textarea class=\"ta-response\" name=\"responses[{$index}]\" ta- required>{$response['text']}</textarea><br><br>";
			}
			
			echo "</div>"; /*end resp-grade*/
		echo "</div>"; /*end question*/
			
			if (count($questions) > 0) {
				echo "<button style=\"font-size: 13.33px;\" name=\"action\" value=\"save-survey\" >Save & Exit</button>&nbsp;|&nbsp;";
				echo "<button type=\"submit\" style=\"font-size: 13.33px;\"id=\"btn-cancel\" name=\"action\" value=\"cancelled\" onclick=\"fakeFillFields();\">Cancel</button>";
				echo "<span style=\"font-size: 13.33px;\">&nbsp;|&nbsp;</span>";
				echo "<button type=\"submit\" style=\"font-size: 13.33px;\" name=\"action\" value=\"submit-survey\">Submit Survey</button>";
			}
			echo "</form>";
		}
	}
	
	private static function getSelected($grade1, $grade2) {
		return $grade1 == $grade2 ? "selected" : "";
	}
	
	private static function findResponse($responses, $questionId) {
		if (false !== $responses) {
			$numResponses = count($responses);
			for ($i = 0; $i < $numResponses; $i++) {
				if ($responses[$i]['question_id'] == $questionId) {
					return $responses[$i];
				}
			}
		}
		return false;
	}
}
