<?php
require_once('Database.php');

class QuestionResponseFactory extends DatabaseFactory {

	/*********************************************************
	 * Function: getQuestionResponses 
	 * Description: queries the database for responses
	 * Return: an associative array containing
	 *     responses to survey questions
	 *********************************************************/
	 
	public static function getQuestionResponses($surveyId, $reviewee, $reviewer) {
		
		/* For debugging purposes
		$questionResponses = [];
		$questionResponses[0]=['question'=>'', 'qs_index'=>0, 'text'=>'Hello world0', 'grade_id'=>'1'];
		$questionResponses[1]=['question'=>'', 'qs_index'=>1, 'text'=>'Hello world1', 'grade_id'=>'2'];
		$questionResponses[2]=['question'=>'', 'qs_index'=>2, 'text'=>'Hello world2', 'grade_id'=>'3'];
		$questionResponses[3]=['question'=>'', 'qs_index'=>3, 'text'=>'Hello world3', 'grade_id'=>'5'];
		*/
		
		$questionResponses = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT ";
		$query .= "response_id, ";
		$query .= "tbl_survey_response.question_id, ";
		$query .= "reviewee, ";
		$query .= "reviewer, ";
		$query .= "tbl_survey_response.text AS response, ";
		$query .= "tbl_question.text AS question, ";
		$query .= "grade_id ";
		$query .= "FROM tbl_survey_response ";
		$query .= "  JOIN tbl_question ";
		$query .= "    ON tbl_survey_response.question_id = tbl_question.question_id ";
		$query .= "  JOIN tbl_survey_question ";
		$query .= "    ON tbl_survey_question.question_id = tbl_question.question_id ";
		$query .= "WHERE ";
		$query .= "  tbl_survey_response.reviewee = {$reviewee} AND ";
		$query .= "  tbl_survey_response.reviewer = {$reviewer} AND ";
		$query .= "  tbl_survey_question.SURVEY_ID = {$surveyId};";
		
		if (false != ($result = $db->query($query))) {
			$questionResponses = [];
			while ($questionResponse = $result->fetch_assoc()){
				$questionResponses[] = $questionResponse;
			}
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		return $questionResponses;
	}

	/*********************************************************
	 * Function: getQuestions 
	 * Description: queries the database for questions
	 * Return: associative array containing survey questions
	 *********************************************************/
	 
	public static function getQuestions($surveyId) {
		
		$questions = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT ";
		$query .= "tbl_survey_question.question_id, text, qs_index ";
		$query .= "FROM tbl_survey ";
		$query .= "  JOIN tbl_survey_question ";
		$query .= "    ON tbl_survey.survey_id = tbl_survey_question.survey_id ";
		$query .= "  JOIN tbl_question ";
		$query .= "    ON tbl_survey_question.question_id = tbl_question.question_id ";
		$query .= "WHERE ";
		$query .= "  tbl_survey.survey_id = {$surveyId};";
		
		if (false != ($result = $db->query($query))) {
			$questions = [];
			while ($question = $result->fetch_assoc()){
				$questions[] = $question;
			}
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		return $questions;
	}

	/*********************************************************
	 * Function: getResponses 
	 * Description: queries the database for responses
	 * Return: an associative array containing
	 *     responses to survey questions
	 *********************************************************/
	 
	public static function getResponses($questionId, $reviewee, $reviewer) {
		
		$responses = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT * ";
		//$query .= "text, grade_id, response_id ";
		$query .= "FROM tbl_survey_response ";
		$query .= "WHERE ";
		$query .= "  reviewee = {$reviewee} AND ";
		$query .= "  reviewer = {$reviewer}; ";

		//echo "<pre>"; print_r($query); echo "</pre>";

		if (false != ($result = $db->query($query))) {
			$responses = [];
			while ($response = $result->fetch_assoc()){
				$responses[] = $response;
			}
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		return $responses;
	}

	/*********************************************************
	 * Function: updateResponses 
	 * Description: queries the database for responses
	 * Return: an associative array containing
	 *     responses to survey questions
	 *********************************************************/
	 
	public static function updateResponses($reviewee, $reviewer, $questionIds, $gradeIds, $responses, $responseIds, $submitFlag) {
		
		$numEntries = count($questionIds);
		$db = DatabaseConnectionFactory::getConnection();
		
		
		if (empty($responseIds[0])) {

			// Do an Insert query
			$query = "INSERT INTO tbl_survey_response ";
			$query .= "(question_id, reviewee, reviewer, grade_id, text) ";
			$query .= "VALUES ";
			
			// Add each question's values into query
			for ($i = 0; $i < $numEntries; $i++) {
				$text = $db->escape_string($responses[$i]);
				$query .= "('{$questionIds[$i]}','{$reviewee}','{$reviewer}','{$gradeIds[$i]}','{$text}')";
				if ($i < $numEntries - 1) {
					$query .=",";
				}
			}
			
			// Execute query
			if ($db->query($query) === true) {
				return true;
			} else {
				self::$lastError = $db->error;
				return false;
			}
		} else {
			// Do an update query on each question
			
			// UPDATE `tbl_survey_response` SET `response_id` = '26', `question_id` = '999', `reviewee` = '989', `reviewer` = '979', `text` = 'google plus', `grade_id` = '2' WHERE `tbl_survey_response`.`response_id` = 25;

			for ($i = 0; $i < $numEntries; $i++) {
				
				$text = $db->escape_string($responses[$i]);
				
				$query = "UPDATE tbl_survey_response SET ";
				$query .= "question_id={$questionIds[$i]}, ";
				$query .= "reviewee={$reviewee}, ";
				$query .= "reviewer={$reviewer}, ";
				$query .= "text='{$text}', ";
				$query .= "grade_id={$gradeIds[$i]} ";
				$query .= "WHERE response_id={$responseIds[$i]};";
				// Execute query
				if ($db->query($query) === false) {
					self::$lastError = $db->error;
					return false;
				}
			}
			return true;
		}
	}
}