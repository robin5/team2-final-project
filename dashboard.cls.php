<?php
require_once('includes/Database/SurveyInstanceFactory.php');
require_once('includes/Database/TeamFactory.php');
require_once('includes/Database/SurveyFactory.php');
require_once('includes/Database/QuestionResponseFactory.php');
require_once('includes/Database/SurveyInstanceFactory.php');

class DashBoard {

	public static function injectSurveyInstancesTable() {

		$numRows = 0;

		echo "<h3>Survey Instances</h3>";
		echo "<table><tr><th>Survey</th><th>Start Date</th><th>End Date</th><th>Status</th><th>Action</th></tr>";

		if (false === ($surveyInstances = surveyInstanceFactory::getSurveyInstancesByOwner($_SESSION['userId']))) {
			$errMsg = surveyInstanceFactory::getLastError();
			echo "<tr><td colspan=5>{$errMsg}</td>";
		} else {
			foreach($surveyInstances as $surveyInstance)  {
			$numRows++;
				
				$currentTime = new DateTime();
				$startDate = new DateTime($surveyInstance['start_date']);
				$endDate = new DateTime($surveyInstance['end_date']);

				// Determine survey status
				
				if ($surveyInstance['released']) {
					$status = 'Released';
				}
				else {
					if ($currentTime < $startDate) {
						$status = "Queued";
					} else if (($currentTime >= $startDate) && ($currentTime <= $endDate)) {
						$status = 'In Progress..';
					} else {
						$status = 'Completed';
					}
				}
				

				// Create table row
				echo "<tr>";
				echo "<td>{$surveyInstance['name']}</td>";
				echo "<td>{$surveyInstance['start_date']}</td>";
				echo "<td>{$surveyInstance['end_date']}</td>";
				echo "<td>{$status}</td>";
				echo "<td><a href=\"survey_results.php?instance-id={$surveyInstance['instance_id']}&survey-name={$surveyInstance['name']}\">View Results</a>";
				if ($status == 'Completed') {
					echo "<br><a onclick=\"return areYouSure();\" href=\"dashboard.php?action=release-survey&instance-id={$surveyInstance['instance_id']}\">Release Results</a>";
				}
				echo "</td>";
				echo "</tr>";
			}
		}
		if ($numRows === 0) {
			echo "<td colspan='5'>None defined.</td>";
		}
		echo "</table>";
	}

	public static function injectSurveyTemplatesTable() {

		$numRows = 0;

		echo "<h3>My Templates</h3>";
		echo "<table><tr><th>Survey</th><th>Action</th></tr>";
		if (false === ($sourceSurveys = surveyFactory::getSourceSurveys($_SESSION['userId']))) {
			$errMsg = surveyFactory::getLastError();
			echo "<tr><td colspan=2>{$errMsg}</td>";
		} else {
			foreach($sourceSurveys as $survey)  {
			$numRows++;
				
				$surveyName = htmlspecialchars($survey['name']); // [REVISIT]
				
				echo "<tr>";
				echo "<td>{$survey['name']}</td>";
				echo "<td>"; 
				echo "<a href=\"edit_survey.php?action=edit&survey-name={$surveyName}&survey-id={$survey['survey_id']}\">Edit</a>&nbsp;";
				echo "<a onclick=\"return areYouSure();\" ";
				echo "href=\"{$_SERVER['PHP_SELF']}?action=delete-survey&survey-id={$survey['survey_id']}\">Delete</a></td>";
				echo "</tr>";
			}
		}

		if ($numRows === 0) {
			echo "<td colspan='2'>None defined.</td>";
		}
		echo "</table>";
	}

	public static function injectTeamsTable($owner_id) {

		echo "<h3>My Teams</h3>";

		echo "<table>";
		echo "<tr><th>Team</th><th>Action</th></tr>";

		$teams = TeamFactory::getRootTeams($owner_id);
		if (count($teams) == 0) {
			echo "<tr><td colspan=\"2\">None defined.</tr>";
		} else {
			foreach ($teams as $team) {
				echo "<tr><td>{$team['name']}</td><td>";
				echo "<a href=\"edit_team.php?action=edit-team&team-id={$team['team_id']}&team-name={$team['name']}\">Edit</a>";
				echo '&nbsp;&nbsp;';
				echo "<a onclick=\"return areYouSure();\" href=\"{$_SERVER['PHP_SELF']}?action=delete-team&team-id={$team['team_id']}&team-name={$team['name']}\">Delete</a>";
				echo '</td></tr>';
			}
		}
		echo '</table>';
	}

	public static function injectPendingSurveysTable() {
		
		echo "<h3>Surveys To Do</h3>";
		echo "<table><tr><th>Survey</th><th>Team</th><th>Student</th><th>User Name</th><th>Action</th></tr>";
		if (false === ($surveyInstances = SurveyInstanceFactory::getPendingSurveys($_SESSION['userId']))) {
			$errMsg = surveyInstanceFactory::getLastError();
			echo "<tr><td colspan=6>{$errMsg}</td>"; // [REVISIT] USE JAVASCRIPT TO PUT IN CORRECT PLACE ON PAGE
		} else {
			foreach($surveyInstances as $survey)  {
				
				if (false === ($teamMembers = TeamUserFactory::getTeamMembersByTeamId($survey['team_id']))) {
					$errMsg = surveyInstanceFactory::getLastError();
					echo "<tr><td colspan=5>{$errMsg}</td>"; // [REVISIT] USE JAVASCRIPT TO PUT IN CORRECT PLACE ON PAGE
				} else if (false === ($submittedReviewees = SurveyCompleteFactory::getSubmittedReviewees($survey['survey_id'], $_SESSION['userId']))) {
					$errMsg = SurveyCompleteFactory::getLastError();
					echo "<tr><td colspan=5>{$errMsg}</td>"; // [REVISIT] USE JAVASCRIPT TO PUT IN CORRECT PLACE ON PAGE
				}
				else {
					$rowSpan = count($teamMembers);
					$row = 0;
					while ($row < $rowSpan) {
						if ($row == 0) {
							echo "<tr>";
							echo "<td rowspan=\"{$rowSpan}\">{$survey['survey_name']}</td>";
							echo "<td rowspan=\"{$rowSpan}\">{$survey['team_name']}</td>";
						}
						$revieweeName = $teamMembers[$row]['first_name'] . " " . $teamMembers[$row]['last_name'];
						//echo "[{$teamMembers[$row]['user_id']},{$submittedReviewees[0]}]";
						if (false === array_search($teamMembers[$row]['user_id'], $submittedReviewees, false)) {
							$anchor = "<a href=\"take_survey.php?" .
								"survey-id={$survey['survey_id']}&" .
								"survey-name={$survey['survey_name']}&" .
								"team-name={$survey['team_name']}&" .
								"reviewee-name={$revieweeName}&" .
								"reviewer-id={$_SESSION['userId']}&" .
								"reviewee-id={$teamMembers[$row]['user_id']}\">" .
								"Start</a>";
						} else {
							$anchor = 'submitted';
						}
						
						echo "<td>{$revieweeName}</td>";
						echo "<td>{$teamMembers[$row]['user_name']}</td>";
						echo "<td>{$anchor}</td>";
						echo "</tr>";
						$row++;
					}
				}
			}
		}
		echo "</table>";
	}
	
	public static function injectSurveysOnMeTable() {

		echo "<h3>Surveys Results on Me</h3>";
		echo "<table><tr><th>Survey</th><th>Team</th><th>Action</th></tr>";
		if (false === ($surveyResponses = SurveyInstanceFactory::getSurveyResponses($_SESSION['userId']))) {
			$errMsg = surveyInstanceFactory::getLastError();
			echo "<tr><td colspan=3>{$errMsg}</td>"; // [REVISIT] USE JAVASCRIPT TO PUT IN CORRECT PLACE ON PAGE
		} else {
		
			foreach($surveyResponses as $surveyResponse)  {
				
				$fullName = $surveyResponse['first_name'] . " " . $surveyResponse['last_name'];
				
				if ($surveyResponse['released']) {
					// $action = "<a href=\"survey_on_me.php?user-name={$surveyResponse['survey_name']}\">Review</a>";
					$action =
						"<a href=\"survey_on_me.php" . 
						"?instance-id={$surveyResponse['instance_id']}" .
						"&survey-name={$surveyResponse['survey_name']}" .
						"&full-name={$fullName}" .
						"&team-id={$surveyResponse['team_id']}" . 
						"&reviewee={$_SESSION['userId']}\">" . 
						"Review</a>";
				} else {
					$action = "Pending...";
				}
				
				echo "<td>{$surveyResponse['survey_name']}</td>";
				echo "<td>{$surveyResponse['team_name']}</td>";
				echo "<td>{$action}</td>";
				echo "</tr>";
			}
		}
		echo "</table>";
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

	public static function launchSurveyInstance($instanceName, $surveyId, $ownerId, $startDateTime, $endDateTime, $teamIds, &$errMsg) {

		$status = SurveyFactory::launchSurveyInstance($instanceName, $surveyId, $ownerId, $startDateTime, $endDateTime, $teamIds);
		if (false === $status) {
			$errMsg = SurveyFactory::getLastError();
		} else {
			$errMsg = "";
		}
		return $status;
	}
	
	public static function saveSubmitSurvey($surveyId, $reviewee, $reviewer, $questionIds, $gradeIds, $responses, $responseIds, $submitFlag, &$errMsg) {
		
		if (false === QuestionResponseFactory::updateResponses($surveyId, $reviewee, $reviewer, $questionIds, $gradeIds, $responses, $responseIds, $submitFlag)) {
			$errMsg = QuestionResponseFactory::getLastError();
			return false;
		}
		return true;
	}

	public static function releaseSurvey($instanceId, $ownerId, &$errMsg) {
		
		$status = SurveyInstanceFactory::releaseSurvey($instanceId, $ownerId);
		if (false === $status) {
			$errMsg = SurveyInstanceFactory::getLastError();
		} else {
			$errMsg = "";
		}
		return $status;
	}

}
