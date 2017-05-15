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
</head>
<body>
	<?php injectHeader(); ?>
	<?php injectNav("Dashboard > Post Review"); ?>
	<main>
	<form action="dashboard.php" method="post">
		<div id="div-entries">
			<?php PostSurvey::injectSurveySelect(); ?>
			<br><br>
			<?php PostSurvey::injectTeamSelect(); ?>
			<br><br>
			<label for="start-date" class="lbl-post-survey">Start Date:</label>
			<input id="review-name" type="datetime-local" class="inp-post-survey" name="survey-start" required />
			<br><br>
			<label for="End-date" class="lbl-post-survey">End Date:</label>
			<input id="review-name" type="datetime-local" class="inp-post-survey" name="survey-end" required />
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
</body>
</html>
