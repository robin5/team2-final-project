<?php
require_once('includes/Database/SurveyInstanceFactory.php');
require_once('includes/Database/TeamFactory.php');
require_once('includes/Database/SurveyFactory.php');

class DashBoard {

	public static function injectSurveysTable() {

		$numRows = 0;

		echo "<h3>My Surveys</h3>";
		echo "<table><tr><th>Survey</th><th>Start Date</th><th>End Date</th><th>Status</th><th>Action</th></tr>";

		if (false === ($surveyInstances = surveyInstanceFactory::getSurveyInstancesByOwner($_SESSION['userId']))) {
			$errMsg = surveyInstanceFactory::getLastError();
			echo "<tr><td colspan=5>{$errMsg}</td>";
			$numRows++;
		} else {
			foreach($surveyInstances as $surveyInstance)  {
			$numRows++;
				
				$currentTime = new DateTime();
				$startDate = new DateTime($surveyInstance['start_date']);
				$endDate = new DateTime($surveyInstance['end_date']);

				// Determine survey status
				if ($currentTime < $startDate) {
					$status = "Queued";
				} else if (($currentTime >= $startDate) && ($currentTime <= $endDate)) {
					$status = 'In Progress..';
				} else {
					$status = 'Completed';
				}

				// Create table row
				echo "<tr>";
				echo "<td>{$surveyInstance['name']}</td>";
				echo "<td>{$surveyInstance['start_date']}</td>";
				echo "<td>{$surveyInstance['end_date']}</td>";
				echo "<td>{$status}</td>";
				echo "<td><a href=\"survey_results.php\">Results</a></td>";
				echo "</tr>";
			}
		}
		if (false === ($sourceSurveys = surveyFactory::getSourceSurveys($_SESSION['userId']))) {
			$errMsg = surveyFactory::getLastError();
			echo "<tr><td colspan=5>{$errMsg}</td>";
			$numRows++;
		} else {
			foreach($sourceSurveys as $survey)  {
			$numRows++;
				
				$surveyName = htmlspecialchars($survey['name']); // [REVISIT]
				
				echo "<tr>";
				echo "<td>{$survey['name']}</td>";
				echo "<td>---</td>";
				echo "<td>---</td>";
				echo "<td>---</td>";
				echo "<td>"; 
				echo "<a href=\"edit_survey.php?action=edit&survey-name={$surveyName}&survey-id={$survey['survey_id']}\">Edit</a>&nbsp;";
				echo "<a onclick=\"return confirm('Are you sure?');\" ";
				echo "href=\"{$_SERVER['PHP_SELF']}?action=delete-survey&survey-id={$survey['survey_id']}\">Delete</a></td>";
				echo "</tr>";
			}
		}

		if ($numRows === 0) {
			echo "<td colspan='5'>None defined.</td>";
		}
		
		echo "</table>";
		
		
	}

	public static function injectTeamsTable($owner_id) {

		echo "<h3>My Teams</h3>";

		echo "<table>";
		echo "<tr><th>Team</th><th>Action</th></tr>";

		$teams = TeamFactory::getTeams($owner_id);
		if (count($teams) == 0) {
			echo "<tr><td colspan=\"2\">None defined.</tr>";
		} else {
			foreach ($teams as $team) {
				echo "<tr><td>{$team['name']}</td><td>";
				echo "<a href=\"edit_team.php?action=edit-team&team-id={$team['team_id']}&team-name={$team['name']}\">Edit</a>";
				echo '&nbsp;&nbsp;';
				echo "<a onclick=\"return confirm('Are you sure?');\" href=\"{$_SERVER['PHP_SELF']}?action=delete-team&team-id={$team['team_id']}&team-name={$team['name']}\">Delete</a>";
				echo '</td></tr>';
			}
		}
		echo '</table>';
	}

	public static function injectPendingSurveysTable() {

		$div = <<<"EOD"
			<h3>My Pending Surveys</h3>
			<table>		
				<tr>
					<th>Review</th>
					<th>Team</th>
					<th>Student</th>
					<th>Action</th>
				</tr>
				<tr>
					<td rowspan="3">CTEC-227 Spring 2017</td>
					<td rowspan="3">Team-1</td>
					<td>Richard Lint</td>
					<td><a href="take_survey.php?user-name=Richard%20Lint">Start</a></td>
				</tr>
				<tr>

				
					<td>Patrick McCulley</td>
					<td><a href="take_survey.php?user-name=Patrick%20McCulley">Start</a></td>
				</tr>
				<tr>

				
					<td>Andrey Demchenko</td>
					<td><a href="take_survey.php?user-name=Andrey%20Demchenko">Start</a></td>
				</tr>
			</table>
EOD;
		echo $div;
	}
	
	public static function injectSurveysOnMeTable() {

		$div = <<<"EOD"
			<h3>Surveys Done on Me</h3>
			<table>
				<tr>
					<th>Review</th>
					<th>Action</th>
				</tr>
				<tr>
					<td>CTEC-127 Spring 2016</td>
					<td><a href="survey_on_me.php">Results</a></td>
				</tr>
			</table>
EOD;
		echo $div;
	}
	
	public static function createTeam($teamName, $ownerId, $userIds, &$errMsg) {
		
		$status = TeamFactory::insertTeam($teamName, $ownerId, $userIds);
		if (false === $status) {
			$errMsg = TeamFactory::getLastError();
		} else {
			$errMsg = "";
		}
		return $status;
	}

	public static function updateTeam($teamId, $teamName, $ownerId, $userIds, &$errMsg) {
		
		$status = TeamFactory::updateTeam($teamId, $teamName, $ownerId, $userIds);
		if (false === $status) {
			$errMsg = TeamFactory::getLastError();
		} else {
			$errMsg = "";
		}
		return $status;
	}
	
	public static function deleteTeam($teamId, $ownerId, &$errMsg) {
		
		$status = TeamFactory::deleteTeam($teamId, $ownerId);
		if (false === $status) {
			$errMsg = TeamFactory::getLastError();
		} else {
			$errMsg = "";
		}
		return $status;
	}
	
	public static function createSurvey($surveyName, $ownerId, $questions, &$errMsg) {
		
		$surveyId = SurveyFactory::insertSurvey($surveyName, $ownerId, $questions);
		if (false === $surveyId) {
			$errMsg = SurveyFactory::getLastError();
		} else {
			$errMsg = "";
		}
		return $surveyId;
	}

	public static function updateSurvey($surveyId, $surveyName, $ownerId, $questions, &$errMsg) {
		
		$status = SurveyFactory::updateSurvey($surveyId, $surveyName, $ownerId, $questions);
		if (false === $status) {
			$errMsg = SurveyFactory::getLastError();
		} else {
			$errMsg = "";
		}
		return $status;
	}
	
	public static function deleteSurvey($surveyId, $ownerId, &$errMsg) {
		
		$status = SurveyFactory::deleteSurvey($surveyId, $ownerId);
		if (false === $status) {
			$errMsg = SurveyFactory::getLastError();
		} else {
			$errMsg = "";
		}
		return $status;
	}

	public static function startSurvey($surveyId, $ownerId, $startDateTime, $endDateTime, $teams, &$errMsg) {

		$status = SurveyFactory::startSurvey($surveyId, $ownerId, $startDateTime, $endDateTime, $teams);
		if (false === $status) {
			$errMsg = SurveyInstanceFactory::getLastError();
		} else {
			$errMsg = "";
		}
		return $status;
	}
	
}
