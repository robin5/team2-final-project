<?php
require_once('Database.php');

class TeamInstanceFactory extends DatabaseFactory {

	/*********************************************************
	 * Function: insert 
	 * Description: Insert a new row into the table
	 *********************************************************/
	 
    public static function insert($teamId, $instanceId) {
        
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
}

