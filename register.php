<?php
session_start();
require_once('includes/session_out.inc.php');
try {
	require_once('includes/header.php');
	require_once('includes/nav.php');
	require_once('includes/functions.inc.php');

	$registrationError = '';
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		
		// Check for registration variables
		if (isset($_POST['register']) &&
			!empty($_POST['username']) &&
			!empty($_POST['first-name']) &&
			!empty($_POST['last-name']) &&
			!empty($_POST['email'])) {
				
			// Attempt to register user
			if (registerUser($_POST['username'],
					$_POST['password'],
					$_POST['first-name'],
					$_POST['last-name'],
					$_POST['email'],
					isset($_POST['instructor']))) {
				header('Location:dashboard.php');
				exit;
			}
			else {
				$registrationError = 'Wrong username or password'; 
			}
		}
	}
} catch(Exception $e) {
	$error = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Register</title>
	<link href="css/style.css" rel="stylesheet" />
</head>
<body>
	<?php injectHeader(); ?>
	<?php injectNav("Register"); ?>
	<main>
	<?php injectDivError($registrationError); ?>
		<div id="div-register-user">
		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
			<div id="div-entries">
			<label class="reg-label" for="username">User Name: </label>
			<input class="reg-field" id="username" name="username" type="text" required /><br><br>
			<label class="reg-label" for="password">Password: </label>
			<input class="reg-field" id="password" name="password" type="text"  required /><br><br>
			<label class="reg-label" for="first-name">First Name: </label>
			<input class="reg-field" id="first-name" name="first-name" type="text"  required /><br><br>
			<label class="reg-label" for="last-name">Last Name: </label>
			<input class="reg-field" id="last-name" name="last-name" type="text"  required /><br><br>
			<label class="reg-label" for="email">E-Mail: </label>
			<input class="reg-field" id="email" name="email" type="text" size="30" required /><br><br>
			
			<label class="reg-label" for="chk-instructor">Instructor:<label>
			<input id="chk-instructor" type="checkbox" name="instructor" value="2"><br><br>
			</div>
			<div id="div-reg-button">
				<button class="reg-button" id="register" name="register" type="submit">Register</button>
			</div>
		</form>
		</div>
	</main>

</body>
</html>
