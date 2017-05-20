<?php
require_once('includes/Database/SurveyInstanceFactory.php');
require_once('includes/Database/TeamFactory.php');
require_once('includes/Database/SurveyFactory.php');
require_once('includes/Database/QuestionResponseFactory.php');
require_once('includes/Database/GradeFactory.php');
require_once('includes/functions.inc.php');

class TakeSurvey {
	
	public static function xxxinjectSurveyQuestions() {
	
	echo <<<EOT
			<p><label for="select-grade-1">Grade:</label>
			<select id="select-grade-1">
				<option>A</option>
				<option>B</option>
				<option>C</option>
				<option>D</option>
				<option>F</option>
			</select>&nbsp;
			Work cooperatively as part of a team and contribute in both leadership and supportive roles.</p>
			<textarea id="input-q1" cols="80" rows="5"></textarea><br>
			<br>		
			<p><label for="select-grade-1">Grade:</label>
			<select id="select-grade-1">
				<option>A</option>
				<option>B</option>
				<option>C</option>
				<option>D</option>
				<option>F</option>
			</select>&nbsp;
			Build relationships of trust, mutual respect and productive interactions.</p>
			<textarea id="input-q2" cols="80" rows="5"></textarea>
			<br>

			<p><label for="select-grade-1">Grade:</label>
			<select id="select-grade-1">
				<option>A</option>
				<option>B</option>
				<option>C</option>
				<option>D</option>
				<option>F</option>
			</select>&nbsp;
			Be flexible, adapt to unanticipated situations and resolve conflicts</p>
			<textarea id="input-q3" cols="80" rows="5"></textarea>
			<br>

			<p><label for="select-grade-1">Grade:</label>
			<select id="select-grade-1">
				<option>A</option>
				<option>B</option>
				<option>C</option>
				<option>D</option>
				<option>F</option>
			</select>&nbsp;
			Communicate and clarify ideas through well-written business correspondence, proposals, instructions, design summaries and client briefs. (Note: This includes all correspondence through email, Slack, and other communication methodologies adopted by your team.)</p>
			<textarea id="input-q4" cols="80" rows="5"></textarea>
			<br><br>
EOT;
	}
	
	public static function injectSurveyQuestions($reviewee, $questions, $responses) {
		
		if (false === ($grades = GradeFactory::getGrades())) {
			return;
		}
		$numGrades = count($grades);
		
		// Get survey questions
		if (false !== $questions) {
			echo "<form action=\"dashboard.php\" method=\"post\" onsubmit=\"return isValidForm(evt)\"><div>";
			echo "<input type=\"hidden\" name=\"reviewee\" value=\"{$reviewee}\" >";
			foreach($questions as $question) {
				
				$index = $question['qs_index'];
				$questionId = $question['question_id'];
				$response = TakeSurvey::findResponse($responses, $questionId);
				
				echo "<p>";
				echo "<input type=\"hidden\" name=\"question-id[{$index}]\" value=\"{$question['question_id']}\">";
				echo "<input type=\"hidden\" name=\"response-id[{$index}]\" value=\"{$response['response_id']}\">";
				echo "<label for=\"select-grade-{$index}\">Grade:&nbsp;</label>";
				echo "<select id=\"select-grade-{$index}\" name=\"grade-id[{$index}]\">";
				echo "<option value=\"0\">---</option>";
				for ($i = 0; $i < $numGrades; $i++) {
					$selected = TakeSurvey::getSelected($responses[$index]['grade_id'], $grades[$i]['grade_id']);
					echo "<option value=\"{$grades[$i]['grade_id']}\" {$selected}>{$grades[$i]['text']}</option>";
				}
				
				echo "</select>&nbsp;";


				echo "{$question['text']}</p>";
				echo "<textarea name=\"responses[{$index}]\" cols=\"80\" rows=\"5\" required>{$response['text']}</textarea><br><br>";
			}
			echo "</div>";
			
			if (count($questions) > 0) {
				echo "<button style=\"font-size: 1.25em;\" name=\"action\" value=\"save-survey\">Save & Exit</button>&nbsp;|&nbsp;";
				echo "<button type=\"submit\" id=\"btn-cancel\" name=\"action\" value=\"cancelled\" onclick=\"fakeFillFields();\">Cancel</button>";
				echo "<span style=\"font-size: 1.25em;\">&nbsp;|&nbsp;</span>";
				echo "<button type=\"submit\" style=\"font-size: 1.25em;\" name=\"action\" value=\"submit-survey\">Submit</button>";
			}
			echo "</form>";
		}
	}
	
	private static function getSelected($grade1, $grade2) {
		
		echo "{$grade1}{$grade2}";
		
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

