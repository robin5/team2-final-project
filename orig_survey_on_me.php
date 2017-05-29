<?php
session_start();
require_once('includes/session_in.inc.php');
try {
	if (empty($_SESSION['valid'])) {
		header('Location:index.php');
		exit;
	}
	require_once('includes/header.php');
	require_once('includes/nav.php');
	require_once('includes/footer.php');
	require_once('survey_on_me.cls.php');
	require_once('includes/Database/TeamUserFactory.php');
	require_once('includes/Database/SurveyInstanceFactory.php');
	
} catch(Exception $e) {
	$error = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>CSS Review On Student</title>
	<link href="css/style.css" rel="stylesheet" />
</head>
<body>
	<?php injectHeader(); ?>
	<?php
		$errMsg = "";
		$instanceId = false;
		$teamId = false;
		$reviewee = false;
		$userIds = false;
		$questions = false;
		$surveyName = false;
		$fullName = false;
		
		if ($_SERVER['REQUEST_METHOD'] === "GET") {
			if (!empty($_GET['instance-id']) &&
				!empty($_GET['team-id']) &&
				!empty($_GET['reviewee']) &&
				!empty($_GET['survey-name']) &&
				!empty($_GET['full-name'])) {

				$instanceId = $_GET['instance-id'];
				$teamId = $_GET['team-id'];
				$reviewee = $_GET['reviewee'];
				$surveyName = $_GET['survey-name'];
				$fullName = $_GET['full-name'];
				
				if (false === ($users = TeamUserFactory::getTeamMembersByTeamId($teamId))) {
					$errMsg =  TeamInstanceFactory::getLastError();
				} else if (false === ($questions = SurveyInstanceFactory::getSurveyInstanceQuestionIds($instanceId))) {
					$errMsg =  TeamInstanceFactory::getLastError();
				}
			}
		}
	?>
	<?php injectNav("Dashboard > Survey results: {$surveyName}"); ?>
	<main>
		<?php
			if (!empty($errMsg)) {
				injectDivError($errMsg);
			}
		?>
		<h3><strong>About <?php  echo "{$fullName}"; ?></strong></h3><hr>
		<?php 
			if ($questions && $users) {
				SurveyOnMe::injectQuestionAnswers($reviewee, $questions, $users);
			}
		?>
	</main>
	<?php injectFooter(); ?>
</body>
</html>
