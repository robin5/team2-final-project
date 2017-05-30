<?php

require_once('Database.php');
require_once('UserRole.php');

class UserRoleFactory
{
	static $class = 'UserRole';

	const ROLE_DEVELOPER = 1;
	const ROLE_INSTRUCTOR = 2;
	const ROLE_STUDENT = 3;

    public static function insert($user_id, $role_id) {
        
		$result = false;
		
		$db = DatabaseConnectionFactory::getConnection();

		$user_id = $db->escape_string($user_id);
		$role_id = $db->escape_string($role_id);
		
        $query = "INSERT INTO UserRoles (user_id, role_id) VALUES ";
		$query .= "('{$user_id}','{$role_id}')";

		$result = $db->query($query);

		return $result;
    }

	/*****************************************************
	 * Function: getUserRoles 
	 * Description: Insert a new user into the users table
	 *              and adds role into users_roles table
	 *****************************************************/
	 
	public static function getUserRoles($userId) {
		
		$result;
		$row;
		$roles = [];
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT role_id FROM UserRoles WHERE user_id = \"{$userId}\"";

		if (false != ($result = $db->query($query))) {
			if ($result->num_rows) {
				while (null != ($row = $result->fetch_row())) {
					$roles[] = intval($row[0]);
				}
			}
			$result->close();
		} else {
			echo "query failed!";
		}
		return $roles;
	}
}
