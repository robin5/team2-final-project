<?php
	function injectFooter($showBack = true, $backText = 'Back'){
		echo "<footer>";
		echo "<span class=\"footer-back\">";
		if ($showBack) {
			echo "<a href=\"dashboard.php\">{$backText}</a>";
		}
		echo"</span>";
		echo "<span class=footer-log-out>";
		echo "<a href=\"logout.php\">Log Out</a>";
		echo "</span>";
		echo "<span class=\"footer-copyright\">Copyright 2017</span>";
		echo "</footer>";
	}
?>
