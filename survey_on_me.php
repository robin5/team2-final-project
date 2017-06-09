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
<!-- deleted the section adding name and team to the header as was not needed in summary -->
	<main>
		<?php
			if (!empty($errMsg)) {
				injectDivError($errMsg);
			}
		?>
		<h3><strong>About <?php  echo "{$fullName}"; ?></strong></h3><hr>
		<!--AMY -->
				<!-- <SUMMARY> -->
		<div id="txt-summary">	
			<!-- <hr> -->

			<div>
				<button id='btn-summary' class="resp-button" onclick="getAreaTxt('btn-summary','txt-summary','tone-summary')">
					Click to See Tone Summary
				</button>
			</div>
			<div id="tone-summary"></div>
		</div> 
		<!-- -->
		<?php 
			if ($questions && $users) {
				SurveyOnMe::injectQuestionAnswers($reviewee, $questions, $users);
			}
		?>
	</main>
	<?php injectFooter(); ?>
		<script>
	
		$(document).ready(function(){
			$('input[type="text"], textarea').attr('readonly','readonly');//AMY turned tab off
			//tabClick(0);
		});
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
		</script>function
</body>
</html>
