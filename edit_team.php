<?php // Session control
session_start();
require_once('includes/session_in.inc.php');
try {
	require_once('includes/header.php');
	require_once('includes/nav.php');
	require_once('includes/footer.php');
	require_once('includes/functions.inc.php');
	
} catch(Exception $e) {
	$error = $e->getMessage();
}
?>
<?php // Parsing posts
	$errorMsg = "";
	$teamId = false;
	$teamName="";
	$backText = "Back";
	
	if (isset($_GET['action']) && $_GET['action'] == "edit-team"){
		$teamName = $_GET['team-name'];
		$teamId = $_GET['team-id'];
	} else {
		$teamName = "";
		$teamId = false;
	}
?>
<?php // Function: injectUsersSelect()
function injectUsersSelect() {

	if (false != ($users = getAllUsers())) {
		echo "<option value=\"-1\" selected>--Select a student--</option>";
		foreach($users as $user) {
			
			$firstLast = "{$user['first_name']} {$user['last_name']}";
			$optionText = $firstLast . "&nbsp;&nbsp;({$user['user_name']}";
			
			echo "<option data-username=\"{$user['user_name']}\" data-firstlast=\"{$firstLast}\"  value=\"{$user['user_id']}\">{$optionText})</option>";
		}
	}
}
?>
<?php // Function: injectTeamTable()
	function injectTeamTable($teamName, $teamId) {

		echo "<table id=\"tbl-edit-team\">";
		echo "<tr><td id=\"table-team-name\" colspan=\"4\">{$teamName}</td></tr>";
		//echo "<tr><th class=\"td-user-id\" style=\"display:none;\"></th><th>User</th><th>Name</th><th>Action</th></tr>";
		echo "<tr><th>User</th><th>Name</th><th>Action</th></tr>";

		if (false != ($members = getTeamMembers($teamId))) {
			foreach($members as $user) {
				echo "<tr id=row-id-{$user['user_id']}>";
				//echo "<td class=\"td-user-id\" style=\"display:none;\">{$user['user_id']}</td>";
				echo "<td data-user-id=\"{$user['user_id']}\">{$user['user_name']}</td>";
				echo "<td>{$user['first_name']}" . " " . "{$user['last_name']}</td>";
				echo "<td><a data-row-id=\"{$user['user_id']}\" href=\"#\" onclick=\"deleteRow(event)\">delete</a></td>";
				echo "</tr>";
			}
		}
		echo "</table>";
	}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Create Team</title>
	<link href="css/style.css" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>
<body>
	<div class="fixedheader">
	<?php injectHeader(); ?>
	<?php 
		if ($teamId === false) { 
			injectNav("Dashboard > Create Team");
			$action = "create-team";
		} else {
			injectNav("Dashboard > Edit Team");
			$action = "update-team";
		}
	?>
	</div>
	<main>
		<?php
			if (!empty($errorMsg)) {
				injectDivError($errorMsg);
			}
		?>
		
		<label class="lbl-create-team" for="input-team-name">Team Name:</label>
		<input 
			id="input-team-name" 
			class="input-create-team" 
			name="team-name" 
			type="text" 
			oninput="updateTeamName();"
			value=<?php echo "\"{$teamName}\""; ?> required />
		<br><br>
		
		<!-- Add User Button -->
		<button type="button" id="btn-add-team" name="action" value="add-user" onclick="addBlankRow();">
			Add User
		</button>
		<select class="input-create-team" id="sel-user-id" name="user-id">
			<?php injectUsersSelect(); ?>
		</select>

		<?php injectTeamTable($teamName, $teamId); ?>

		<form action="dashboard.php" method="post" onsubmit="collectTeamMembers();">
			<div id="div-entries">
			
				<input id="team-id" type="hidden" name="team-id" value=<?php echo "\"{$teamId}\""; ?> />
				<input id="team-name" type="hidden" name="team-name" value=<?php echo "\"{$teamName}\""; ?> />
				<input id="team-user-ids" type="hidden" name="team-user-ids" value="" />
				
				<!-- Save & Exit Button -->
				<button id="btn-create-team" name="action" type="submit"
					value=<?php echo "\"{$action}\"";?> >Save & Exit
				</button>
				
				&nbsp;|&nbsp;
				
				<!-- Cancel Button -->
				<button type="submit" id="btn-cancel" name="action" value="cancelled">
					Cancel
				</button>
			</div>
		</form>
	</main>
	<?php injectFooter(true, $backText); ?>
	<script>
		function collectTeamMembers() {

			// Get user IDs from <td> elements in table
			var userIds = [];
			$('td[data-user-id]').each(function(){
				// Add userId values to an array
				userIds.push($(this).attr('data-user-id'));
			});
			
			// Copy user IDs to hidden control's value attribute
			$('#team-user-ids').val(userIds.toString());
			
			// Copy the team's name to hidden control's value attribute
			$('#team-name').val($('#input-team-name').val());
		}
		
		function updateTeamName() {
			$('#table-team-name').text($('#input-team-name').val());
		}
		
		function addBlankRow() {
			var userId = $( "#sel-user-id").val();
			
			if (-1 != userId) {
				var userName = $( "#sel-user-id option:selected" ).attr('data-username');
				var firstLast = $( "#sel-user-id option:selected" ).attr('data-firstlast');
				if ($('td[data-user-id = ' + userId + ']').length > 0) {
					alert(firstLast + " is already a member of the team!");
				} else {
					$('table').append(getNextRow(userId, userName, firstLast));
				}
			}
		}
		
		function getNextRow(userId, userName, firstLast) {
			var row = '<tr id="row-id-' + userId + '">';
			//row += '<td class="td-user-id" style="display:none;">' + userId + '</td>';
			row += '<td data-user-id="' + userId + '">' + userName + '</td>';
			row += '<td>' + firstLast + '</td>';
			row += '<td><a data-row-id=\"' + userId + '\" href=\"#\" onclick=\"deleteRow(event)\">delete</a></td></tr>'
			console.log(row);
			return row;
		}
		
		// Deletes a row from the table and prevents the anchor from firing.
		function deleteRow(event) {
			var rowToDelete = "#row-id-" + event.target.getAttribute('data-row-id');
			$(rowToDelete).remove();
			event.preventDefault();
		}
	</script>
</body>
</html>
