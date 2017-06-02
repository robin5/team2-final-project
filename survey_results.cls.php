<?php
require_once('includes/Database/TeamFactory.php');

class SurveyResults {

	public static function injectTeamTables($instanceId, $surveyName) {
		
		$lastTeamId = "";
		$isFirst = true;
		echo "<div id=\"div-entries\">";
		echo "<table>";
		if (false !== ($instanceTeams = TeamFactory::getTeamUsersByInstance($instanceId))) {

			foreach($instanceTeams as $instanceTeam) {
				
				$teamId = $instanceTeam['team_id'];
				
				if ($teamId != $lastTeamId) {
					if ($isFirst) {
						echo "<th>{$instanceTeam['team_name']}</th><th>User Name</th><th>Done By Student</th><th>Done On Student</th></tr>";
						$isFirst = false;
					} else {
						echo "<th>{$instanceTeam['team_name']}</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
					}
				}
				$lastTeamId = $teamId;
				
				$fullName = "{$instanceTeam['first_name']}&nbsp;{$instanceTeam['last_name']}";
				echo "<tr>";

				echo "<td>{$fullName}</td>";

				echo "<td>{$instanceTeam['user_name']}</td>";
				//echo "<td><a href=\"responses.php?instance-id={$instanceId}&reviewer={$instanceTeam['user_id']}\">surveys</a></td>";

				echo "<td><a href=\"responses.php" . 
						"?instance-id={$instanceId}" .
						"&survey-name={$surveyName}" .
						"&full-name={$fullName}" .
						"&team-id={$instanceTeam['team_id']}" . 
						"&team-name={$instanceTeam['team_name']}" . 
						"&reviewer={$instanceTeam['user_id']}\">" . 
						"{$instanceTeam['first_name']}'s surveys</a></td>";

				echo "<td><a href=\"survey_on_me.php" . 
						"?instance-id={$instanceId}" .
						"&survey-name={$surveyName}" .
						"&full-name={$fullName}" .
						"&team-id={$instanceTeam['team_id']}" . 
						"&reviewee={$instanceTeam['user_id']}\">" . 
						"About&nbsp;{$instanceTeam['first_name']}</a></td>";
				echo "</tr>";
			}
		}
		echo "</table>";
		
	}	
}