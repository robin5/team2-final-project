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
	
	if ($_SERVER['REQUEST_METHOD']) {
		$userName = $_GET['user-name'];
	}
	
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
	<h1><strong><?php echo($userName); ?></strong></h1><hr>
	
	<main>
		<div>
		<p><label for="select-grade-1">Grade:</label>
		<select id="select-grade-1">
			<option>A</option>
			<option>B</option>
			<option>C</option>
			<option>D</option>
			<option>F</option>
		</select>&nbsp;
		Work cooperatively as part of a team and contribute in both leadership and supportive roles.</p>
		<textarea id="input-q1" cols="80" rows="5"></textarea><br>
		<br>		
		<p><label for="select-grade-1">Grade:</label>
		<select id="select-grade-1">
			<option>A</option>
			<option>B</option>
			<option>C</option>
			<option>D</option>
			<option>F</option>
		</select>&nbsp;
		Build relationships of trust, mutual respect and productive interactions.</p>
		<textarea id="input-q2" cols="80" rows="5"></textarea>
		<br>

		<p><label for="select-grade-1">Grade:</label>
		<select id="select-grade-1">
			<option>A</option>
			<option>B</option>
			<option>C</option>
			<option>D</option>
			<option>F</option>
		</select>&nbsp;
		Be flexible, adapt to unanticipated situations and resolve conflicts</p>
		<textarea id="input-q3" cols="80" rows="5"></textarea>
		<br>

		<p><label for="select-grade-1">Grade:</label>
		<select id="select-grade-1">
			<option>A</option>
			<option>B</option>
			<option>C</option>
			<option>D</option>
			<option>F</option>
		</select>&nbsp;
		Communicate and clarify ideas through well-written business correspondence, proposals, instructions, design summaries and client briefs. (Note: This includes all correspondence through email, Slack, and other communication methodologies adopted by your team.)</p>
		<textarea id="input-q4" cols="80" rows="5"></textarea>
		<br><br>

		</div>
		<form>
			<button style="font-size: 1.25em;">Save & Exit</button>
			<span style="font-size: 1.25em;">&nbsp;|&nbsp;</span>
			<button type="submit" style="font-size: 1.25em;">Submit</button>
		</form>
	</main>
	<?php injectFooter(); ?>
</body>
</html>
