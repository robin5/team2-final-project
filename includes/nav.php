<?php
	function injectNav($subHeader){
		echo "<div id=\"breadcrumb\">";
		//echo "<img class=\"logo\" src=\"images/team2.jpg\" alt=\"Team 2 Logo\" height=\"48\" width=\"48\"/>";
		//echo "<div class=\"nav-sub-header\">";
		echo "{$subHeader}";
		echo "</div>";
	}
?>