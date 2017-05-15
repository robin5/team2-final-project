<?php
function injectHeader(){
	
	// Try to get user name from session
	if (empty($_SESSION['userName'])) {
		$userName = "";
	} else {
		$userName = $_SESSION['userName'];
	}
	
	// Try to get user role from session
	if (!empty($_SESSION['role_instructor']) && $_SESSION['role_instructor'] === true) {
		$role = "Instructor";
	} else if (!empty($_SESSION['role_student']) && $_SESSION['role_student'] === true) {
		$role = "Student";
	} else {
		$role = "";
	}
	
	// Output page header including user name and role if present
	echo "<header>";
	echo "<span class=\"header-title\">";
	echo "<span class=\"header-caps\">C</span>";
	echo "<span class=\"header-small-caps\">LARK </span>";
	echo "<span class=\"header-caps\">S</span>";
	echo "<span class=\"header-small-caps\">TUDENT </span>";
	echo "<span class=\"header-caps\">S</span>";
	echo "<span class=\"header-small-caps\">URVEY</span>";
	echo "</span>";
	echo "<span class=\"header-user-name\">";
	if (!empty($userName)) {
		echo "{$userName}";
		if (!empty($role)) {
			echo " ({$role})";
		}
	}
	echo "</span><div id=\"header-bottom\">&nbsp;</div>";
	echo "</header>";
}
?>