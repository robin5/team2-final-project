<?php
require_once('Database.php');

class TeamInstanceFactory extends DatabaseFactory {

	/*********************************************************
	 * Function: insert 
	 * Description: Insert a new row into the table
	 *********************************************************/
	 
    public static function xinsert($teamId, $instanceId) {
        
		$teamInstanceId = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
        $query = "INSERT INTO tbl_team_instance (team_id, instance_id) VALUES ('{$teamId}','{$instanceId}')";

		// Insert user into database
        if ($db->query($query) === true) {
			$teamInstanceId = $db->insert_id;
		} else {
			$lastError = $db->error;
		}
		
        return $teamInstanceId;
    }
	
	public static function xgetInstanceTeams($instanceId) {

		$instanceTeams = false;
	
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = 
		
			"SELECT users.first_name, users.last_name, users.user_name, users.user_id, tbl_team_instance.team_id, tbl_team.name AS team_name " .
			"FROM tbl_team_instance " .
			"	JOIN tbl_team_user " .
			"		ON tbl_team_instance.team_id = tbl_team_user.team_id " .
			"	JOIN tbl_team " .
			"		ON tbl_team_user.team_id = tbl_team.team_id " .
			"	JOIN users " .
			"		ON tbl_team_user.user_id = users.user_id " .
			"WHERE " .
			"	tbl_team_instance.instance_id = {$instanceId} " .
			"ORDER BY " .
			"	tbl_team_instance.team_id, users.user_id;";

		if (false != ($result = $db->query($query))) {
			$instanceTeams = [];
			while ($instanceTeam = $result->fetch_assoc()){
				$instanceTeams[] = $instanceTeam;
			}
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		return $instanceTeams;
	}

	public static function xgetInstanceTeamUserIds($instanceId, $teamId)	 {
		
		$userIds= false;
	
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "
				SELECT tbl_team_user.user_id 
				FROM tbl_team_user JOIN tbl_team_instance
					ON tbl_team_user.team_id = tbl_team_instance.team_id
				WHERE
					tbl_team_instance.instance_id = {$instanceId} AND tbl_team_user.team_id = {$teamId}";
		
		if (false != ($result = $db->query($query))) {
			$userIds = [];
			while ($userId = $result->fetch_assoc()){
				$userIds[] = $userId['user_id'];
			}
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		return $userIds;
	}

}
