<?php
session_start();
require_once('includes/session_in.inc.php');
try {
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
	<?php injectNav("Dashboard > Edit Review Questions"); ?>
	<main>
		<hr />
		<h3>CTEC-227 Spring 2017</h3>
		<div>
		<table>
			<tr><th>Question</th><th>Action</th></tr>
			<tr>
				<td>Work cooperatively as part of a team and contribute in both leadership and supportive roles.</td>
				<td><a href="#">Edit</a>&nbsp;&nbsp;<a href="#">Delete</a></td>
			</tr>
			<tr>
				<td>Build relationships of trust, mutual respect and productive interactions.</td>
				<td><a href="#">Edit</a>&nbsp;&nbsp;<a href="#">Delete</a></td>
			</tr>
			<tr>
				<td>Be flexible, adapt to unanticipated situations and resolve conflicts</td>
				<td><a href="#">Edit</a>&nbsp;&nbsp;<a href="#">Delete</a></td>
			</tr>
			<tr>
				<td>Communicate and clarify ideas through well-written business correspondence, proposals, instructions, design summaries and client briefs. (Note: This includes all correspondence through email, Slack, and other communication methodologies adopted by your team.)</td>
				<td><a href="#">Edit</a>&nbsp;&nbsp;<a href="#">Delete</a></td>
			</tr>
		</table>
		</div>
	</main>
	<?php injectFooter(); ?>
</body>
</html>
