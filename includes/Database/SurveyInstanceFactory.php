<?php
require_once('Database.php');

class surveyInstanceFactory extends DatabaseFactory {

	/*********************************************************
	 * Function: insert 
	 * Description: Starts a new survey instance
	 *********************************************************/
	 
    public static function insert($start, $end) {
        
		$instanceId = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
        $query = "INSERT INTO tbl_survey_instance (start_date, end_date) VALUES ('{$start}','{$end}')";

		// Insert instance into database
        if ($db->query($query) === true) {
			$instanceId = $db->insert_id;
		} else {
			self::$lastError = $db->error;
		}
		
        return $instanceId;
    }

	/**********************************************************
	 * Function: getSurveyInstancesByOwner
	 * Description: Returns all survey instances from 
	 *     tbl_survey_instance given the owner_id
	 **********************************************************/
	 
	public static function getSurveyInstancesByOwner($ownerId, $order = "instance_id") {
		
		$result;
		$surveyInstances = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT tbl_survey_instance.instance_id, name, start_date, end_date, released ";
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
		$query = 
			"SELECT 
				tbl_survey.name AS survey_name,
				tbl_survey.survey_id,
				tbl_team.name AS team_name,
				tbl_team.team_id
			FROM users
				JOIN tbl_team_user
					ON users.user_id = tbl_team_user.user_id
				JOIN tbl_team
					ON tbl_team.team_id = tbl_team_user.team_id
				JOIN tbl_survey_instance
					ON tbl_survey_instance.instance_id = tbl_team.instance_id
				JOIN tbl_survey
					ON tbl_survey.instance_id = tbl_survey_instance.instance_id
				WHERE
					tbl_survey_instance.start_date < NOW() AND 
					NOW() < tbl_survey_instance.end_date AND 
					users.user_id={$userId}";

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
		$query = 
		   "SELECT
				users.first_name,
				users.last_name,
				tbl_survey.name AS survey_name,
				tbl_survey.survey_id,
				tbl_survey.instance_id,
				tbl_team.name AS team_name,
				tbl_team.team_id,
				tbl_survey_instance.released 
			FROM users 
				JOIN tbl_team_user
					ON users.user_id = tbl_team_user.user_id
				JOIN tbl_team
					ON tbl_team.team_id = tbl_team_user.team_id
				JOIN tbl_survey_instance
					ON tbl_survey_instance.instance_id=tbl_team.instance_id
				JOIN tbl_survey
					ON tbl_survey.instance_id = tbl_survey_instance.instance_id
			WHERE users.user_id={$userId}";

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
	 * Function: releaseSurvey
	 * Description: Sets the released column in 
	 *     tbl_survey_instance to true;
	 **********************************************************/
	 
	public static function releaseSurvey($instanceId, $ownerId) {
		
		$result;
		$surveyInstances = false;
		
		// Get the database connection

		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "UPDATE tbl_survey_instance a ";
		$query .= "JOIN tbl_survey b ";
		$query .= "   ON a.instance_id =  b.instance_id ";
		$query .= "SET ";
		$query .= "   a.released=1 ";
		$query .= "WHERE ";
		$query .= "   a.instance_id = {$instanceId} AND b.owner_id={$ownerId};";
		
		// Execute query
		if ($db->query($query) === false) {
			self::$lastError = $db->error;
			return false;
		}
		return true;
	}

	
	public static function getSurveyInstanceQuestionIds($instanceId) {
		
		$result;
		$questionIds= false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = 
		
			"SELECT 
				tbl_question.text,
				tbl_question.question_id
			FROM tbl_question
				JOIN tbl_survey_question
					ON tbl_question.question_id = tbl_survey_question.question_id
				JOIN tbl_survey
					ON tbl_survey_question.survey_id = tbl_survey.survey_id
			WHERE
				tbl_survey.instance_id = {$instanceId}
			ORDER BY
				tbl_survey_question.qs_index";

		if (false != ($result = $db->query($query))) {
			$questionIds = [];
			while ($questionId = $result->fetch_assoc()){
				$questionIds[] = $questionId;
			}
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		return $questionIds;
	}
	
}
