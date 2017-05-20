<?php
require_once('Database.php');

class GradeFactory {
	
	/*********************************************************
	 * Function: getGrades 
	 * Description: queries the database for questions
	 * Return: associative array containing survey questions
	 *********************************************************/
	 
	public static function getGrades() {
		
		$grades = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT * FROM tbl_grade ORDER BY grade_id;";
		
		if (false != ($result = $db->query($query))) {
			$grades = [];
			while ($grade = $result->fetch_assoc()){
				$grades[] = $grade;
			}
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		return $grades;
	}

}