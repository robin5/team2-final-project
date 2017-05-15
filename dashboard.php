<?php
session_start();
require_once('includes/session_in.inc.php');
try {
	require_once('dashboard.cls.php');
	require_once('includes/header.php');
	require_once('includes/nav.php');
	require_once('includes/functions.inc.php');
	require_once('includes/footer.php');
	
	$userId = $_SESSION['userId'];
	$userName = $_SESSION['userName'];
} catch(Exception $e) {
	$error = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Dashboard</title>
	<link href="css/style.css" rel="stylesheet" />
</head>
<body>
	<div class="wrapper">
	<?php injectHeader(); ?>
	<?php injectNav("Dashboard"); ?>
	<div id='div-error-msg'>
	</div>
	<?php
		$errMsg = "";
		if (($_SERVER['REQUEST_METHOD'] === "POST") && (isset($_POST['action']))) {

			// ---------------------------------------
			// Create a team
			// ---------------------------------------
			
			if ($_POST['action'] === "create-team") {

				// Verify having all parameters
				if (!empty($_POST['team-name']) && 
					!empty($_POST['team-user-ids'])) {
					
					$teamUserIds = explode(",", $_POST['team-user-ids']);
					
					// Create the team
					DashBoard::createTeam(
						$_POST['team-name'], 
						$_SESSION['userId'], 
						$teamUserIds,
						$errMsg);
				}
			}
			
			// ---------------------------------------
			// Update a team
			// ---------------------------------------
			
			else if ($_POST['action'] === "update-team") {

				// Verify having all parameters
				if (!empty($_POST['team-id']) &&
					!empty($_POST['team-name']) && 
					!empty($_POST['team-user-ids'])) {
					
					$teamUserIds = explode(",", $_POST['team-user-ids']);

					// Update the team
					DashBoard::updateTeam(
						$_POST['team-id'], 
						$_POST['team-name'], 
						$_SESSION['userId'], 
						$_POST['team-user-ids'],
						$errMsg);
				}
			}
			
			// ---------------------------------------
			// Create a survey
			// ---------------------------------------

			else if ($_POST['action'] === "create-survey") {
				// Verify having all parameters
				if (!empty($_POST['survey-name']) && 
					!empty($_POST['survey-questions'])) {
					
					// Create the survey
					DashBoard::createSurvey(
						$_POST['survey-name'],
						$_SESSION['userId'],
						$_POST['survey-questions'],
						$errMsg);
				}
			}
			
			// ---------------------------------------
			// Edit a survey
			// ---------------------------------------
			
			else if ($_POST['action'] === "edit-survey") {
				// Verify having all parameters
				if (!empty($_POST['survey-id']) &&
					!empty($_POST['survey-name']) && 
					!empty($_POST['survey-questions'])) {
					
					// Update the survey
					DashBoard::updateSurvey(
						$_POST['survey-id'], 
						$_POST['survey-name'], 
						$_SESSION['userId'], 
						$_POST['survey-questions'],
						$errMsg);
				}
			}
			
			// ---------------------------------------
			// Start a survey
			// ---------------------------------------
			
			else if ($_POST['action'] === "start-survey") {

				// Verify having all parameters
				if (!empty($_POST['survey-id']) &&
					!empty($_POST['survey-start']) && 
					!empty($_POST['survey-end']) &&
					!empty($_POST['survey-teams'])) {
					
					// start the survey
					DashBoard::startSurvey(
						$_POST['survey-id'], 
						$_SESSION['userId'], 
						$_POST['survey-start'], 
						$_POST['survey-end'], 
						$_POST['survey-teams'],
						$errMsg);
				}
			}
			
			
			
			
			else if ($_POST['action'] === "cancelled") {
			}
		} else if (($_SERVER['REQUEST_METHOD'] === "GET") && (isset($_GET['action']))) {
			
			// ---------------------------------------
			// delete a team
			// ---------------------------------------
			
			if ($_GET['action'] === "delete-team") {

				if (!empty($_GET['team-id'])) {
					// Delete the team
					DashBoard::deleteTeam(
						$_GET['team-id'], 
						$_SESSION['userId'],
						$errMsg);
				}
			}
			
			// ---------------------------------------
			// delete a survey
			// ---------------------------------------
			
			if ($_GET['action'] === "delete-survey") {

				if (!empty($_GET['survey-id'])) {
					
					// Delete the survey
					DashBoard::deleteSurvey(
						$_GET['survey-id'], 
						$_SESSION['userId'],
						$errMsg);
				}
			}
		}
	?>
	<?php
		if ($_SESSION['role_instructor'] === true) {
			echo "<ul>";
			echo "<li><a href=\"edit_survey.php?action=create\">Create a survey</a></li>";
			echo "<li><a href=\"edit_team.php\">Create a team</a></li>";
			echo "<li><a href=\"post_survey.php\">Launch a Survey</a></li>";
			echo "</ul><hr>";
		}
	?>
	<main>
		<?php
			if (!empty($errMsg)) {
				injectDivError($errMsg);
			}
		?>
		<?php
			if (true === $_SESSION['role_instructor']) {
				DashBoard::injectSurveysTable();
				echo "<hr>";
				DashBoard::injectTeamsTable($userId);
				echo "<hr>";
			} else if (true === $_SESSION['role_student']){
				DashBoard::injectPendingSurveysTable();
				//echo "<hr>";
				DashBoard::injectSurveysOnMeTable();
			}
		?>
	</main>
	<?php injectFooter(false); ?>
	<div class="push"></div>
	</div>
</body>
</html>
