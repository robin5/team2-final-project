<?php
session_start();
require_once('includes/session_in.inc.php');
try {
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="js/analyzetone.js"></script>
</head>
<body>
	<div class="fixedheader">
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
					$errMsg =  TeamUserFactory::getLastError();
				} else if (false === ($questions = SurveyInstanceFactory::getSurveyInstanceQuestionIds($instanceId))) {
					$errMsg =  SurveyInstanceFactory::getLastError();
				}
			}
		}
	?>
	<?php injectNav("Dashboard > Survey results: {$surveyName}"); ?>
	</div>

<!-- deleted the section adding name and team to the header as was not needed in summary -->
	<main>
		<div>
		<?php
			if (!empty($errMsg)) {
				injectDivError($errMsg);
			}
		?>
		<div>
		<h3><strong>About <?php  echo "{$fullName}"; ?></strong></h3><hr>
		

		<?php 
			if ($questions && $users) {
				SurveyOnMe::injectQuestionAnswers($reviewee, $questions, $users);
			}
		?>
	</div>
	</main>
	<?php injectFooter(); ?>
		<script>
	
		$(document).ready(function(){
			$('input[type="text"], textarea').attr('readonly','readonly');//AMY turned tab off
			//tabClick(0); //AMY commented out tab -Not used?
		});

				/*****REDO TOGGLE TONE *****************************/

	$( ".resp-tone").click(function() {
		$(this).hide();
		$( ".resp-button").show();
		//$( ".resp-tone").hide();
		console.log("1");
	});

	
	$( ".resp-button").click(function() {
		// $(this).hide();
		$( ".tone-summary").show();
		$( ".resp-tone").show();
		console.log("2");
	});
	
	$( ".tone-summary").click(function() {
		$(".resp-button").show();
		$( this).hide();
		console.log("3");
	});
	/*****END REDO TOGGLE for SUMMARY w USER ID*****************************/
		</script>
</body>
</html>
