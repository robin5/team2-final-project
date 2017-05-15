<?php
/********************************
 * File: session_in.inc.php
 * Description:
 ********************************/
 
// If user is not logged in, redirect them to the index page
if (empty($_SESSION['valid'])) {
	header('location:index.php');
	exit;
}
