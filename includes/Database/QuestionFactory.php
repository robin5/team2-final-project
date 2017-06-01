<?php
require_once('Database.php');
require_once('Question.php');

class QuestionFactory extends DatabaseFactory
{
	/**************************************************************
	 * Function: insert 
	 * Description: Insert a new entry into the questions table
	 **************************************************************/
	 
    public static function insert($questionText) {
        
		$questionId = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$questionText = $db->escape_string($questionText);
		
        $query = "INSERT INTO tbl_question (text) VALUES ('{$questionText}')";

		if (false === $db->query($query)) {
			self::$lastError = $db->error;
		} else {
			$questionId = $db->insert_id;
		}
        return $questionId;
    }

	/**************************************************************
	 * Function: getQuestion 
	 * Description: Insert a new entry into the questions table
	 **************************************************************/
	 
    public static function getQuestion($questionId) {
        
		$text = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT text FROM tbl_question WHERE question_id={$questionId}";

		if (false === ($result = $db->query($query))) {
			self::$lastError = $db->error;
		} else {
			$text = $result->fetch_assoc('text');
			$result->close();
		}
        return $text;
    }
	
	/**************************************************************
	 * Function: deleteQuestionsBySurveyId 
	 * Description: deletes all questions from the survey specified
	 *              by surveyId.
	 **************************************************************/
	 
	public static function deleteQuestionsBySurveyId($surveyId) {
		
		$db = DatabaseConnectionFactory::getConnection();

		$query = 
		   "DELETE FROM tbl_question 
			WHERE question_id IN
				(SELECT question_id
				 FROM tbl_survey_question 
				 WHERE survey_id={$surveyId})";

		if (false !== $db->query($query)) {
			return true;
		} else {
			$lastError = $db->error;
		}
		return false;
	}
}
	
