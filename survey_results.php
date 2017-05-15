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
	<title>Member Responses</title>
	<link href="css/style.css" rel="stylesheet" />
</head>
<body>
	<?php injectHeader(); ?>
	<?php
		if (isset($_GET['team'])) {
			switch($_GET['team']){
				case 'team1':
					$team = 'Team-1 Responses';
					break;
				case 'team2':
					$team = 'Team-2 Responses';
					break;
				case 'team3':
					$team = 'Team-3 Responses';
					break;
				case 'team4':
					$team = 'Team-4 Responses';
					break;
			}
		}
	?>
	<?php injectNav("Dashboard > Survey Results"); ?>
	<main>
		<div id="div-user">
		<span style="font-size: 1.5em;">Survey: CTEC-227 Spring 2017</span><br><br>
		<form action="dashboard.php" method="post">
			<div id="div-entries">
			
			<table>
				<th>Team-1</th><th>Surveys Done<br>By Student</th><th>Surveys Done<br>On Student</th>
				<tr><td style="width: 200px;">Richard Lint</td><td><a href="responses.php">review</a></td><td><a href="survey_on_me.php">review</a></td>
				<tr><td>Patrick McCulley</td><td><a href="responses.php">review</a></td><td><a href="survey_on_me.php">review</a></td>
				<tr><td>Andrey Demchenko</td><td><a href="responses.php">review</a></td><td><a href="survey_on_me.php">review</a></td>
			</table>
			<br>
			<table>
				<th>Team-2</th><th>Surveys Done<br>By Student</th><th>Surveys Done<br>On Student</th>
				<tr><td style="width: 200px;">Robin Murray</td><td><a href="responses.php">review</a></td><td><a href="survey_on_me.php">review</a></td>
				<tr><td>Dave King</td><td><a href="responses.php">review</a></td><td><a href="survey_on_me.php">review</a></td>
				<tr><td>Amy Jaeger</td><td><a href="responses.php">review</a></td><td><a href="survey_on_me.php">review</a></td>
			</table>
			<br>
			<table>
				<th>Team-2</th><th>Surveys Done<br>By Student</th><th>Surveys Done<br>On Student</th>
				<tr><td style="width: 200px;">David Richards</td><td><a href="responses.php">review</a></td><td><a href="survey_on_me.php">review</a></td>
				<tr><td>Wayne Woods</td><td><a href="responses.php">review</a></td><td><a href="survey_on_me.php">review</a></td>
				<tr><td>Yevgen Shapovalov</td><td><a href="responses.php">review</a></td><td><a href="survey_on_me.php">review</a></td>
				<tr><td>Jacob Ruff</td><td><a href="responses.php">review</a></td><td><a href="survey_on_me.php">review</a></td>
			</table>
			<br>
			<table>
				<th>Team-4</th><th>Surveys Done<br>By Student</th><th>Surveys Done<br>On Student</th>
				<tr><td style="width: 200px;">Chris McGuire</td><td><a href="responses.php">review</a></td><td><a href="survey_on_me.php">review</a></td>
				<tr><td>Matt Lehr</td><td><a href="responses.php">review</a></td><td><a href="survey_on_me.php">review</a></td>
				<tr><td>Bilal Sejouk</td><td><a href="responses.php">review</a></td><td><a href="survey_on_me.php">review</a></td>
			</table>
			</div>
		</form>
		</div>
	</main>
	<?php injectFooter(); ?>
</body>
</html>
