<?php
require_once('Database.php');
require_once('TeamUserFactory.php');

class TeamUserFactory
{
	static $class = 'TeamUser';
	
	static $lastError = null;
	
	/**************************************************************
	 * Function: insert 
	 * Description: Insert a new entry into the tbl_team_user table
	 **************************************************************/
	 
    public static function insert($team_id, $user_id) {
        
		$db = DatabaseConnectionFactory::getConnection();
		
		$team_id = $db->escape_string($team_id);
		$user_id = $db->escape_string($user_id);
		
        $query = "INSERT INTO tbl_team_user (team_id, user_id) VALUES ('{$team_id}','{$user_id}')";

		if (false == ($result = $db->query($query))) {
			$lastError = $db->error;
		}
		
        return $result;
    }
	
	public static function getTeamMembersByTeamId($teamId) {
		
		$userIds = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$teamId = $db->escape_string($teamId);

		// SELECT users.user_name, first_name, last_name FROM tbl_team_user JOIN users ON tbl_team_user.user_id = users.user_id WHERE team_id = 33
		
        $query  = "SELECT users.user_id, users.user_name, first_name, last_name ";
		$query .= "FROM tbl_team_user JOIN users ";
		$query .= "ON tbl_team_user.user_id = users.user_id ";
		$query .= "WHERE team_id=\"{$teamId}\"";

		if (false != ($result = $db->query( $query ))) {
			$userIds = [];
			while ($userId = $result->fetch_assoc()) {
				$userIds[] = $userId;
			}
			$result->close();
		} else {
			$lastError = $db->error;
		}

		return $userIds;
	}
	
	public static function getUserIds($teamId) {
		
		$userIds = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$teamId = $db->escape_string($teamId);

        $query = "SELECT user_id 
				  FROM tbl_team_user 
				  WHERE team_id='{$teamId}'";
				  
		if (false !== ($result = $db->query( $query ))) {
			$userIds = [];
			while ($row = $result->fetch_assoc()) {
				$userIds[] = $row['user_id'];
			}
			$result->close();
		} else {
			$lastError = $db->error;
		}

		return $userIds;
	}

    public static function deleteTeam($teamId) {
        
		$db = DatabaseConnectionFactory::getConnection();
		
        $query = "DELETE FROM tbl_team_user WHERE team_id = {$teamId}";

		if (false === $db->query($query)) {
			$lastError = $db->error;
			return false;
		}
        return true;
    }
	
	/**************************************************************
	 * Function: insertUsers 
	 * Description: Insert array of users into the tbl_team_user
	 **************************************************************/
	 
    public static function insertUsers($teamId, $userIds) {
        
		foreach ($userIds as $userId) {
			if (false === TeamUserFactory::insert($teamId, $userId))
				return false;
		}
		return true;
    }
}