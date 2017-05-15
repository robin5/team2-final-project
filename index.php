<?php
session_start();
require_once('includes/session_out.inc.php');
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
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Final Project</title>
	<link href="css/style.css" rel="stylesheet" />
</head>
<body>
	<?php injectHeader(); ?>
	<?php injectNav(null); ?>
	<?php injectDivError($loginError); ?>
	<main>
	<h3>Enter UserName and Password</h3 
	<div>
		<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
			<label for="username">User Name:</label>
			<input id="username" type="text" name="username" value="belgort" required autofocus>
			<br /><br />
			<label for="password">Password:</label>
			<input id="password" type="password" name="password" value="1234" required>
			<br /><br />
			<button type="submit" name="login">Login</button>&nbsp;|&nbsp;<a href="register.php">register new user:</a>
		</form>
		
		
	</div> 
	</main>
</body>
</html>