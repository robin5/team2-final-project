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
	
} catch(Exception $e) {
	$error = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Edit Review</title>
	<link href="css/style.css" rel="stylesheet" />
</head>
<body>
	<?php injectHeader(); ?>
	<?php injectNav("Dashboard > Survey"); ?>
	<h3><strong>CTEC-227 Spring 2017 (Team-1)</strong></h3><hr>
	<main>
		<div>
		<p>
		1. Work cooperatively as part of a team and contribute in both leadership and supportive roles.</p>
		Grade: <span style="background: white;">&nbsp;A&nbsp;</span><br>
		<textarea id="input-q1" cols="80" rows="5"></textarea><br>
		Grade: <span style="background: white;">&nbsp;B&nbsp;</span><br>
		<textarea id="input-q2" cols="80" rows="5"></textarea><br>
		Grade: <span style="background: white;">&nbsp;C&nbsp;</span><br>
		<textarea id="input-q3" cols="80" rows="5"></textarea><br>
		<br>		
		<p>
		2. Build relationships of trust, mutual respect and productive interactions.</p>
		Grade: <span style="background: white;">&nbsp;A&nbsp;</span><br>
		<textarea id="input-q4" cols="80" rows="5"></textarea><br>
		Grade: <span style="background: white;">&nbsp;B&nbsp;</span><br>
		<textarea id="input-q5" cols="80" rows="5"></textarea><br>
		Grade: <span style="background: white;">&nbsp;C&nbsp;</span><br>
		<textarea id="input-q6" cols="80" rows="5"></textarea><br>
		<br>

		<p>3. Be flexible, adapt to unanticipated situations and resolve conflicts</p>
		Grade: <span style="background: white;">&nbsp;A&nbsp;</span><br>
		<textarea id="input-q7" cols="80" rows="5"></textarea><br>
		Grade: <span style="background: white;">&nbsp;B&nbsp;</span><br>
		<textarea id="input-q8" cols="80" rows="5"></textarea><br>
		Grade: <span style="background: white;">&nbsp;C&nbsp;</span><br>
		<textarea id="input-q9" cols="80" rows="5"></textarea><br>
		<br>

		<p>4. Communicate and clarify ideas through well-written business correspondence, proposals, instructions, design summaries and client briefs. (Note: This includes all correspondence through email, Slack, and other communication methodologies adopted by your team.)</p>
		Grade: <span style="background: white;">&nbsp;A&nbsp;</span><br>
		<textarea id="input-q10" cols="80" rows="5"></textarea><br>
		Grade: <span style="background: white;">&nbsp;B&nbsp;</span><br>
		<textarea id="input-q11" cols="80" rows="5"></textarea><br>
		Grade: <span style="background: white;">&nbsp;C&nbsp;</span><br>
		<textarea id="input-q12" cols="80" rows="5"></textarea><br>


		</div>
	</main>
	<?php injectFooter(); ?>
</body>
</html>
