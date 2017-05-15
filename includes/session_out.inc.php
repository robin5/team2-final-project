<?php
/********************************
 * File: session_out.inc.php
 * Description:
 ********************************/
 
// If user is logged in, redirect them to the dashboard page
if (!empty($_SESSION['valid'])) {
	header('location:dashboard.php');
	exit;
}
