<?php
require_once('includes/Database/TeamFactory.php');
require_once('includes/Database/SurveyInstanceFactory.php');
require_once('includes/Database/QuestionResponseFactory.php');

class SurveyResults {

	public static function injectTeamTables2($instanceId, $surveyName, $questions, $instanceTeams) {
		
		$lastTeamId = "";
		$isFirst = true;
		echo "<div id=\"div-entries\">";

		if (false !== ($instanceTeams = TeamFactory::getTeamUsersByInstance($instanceId))) {

			foreach($instanceTeams as $instanceTeam) {
				
				$teamId = $instanceTeam['team_id'];
				
				if ($teamId != $lastTeamId) {
					if ($isFirst) {
						echo "<h1>{$instanceTeam['team_name']}</h1>";
						$isFirst = false;
					} else {
						echo "<hr><h1>{$instanceTeam['team_name']}</h1>";
					}
				}
				$lastTeamId = $teamId;
				
				$fullName = "{$instanceTeam['first_name']}&nbsp;{$instanceTeam['last_name']}";

				// Get the members of the team
				if (false === ($members = TeamUserFactory::getTeamMembersByTeamId($teamId))) {
					$errMsg =  TeamUserFactory::getLastError();
					// Display this to user somehow
				} else {
					
					$pieData = SurveyResults::getPieData($questions, $instanceTeam['user_id'], $members);

					$percentDone = SurveyResults::getPercentDone($questions, $instanceTeam['user_id'], $members);
					
					echo "<div class=\"reviewee-content\" style=\"float: left; margin-left: 40px; margin-top: 20px;\">";

					echo "<h2>{$fullName} - {$instanceTeam['user_name']}</h2>";

					echo "<a href=\"survey_on_me.php" . 
							"?instance-id={$instanceId}" .
							"&survey-name={$surveyName}" .
							"&full-name={$fullName}" .
							"&team-id={$instanceTeam['team_id']}" . 
							"&reviewee={$instanceTeam['user_id']}\">" . 
							"See comments about {$instanceTeam['first_name']}</a><br><br>";

							
					echo "<td><a href=\"responses.php" . 
							"?instance-id={$instanceId}" .
							"&survey-name={$surveyName}" .
							"&full-name={$fullName}" .
							"&team-id={$instanceTeam['team_id']}" . 
							"&team-name={$instanceTeam['team_name']}" . 
							"&reviewer={$instanceTeam['user_id']}\">" . 
							"Review {$instanceTeam['first_name']}'s surveys: {$percentDone}% complete</a></td>";

					echo "</div>";

					echo "<div data='{$pieData}' id=\"pie-chart-{$instanceTeam['user_id']}\" class=\"pie-chart\" style=\"width: 200px; height: 200px; margin-top: 0; float: left;\">&nbsp;</div>";
					
					echo "<div class=\"clear-detail\" style=\"clear: both;\">";
					echo "</div>";
				}
			}
		}
	}
	
	public static function getPieData($questions, $reviewee, $members) {

		$grades = [];
		
		// Init the accumulators
		$numA = 0;
		$numB = 0;
		$numC = 0;
		$numD = 0;
		$numF = 0;
		$numNA = 0;
	
		// Loop thru each question
		foreach($questions as $question) {
			
			// Loop thru each reviewer
			foreach($members as $reviewer) {
				
				// Get the response to each question
				if (false !== ($response = QuestionResponsefactory::getResponse($question['question_id'], $reviewee, $reviewer['user_id']))) {
					$text = $response['text'];
					$grade = $response['grade'];
					if (empty($grade)) {
						$grade = "---";
					}
				}
				
				// Add the occurences of grades
				switch($grade) {
					case 'A': $numA++; break;
					case 'B': $numB++; break;
					case 'C': $numC++; break;
					case 'D': $numD++; break;
					case 'F': $numF++; break;
					default: $numNA++; break;
				}
			}
		}

		// Place grades into series format for pie charts
		// [['A', 1],['B', 1],['C', 1],['D', 1],['F', 1]]"

		if ($numA > 0) {$grades[] = (['A', $numA]);}
		if ($numB > 0) {$grades[] = (['B', $numB]);}
		if ($numC > 0) {$grades[] = (['C', $numC]);}
		if ($numD > 0) {$grades[] = (['D', $numD]);}
		if ($numF > 0) {$grades[] = (['F', $numF]);}
		if ($numNA > 0) {$grades[] = (['Not Available', $numNA]);}

		return json_encode($grades);
	}
	
	public static function getPercentDone($questions, $reviewer, $reviewees) {
	
		$userIndex = 0;
		$buttonIndex = 0;
		
		if (0 == ($numExpectedAnswers = count($questions) * count($reviewees))) {
			return 0;
		} else {
			
			$numFoundAnswers = 0;

			foreach($reviewees as $user) {
				foreach($questions as $question) {
					if (false !== ($response = QuestionResponsefactory::getResponse($question['question_id'], $user['user_id'], $reviewer))) {
						if (!empty($response['text'])) {
							$numFoundAnswers++;
						}
					}
				} // for each questions
			} // for each users
			return (round((100 * $numFoundAnswers)/$numExpectedAnswers, 2, PHP_ROUND_HALF_UP));
		}
	}
}