<?php
session_start();
require_once('includes/session_in.inc.php');
try {
	require_once('includes/header.php');
	require_once('includes/nav.php');
	require_once('includes/footer.php');
	require_once('post_survey.cls.php');
	
} catch(Exception $e) {
	$error = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Post Review</title>
	<link href="css/style.css" rel="stylesheet" />
	<link href="css/jquery.datetimepicker.css" rel="stylesheet" type="text/css"/ >
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="js/jquery.datetimepicker.full.min.js"></script>
</head>
<body>
	<div class="fixedheader">
	<?php injectHeader(); ?>
	<?php injectNav("Dashboard > Post Survey"); ?>
	</div>
	<main>
	<form action="dashboard.php" method="post">
		<div id="div-post-survey">
			<br><br>
			<label for="instance-name" class="lbl-post-survey">Instance Name:</label>
			<input id="instance-name" type="text" class="inp-post-survey" name="instance-name" required />
			<br><br>
			<?php PostSurvey::injectSurveySelect(); ?>
			<br><br>
			<?php PostSurvey::injectTeamSelect(); ?>
			<br><br>
			<label for="start-date-time" class="lbl-post-survey">Start Date:</label>
			<input id="start-date-time" class="inp-post-survey" name="survey-start" required />
			<br><br>
			<label for="end-date-time" class="lbl-post-survey">End Date:</label>
			<input id="end-date-time" class="inp-post-survey" name="survey-end" required />
			<br><br><hr>
			<button id="post-survey" type="submit" name="action" value="start-survey">Start Survey</button>
			&nbsp;|&nbsp;
			<button type="submit" id="btn-cancel" name="action" value="cancelled">
				<span>Cancel</span>
			</button>
		</div>
		<div id="div-reg-button">
		</div>
	</form>
	</main>
	<?php injectFooter(); ?>
	<script>
		$(function() {
			$('#start-date-time').datetimepicker();
			$('#end-date-time').datetimepicker();
		})
	</script>
</body>
</html>
