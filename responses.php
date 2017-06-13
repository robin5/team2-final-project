<?php
session_start();
require_once('includes/session_in.inc.php');
try {
	require_once('includes/header.php');
	require_once('includes/nav.php');
	require_once('includes/footer.php');
	require_once('includes/Database/TeamUserFactory.php');
	require_once('includes/Database/SurveyInstanceFactory.php');
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
					$errMsg =  TeamInstanceFactory::getLastError();
				} else if (false === ($questions = SurveyInstanceFactory::getSurveyInstanceQuestionIds($instanceId))) {
					$errMsg =  TeamInstanceFactory::getLastError();
				}
			}
		}
	?>
	<?php injectNav("Dashboard > Survey results: {$surveyName}"); ?>
	<!-- Amy moved from main -->
		<div id="div-resp-student">
			<div id="reviewer"><?php echo "Responses by: {$fullName} ({$teamName})"; ?></div>
		</div>
	</div>
	<main>
		<?php
			if (!empty($errMsg)) {
				injectDivError($errMsg);
			}
		?>
		<!-- AMY - moved to main
		<div id="div-resp-student">
			<div id="reviewer"><?php //echo "Responses by: {$fullName} ({$teamName})"; ?></div>
		</div>-->

		<?php 
			if ($users) {
				Responses::injectUserTabs($users);
			}
		?>

		<!-- <SUMMARY> -->
		<div id="txt-summary">	
			<hr>

			<div>
				<button id='btn-summary' class="resp-button" onclick="getAreaTxt('btn-summary','txt-summary','tone-summary')">
					Click to See Tone Summary
				</button>
			</div>
			<div id="tone-summary"></div>
		</div> 
		
		<?php 
			if ($questions && $users) {
				Responses::injectQuestionAnswers($reviewer, $questions, $users);
			}
		?>
	</main>
	<?php injectFooter(); ?>
	<script>
	
		$(document).ready(function(){
			tabClick(0);
		});
	
/*		function toggleAnalyze() {
			var value = $('#summary-analyze').css('display');
			
			if (value == 'block') {
				value = $('#summary-analyze').css('display', 'none');
			} else {
				value = $('#summary-analyze').css('display', 'block');
			}
		}*/
		function toggleAnalyze() {
			var value = $('#tone-summary').css('display');

			if (value == 'block') {
				value = $('#tone-summary').css('display', 'none');

				$("#btn-summary").html('Refresh');		
				$("#btn-summary").attr("onclick","getAreaTxt('btn-summary','txt-summary','tone-summary')");

			} else {
				value = $('#tone-summary').css('display', 'block');
				$("#btn-summary").html('Hide');

			}
		}
		
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
