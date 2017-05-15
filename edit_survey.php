<?php
session_start();
require_once('includes/session_in.inc.php');
try {
	require_once('includes/header.php');
	require_once('includes/nav.php');
	require_once('includes/footer.php');
	require_once('includes/functions.inc.php');
	require_once('includes/Database/SurveyQuestionFactory.php');
	
} catch(Exception $e) {
	$error = $e->getMessage();
}
?>
<?php
	$errorMsg = "";
	$crumb = "undefined!";
	$surveyId = false;
	$surveyName = "";
	$backText = "Back";

	if ($_SERVER['REQUEST_METHOD'] == "GET") {
		// Create a survey
		if ($_GET['action'] == "create") {
			
			$crumb = "Create Survey";
			$backText = "back";

		} else if ($_GET['action'] == "edit"){
			
			$crumb = "Edit Survey";
			$surveyName = $_GET['survey-name'];
			$surveyId = $_GET['survey-id'];
		}
	}
?>
<?php // injectDivBlankSurvey
function injectDivSurvey($surveyName, $surveyId) {
?>
	<br>
	<br>

	<form id="form-survey" action = "dashboard.php" method="post">

		<label for="survey-name">Survey Name:</label>
		<input id="survey-name" name="survey-name" type="text" value="<?php echo "{$surveyName}"?>" required/><br><br>
		
		<table>
			<tr><th>Question</th><th>Action</th><tr>
			
			<?php 
				$rowId = 0;

				if (false !== $surveyId) {
					if (false != ($questions = SurveyQuestionFactory::getSurveyQuestions($surveyId))) {
						foreach($questions as $question) {
							$tr = "<tr id=row-\"{$rowId}\">";
							$tr .= "<td><textarea name=\"survey-questions[{$rowId}]\" cols=\"80\" rows=\"5\" required>{$question}</textarea></td>";
							$tr .= "<td><span id=\"delete-row{$rowId}\" class=\"fake-anchor\">delete</span></td></tr>";
							echo $tr;
							$rowId++;
						}
					}
				}
			?>
			<tr id="tr-add" >
				<td style="min-width: 400px;">
					<button type="button" style="width: 100%" id="add-question" name="action" value="add-question" onclick="addBlankRow()" >
						<span style="font-size: 1.5em;">Add Question</span>
					</button>
				</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<br>

	<button id="create-survey" for="form-survey" name="action" value="<?php echo (false === $surveyId) ? "create-survey" : "edit-survey" ?>" type="submit">
		<span style="font-size: 1.5em">Save & Exit</span>
	</button>
	&nbsp;|&nbsp;
	<button type="submit" id="btn-cancel" name="action" value="cancelled" onclick="fakeFillFields();">
		<span style="font-size: 1.5em">Cancel</span>
	</button>
	</form>
<?php 
}
?>


<!--

1. Work cooperatively as part of a team and contribute in both leadership and supportive roles.

2. Be flexible, adapt to unanticipated situations and resolve conflicts

3. Build relationships of trust, mutual respect and productive interactions.

4. Communicate and clarify ideas through well-written business correspondence, proposals, instructions, design summaries and client briefs. (Note: This includes all correspondence through email, Slack, and other communication methodologies adopted by your team.)

-->
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Create Survey</title>
	<link href="css/style.css" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>
<body>
	<?php injectHeader(); ?>
	<?php 
		injectNav("Dashboard > " . $crumb);
	?>
	<main>
		<?php
			if (!empty($errorMsg)) {
				injectDivError($errorMsg);
			}
			injectDivSurvey($surveyName, $surveyId);
		?>
	</main>
	<?php injectFooter(); ?>
	<script>
	
		$(document).ready(function(){
			// addBlankRow();
		});

		var rowId = 0;

		function addBlankRow() {
			$('#tr-add').before(getNextRow());
		}

		function getNextRow() {
			var tr = '<tr id=row-"' + rowId + '">';
			tr += '<td><textarea name="survey-questions[' + rowId + ']" cols="80" rows="5" required></textarea></td>';
			tr += '<td><span id="delete-row' + rowId + '" class="fake-anchor">delete</span></td></tr>'
			rowId++;
			return tr;
		}
		
		// fill in required fields with some data
		function fakeFillFields() {
			$('textarea').val(" ");
			$('#survey-name').val(" ");
			return true;
		}
	</script>
</body>
</html>
