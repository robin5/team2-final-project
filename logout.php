<?php
session_start();
if (isset($_SESSION['valid']))
	$_SESSION['valid'] = false;
session_destroy();
try {
	require_once('includes/header.php');
	require_once('includes/nav.php');
	require_once('includes/functions.inc.php');

	$loginError = '';
	if (isset($_POST['login']) && 
		!empty($_POST['username']) && 
		!empty($_POST['password'])) {
		
		if (login($_POST['username'], $_POST['password'])) {
			header('Location:dashboard.php');
			exit;
		}
		else {
			$loginError = 'Login Failed!  Please try again.'; 
		}
	}
} catch(Exception $e) {
	$error = $e->getMessage();
}
/*****************************************************************
 *
 *****************************************************************/
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>CSS Login</title>
	<link href="css/style.css" rel="stylesheet" />
</head>
<body>
	<?php injectHeader(); ?>
	<?php injectNav(null); ?>
	<main>
		<h3>CSS Application Has Logged Out</h3>
		<div>
			<form>
			<button type="submit" name="login"><a href="index.php">Log back in</a></button><a href="register.php">
			</form>
		</div>
	</main>
</body>
</html>
