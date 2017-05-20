<?php
require_once('Database.php');

class surveyInstanceFactory extends DatabaseFactory {

	/*********************************************************
	 * Function: insert 
	 * Description: Starts a new survey instance
	 *********************************************************/
	 
    public static function insert($surveyId, $ownerId, $start, $end) {
        
		$instanceId = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		//$surveyName = $db->escape_string($surveyName);
		$ownerId = $db->escape_string($ownerId);
		
        $query = "INSERT INTO tbl_survey_instance (survey_id, owner_id, start_date, end_date) VALUES ('{$surveyId}','{$ownerId}','{$start}','{$end}')";

		// Insert instance into database
        if ($db->query($query) === true) {
			$instanceId = $db->insert_id;
		} else {
			self::$lastError = $db->error;
		}
		
        return $instanceId;
    }

	/**********************************************************
	 * Function: getSurveyInstances
	 * Description: Returns all survey instances from 
	 *     tbl_survey_instance given the owner_id
	 **********************************************************/
	 
	public static function getSurveyInstancesByOwner($ownerId, $order = "instance_id") {
		
		$result;
		$surveyInstances = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT tbl_survey_instance.instance_id, name, start_date, end_date ";
		$query .= "FROM tbl_survey_instance JOIN tbl_survey ";
		$query .= "ON tbl_survey_instance.instance_id = tbl_survey.instance_id ";
		$query .= "WHERE tbl_survey.owner_id = {$ownerId} ORDER BY {$order}";

		if (false != ($result = $db->query($query))) {
			$surveyInstances = [];
			while ($surveyInstance = $result->fetch_assoc()){
				$surveyInstances[] = $surveyInstance;
			}
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		return $surveyInstances;
	}
	
	/**********************************************************
	 * Function: getPendingSurveys
	 * Description: Returns pending surveys for a given user
	 **********************************************************/
	 
	public static function getPendingSurveys($userId) {
		
		$result;
		$surveyInstances = false;
		
		// Get the database connection
		$db = DatabaseConnectionFactory::getConnection();

		// Define the query
		$query = "";
		$query .= "SELECT tbl_survey.name AS survey_name,tbl_survey.survey_id,tbl_team.name AS team_name,tbl_team.team_id
		FROM users ";
		$query .= "	JOIN tbl_team_user ";
		$query .= "		ON users.user_id = tbl_team_user.user_id ";
		$query .= "	JOIN tbl_team ";
		$query .= "		ON tbl_team.team_id = tbl_team_user.team_id ";
		$query .= "	JOIN tbl_team_instance ";
		$query .= "		ON tbl_team_instance.team_id = tbl_team.team_id ";
		$query .= "	JOIN tbl_survey_instance ";
		$query .= "		ON tbl_survey_instance.instance_id=tbl_team_instance.instance_id ";
		$query .= "	JOIN tbl_survey ";
		$query .= "		ON tbl_survey.instance_id = tbl_survey_instance.instance_id ";
		$query .= "WHERE tbl_survey_instance.start_date < NOW() AND NOW() < tbl_survey_instance.end_date AND users.user_id={$userId}";

		// Execute the query
		if (false != ($result = $db->query($query))) {
			$surveyInstances = [];
			while ($surveyInstance = $result->fetch_assoc()){
				$surveyInstances[] = $surveyInstance;
			}
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		return $surveyInstances;
	}
	/**********************************************************
	 * Function: getSurveyResponses
	 * Description: Returns survey for a given user
	 **********************************************************/
	 
	public static function getSurveyResponses($userId) {
		
		$result;
		$surveyInstances = false;
		
		// Get the database connection
		$db = DatabaseConnectionFactory::getConnection();

		// Define the query
		$query = "";
		$query .= "SELECT tbl_survey.name AS survey_name,tbl_survey.survey_id,tbl_team.name AS team_name,tbl_team.team_id,tbl_survey_instance.released ";
		$query .= "	FROM users ";
		$query .= "	JOIN tbl_team_user ";
		$query .= "		ON users.user_id = tbl_team_user.user_id ";
		$query .= "	JOIN tbl_team ";
		$query .= "		ON tbl_team.team_id = tbl_team_user.team_id ";
		$query .= "	JOIN tbl_team_instance ";
		$query .= "		ON tbl_team_instance.team_id = tbl_team.team_id ";
		$query .= "	JOIN tbl_survey_instance ";
		$query .= "		ON tbl_survey_instance.instance_id=tbl_team_instance.instance_id ";
		$query .= "	JOIN tbl_survey ";
		$query .= "		ON tbl_survey.instance_id = tbl_survey_instance.instance_id ";
		$query .= "WHERE users.user_id={$userId}";

		// Execute the query
		if (false != ($result = $db->query($query))) {
			$surveyInstances = [];
			while ($surveyInstance = $result->fetch_assoc()){
				$surveyInstances[] = $surveyInstance;
			}
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		
		return $surveyInstances;
	}
}
