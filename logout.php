<?php
session_start();
if (isset($_SESSION['valid']))
	$_SESSION['valid'] = false;
session_destroy();
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
</head>
<body>
	<h1>Application Logged out</h1>
	<p><a href="index.php">Log back in</a></p>
</body>
</html>
