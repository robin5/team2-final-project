<?php
require_once('Database.php');

class SurveyCompleteFactory extends DatabaseFactory {
	
	/*********************************************************
	 * Function: insert 
	 * Description: inserts a record into tbl_survey_complete 
	 * Return: true if successful, false otherwise.
	 *********************************************************/
	 
	public static function insert($surveyId, $reviewee, $reviewer, $submissionId) {
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "INSERT INTO tbl_survey_complete ";
		$query .= "(survey_id, reviewee, reviewer, submission_id) ";
		$query .= "VALUES ";
		$query .= "('{$surveyId}','{$reviewee}','{$reviewer}','{$submissionId}')";			
		// Execute query
		if ($db->query($query) === false) {
			self::$lastError = $db->error;
			return false;
		}
		return true;
	}

	public static function getRevieweesByReviewer($surveyId, $reviewer) {

		$reviewees = false;

		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT reviewee, submission_id FROM tbl_survey_complete ";
		$query .= "WHERE survey_id={$surveyId} AND reviewer={$reviewer}; ";
		if (false !== ($result = $db->query($query))) {
			$reviewees = [];
			while ($reviewee = $result->fetch_assoc()){
				$reviewees[] = $reviewee;
			}
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		return $reviewees;
	}

	public static function getSubmittedReviewees($surveyId, $reviewer) {

		$submittedReviewees = false;

		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT reviewee FROM tbl_survey_complete ";
		$query .= "WHERE survey_id={$surveyId} AND reviewer={$reviewer} AND submission_id = 1; ";
		if (false !== ($result = $db->query($query))) {
			$submittedReviewees = [];
			while ($submittedReviewee = $result->fetch_assoc()){
				$submittedReviewees[] = $submittedReviewee['reviewee'];
			}
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		return $submittedReviewees;
	}
	
	public static function getSubmissionId($surveyId, $reviewee, $reviewer) {

		$submissionId = false;

		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT submission_id
				  FROM tbl_survey_complete
				  WHERE survey_id={$surveyId} AND reviewer={$reviewer} AND reviewee={$reviewee}; ";
				  
		if (false !== ($result = $db->query($query))) {
			$submissionId = $result->fetch_assoc()['submission_id'];
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		return $submissionId;
	}

	public static function setSubmissionId($surveyId, $reviewer, $reviewee, $submissionId) {

		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "UPDATE tbl_survey_complete 
				  SET submission_id = {$submissionId}
				  WHERE survey_id={$surveyId} AND reviewer={$reviewer} AND reviewee={$reviewee}";

		if (false === ($result = $db->query($query))) {
			self::$lastError = $db->error;
		}
		
		return $result;
	}

	public static function generateSurveyCompletes($surveyId, $ownerId, $teamIds) {

		// Attach the teamIds to the new survey's instance
		for ($i = 0; $i < count($teamIds); $i++) {
			if (false !== ($team = TeamFactory::getTeam($teamIds[$i], $ownerId))) {
				if (false !== ($userIds = TeamUserFactory::getUserIds($teamIds[$i]))) {
					
					foreach($userIds as $reviewer) {
						foreach($userIds as $reviewee) {
							SurveyCompleteFactory::Insert($surveyId, $reviewee, $reviewer, 0 /* not submitted */);
						}
					}
				}
			}
		}
		return true;
	}
}