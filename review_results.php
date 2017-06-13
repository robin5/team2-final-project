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
	<title>Review Survey Results</title>
	<link href="css/style.css" rel="stylesheet" />
</head>
<body>
	<div class="fixedheader">
	<?php injectHeader(); ?>
	<?php injectNav("Dashboard > Review Aggregate Results"); ?>
	</div>
	<main>
		<div id="div-user">
		<form action="dashboard.php" method="post">
			<div id="div-entries">
			</div>
			<div id="div-reg-button">
				TBD<br>TBD<br>TBD<br>TBD<br>TBD<br>TBD<br>TBD<br>
			</div>
		</form>
		</div>
	</main>
	<?php injectFooter(); ?>
</body>
</html>
