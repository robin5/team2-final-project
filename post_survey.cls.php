<?php

require_once('includes/Database/SurveyFactory.php');
require_once('includes/Database/TeamFactory.php');

class PostSurvey {

	public static function injectSurveySelect() {
		echo '<label for="select-review" class="lbl-post-survey">Survey:</label>';
		echo '<select id="select-review" class="inp-post-survey" name="survey-id">';
		echo '<option>-- select a survey --</option>';

		$surveys = false;

		if (false === ($surveys = SurveyFactory::getSourceSurveys($_SESSION['userId']))) {
			$errMsg = SurveyFactory::getLastError();
			echo "<option>{$errMsg}</option>";
		} else {
			foreach($surveys as $survey) {

				// Create table row
				echo "<option value=\"{$survey['survey_id']}\">{$survey['name']}</option>";
			}
		}
		
		echo '</select>';
	}
	
	public static function injectTeamSelect() {

		echo '<label for="select-team" class="lbl-post-survey">Team:</label>';
		echo '<select id="select-review" class="inp-post-survey" name="team-ids[]" multiple>';

		$teams = false;

		if (false === ($teams = TeamFactory::getRootTeams($_SESSION['userId']))) {
			$errMsg = TeamFactory::getLastError();
			echo "<option>{$errMsg}</option>";
		} else {
			foreach($teams as $team) {
				// Create table row
				echo "<option value=\"{$team['team_id']}\">{$team['name']}</option>";
			}
		}

		/*echo '<option value="23">Team-1</option>';
		echo '<option value="24">Team-2</option>';
		echo '<option value="25">Team-3</option>';
		echo '<option value="26">Team-5</option>';*/
		
		
		
		echo '</select>';
	}
}