<?php
require_once('Database.php');
require_once('TeamUserFactory.php');

class TeamFactory extends DatabaseFactory
{
	/*****************************************************
	 * Function: insert 
	 * Description: Insert a new team into the Teams table
	 *****************************************************/
	 
    public static function insert($teamName, $ownerId, $instanceId) {
        
		$teamId = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$teamName = $db->escape_string($teamName);
		$ownerId = $db->escape_string($ownerId);
		
		if (is_null($instanceId)) {
			$query = "INSERT INTO tbl_team (name, owner_id) VALUES ('{$teamName}','{$ownerId}')";
		} else {
			$query = "INSERT INTO tbl_team (name, owner_id, instance_id) VALUES ('{$teamName}','{$ownerId}','{$instanceId}')";
		}

		// Insert user into database
        if ($db->query($query) === true) {
			$teamId = $db->insert_id;
		} else {
			self::$lastError = $db->error;
		}
		
        return $teamId;
    }

	/*******************************************************
	 * Function: getTeams 
	 * Description: Returns all teams in the tbl_team table
	 *     given them team_id
	 *******************************************************/
	 
	public static function getRootTeams($ownerId) {
		
		$result;
		$row;
		$userId = false;
		$teams = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT name, team_id 
				  FROM tbl_team 
				  WHERE owner_id = {$ownerId} AND ISNULL(instance_id)
				  ORDER BY name";

		if (false != ($result = $db->query($query))) {
			$teams = [];
			while ($team = $result->fetch_assoc()){
				$teams[] = $team;
			}
			$result->close();
		} else {
			self::$lastError = $db->error;
		}
		return $teams;
	}

	/*******************************************************
	 * Function: getTeams
	 * Description: Returns all teams in the tbl_team table
	 *     given them team_id
	 *******************************************************/
	 
	public static function getTeam($teamId, $ownerId) {
		
		$result;
		$team = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT team_id, name, owner_id, instance_id FROM tbl_team where team_id = {$teamId} AND owner_id = {$ownerId} AND ISNULL(instance_id)";

		if (false !== ($result = $db->query($query))) {
			if (false !== ($team = $result->fetch_assoc())) {
				return $team;
			} else {
				return false;
			}
		} else {
			self::$lastError = $db->error;
			return false;
		}
	}

	/*******************************************************
	 * Function: insertTeam 
	 * Description: Adds a new team to tbl_team, and then
	 *     adds users to tbl_team_user.  REVISIT: this
	 *     should be a transaction in a stored procedure.
	 *******************************************************/
	 
	function insertTeam($teamName, $ownerId, $userIds, $instanceId = null) {
	
		$status = false;
		
		if (false !== ($teamId = self::insert($teamName, $ownerId, $instanceId))) {
			foreach($userIds as $userId) {
				if (false === TeamUserFactory::insert($teamId, $userId)) {
					return $status; // [REVISIT] at this point we really should roll back the entire transaction
				}
			}
			$status = true;
		}
		return $status;
	}
	
	/*******************************************************
	 * Function: deleteTeam 
	 * Description: 
	 *******************************************************/
	 
	function deleteTeam($teamId, $ownerId) {
	
		$db = DatabaseConnectionFactory::getConnection();
		
		$teamId = $db->escape_string($teamId);
		$ownerId = $db->escape_string($ownerId);
		
        $queryTeamUser = "Delete FROM tbl_team_user WHERE team_id={$teamId};";
        $queryTeam = "Delete FROM tbl_team WHERE team_id={$teamId} AND owner_id={$ownerId};";

		// Insert user into database
        if (false === $db->query($queryTeamUser)) {
			self::$lastError = $db->error;
			return false;
		} else if (false === $db->query($queryTeam)) {
			self::$lastError = $db->error;
			return false;
		}
		
        return true;
	}

	public static function generateInstanceTeams($instanceId, $ownerId, $teamIds) {

		// Attach the teamIds to the new survey's instance
		for ($i = 0; $i < count($teamIds); $i++) {
			if (false !== ($team = TeamFactory::getTeam($teamIds[$i], $ownerId))) {
				if (false !== ($userIds = TeamUserFactory::getUserIds($teamIds[$i]))) {
					if (false === (TeamFactory::insertTeam($team['name'], $team['owner_id'], $userIds, $instanceId))) {
						return false;
					}
				}
			}
		}
		return true;
	}
	
	public static function getTeamUsersByInstance($instanceId) {

		$instanceTeams = false;
	
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = 
		
			"SELECT 
				users.first_name, 
				users.last_name, 
				users.user_name, 
				users.user_id, 
				tbl_team.team_id, 
				tbl_team.name AS team_name 
			FROM tbl_team_user
				JOIN tbl_team
					ON tbl_team_user.team_id = tbl_team.team_id
				JOIN users
					ON tbl_team_user.user_id = users.user_id
			WHERE
				tbl_team.instance_id = {$instanceId}
			ORDER BY
				tbl_team.team_id, users.user_id;";

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

	/*******************************************************
	 * Function: updateTeam 
	 * Description: updates team parameters and members
	 *******************************************************/
	 
	public static function updateTeam($teamId, $teamName, $ownerId, $userIds) {
		if (false !== TeamUserFactory::deleteTeam($teamId)) {
			if (false != TeamFactory::updateTeamName($teamId, $teamName, $ownerId)) {
				if (false === TeamUserFactory::insertUsers($teamId, $userIds))
					return true;
			}
		}
		return false;
	}
	
	/*******************************************************
	 * Function: updateTeamName 
	 * Description: updates the team name
	 *******************************************************/
	 
	public static function updateTeamName($teamId, $teamName, $ownerId) {

		$db = DatabaseConnectionFactory::getConnection();
		
		$teamName = $db->escape_string($teamName);
		$ownerId = $db->escape_string($ownerId);
		
		$query = "UPDATE tbl_team SET name = '{$teamName}' 
					WHERE team_id={$teamId} AND owner_id={$ownerId}";

		// Execute query
        if (false === $db->query($query)) {
			self::$lastError = $db->error;
			return false;
		}
        return true;
	}
}