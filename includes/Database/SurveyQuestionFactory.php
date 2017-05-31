<?php
require_once('Database.php');

class SurveyQuestionFactory
{
	static $class = 'SurveyQuestion';
	
	/**************************************************************
	 * Function: insert 
	 * Description: Insert a new entry into the tbl_team_user table
	 **************************************************************/
	 
    public static function insert($surveyId, $question, $qsIndex) {
        
		$surveyQuestionId = false;
		
		if (false !== ($questionId = QuestionFactory::insert($question))) {
			
			$db = DatabaseConnectionFactory::getConnection();
			
			$query = "INSERT INTO tbl_survey_question (survey_id, question_id, qs_index) VALUES ('{$surveyId}','{$questionId}','{$qsIndex}')";

			if (false === $db->query($query)) {
				$lastError = $db->error;
			} else {
				$surveyQuestionId = $db->insert_id;
			}
		}
        return $surveyQuestionId;
    }

	/*******************************************************
	 * Function: getQuestions 
	 * Description: Returns an array of question text
	 *******************************************************/
	 
	public static function getSurveyQuestions($surveyId) {
		
		$questions = FALSE;
		$result;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT text ";
		$query .= "FROM tbl_survey_question JOIN tbl_question ";
		$query .= "ON tbl_survey_question.question_id=tbl_question.question_id ";
		$query .= "WHERE tbl_survey_question.survey_id={$surveyId} ";
		$query .= "ORDER BY qs_index";
		
		if (false === ($result = $db->query($query))) {
			$lastError = $db->error;
		} else {			
			// get the question_ids for this survey
			$questions = [];
			while ($question = $result->fetch_assoc()){
					$questions[] = $question['text'];
			}
			$result->close();
			
		}
		return $questions;
	}

	/*******************************************************
	 * Function: deleteSurvey 
	 * Description: Deletes survey and thru cascading deletes
	 *     all survey questions
	 *******************************************************/
	 
	public static function deleteSurvey($surveyId) {
	
		$db = DatabaseConnectionFactory::getConnection();

		$query = "DELETE FROM tbl_survey_question WHERE survey_id = {$surveyId}";

		if (false !== $db->query($query)) {
			return true;
		} else {
			$lastError = $db->error;
		}
		return false;
	}
	
}
