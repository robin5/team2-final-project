<?php
require_once('Database.php');
require_once('TeamUserFactory.php');

class TeamFactory extends DatabaseFactory
{
	/*****************************************************
	 * Function: insert 
	 * Description: Insert a new team into the Teams table
	 *****************************************************/
	 
    public static function insert($teamName, $ownerId) {
        
		$teamId = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$teamName = $db->escape_string($teamName);
		$ownerId = $db->escape_string($ownerId);
		
        $query = "INSERT INTO tbl_team (name, owner_id) VALUES ('{$teamName}','{$ownerId}')";

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
	 
	public static function getTeams($teamId) {
		
		$result;
		$row;
		$userId = false;
		$teams = false;
		
		$db = DatabaseConnectionFactory::getConnection();
		
		$query = "SELECT name, team_id FROM tbl_team where owner_id = {$teamId} ORDER BY name";

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
	 * Function: insertTeam 
	 * Description: Adds a new team to tbl_team, and then
	 *     adds users to tbl_team_user.  REVISIT: this
	 *     should be a transaction in a stored procedure.
	 *******************************************************/
	 
	function insertTeam($teamName, $ownerId, $userIds) {
	
		$status = false;
		
		if (false !== ($teamId = self::insert($teamName, $ownerId))) {
			foreach($userIds as $userId) {
				if (false === TeamUserFactory::insert($teamId, $userId)) {
					// [REVISIT] at this point we really should roll back the entire transaction
					return $status;
				}
			}
			$status = true;
		}
		return $status;
	}
	
	/*******************************************************
	 * Function: updateTeam 
	 * Description: 
	 *******************************************************/
	 
	function updateTeam($teamName, $userIds, $ownerId) {
	
		$status = false;
		self::$lastError = "Not Yet Implemented";
		
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
			echo $db->error;
			self::$lastError = $db->error;
			return false;
		} else if (false === $db->query($queryTeam)) {
			echo $db->error;
			self::$lastError = $db->error;
			return false;
		}
		
        return true;
	}
}