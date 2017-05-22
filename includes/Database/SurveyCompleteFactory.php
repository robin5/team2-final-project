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
}