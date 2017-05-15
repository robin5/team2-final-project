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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>
<body>
	<?php injectHeader(); ?>
	<?php injectNav("Dashboard > Responses by Richard Lint (Team-1)"); ?>
	<strong>Richard Lint | <span style="background-color: #888;">Patrick McCulley</span> | Andrey Demchenko</strong><hr>
	<main>
		<div>
		<p class="resp-grade">Grade: <span style="background: white;">&nbsp;A&nbsp;</span> - Work cooperatively as part of a team and contribute in both leadership and supportive roles.</p>
		<textarea id="input-q1" class="ta-response" rows="5"></textarea>
		</div>

		<div class="div-analyze">
		<button class="btn-analyze" onclick="toggleAnalyze();">Analysize</button>
		<table id="analysis1" class="tbl-analyze">
			<tr>
				<td><b>Emotion Tone</b><br>Anger: 13.88%<br>Disgust: 33.3%<br>Fear: 5.16%<br>Joy: 23.58%<br>Sadness: 17.12%</td>
				<td><b>Language Tone</b><br>Analytical: 93.49%<br>Confident: 0%<br>Confident: 0%</td>
				<td><b>Social: 0% Tone</b><br>Openness: 65.06%<br>Conscientiousness: 56.93%<br>Extraversion: 79.24%<br>Agreeableness: 71.19%<br>Emotional Range: 41.68%</td>
			</tr>
		</table>
		</div>


		<div>
		<p class="resp-grade">Grade: <span style="background: white;">&nbsp;A&nbsp;</span> - Build relationships of trust, mutual respect and productive interactions.</p>
		<textarea id="input-q2" class="ta-response"></textarea>
		<button>Analysize</button><br>
		</div>

		<div class="div-analyze">
		<p class="resp-grade">Grade: <span style="background: white;">&nbsp;A&nbsp;</span> - Be flexible, adapt to unanticipated situations and resolve conflicts</p>
		<textarea id="input-q3" class="ta-response"></textarea>
		<button>Analysize</button><br>

		<p class="resp-grade">Grade: <span style="background: white;">&nbsp;A&nbsp;</span> -  Communicate and clarify ideas through well-written business correspondence, proposals, instructions, design summaries and client briefs. (Note: This includes all correspondence through email, Slack, and other communication methodologies adopted by your team.)</p>
		<textarea id="input-q4" class="ta-response"></textarea>
		<br><button>Analysize</button><br>

		</div>
	</main>
	<?php injectFooter(); ?>
	<script>
		function toggleAnalyze() {

			var value = $('#analysis1').css('display');
			
			if (value == 'block') {
				value = $('#analysis1').css('display', 'none');
			} else {
				value = $('#analysis1').css('display', 'block');
			}
		}
	</script>
</body>
</html>
