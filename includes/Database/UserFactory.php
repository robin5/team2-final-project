<?php
require_once('Database.php');
require_once('User.php');
require_once('UserRoleFactory.php');

class UserFactory extends DatabaseFactory {

	/*****************************************************
	 * Function: insert 
	 * Description: Insert a new user into the users table
	 *              and adds role into users_roles table
	 * [REVISIT] - THIS FUNCTIONALITY SHOULD BE IN A STORED 
	 *     PROCEDURE INCASED IN A TRANSACTION
	 *****************************************************/
	 
    public static function insert($user_name, $password, $first_name, $last_name, $email, $isInstructor) {
        
		$userId = false;
		
		//$db = new mysqli('localhost','root','','ctec227_final_project');
		$db = DatabaseConnectionFactory::getConnection();
		
		$user_name = $db->escape_string($user_name);
		$password = $db->escape_string($password);
		$first_name = $db->escape_string($first_name);
		$last_name = $db->escape_string($last_name);
		
        $query = "INSERT INTO Users (user_name, password, first_name, last_name, email) VALUES ";
		$query .= "('{$user_name}','{$password}','{$first_name}','{$last_name}','{$email}')";

		// Insert user into database
        if ($db->query($query) === true) {
			$userId = $db->insert_id;
		} else {
			$lastError = $db->error;
		}
		
        return $userId;
    }

	/*****************************************************
	 * Function: getUserId 
	 * Description: Insert a new user into the users table
	 *              and adds role into users_roles table
	 *****************************************************/
	 
	public static function getUserId($userName, $passwordHash = null) {
		
		$result;
		$row;
		$userId = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		if ($passwordHash == null) {
			$query = "SELECT user_id FROM users ";
			$query .= "WHERE user_name = \"{$userName}\" LIMIT 1";
		} else {
			$query = "SELECT user_id, password FROM users ";
			$query .= "WHERE user_name = \"{$userName}\" LIMIT 1";
		}

		if (false != ($result = $db->query($query))) {
			if ($result->num_rows) {
				if (null != ($row = $result->fetch_row())) {
					$userId = intval($row[0]);
					if ($passwordHash != null) {
						if (!password_verify($passwordHash, $row[1])) {
							$lastError = "Invalid password";
							$userId = false;
						}
					}
				} else {
					$lastError = "User not found.";
				}
			} else {
				$lastError = "User not found.";
			}
			$result->close();
		} else {
			$lastError = $db->error;
		}
		return $userId;
	}

	/*******************************************************
	 * Function: getAllUsers 
	 * Description: Returns all users in the tbl_users table
	 *******************************************************/
	 
	public static function getAllUsers() {
		
		$result;
		$row;
		$userId = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT * FROM users ";

		if (false != ($result = $db->query($query))) {
			$users = [];
			while ($user = $result->fetch_assoc()){
				$users[] = $user;
			}
			$result->close();
		} else {
			$lastError = $db->error;
			echo $lastError;
		}
		return $users;
	}
}
