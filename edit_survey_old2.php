<?php
session_start();
require_once('includes/session_in.inc.php');
try {
	require_once('includes/header.php');
	require_once('includes/nav.php');
	require_once('includes/footer.php');
	require_once('includes/functions.inc.php');
	
} catch(Exception $e) {
	$error = $e->getMessage();
}
?>
<?php
	$errorMsg = "";
	$surveyId = false;
	$surveyName = "";
	$backText = "Back";
	$action = "";

	if ($_SERVER['REQUEST_METHOD'] == "GET") {
		// Create a survey
		if ($_GET['action'] == "create") {
			
			$action = "create";
			$owner = $_SESSION['userId']; // used to specify survey owner
			$backText = "back";

			/*if (false == ($surveyId = createSurvey($surveyName, $owner))) {
				$errorMsg = "Could not create survey: " . $surveyName;
			}*/
		} else if ($_GET['action'] == "edit"){
			
			//print_r($_GET);
			$action = "edit";
			$surveyName = $_GET['survey-name'];
			//$surveyId = $_GET['survey-id'];
		}
	}
	else { //($_SERVER['REQUEST_METHOD'] == "POST"
		
		if (false) {
			$owner = 32;
			$surveyName = "Team2";
			$backText = "Done";
			$surveyId = 33;
		}
		// Post to create a survey
		else if (isset($_POST['action']) && $_POST['action'] == "edit-survey") {
			if (!empty($_SESSION['userId']) && 
				!empty($_POST['survey-name']) ) {
					
				$owner = $_SESSION['userId']; // used to specify survey owner
				$surveyName = $_POST['survey-name'];
					
				$backText = "Done";

				if (false == ($surveyId = createSurvey($surveyName, $owner))) {
					$errorMsg = "Could not create survey: " . $surveyName;
				}
			}
		}
		// Post to add a question to the survey
		else if (isset($_POST['action']) && $_POST['action'] == "add-question") {

			$backText = "Done";

			if (!empty($_POST['survey-id'])) {

				$surveyName = $_POST['survey-name'];
				$surveyId = $_POST['survey-id'];
				$questionText = $_POST['question-text'];
				$questionIndex = $_POST['question-index'];
				
				addSurveyQuestion($surveyId, $questionText, $questionIndex, $errorMsg);
			}
		}
	} 
?>
<?php // injectDivBlankSurvey
function injectDivBlankSurvey() {
?>
	<button id="add-question" name="action" value="add-question" onclick="addBlankRow()" ><span style="font-size: 1.5em">Add Question</span></button><br><br>

	<form id="form-survey" action = "dashboard.php" method="post">

		<label for="survey-name">Survey Name:</label>
		<input id="survey-name" name="survey-name" type="text" required/><br><br>
		
		<table>
			<th></th><th>Question</th><th>Action</th>
		</table>
		<br>

	<button id="create-survey" for="form-survey" name="action" value="create-survey" type="submit"><span style="font-size: 1.5em">Save & Exit</span></button> | 
	</form>
<?php 
}
?>
<?php // Function: injectDivEditSurvey()
function injectDivEditSurvey($surveyName, $surveyId) {
?>
	<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

		<div id="div-entries">
		<label for="survey-name">Survey Name:</label>
		<input id="survey-name" name="survey-name" type="text" required value="CTEC-227 Spring 2017"/><br><br>
		
		<table>
			<th></th><th>Question</th><th>Action</th>
			<tr>
				<td>1</td>
				<td><textarea id="input-q1" cols="80" rows="5">Work cooperatively as part of a team and contribute in both leadership and supportive roles.</textarea></td>
				<td><a href="#">delete</a></td>
			</tr>
			<tr>
				<td>2</td>
				<td><textarea id="input-q3" cols="80" rows="5">Be flexible, adapt to unanticipated situations and resolve conflicts</textarea></td>
				<td><a href="#">delete</a></td>
			</tr>
			<tr>
				<td>3</td>
				<td><textarea id="input-q2" cols="80" rows="5">Build relationships of trust, mutual respect and productive interactions.</textarea></td>
				<td><a href="#">delete</a></td>
			</tr>
			<tr>
				<td>4</td>
				<td><textarea id="input-q4" cols="80" rows="5">Communicate and clarify ideas through well-written business correspondence, proposals, instructions, design summaries and client briefs. (Note: This includes all correspondence through email, Slack, and other communication methodologies adopted by your team.)</textarea></td>
				<td><a href="#">delete</a></td>
			</tr>
		</table>
		
		<br>
			<button id="add-question" name="action" value="add-question" type="submit"><span style="font-size: 1.5em">Add Question</span></button> | 
			<button id="create-survey" name="action" value="create-survey" type="submit"><span style="font-size: 1.5em">Save & Exit</span></button><br><br>

		</div>
	</form>
<?php
	}
?>
<?php // Function: injectSurveyTable()
	function injectSurveyTable($surveyName, $surveyId) {

		echo "<table style=\"border:3px solid #666;border-collapse: collapse;\">";
		echo "<tr><td colspan=\"3\">{$surveyName}</td></tr>";
		echo "<tr><th>Order</th><th>Question</th><th>Action</th></tr></tr>";

		if (false == ($questions = getSurveyQuestions($surveyId))) {
			echo "<tr>";
			echo "<td colspan=\"3\">Please add survey questions.</td>";
			echo "</tr>";
		} else {
			foreach($questions as $question) {
				echo "<tr>";
				echo "<td><input type=\"text\" size=\"3\"value=\"{$question['qs_index']}\"/></td>";
				echo "<td><textarea cols=\"60\" rows=\"3\">{$question['text']}</textarea></td>";
				echo "<td><a href=\"#\">delete</a></td>";
				echo "</tr>";
			}
		}
		echo "</table>";
	}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Create Survey</title>
	<link href="css/style.css" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>
<body>
	<script>
		var rowId = 0;
		function addBlankRow() {
			var table = $('table');
			table.append(getNextRow());
		}
		function getNextRow() {
			rowId++;
			var row = '<tr id="row' + rowId + '">';
			row += '<td>' + rowId + '</td>';
			row += '<td><textarea id="input-q' + rowId + '" cols="80" rows="5" required></textarea></td>';
			row += '<td><span id="delete-row' + rowId + '" class="fake-anchor">delete</span></td></tr>'
			return row;
		}
	</script>
	<?php injectHeader(); ?>
	<?php 
		if ($action == "create") { 
			injectNav("Dashboard > Create Survey");
		} else {
			injectNav("Dashboard > Edit Survey");
		}
	?>
	<main>
		<?php
			if (!empty($errorMsg)) {
				injectDivError($errorMsg);
			}

			if ($action == "create") { 
				injectDivBlankSurvey();
			} else {
				injectDivEditSurvey($surveyName, $surveyId);
			}
		?>
	</main>
	<?php injectFooter(); ?>
	<script>
		$(document).ready(function(){
			addBlankRow();
			addBlankRow();
			addBlankRow();
			addBlankRow();
		});
	</script>
</body>
</html>
