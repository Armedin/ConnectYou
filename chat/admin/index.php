<?php require_once '../include/functions.php'; ?>
<?php require_once './admin-functions.php'; ?>
<?php js_variables(); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="HandheldFriendly" content="true" />
		<meta name="viewport" content="height=device-height, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		
		<title>Instant Messaging Demo</title>

		<link type="text/css" rel="stylesheet" href="../include/css/materialize.min.css" />
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<link type="text/css" rel="stylesheet" href="../include/css/perfect-scrollbar.css" />
		<link type="text/css" rel="stylesheet" href="./style.css" />
		<link type="text/css" rel="stylesheet" href="../include/css/emoticons.css" />
		<script type='text/javascript' src='../include/js/jquery.min.js'></script>
		<script type='text/javascript' src='../include/js/materialize.min.js'></script>
		<script type='text/javascript' src='../include/js/reconnecting-websocket.js'></script>
		<script type='text/javascript' src='../include/js/perfect-scrollbar.jquery.js'></script>
		<script type="text/javascript" src="./Chart.bundle.min.js"></script>
		<script type='text/javascript' src='./admin.js'></script>
		<script type='text/javascript'>
			$( document ).ready( function() {
				$(window).on("beforeunload", function(){
					if(websocket.readyState != 1) {
						$.post("./action.php?act=offline", {userid: userid, username: username, token: token});
					}
				});
				websocket.onopen = function (event) {
					var msg = {
						name: username,
						iduser: userid,
						token: token,
					};
					var json_msg = JSON.stringify( msg );
					websocket.send( json_msg );
				};
			});
		</script>
	</head>
	<body>
	<?php if(blockUsers()) :
		header('Location: ../');
		exit;
	else : ?>
		<?php if(isUserLoggedIn()) : ?>
			<?php if(isAdmin(user_id()) && !isBanned(user_id())) :
				echo "<script type='text/javascript'>
							$( document ).ready( function() {
									websocket.onopen = function (event) {
										var msg = {
										name: username,
										iduser: userid,
										token: token,
										ip_address: '".getRealIpAddr()."'
									};
									var json_msg = JSON.stringify( msg );
									websocket.send( json_msg );
								};
							});
						</script>"; ?>
				<?php admin_header($_GET);?>
				<div id='delete-modal' class='modal'>
					<div class='modal-content'>
						<h4>Delete the User</h4>
						<p>Do you really want to delete this user?</p>
					</div>
					<div class='modal-footer'>
						<a href='#' id='delete-user-confirm' class=' modal-action waves-effect waves-green btn-flat'>Delete</a>
						<a href='#' class=' modal-action modal-close waves-effect waves-green btn-flat'>Cancel</a>
					</div>
				</div>
				<div id='delete-chat-modal' class='modal'>
					<div class='modal-content'>
						<h4>Delete the User</h4>
						<p>Do you really want to delete the chat room?</p>
					</div>
					<div class='modal-footer'>
						<a href='#' id='delete-chatroom-confirm' class=' modal-action waves-effect waves-green btn-flat'>Delete</a>
						<a href='#' class=' modal-action modal-close waves-effect waves-green btn-flat'>Cancel</a>
					</div>
				</div>
				<div id='block-modal' class='modal'>
					<div class='modal-content'>
						<h4>Temporarily Ban the User</h4>
						<div class="row">
							<div class="col s12 m6">
								<div class="card blue-grey darken-1">
									<div class="card-content white-text">
										<div class="row">
											<div class="input-field col s4">
												<input placeholder="2030" id="temp_year" type="number">
												<label for="temp_year" class="white-text">Year</label>
											</div>
											<div class="input-field col s4">
												<input placeholder="05" id="temp_month" type="number">
												<label for="temp_month" class="white-text">Month</label>
											</div>
											<div class="input-field col s4">
												<input placeholder="08" id="temp_day" type="number">
												<label for="temp_day" class="white-text">Day</label>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s6">
												<input placeholder="12" id="temp_hour" type="number">
												<label for="temp_hour" class="white-text">Hour</label>
											</div>
											<div class="input-field col s6">
												<input placeholder="00" id="temp_min" type="number">
												<label for="temp_min" class="white-text">Minute</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col s12 m6">
								<div class="card blue-grey darken-1">
									<div class="card-content white-text">
										<div class="row">
											<div class="input-field col s12">
												<input id="temp_reason" type="text" val="">
												<label for="temp_reason" class="white-text">Reason</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>					
					</div>
					<div class='modal-footer'>
						<a href='#' id='block-user-confirm' class=' modal-action waves-effect waves-green btn-flat'>Ban the User</a>
						<a href='#' class=' modal-action modal-close waves-effect waves-green btn-flat'>Cancel</a>
					</div>
				</div>
				<div id='perm-modal' class='modal'>
					<div class='modal-content'>
						<h4>Temporarily Ban the User</h4>
						<div class="col s12">
							<div class="card blue-grey darken-1">
								<div class="card-content white-text">
									<div class="row">
										<div class="input-field col s12">
											<input id="temp_reason2" type="text" val="">
											<label for="temp_reason2" class="white-text">Reason</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class='modal-footer'>
						<a href='#' id='perm-user-confirm' class=' modal-action waves-effect waves-green btn-flat'>Ban the User</a>
						<a href='#' class=' modal-action modal-close waves-effect waves-green btn-flat'>Cancel</a>
					</div>
				</div>
				<div id='logout-modal' class='modal'>
					<div class='modal-content'>
						<h4>Delete this User</h4>
						<p>Do you really want to terminate the this user's session?</p>
					</div>
					<div class='modal-footer'>
						<a href='#' id='logout-user-confirm' class=' modal-action waves-effect waves-green btn-flat'>Logout</a>
						<a href='#' class=' modal-action modal-close waves-effect waves-green btn-flat'>Cancel</a>
					</div>
				</div>
				<div id='remove-ban-modal' class='modal'>
					<div class='modal-content'>
						<h4>Remove Ban of the User</h4>
						<p>Do you really want to remove the ban of the user?</p>
					</div>
					<div class='modal-footer'>
						<a href='#' id='remove-ban-confirm' class=' modal-action waves-effect waves-green btn-flat'>Remove</a>
						<a href='#' class=' modal-action modal-close waves-effect waves-green btn-flat'>Cancel</a>
					</div>
				</div>
				<div id='messages-modal' class='modal modal-fixed-footer'>
					<div class='modal-content' id="msg-content"></div>
					<div class='modal-footer'>
						<a href='#' class='modal-close modal-action waves-effect waves-green btn-flat'>Close</a>
					</div>
				</div>
				<?php 
				if(isset($_GET['manage-chat'])) :
					invite_reveal($_GET['manage-chat']);
				endif;
				echo "<div class='content grey lighten-3'>";
				if(isset($_GET['action']) && $_GET['action'] == 'manage-users'):
						
					echo "<div class='row'>";
						total_member("s4", "blue-grey darken-1 white-text");
						total_guest("s4", "blue-grey darken-1 white-text");
						total_admin("s4", "blue-grey darken-1 white-text");
						search_card("user");
						last_registered_card();
						user_chart_card();
					echo "</div>";
				elseif(isset($_GET['search'])) :
					admin_search($_GET['search'], "user"); 
				elseif(isset($_GET['chat-search'])) :
					admin_search($_GET['chat-search'], "chat");
				elseif(isset($_GET['message'])) :
					admin_search($_GET['message'], "msg");
				elseif(isset($_GET['manage-user'])) :
					edit_user($_GET['manage-user']);
				elseif(isset($_GET['manage-chat'])) :
					edit_chat($_GET['manage-chat']);
				elseif(isset($_GET['action']) && $_GET['action'] == "create-user") :
					new_user();
				elseif(isset($_GET['action']) && $_GET['action'] == "settings") :
					general_settings();
				elseif(isset($_GET['action']) && $_GET['action'] == "user-settings") :
					user_settings();
				elseif(isset($_GET['action']) && $_GET['action'] == "chat-settings") :
					chat_settings();
				elseif(isset($_GET['action']) && $_GET['action'] == "ban-settings") :
					ban_settings();
				elseif(isset($_GET['action']) && $_GET['action'] == "messages" && save_messages() == 1) :
					all_messages();
				elseif(isset($_GET['action']) && $_GET['action'] == "manage-chats") :
					echo "<div class='row'>";
						total_messages("s6", "blue-grey darken-1 white-text");
						total_chats("s6", "blue-grey darken-1 white-text");
						search_card("chat");
						all_chats();
					echo "</div>";
				else : ?>
					<script type='text/javascript'>$(document).ready(function(){websocket.onopen = function(){$('#websocket').removeClass('red').addClass('green');};websocket.onerror = function(){$('#websocket').removeClass('green').addClass('red');};websocket.onclose = function(){$('#websocket').removeClass('green').addClass('red');};});</script>
					<?php echo "<div class='row'>";
						echo "<div class='col s6 m5' style='padding-left:0;padding-right:0;'>";
						echo "<h3 class='center-align title'><i class='material-icons medium'>network_check</i>Connection Statuses</h3>";
						websocket_connection("s12", "white");
						database_connection("s12", "white");
					echo "</div>";
					echo "<div class='col s6 m7' style='padding-left:0;padding-right:0;'>";
					echo "<h3 class='center-align title'><i class='material-icons medium'>public</i>Online Users</h3>";
						online_user("s12 m6", "white");
						online_member("s12 m6", "white");
						online_guest("s12 m6", "white");
						online_admin("s12 m6", "white");
					echo "</div>";
					echo "</div>";
					echo "<div class='row'>";
						
					echo "</div>";
					echo "<div class='row'>";
						echo "<h3 class='center-align title'><i class='material-icons medium'>supervisor_account</i>Registered Accounts</h3>";
						total_user("s6 m3", "orange darken-4 white-text");
						total_member("s6 m3", "orange darken-4 white-text");
						total_guest("s6 m3", "orange darken-4 white-text");
						total_admin("s6 m3", "orange darken-4 white-text");
					echo "</div>";
					echo "<div class='row'>";
					echo "<div class='col s6 m3' style='padding-left:0;padding-right:0;'>";
						total_chats("s12", "cyan darken-3 white-text");
						total_messages("s12", "cyan darken-3 white-text");
						total_files("s12", "cyan darken-3 white-text");
					echo "</div>";
					echo "<div class='col s6 m4' style='padding-left:0;padding-right:0;'>";
						last_registered_card("s12");
					echo "</div>";
					echo "<div class='col s12 m5' style='padding-left:0;padding-right:0;'>";
						user_chart_card("s12");
					echo "</div>";
					echo "</div>";
				endif;
			else :
				echo "<div class='content2'>";
				echo "<div class='bg-top'></div>";
				echo "<div class='bg-bottom'></div>";
				echo "<div class='row'>";
				echo "<div class='col s12 m6'>";
				echo "<div class='card blue-grey darken-1 error-card' style='z-index:999'>";
				echo "<div class='card-content white-text'>";
				echo "<span class='card-title'>A Problem Occured</span>";
				echo "<p>You are not authorized to view this page.</p><br>";
				echo "</div>";
				echo "<div class='card-action'>";
				echo "<a id='btn-continue' href='../'>Go Back</a>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			endif;
		else :
			header("location: ../");
			exit;
		endif;
	endif; ?>
</div>
  