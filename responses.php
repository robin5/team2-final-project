<?php
session_start();
require_once('includes/session_in.inc.php');
try {
	require_once('includes/header.php');
	require_once('includes/nav.php');
	require_once('includes/footer.php');
	require_once('includes/Database/TeamUserFactory.php');
	require_once('includes/Database/SurveyInstanceFactory.php');
	require_once('includes/Database/SurveyFactory.php');
	require_once('Responses.cls.php');
	
} catch(Exception $e) {
	$error = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>CSS Review By Student</title>
	<link href="css/style.css" rel= "stylesheet" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<!-- <script src="jquery-3.2.1.slim.min.js"></script> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->
	<script src="js/analyzetone.js"></script>


</head>
<body>
	<div class="fixedheader"> <!-- ?? Is this what you wanted to add Dave??-->
	
	<?php injectHeader(); ?>
	<?php
		$errMsg = "";
		$instanceId = false;
		$teamId = false;
		$userIds = false;
		$questions = false;
		$surveyName = false;
		$fullName = false;
		
		if ($_SERVER['REQUEST_METHOD'] === "GET") {
			if (!empty($_GET['instance-id']) &&
				!empty($_GET['team-id']) &&
				!empty($_GET['survey-name']) &&
				!empty($_GET['team-name']) &&
				!empty($_GET['full-name']) &&
				!empty($_GET['reviewer'])) {

				$instanceId = $_GET['instance-id'];
				$teamId = $_GET['team-id'];
				$surveyName = $_GET['survey-name'];
				$fullName = $_GET['full-name'];
				$teamName = $_GET['team-name'];
				$reviewer = $_GET['reviewer'];
				
				if (false === ($users = TeamUserFactory::getTeamMembersByTeamId($teamId))) {
					$errMsg =  TeamUserFactory::getLastError();
				} else if (false === ($questions = SurveyInstanceFactory::getSurveyInstanceQuestionIds($instanceId))) {
					$errMsg =  SurveyInstanceFactory::getLastError();
				} else if (false === ($surveyId = SurveyFactory::getSurveyIdByInstance($_SESSION['userId'], $instanceId))) {
					$errMsg =  SurveyFactory::getLastError();
				}
			}
		}
	?>
	<?php injectNav("Dashboard > Survey results: {$surveyName}"); ?>

		<div id="div-resp-student">
			<div id="hdr-reviewer"><?php echo "Responses by: {$fullName} ({$teamName})"; ?></div>
		</div>
	</div><!-- end Header -->
	<main>
		<?php
			if (!empty($errMsg)) {
				injectDivError($errMsg);
			}
		?>

		<?php 
			if ($users) {
				Responses::injectUserTabs($users);
			}
		?>

		<?php 
			if ($questions && $users) {
				Responses::injectQuestionAnswers($surveyId, $reviewer, $questions, $users);
			}
		?>
		
		<?php 
			if ($users) {
				Responses::injectUserRedoSection($surveyId, $instanceId, $reviewer, $users);
			}
		?>

	</main>
	<?php injectFooter(); ?>
	<script>
	
		$(document).ready(function(){
			tabClick(0);
		});


	/*****Hide/Show TOGGLE for TONE RESULTS *************/

	$( ".resp-tone").click(function() {
		$(this).toggle();
		$( ".resp-button").show();
		console.log("1");
	});

	$( ".resp-button").click(function() {
		$(this.button).hide();
		$( ".tone-summary").show();
		$( ".resp-tone").show();
		console.log("2");
	});
	
	$( ".tone-summary").click(function() {
		$(".resp-button").show();
		$( this).hide();
		console.log("3");
	});
	/*****END TOGGLE TONE*****************************/

		function tabClick(index) {
			// loop through tabs class
			$('.sp-tab').each(function(){
				// if indexed tab is same as clicked index
				 if ($(this).attr('id') == 'sp-tab-' + index) {
					 // Change to selected color
					 $(this).css('background-color', '#DBE5EB');
					 $(this).css('padding', '10px');
					 // Show div with answers
					 $('#user-' + $(this).attr('data-index')).show();
				 } else {
					 // Change to not selected color
					 $(this).css('background-color', '#fff');
					 // Hide div with answers
					 $('#user-' + $(this).attr('data-index')).hide();
				 }
			});
		}
	</script>

</body>

</html>
