<?php // functions.inc.php

require_once('Database/UserFactory.php');
require_once('Database/SurveyFactory.php');
require_once('Database/TeamUserFactory.php');
require_once('Database/QuestionFactory.php');
require_once('Database/RoleFactory.php');
require_once('util.inc.php');

/******************************************************
 * Function: registerUser
 * Description: registers user in the database
 * Return: New users userId or false if unsuccessful
 ******************************************************/

function registerUser($userName, $password, $firstName, $lastName, $email, $isInstructor) {
	// Get roleId from the database
	
	$password_hash = password_hash($password, PASSWORD_DEFAULT);
	
	if ($user_id = UserFactory::insert($userName, $password_hash, $firstName, $lastName, $email, $isInstructor)) {
		// Set the variables for a valid session
		$_SESSION['valid'] = true;
		$_SESSION['timeout'] = time();
		$_SESSION['userId'] = $user_id;
		$_SESSION['userName'] = $_POST['username'];
		if ($isInstructor) {
			UserRoleFactory::insert($user_id, UserRoleFactory::ROLE_INSTRUCTOR);
			$_SESSION['role_student'] = false;
			$_SESSION['role_instructor'] = true;
		} else {
			UserRoleFactory::insert($user_id, UserRoleFactory::ROLE_STUDENT);
			$_SESSION['role_student'] = true;
			$_SESSION['role_instructor'] = false;
		}
		return true;
	}
	return false;
}

/******************************************************
 * Function: login
 * Description: logs user into the application and sets
 *     session variables
 * Return:
 *     user_id - if user was logged in
 *     false - otherwise
 * [REVISIT] - THIS FUNCTIONALITY SHOULD BE IN A STORED 
 *     PROCEDURE INCASED IN A TRANSACTION
 ******************************************************/

function login($userName, $passwordHash) {

	$userId = false;
	
	if (false != ($userId = UserFactory::getUserId($userName, $passwordHash))) {
		$_SESSION['valid'] = true;
		$_SESSION['timeout'] = time();
		$_SESSION['userName'] = $userName;
		$_SESSION['userId'] = $userId;
		
		if (null != ($userRoles = UserRoleFactory::getUserRoles($userId))) {
			$_SESSION['role_developer'] = (false !== array_search(UserRoleFactory::ROLE_DEVELOPER, $userRoles));
			$_SESSION['role_instructor'] = (false !== array_search(UserRoleFactory::ROLE_INSTRUCTOR, $userRoles));
			$_SESSION['role_student'] = (false !== array_search(UserRoleFactory::ROLE_STUDENT, $userRoles));
		}
	}

	return $userId;
}

/******************************************************
 * Function: injectDivError
 * Description: creates a div which is used to display
 *     an error message
 * Return: None
 ******************************************************/

function injectDivError($msg) {
	if (!empty($msg)) {
		echo "<div id=\"div-error-msg\">";
		echo "<h4>{$msg}</h4>";
		echo "</div>";
	}
}
	
function createSurvey($surveyName, $ownerId) {
	return SurveyFactory::insert($surveyName, $ownerId);
}

function addTeamUser($teamId, $userId, &$errMsg) {
	
	if (false == (TeamUserFactory::insert($teamId, $userId))) {
		$errMsg = "Could not add user";
	} else {
		$errMsg = "";
		return true;
	}
	return false;
}

function addSurveyQuestion($surveyId, $questionText, $questionIndex, &$errorMsg) {
	
	if (false === QuestionFactory::insert($surveyId, $questionText, $questionIndex)) {
		$errorMsg = QuestionFactory::getLastError();
		return false;
	}
	return true;
}


function getTeamMembers($teamId) {
	return TeamUserFactory::getTeamMembersByTeamId($teamId);
}

function getAllUsers() {
	return UserFactory::getAllUsers();
}

function getSurveyQuestions($surveyId) {
	return SurveyFactory::getSurveyQuestion($surveyId);
}

function logout($data) {
	file_put_contents("css.log", $data . "\r\n", FILE_APPEND);
}

?>
