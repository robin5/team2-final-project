<?php
session_start();
require_once('includes/session_in.inc.php');
try {
	require_once('includes/header.php');
	require_once('includes/nav.php');
	require_once('includes/footer.php');
	require_once('take_survey.cls.php');
	
} catch(Exception $e) {
	$error = $e->getMessage();
}
?>
<?php

	$errMsg = "";
	$surveyId = 0;
	$surveyName = "";
	$teamName = "";
	$reviewerId = 0;
	$revieweeId = 0;
	$questions = false;
	$responses = false;

	if ($_SERVER['REQUEST_METHOD'] == "GET") {
		// Create a survey
		if (!empty($_GET['survey-id']) &&
			!empty($_GET['reviewer-id']) &&
			!empty($_GET['reviewee-id'])) {
			
			$surveyId = $_GET['survey-id'];
			$surveyName = $_GET['survey-name'];
			$teamName = $_GET['team-name'];
			$reviewerId = $_GET['reviewer-id'];
			$revieweeId = $_GET['reviewee-id'];
			$revieweeName = $_GET['reviewee-name'];

			if (false === ($questions = QuestionResponseFactory::getQuestions($surveyId))) {
				$errMsg = QuestionResponseFactory::getLastError();
			} else if (false === ($responses = QuestionResponseFactory::getResponses($revieweeId, $_SESSION['userId']))) {
				$errMsg = QuestionResponseFactory::getLastError();
			} 
		}
	}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Edit Review</title>
	<link href="css/style.css" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>
<body>
	<?php injectHeader(); ?>
	<?php injectNav("Dashboard > Survey: {$surveyName}"); ?>
	<?php injectDivError($errMsg); ?>
	<h1><strong><?php echo "{$teamName}: {$revieweeName}"; ?></strong></h1><hr>
	<main>
		<?php TakeSurvey::injectSurveyQuestions($surveyId, $revieweeId, $questions, $responses); ?>
	</main>
	<?php injectFooter(); ?>
	<script>
		// fill in required fields with some data
		function fakeFillFields() {
			$('textarea').val(" ");
			$('#survey-name').val(" ");
			return true;
		}
		
		function isValidForm(evt) {
			var isValid = true;
			$('textarea').each(function(){
				if ($(this).val() == "") {
					isValid = false;
				}
			});
			
			$('select').each(function(){
				console.log($(this).val());
				if ($(this).val() == 0) {
					alert("You must select a grade for each question.");
					isValid = false;
				}
			});
			return isValid;
		}
	</script>
</body>
</html>
