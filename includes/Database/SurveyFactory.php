<?php
require_once('Database.php');
require_once('QuestionFactory.php');
require_once('SurveyQuestionFactory.php');
require_once('TeamFactory.php');

class SurveyFactory extends DatabaseFactory {

	/*********************************************************
	 * Function: insert 
	 * Description: Insert a new survey into the tbl_survey table
	 *********************************************************/
	 
    public static function insert($surveyName, $ownerId) {
        
		$surveyId = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$surveyName = $db->escape_string($surveyName);
		$ownerId = $db->escape_string($ownerId);
		
        $query = "INSERT INTO tbl_survey (name, owner_id) VALUES ('{$surveyName}','{$ownerId}')";

		// Insert user into database
        if ($db->query($query) === true) {
			$surveyId = $db->insert_id;
		} else {
			$lastError = $db->error;
			echo $lastError;
		}
		
        return $surveyId;
    }

	/**********************************************************
	 * Function: getSurveys 
	 * Description: Returns all surveys in the tbl_survey table
	 *     given the survey_id
	 **********************************************************/
	 
	public static function getSurveys($ownerId) {
		
		$result = false;
		$surveys = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT name, survey_id FROM tbl_survey where owner_id = {$ownerId} ORDER BY name";

		if (false != ($result = $db->query($query))) {
			$surveys = [];
			while ($survey = $result->fetch_assoc()){
				$surveys[] = $survey;
			}
			$result->close();
		} else {
			$lastError = $db->error;
			echo $lastError;
		}
		return $surveys;
	}
	
	public static function getSourceSurveys($ownerId) {
		
		$result;
		$row;
		$userId = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		//$query = "SELECT name, survey_id FROM tbl_survey WHERE owner_id = {$ownerId} AND survey_id NOT IN ";
		//$query .= "(SELECT survey_id FROM tbl_survey_instance WHERE owner_id = {$ownerId})";

		$query = "SELECT name, survey_id FROM tbl_survey WHERE owner_id = {$ownerId} AND instance_id IS NULL";

		if (false != ($result = $db->query($query))) {
			$surveys = [];
			while ($survey = $result->fetch_assoc()){
				$surveys[] = $survey;
			}
			$result->close();
		} else {
			$lastError = $db->error;
			echo $lastError;
		}
		return $surveys;
	}

	public static function xxxxgetSurveyQuestion($surveyId) {
		
		$result;
		$row;
		$userId = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT qs_index, text ";
		$query .= "FROM tbl_survey_question JOIN tbl_question ";
		$query .= "ON tbl_survey_question.question_id = tbl_question.question_id ";
		$query .= "WHERE survey_id = {$surveyId} ORDER BY qs_index";

		if (false != ($result = $db->query($query))) {
			$surveys = [];
			while ($survey = $result->fetch_assoc()){
				$surveys[] = $survey['text'];
			}
			$result->close();
		} else {
			$lastError = $db->error;
		}
		return $surveys;
	}
	
	/*******************************************************
	 * Function: insertSurvey 
	 * Description: Adds a new survey to tbl_team, and then
	 *     adds users to tbl_team_user.  REVISIT: this
	 *     should be a transaction in a stored procedure.
	 *******************************************************/
	 
	public static function insertSurvey($surveyName, $ownerId, $questions) {
	
		$surveyId = false;
		
		if (false !== ($surveyId = self::insert($surveyName, $ownerId))) {
			
			$numQuestions = count($questions);
			for ($qsIndex = 0; $qsIndex < $numQuestions; $qsIndex++) {
				if (false === SurveyQuestionFactory::insert($surveyId, $questions[$qsIndex], $qsIndex)) {
					// [REVISIT] at this point we really should roll back the entire transaction
					return false;
				}
			}
		}
		return $surveyId;
	}
	
	/*******************************************************
	 * Function: updateSurvey 
	 * Description: 
	 *******************************************************/
	 
	public static function updateSurvey($surveyId, $surveyName, $ownerId, $questions) {
	
		if (false === QuestionFactory::deleteQuestionsBySurveyId($surveyId)) {
			return false;
		} else if (false === SurveyQuestionFactory::deleteSurvey($surveyId)) {
			return false;
		} else {
			
			$qs_index = 0;
			foreach($questions as $question) {
				if (false === SurveyQuestionFactory::insert($surveyId, $question, $qs_index)) {
					return false;
				}
				$qs_index++;
			}
			
			if (false === SurveyFactory::updateSurveyName($surveyId, $surveyName, $ownerId)) {
				return false;
			}
		}
		return true;
	}

	public static function updateSurveyName($surveyId, $surveyName, $ownerId) {
		
		$query = "UPDATE tbl_survey SET name = '{$surveyName}' WHERE survey_id={$surveyId} AND owner_id={$ownerId};";
		
		$db = DatabaseConnectionFactory::getConnection();

		if (false === $db->query($query)) {
			self::$lastError = $db->error;
			return false;
		}
		return true;
	}
	
	/*******************************************************
	 * Function: deleteSurvey 
	 * Description: 
	 *******************************************************/
	 
	public static function deleteSurvey($surveyId, $ownerId) {

		if (self::canDelete($surveyId, $ownerId)) {

			$db = DatabaseConnectionFactory::getConnection();

			$query = "DELETE FROM tbl_survey WHERE survey_id = {$surveyId} AND owner_id = {$ownerId} LIMIT 1;";
			
			if (false !== $db->query($query)) {
				return true;
			} else {
				self::$lastError = $db->error;
			}
		}
		return false;
	}
	
	/*******************************************************
	 * Function: canDelete 
	 * Description: If the instance_id of a survey is null
	 *     then the survey has not been distribtued, and it
	 *     may be deleted.
	 *******************************************************/
	 
	public static function canDelete($surveyId, $ownerId) {
		
		$db = DatabaseConnectionFactory::getConnection();

		$query = "SELECT instance_id FROM tbl_survey WHERE survey_id = {$surveyId} AND owner_id = {$ownerId};";
		
		if (false === ($result = $db->query($query))) {
			self::$lastError = $db->error;
		} else {
			if (false === ($survey = $result->fetch_assoc())) {
				self::$lastError = $db->error;
			} else {
				if (empty($survey['instance_id'])) {
					return true;
				}
			}
		}
		return false;
	}

	/**********************************************************
	 * Function: launchSurveyInstance
	 * Description: launches an instance of a survey 
	 *     [REVISIT] THIS SHOULD BE A TRANSACTION IN A STORED 
	 *     PROCEDURE IN THE DATABASE
	 **********************************************************/
	 
	public static function launchSurveyInstance($instanceName, $surveyId, $ownerId, $start, $end, $teamIds) {
		
		if (false !== ($surveyId = self::duplicateSurvey($instanceName, $surveyId, $ownerId))){
			// Note: that $surveyId now point to new duplicate survey
			if (false !== ($instanceId = SurveyInstanceFactory::insert($start, $end))) {
				// Update the new survey's instance
				if (false !== self::updateSurveyInstance($surveyId, $instanceId, $ownerId)) {
					return TeamFactory::generateInstanceTeams($instanceId, $ownerId, $teamIds);
				}
			}
		}
		return false;
	}
	
	/**********************************************************
	 * Function: duplicateSurvey
	 * Description: duplicates the survey given by survey
	 * [REVISIT] THIS SHOULD BE A TRANSACTION
	 **********************************************************/
	 
	public static function duplicateSurvey($instanceName, $surveyId, $ownerId) {

		if (false !== ($questions = SurveyQuestionFactory::getSurveyQuestions($surveyId))) {
			if (false !== ($newSurveyId = self::insertSurvey($instanceName, $ownerId, $questions))) {
				return $newSurveyId;
			}
		}
		return false;
	}

	/**********************************************************
	 * Function: getSurvey
	 * Description: returns a row from the tbl_survey table
	 **********************************************************/
	 
	public static function getSurvey($surveyId, $ownerId) {
		
		$query = "SELECT * FROM tbl_survey WHERE survey_id={$surveyId} AND owner_id={$ownerId}";
		
		$db = DatabaseConnectionFactory::getConnection();

		if (false === ($result = $db->query($query))) {
			self::$lastError = $db->error;
		} else if (false === ($survey = $result->fetch_assoc())) {
			self::$lastError = $db->error;
		} else {
			return $survey;
		}
		return false;
	}
	
	public static function updateSurveyInstance($surveyId, $instanceId, $ownerId) {
		
		$query = "UPDATE tbl_survey SET instance_id = '{$instanceId}' WHERE survey_id={$surveyId} AND owner_id={$ownerId};";
		
		$db = DatabaseConnectionFactory::getConnection();

		if (false === $db->query($query)) {
			self::$lastError = $db->error;
			return false;
		}
		return true;
	}
}