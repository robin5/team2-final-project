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
		$query .= "ON tbl_survey_instance.survey_id = tbl_survey.survey_id ";
		$query .= "WHERE tbl_survey_instance.owner_id = {$ownerId} ORDER BY {$order}";

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
