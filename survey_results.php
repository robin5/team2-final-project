<?php
session_start();
require_once('includes/session_in.inc.php');
try {
	require_once('includes/header.php');
	require_once('includes/nav.php');
	require_once('includes/footer.php');
	require_once('survey_results.cls.php');
	
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/highcharts-more.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
</head>
<body>
	<div class="fixedheader">
	<?php injectHeader(); ?>
	<?php
		$errMsg = "";
		$instanceId = false;
		$surveyName = '';
		
		// echo "<pre>"; print_r($_POST); echo "</pre>";
		if ($_SERVER['REQUEST_METHOD'] === "GET") {
			if (!empty($_GET['instance-id'])) {
				$instanceId = $_GET['instance-id'];
				$surveyName = $_GET['survey-name'];
				
				if (false === ($questions = SurveyInstanceFactory::getSurveyInstanceQuestionIds($instanceId))) {
					$errMsg =  SurveyInstanceFactory::getLastError();
				} else if (false !== ($instanceTeams = TeamFactory::getTeamUsersByInstance($instanceId))) {
					$errMsg =  TeamFactory::getLastError();
				}
			}
		}
	?>
	<?php injectNav("Dashboard > Survey Results: " . $surveyName); ?>
	</div>
	<main>
		<?php
			if (!empty($errMsg)) {
				injectDivError($errMsg);
			}
		?>
		<br>
		<!--?php SurveyResults::injectTeamTables($instanceId, $surveyName); ?-->
		<?php SurveyResults::injectTeamTables2($instanceId, $surveyName, $questions, $instanceTeams); ?>
	</main>
	<?php injectFooter(); ?>
	<script src="js/piechart.js"></script>
	<script>
	
		$(function() {
			
			// Set the pie chart data for each chart
			$('.pie-chart').each(function() {
				// Get the chart data from the chart's div data attribute
				pieData = eval($(this).attr('data'));
				// Create the chart using the retrieved data
				createPieChart($(this).attr('id'), pieData);
			});
			
			// Find the width of the largest content div
			var maxDivWidth = 0;
			$('.reviewee-content').each(function(){
				if ($(this).width() > maxDivWidth) {
					maxDivWidth = $(this).width();
				}
			});
			// Set all of the content widths to the largest content div width
			$('.reviewee-content').width(maxDivWidth);
			
			
		});
	
	</script>
</body>
</html>
