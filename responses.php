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

	<!-- ROBIN temp put the script. It should go in header in once PHP starts generating the html -->
	<script src="js/analyzetone.js"></script>
	<!--                                                                             -->
	<!-- HTML form Moc for demo -->
	<strong>Richard Lint | <span style="background-color: #888;">Patrick McCulley</span> | Andrey Demchenko</strong><hr>
	<main>
		<!-- <SUMMARY> -->
		<div id="txt-summary">
		<div id="tone-summary"><p></p></div>
		<button id='btn-summary' class="button" onclick="getAreaTxt('btn-summary','txt-summary','tone-summary')">Review Overall Tone</button></p>

		</div>

		<!-- <QUESTIONS> -->
		<div class="question">
		<p class="resp-grade">Grade: 
		<select>
		  <option value="a">A</option>
		  <option value="b">B</option>
		  <option value="c">C</option>
		  <option value="d">D</option>
		</select>
		<span style="background: white;">&nbsp;A&nbsp;</span> - Work cooperatively as part of a team and contribute in both leadership and supportive roles.</p>
		<textarea id="txt-q1" class="ta-response" rows="5" placeholder="Enter text here...">Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.</textarea>
		<br><button id="btn-q1" class="button" onclick="getAreaTxt('btn-q1','txt-q1','tone-q1')">Review</button><br>
		<div id="tone-q1"><p></p></div>
		</div>

		<div class="question">
		<p class="resp-grade">Grade: 
				<select>
		  <option value="a">A</option>
		  <option value="b" selected>B</option>
		  <option value="c">C</option>
		  <option value="d">D</option>
		</select>
		<span style="background: white;">&nbsp;A&nbsp;</span> - Build relationships of trust, mutual respect and productive interactions.</p>
		<textarea id="txt-q2" class="ta-response" rows="5" placeholder="Enter text here...">Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.</textarea>
		<br><button id="btn-q2" class="button" onclick="getAreaTxt('btn-q2','txt-q2','tone-q2')">Review</button><br>
		<div id="tone-q2"><p></p></div>
		</div>

		<div class="question">
		<p class="resp-grade">Grade: 
		<select>
		  <option value="a">A</option>
		  <option value="b">B</option>
		  <option value="c" selected>C</option>
		  <option value="d">D</option>
		</select>
		<span style="background: white;">&nbsp;A&nbsp;</span> - Be flexible, adapt to unanticipated situations and resolve conflicts</p>
		<textarea id="txt-q3" class="ta-response" rows="5" placeholder="Enter text here...">Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.</textarea>
		<br><button id="btn-q3" class="button" onclick="getAreaTxt('btn-q3','txt-q3','tone-q3')">Review</button><br>
		<div id="tone-q3"><p></p></div>
		</div>
		
		<div class="question">
		<p class="resp-grade">Grade: 
		<select>
		  <option value="a">A</option>
		  <option value="b">B</option>
		  <option value="c">C</option>
		  <option value="d" selected>D</option>
		</select>
		<span style="background: white;">&nbsp;A&nbsp;</span> -  Communicate and clarify ideas through well-written business correspondence, proposals, instructions, design summaries and client briefs. (Note: This includes all correspondence through email, Slack, and other communication methodologies adopted by your team.)</p>
		<textarea id="txt-q4" class="ta-response" rows="5" placeholder="Enter text here...">Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.</textarea>
		<br><button id="btn-q4" class="button" onclick="getAreaTxt('btn-q4','txt-q4','tone-q4')">Review</button><br>
		<div id="tone-q4"><p></p></div>
		</div>

	</main>
	<!--  -->
		<script>
		function toggleAnalyze() {
			var value = $('#summary-analyze').css('display');
			
			if (value == 'block') {
				value = $('#summary-analyze').css('display', 'none');
			} else {
				value = $('#summary-analyze').css('display', 'block');
			}
		}
	</script>

</body>

	<?php injectFooter(); ?>
</html>
