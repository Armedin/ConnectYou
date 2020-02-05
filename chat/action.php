<?php

require_once __DIR__ . '/include/chat.php';
date_default_timezone_set(timezone());    // Set the timezone

$username = user_name(); // Username
$user_id = user_id(); // User ID

if(!blockUsers()) {
	/////////////////////////////
	////// Unload Messages //////
	// Deletes Unread Messages //
	/////////////////////////////

	if (isset($_GET['act'])
		&& $_GET['act'] == 'unload'
		&& is_ajax()
		&& isset($_POST['chatid'])
		&& !empty($_POST['chatid'])
		&& save_messages() == 1
	) {
		$chat_id = db_escape(chat_id($_POST['chatid']));

		if (userInRoom($user_id, $chat_id)) {
			$delete = db_query("DELETE FROM `chat_unread` WHERE usr_id = '$user_id' AND chat_room = '$chat_id'");
		}
	}

	////////////////////////////////////////////
	////// Changes User Status to Offline //////
	////////////////////////////////////////////

	if (isset($_GET['act'])
		&& $_GET['act'] == 'offline'
		&& is_ajax()
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
	) {
		$user_id = db_escape($_POST['userid']);
		$username = db_escape($_POST['username']);
		$token = db_escape($_POST['token']);
		if ($token == getToken($user_id)) {
			$query = db_query("SELECT `username` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
			$row = mysqli_fetch_array($query);
			$uname = $row['username'];
			if ($uname == $username) {
				db_query("UPDATE `members` SET `online` = 0 WHERE `ID` = '$user_id' LIMIT 1");
			}
		}
	}

	////////////////////////////////
	////// Update User Status //////
	////////////////////////////////

	if (isset($_GET['act'])
		&& $_GET['act'] == 'update-user-status'
		&& is_ajax()
		&& enable_user_status() == 1
		&& isset($_POST['user_id'])
		&& !empty($_POST['user_id'])
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['status'])
	) {
		$user_id = db_escape($_POST['user_id']);
		$username = db_escape($_POST['username']);
		$token = db_escape($_POST['token']);
		$status = db_escape($_POST['status']);
		if ($token == getToken($user_id)) {
			if (userName($user_id) == $username) {
				if(strlen($status) > get_option("MAX_STATUS")) {
					echo -1;
				} else {
					db_query("UPDATE `members` SET `user_status` = '$status' WHERE `ID` = '$user_id' LIMIT 1");
					if(empty($status)) {
						echo get_option("DEFAULT_STATUS");
					} else {
						echo 1;
					}
				}
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
	}

	//////////////////////////
	// Online Users Refresh //
	//////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'online-users'
		&& is_ajax()
	) {
		if(isset($_POST["page"]) && $_POST["page"] > 0) {
			$page = $_POST["page"];
		} else {
			$page = 1;
		}
		
		$begin = ( $page * 10 ) - 10;
		if (guests_on_user_list() == 1) {
			$users_query = db_query("SELECT `username`, `ID` , `profile_pic` FROM `members` WHERE `online` = 1 && `ID` != '$user_id' LIMIT $begin, 10");
			$total_query = db_query("SELECT `username`, `ID` , `profile_pic` FROM `members` WHERE `online` = 1 && `ID` != '$user_id'");
		} else {
			$users_query = db_query("SELECT `username`, `ID` , `profile_pic` FROM `members` WHERE `online` = 1 && `guest` = 0 && `ID` != '$user_id' LIMIT $begin, 10");
			$total_query = db_query("SELECT `username`, `ID` , `profile_pic` FROM `members` WHERE `online` = 1 && `guest` = 0 && `ID` != '$user_id'");
		}

		$total_users = mysqli_num_rows($total_query);
		$total_page = ceil($total_users / 10);
		
		$users = "";
		$pagi = "";
		$stat = 1;
		
		$title = "Online Users: ".$total_users;

		if ($total_users > 0) {
			$users .= "<ul class='collection'>";
			while ($users_row = mysqli_fetch_array($users_query)) {
				$users .= "<li class='collection-item avatar'>";
				$users .= "<div id='user-profile-div' attr-ID='".$users_row['ID']."'>";
				if (empty($users_row['profile_pic'])) {
					$users .= "<i id='chat-search' class='clickable z-depth-1 material-icons circle chat-list-photo grey lighten-2'>person</i>";
					$users .= "<img id='chat-img-search' class='clickable z-depth-1 circle hide chat-list-photo' src=''>";
				} else {
					$users .= "<i id='chat-search' class='clickable z-depth-1 material-icons hide circle chat-list-photo grey lighten-2'>person</i>";
					$users .= "<img id='chat-img-search' class='clickable z-depth-1 circle chat-list-photo' src='".picture_destination().$users_row['profile_pic']."'>";
				}
				$users .= "</div>";
				$users .= "<span class='title truncate'>".$users_row['username'].'</span>';
				$users .= "<a href='#' style='margin-top:15px' attr-ID='".$users_row['ID']."' class='secondary-content pm'><i class='material-icons clickable' id='pm'>send</i></a>";

				$users .= '</li>';
			}
			$users .= "</ul>";
			$pagi .= '<ul class="pagination center-align online-pagination">';
			$next_page = $page + 1;
			$prev_page = $page - 1;
			if ($total_page < 9) {
				if ($page == 1) {
					$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
					$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
				} else {
					$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
					$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
				}
				for ($i = 1; $i <= $total_page; $i++) {
					if ($page == $i) {
						$pagi .= "<li class='active clickable'><a attr-page='".$i."'>".$i.'</a></li>';
					} else {
						$pagi .= "<li class='waves-effect clickable'><a attr-page='".$i."'>".$i.'</a></li>';
					}
				}
				if ($page == $total_page) {
					$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
					$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
				} else {
					$pagi .= "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
					$pagi .= "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
				}
			} else {
				if ($page < 6) {
					if ($page == 1) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = 1; $i <= 9; $i++) {
						if ($page == $i) {
							$pagi .= "<a attr-page='".$i."'><li class='active clickable'>".$i.'</li></a>';
						} else {
							$pagi .= "<a attr-page='".$i."'><li class='waves-effect clickable'>".$i.'</li></a>';
						}
					}
					if ($page == $total_page) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} elseif ($page > $total_page - 4) {
					if ($page == 1) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = $total_page - 8; $i <= $total_page; $i++) {
						if ($page == $i) {
							$pagi .= "<li class='active clickable'><a attr-page='".$i."'>".$i.'</a></li>';
						} else {
							$pagi .= "<li class='waves-effect clickable'><a attr-page='".$i."'>".$i.'</a></li>';
						}
					}
					if ($page == $total_page) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} else {
					$num = $page - 4;
					if ($page == 1) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($num; $num <= $page + 4; $num++) {
						if ($page == $num) {
							$pagi .= "<li class='active clickable'><a attr-page='".$num."'>".$num.'</a></li>';
						} else {
							$pagi .= "<li class='waves-effect clickable'><a attr-page='".$num."'>".$num.'</a></li>';
						}
					}
					if ($page == $total_page) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				}
			}
			$pagi .= "</ul>";
		} else {
			$users .= "<p>There isn't any online user.</p>";
			$stat = 0;
		} 
		echo json_encode(
					array(
						"stat" => $stat, 
						"users" => $users,
						"pagination" => $pagi,
						"title" => $title
					)
				);

	}

	/////////////////////////////
	// Online Users Pagination //
	/////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'online-search'
		&& is_ajax()
		&& isset($_POST['page'])
		&& !empty($_POST['page'])
	) {
		$page = db_escape($_POST['page']);
		$begin = ($page * 10) - 10;
		$users = "";
		$pagi = "";
		$stat = 1;

		
		if (guests_on_user_list() == 1) {
			$users_query = db_query("SELECT `username`, `ID` , `profile_pic` FROM `members` WHERE `activation` = 1 && `online` = 1 && `ID` != '$user_id' LIMIT $begin, 10");
			$total_query = db_query("SELECT `username`, `ID` , `profile_pic` FROM `members` WHERE `activation` = 1 && `online` = 1 && `ID` != '$user_id'");
		} else {
			$users_query = db_query("SELECT `username`, `ID` , `profile_pic` FROM `members` WHERE `activation` = 1 && `online` = 1 && `guest` = 0 && `ID` != '$user_id' LIMIT $begin, 10");
			$total_query = db_query("SELECT `username`, `ID` , `profile_pic` FROM `members` WHERE `activation` = 1 && `online` = 1 && `guest` = 0 && `ID` != '$user_id'");
		}
		$total_users = mysqli_num_rows($total_query);
		$total_page = ceil($total_users / 10);
		
	   
		if ($total_users > 0) {
			$users .= "<ul class='collection'>";
			while ($users_row = mysqli_fetch_array($users_query)) {
				$users .= "<li class='collection-item avatar'>";
				$users .= "<div id='user-profile-div' attr-id='".$users_row['ID']."'>";
				if (empty($users_row['profile_pic'])) {
					$users .= "<i id='chat-search' class='clickable z-depth-1 material-icons circle chat-list-photo grey lighten-2'>person</i>";
					$users .= "<img id='chat-img-search' class='clickable z-depth-1 circle hide chat-list-photo' src=''>";
				} else {
					$users .= "<i id='chat-search' class='clickable z-depth-1 material-icons hide circle chat-list-photo grey lighten-2'>person</i>";
					$users .= "<img id='chat-img-search' class='clickable z-depth-1 circle chat-list-photo' src='".picture_destination().$users_row['profile_pic']."'>";
				}
				$users .= "</div>";
				$users .= "<span class='title truncate'>".$users_row['username'].'</span>';
				$users .= "<a href='#' style='margin-top:15px' attr-id='".$users_row['ID']."' class='secondary-content pm'><i class='material-icons clickable' id='pm'>send</i></a>";
				$users .= '</li>';
			}
			$users .= "</ul>";
			$pagi .= '<ul class="pagination center-align online-pagination">';
			$next_page = $page + 1;
			$prev_page = $page - 1;
			if ($total_page < 9) {
				if ($page == 1) {
					$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
					$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
				} else {
					$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
					$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
				}
				for ($i = 1; $i <= $total_page; $i++) {
					if ($page == $i) {
						$pagi .= "<li class='active clickable'><a attr-page='".$i."'>".$i.'</a></li>';
					} else {
						$pagi .= "<li class='waves-effect clickable'><a attr-page='".$i."'>".$i.'</a></li>';
					}
				}
				if ($page == $total_page) {
					$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
					$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
				} else {
					$pagi .= "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
					$pagi .= "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
				}
			} else {
				if ($page < 6) {
					if ($page == 1) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = 1; $i <= 9; $i++) {
						if ($page == $i) {
							$pagi .= "<a attr-page='".$i."'><li class='active clickable'>".$i.'</li></a>';
						} else {
							$pagi .= "<a attr-page='".$i."'><li class='waves-effect clickable'>".$i.'</li></a>';
						}
					}
					if ($page == $total_page) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} elseif ($page > $total_page - 4) {
					if ($page == 1) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = $total_page - 8; $i <= $total_page; $i++) {
						if ($page == $i) {
							$pagi .= "<li class='active clickable'><a attr-page='".$i."'>".$i.'</a></li>';
						} else {
							$pagi .= "<li class='waves-effect clickable'><a attr-page='".$i."'>".$i.'</a></li>';
						}
					}
					if ($page == $total_page) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} else {
					$num = $page - 4;
					if ($page == 1) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($num; $num <= $page + 4; $num++) {
						if ($page == $num) {
							$pagi .= "<li class='active clickable'><a attr-page='".$num."'>".$num.'</a></li>';
						} else {
							$pagi .= "<li class='waves-effect clickable'><a attr-page='".$num."'>".$num.'</a></li>';
						}
					}
					if ($page == $total_page) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				}
			}
			$pagi .= "</ul>";
		} else {
			$users .= "<p>There isn't any online user.</p>";
			$stat = 0;
		} 
		echo json_encode(
					array(
						"stat" => $stat, 
						"users" => $users,
						"pagination" => $pagi,
					)
				);
	}

	//////////////////
	// Friends List //
	//////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'friends'
		&& is_ajax()
		&& enable_friend_system() == 1
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['page'])
		&& !empty($_POST['page'])
	) {
		if($_POST['token'] == getToken(db_escape($_POST['userid'])) && $_POST['username'] == userName(db_escape($_POST['userid'])) && (isGuest(db_escape($_POST['userid'])) == 0 || (isGuest(db_escape($_POST['userid'])) == 1 && guest_friends() == 1))) {
			$page = db_escape($_POST['page']);
			$begin = ($page * 10) - 10;
			$users = "";
			$pagi = "";
			$stat = 1;
			
			$users_query = db_query("SELECT `friend_id` FROM `chat_friends` WHERE `user_id` = '$user_id' LIMIT $begin, 10");
			$total_query = db_query("SELECT `ID` FROM `chat_friends` WHERE `user_id` = '$user_id'");

			$total_users = mysqli_num_rows($total_query);
			$total_page = ceil($total_users / 10);

			if ($total_users > 0) {
				$users .= "<ul class='collection'>";
				while ($users_row = mysqli_fetch_array($users_query)) {
					$users .= "<li class='collection-item avatar'>";
					$users .= "<div id='user-profile-div' attr-id='".$users_row[0]."'>";
					if (empty(profilePicture($users_row[0]))) {
						$users .= "<i id='chat-search' class='clickable z-depth-1 material-icons circle chat-list-photo grey lighten-2'>person</i>";
						$users .= "<img id='chat-img-search' class='clickable z-depth-1 circle hide chat-list-photo' src=''>";
					} else {
						$users .= "<i id='chat-search' class='clickable z-depth-1 material-icons hide circle chat-list-photo grey lighten-2'>person</i>";
						$users .= "<img id='chat-img-search' class='clickable z-depth-1 circle chat-list-photo' src='".picture_destination().profilePicture($users_row[0])."'>";
					}
					$users .= "</div>";
					$users .= "<span class='title truncate'>".userName($users_row[0]).'</span>';
					if(isOnline($users_row[0])) {
						$users .= "<p id='online-status-all' attr-id='".$users_row[0]."' class='green-text text-darken-2'>Online</p>";
					} else {
						$users .= "<p id='online-status-all' attr-id='".$users_row[0]."' class='red-text text-darken-2'>Offline</p>";
					}
					$users .= "<a href='#' attr-id='".$users_row[0]."' id='remove-friend'>Remove Friend</a>";

					$users .= "<a href='#' attr-ID='".$users_row[0]."' class='pm secondary-content' style='margin-top:15px'><i class='material-icons clickable' style='vertical-align:middle;margin-left:10px' id='pm'>send</i></a>";
					$users .= '</li>';
				}
				$users .= "</ul>";
				$pagi .= '<ul class="pagination center-align friends-pagination">';
				$next_page = $page + 1;
				$prev_page = $page - 1;
				if ($total_page < 9) {
					if ($page == 1) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = 1; $i <= $total_page; $i++) {
						if ($page == $i) {
							$pagi .= "<li class='active clickable'><a attr-page='".$i."'>".$i.'</a></li>';
						} else {
							$pagi .= "<li class='waves-effect clickable'><a attr-page='".$i."'>".$i.'</a></li>';
						}
					}
					if ($page == $total_page) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} else {
					if ($page < 6) {
						if ($page == 1) {
							$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
							$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
						} else {
							$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
							$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
						}
						for ($i = 1; $i <= 9; $i++) {
							if ($page == $i) {
								$pagi .= "<a attr-page='".$i."'><li class='active clickable'>".$i.'</li></a>';
							} else {
								$pagi .= "<a attr-page='".$i."'><li class='waves-effect clickable'>".$i.'</li></a>';
							}
						}
						if ($page == $total_page) {
							$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
							$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
						} else {
							$pagi .= "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons clickable'>chevron_right</i></a></li>";
							$pagi .= "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons last clickable'>last_page</i></a></li>";
						}
					} elseif ($page > $total_page - 4) {
						if ($page == 1) {
							$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
							$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
						} else {
							$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
							$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
						}
						for ($i = $total_page - 8; $i <= $total_page; $i++) {
							if ($page == $i) {
								$pagi .= "<li class='active clickable'><a attr-page='".$i."'>".$i.'</a></li>';
							} else {
								$pagi .= "<li class='waves-effect clickable'><a attr-page='".$i."'>".$i.'</a></li>';
							}
						}
						if ($page == $total_page) {
							$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
							$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
						} else {
							$pagi .= "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
							$pagi .= "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
						}
					} else {
						$num = $page - 4;
						if ($page == 1) {
							$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
							$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
						} else {
							$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
							$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
						}
						for ($num; $num <= $page + 4; $num++) {
							if ($page == $num) {
								$pagi .= "<li class='active clickable'><a attr-page='".$num."'>".$num.'</a></li>';
							} else {
								$pagi .= "<li class='waves-effect clickable'><a attr-page='".$num."'>".$num.'</a></li>';
							}
						}
						if ($page == $total_page) {
							$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
							$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
						} else {
							$pagi .= "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
							$pagi .= "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
						}
					}
				}
				$pagi .= "</ul>";
			} else {
				$users .= "<p>You don't have any friends.</p>";
				$stat = 0;
			} 
			echo json_encode(
						array(
							"stat" => $stat, 
							"users" => $users,
							"pagination" => $pagi,
						)
					);
		}
	}

	/////////////////////////////
	// Online Users Pagination //
	/////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'online-search'
		&& is_ajax()
		&& isset($_POST['page'])
		&& !empty($_POST['page'])
	) {
		$page = db_escape($_POST['page']);
		$begin = ($page * 10) - 10;
		$users = "";
		$pagi = "";
		$stat = 1;

		
		if (guests_on_user_list() == 1) {
			$users_query = db_query("SELECT `username`, `ID` , `profile_pic` FROM `members` WHERE `activation` = 1 && `online` = 1 && `ID` != '$user_id' LIMIT $begin, 10");
			$total_query = db_query("SELECT `username`, `ID` , `profile_pic` FROM `members` WHERE `activation` = 1 && `online` = 1 && `ID` != '$user_id'");
		} else {
			$users_query = db_query("SELECT `username`, `ID` , `profile_pic` FROM `members` WHERE `activation` = 1 && `online` = 1 && `guest` = 0 && `ID` != '$user_id' LIMIT $begin, 10");
			$total_query = db_query("SELECT `username`, `ID` , `profile_pic` FROM `members` WHERE `activation` = 1 && `online` = 1 && `guest` = 0 && `ID` != '$user_id'");
		}
		$total_users = mysqli_num_rows($total_query);
		$total_page = ceil($total_users / 10);
		
	   
		if ($total_users > 0) {
			$users .= "<ul class='collection'>";
			while ($users_row = mysqli_fetch_array($users_query)) {
				$users .= "<li class='collection-item avatar'>";
				$users .= "<div id='user-profile-div' attr-id='".$users_row['ID']."'>";
				if (empty($users_row['profile_pic'])) {
					$users .= "<i id='chat-search' class='clickable z-depth-1 material-icons circle chat-list-photo grey lighten-2'>person</i>";
					$users .= "<img id='chat-img-search' class='clickable z-depth-1 circle hide chat-list-photo' src=''>";
				} else {
					$users .= "<i id='chat-search' class='clickable z-depth-1 material-icons hide circle chat-list-photo grey lighten-2'>person</i>";
					$users .= "<img id='chat-img-search' class='clickable z-depth-1 circle chat-list-photo' src='".picture_destination().$users_row['profile_pic']."'>";
				}
				$users .= "</div>";
				$users .= "<span class='title truncate'>".$users_row['username'].'</span>';
				$users .= "<a href='#' attr-id='".$users_row['ID']."' class='secondary-content pm' style='margin-top:15px'><i class='material-icons clickable' id='pm'>send</i></a>";
				$users .= '</li>';
			}
			$users .= "</ul>";
			$pagi .= '<ul class="pagination center-align online-pagination">';
			$next_page = $page + 1;
			$prev_page = $page - 1;
			if ($total_page < 9) {
				if ($page == 1) {
					$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
					$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
				} else {
					$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
					$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
				}
				for ($i = 1; $i <= $total_page; $i++) {
					if ($page == $i) {
						$pagi .= "<li class='active clickable'><a attr-page='".$i."'>".$i.'</a></li>';
					} else {
						$pagi .= "<li class='waves-effect clickable'><a attr-page='".$i."'>".$i.'</a></li>';
					}
				}
				if ($page == $total_page) {
					$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
					$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
				} else {
					$pagi .= "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
					$pagi .= "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
				}
			} else {
				if ($page < 6) {
					if ($page == 1) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = 1; $i <= 9; $i++) {
						if ($page == $i) {
							$pagi .= "<a attr-page='".$i."'><li class='active clickable'>".$i.'</li></a>';
						} else {
							$pagi .= "<a attr-page='".$i."'><li class='waves-effect clickable'>".$i.'</li></a>';
						}
					}
					if ($page == $total_page) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} elseif ($page > $total_page - 4) {
					if ($page == 1) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = $total_page - 8; $i <= $total_page; $i++) {
						if ($page == $i) {
							$pagi .= "<li class='active clickable'><a attr-page='".$i."'>".$i.'</a></li>';
						} else {
							$pagi .= "<li class='waves-effect clickable'><a attr-page='".$i."'>".$i.'</a></li>';
						}
					}
					if ($page == $total_page) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} else {
					$num = $page - 4;
					if ($page == 1) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($num; $num <= $page + 4; $num++) {
						if ($page == $num) {
							$pagi .= "<li class='active clickable'><a attr-page='".$num."'>".$num.'</a></li>';
						} else {
							$pagi .= "<li class='waves-effect clickable'><a attr-page='".$num."'>".$num.'</a></li>';
						}
					}
					if ($page == $total_page) {
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						$pagi .= "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						$pagi .= "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						$pagi .= "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				}
			}
			$pagi .= "</ul>";
		} else {
			$users .= "<p>There isn't any online user.</p>";
			$stat = 0;
		} 
		echo json_encode(
					array(
						"stat" => $stat, 
						"users" => $users,
						"pagination" => $pagi,
					)
				);
	}

	///////////////////////////////////
	// Creates Personal Message Room //
	///////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'pm'
		&& is_ajax()
		&& isset($_POST['uid'])
		&& !empty($_POST['uid'])
		&& isset($_POST['ui'])
		&& !empty($_POST['ui'])
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
	) {
		if($_POST["token"] == getToken(db_escape($_POST["ui"])) && userName(db_escape($_POST["ui"])) == $_POST["username"]) {
			$stat = 1;
			$target = db_escape($_POST['uid']); // Recepient User
			$target_un = userName($target);
			$pic = profilePicture($target);
			$user_id = db_escape($_POST['ui']); // User ID

			if(empty($pic)) {
				$pic_exist = 0;
			} else {
				$pic_exist = 1;
			}

			$time = time();

			$check = db_query("SELECT `ID`, `id_hash` FROM `chat_room` WHERE (`owner_id` = '$user_id' || `owner_id` = '$target') && `type` = 1");

			if ($username == $target) {
				// Check if the user is trying to send Personal Message to him/herself
				$status = 'error';
			} else {
				while ($row = mysqli_fetch_array($check)) {
					// Check every rows in the chat_members table if the chat was created before
					$ID = $row['ID'];
					$check2 = db_query("SELECT `ID` FROM `chat_members` WHERE `chat_room` = '$ID' && (`user_id` = '$target' || `user_id` = '$user_id')");

					if (mysqli_num_rows($check2) > 1) {
						// If yes, open the exist chat 

						$stat = 0;
						$crypted_chat_id = $row['id_hash'];
						$status = $crypted_chat_id;

						/* Update the Token */
						$token = bin2hex(openssl_random_pseudo_bytes(32));
						db_query("UPDATE `members` SET `token` = '$token' WHERE `ID` = '$user_id'");
						/********************/

						db_query("UPDATE `chat_members` SET `status` = 6 WHERE `user_id` = '$user_id' && `chat_room` = '$ID'");
						break;
					}
				}

				if ($stat == 1) {
					// If no, create a new chat

					$time = time();
					db_query("INSERT INTO `chat_room`(`owner_id`, `owner_name`, `type`, `last_message_time`, `time`) VALUES ('$user_id', '$username', '1', '$time', '$time')");
					$ID = mysqli_insert_id(db_connect());
					$crypted_chat_id = keymaker($ID);
					db_query("UPDATE `chat_room` SET id_hash = '$crypted_chat_id', chat_name = '$username|$target_un' WHERE ID = $ID");
					db_query("INSERT INTO `chat_members`(`chat_room`, `user_name`, `user_id`, `status`, `last_message_time`) VALUES ('$ID', '$username', '$user_id', 5, '$time')");
					db_query("INSERT INTO `chat_members`(`chat_room`, `user_id`, `user_name`, `status`, `last_message_time`) VALUES ('$ID', '$target', '$target_un', 0, '$time')");

					/* Update the Token */
					$token = bin2hex(openssl_random_pseudo_bytes(32));
					db_query("UPDATE `members` SET `token` = '$token' WHERE `ID` = '$user_id'");
					/********************/

					$status = $crypted_chat_id;
				}
			}
			echo json_encode(
					array(
						'token' => $token,
						'chat_id' => $crypted_chat_id,
						'picture' => picture_destination().$pic,
						'status' => $stat,
						'check_stat' => $status,
						'pic_exist' => $pic_exist,
						'chat_name' => $target_un
					)
				);
		}
	}

	///////////////////////////////
	// Number of Unread Messages //
	///////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'unread'
		&& is_ajax()
		&& isset($_POST['ui'])
		&& isset($_POST['hr'])
	) {
		if (save_messages() == 1) {
			$user_id = db_escape($_POST['ui']);    // User ID
			$room_hash = db_escape($_POST['hr']);    // Crypted Chat ID
			$room = chat_id($room_hash);    // Chat ID

			$unread_q = db_query("SELECT `ID` FROM `chat_unread` WHERE `chat_room` = '$room' && `usr_id` = '$user_id'");
			$unread_num = mysqli_num_rows($unread_q);

			echo $unread_num;
		} else {
			echo 0;
		}
	}

	///////////
	// LOGIN //
	///////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'login'
		&& is_ajax()
		&& isset($_POST['email'])
		&& isset($_POST['password'])
		&& !empty($_POST['email'])
		&& !empty($_POST['password'])
	) {
		$cookie_time = time() + (86400 * 30 * 12);
		$status = 1;
		$error = '';

		// change character set to utf8 and check it
		if (!mysqli_set_charset(db_connect(), 'utf8')) {
			$error = mysqli_error(db_connect());
			$status = 0;
		}

		// if no connection errors (= working database connection)
		if (!mysqli_connect_errno(db_connect())) {

			// escape the POST stuff
			$email = db_escape($_POST['email']);

			$result_of_login_check = db_query("SELECT `ID` ,`username`, `password`, `email` FROM `members` WHERE `email` = '$email' || `username` = '$email'");

			// if this user exists
			if (mysqli_num_rows($result_of_login_check) == 1) {

				// get result row (as an object)
				$result_row = mysqli_fetch_assoc($result_of_login_check);

				// User ID
				$user_id = $result_row['ID'];

				// the hash of that user's password
				if (password_verify($_POST['password'], $result_row['password'])) {
					$salt = bin2hex(openssl_random_pseudo_bytes(512));
					$hash = password_hash($salt, PASSWORD_DEFAULT);
					$token = bin2hex(openssl_random_pseudo_bytes(32));
					
					db_query("UPDATE `members` SET `salt` = '$salt', `token` = '$token' WHERE `ID` = $user_id");

					$userid = ($user_id + 412) * 137;    // Crypt User ID with some basic math operations
					setcookie('key', $hash, $cookie_time, '/');    // Set key cookie
					setcookie('user_id', $userid, $cookie_time, '/');    // Set user_id Cookie
					setcookie('login_token', md5(uniqid(mt_rand(), true)), $cookie_time, '/');    // Set user_id Cookie
				} else {
					$error = 'Wrong password. Please try again.';
					$status = 0;
				}
			} else {
				$error = 'We could not find any user.';
				$status = 0;
			}
		} else {
			$error = 'Database connection problem.';
			$status = 0;
		}
		echo json_encode(
				array(
					'stat' => $status,
					'error' => $error,
				)
			);
	}

	/////////////////
	// GUEST LOGIN //
	/////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'guest-login'
		&& is_ajax()
	) {
		$cookie_time = time() + (86400 * 30 * 12);
		$status = 1;
		$error = '';
		$time = time();

		// change character set to utf8 and check it
		if (!mysqli_set_charset(db_connect(), 'utf8')) {
			$error = mysqli_error(db_connect());
			$status = 0;
		}

		// if no connection errors (= working database connection)
		if (!mysqli_errno(db_connect())) {
			$user_password = bin2hex(openssl_random_pseudo_bytes(guest_pass_lenght() / 2));
			$user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);
			$escaped_hash = db_escape($user_password_hash);

			// check if user or email address already exists
			$first_insert = db_query("INSERT INTO `members` (`password`, `temp_pass`, `guest_confirmation`, `guest`, `registration_date`) VALUES('$escaped_hash', '$user_password', 0, 1, '$time')");

			// if user has been added successfully
			if ($first_insert) {
				$salt = bin2hex(openssl_random_pseudo_bytes(512));    // Create a new Salt
				$token = bin2hex(openssl_random_pseudo_bytes(32));    // Create a new Token
				$hash = password_hash($salt, PASSWORD_DEFAULT);    // Crypt the Salt
				$cookie_time = time() + (86400 * 30 * 12);    // Set Cookie Time
				$ID = mysqli_insert_id(db_connect());    // Get the User ID
				$username = guest_name_prefix().$ID;
				$email = guest_name_prefix().$ID.'@guest.com';
				db_query("UPDATE `members` SET `username` = '$username', `email` = '$email', `salt` = '$salt', `token` = '$token' WHERE `ID` = $ID");

				$ID = ($ID + 412) * 137;    // Crypt the User ID with a basic Math Operation
				setcookie('key', $hash, $cookie_time, '/');    // Set the Cookie
				setcookie('user_id', $ID, $cookie_time, '/');    // Set the Cookie
				setcookie('login_token', md5(uniqid(mt_rand(), true)), $cookie_time, '/');
				$status = 1;
			} else {
				$error = 'We could not register. Please try again.';
				$status = 0;
			}
		} else {
			$error = 'Could not connect to the database.';
			$status = 0;
		}
		echo json_encode(
				array(
					'stat' => $status,
					'error' => $error,
				)
			);
	}

	//////////////
	// REGISTER //
	//////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'register'
		&& is_ajax()
	) {
		$error = '';
		$status = 1;

		if (empty($_POST['email'])) {
			$error = 'Please enter your Email address.';
			$status = 0;
		} elseif (empty($_POST['pass']) || empty($_POST['repass'])) {
			$error = 'Please enter your password.';
			$status = 0;
		} elseif ($_POST['pass'] !== $_POST['repass']) {
			$error = 'Your passwords are not matched.';
			$status = 0;
		} elseif (strlen($_POST['pass']) < min_pass_lenght()) {
			if (min_pass_lenght() == 1) {
				$error = 'Your password must be at least 1 character.';
			} else {
				$error = 'Your password must be at least '.min_pass_lenght().' characters.';
			}
			$status = 0;
		} elseif (strlen($_POST['username']) < min_username_lenght()) {
			if (min_pass_lenght() == 1) {
				$error = 'Your username must be at least 1 character.';
			} else {
				$error = 'Your username must be at least '.min_username_lenght().' characters.';
			}
			$status = 0;
		} elseif (strlen($_POST['pass']) > max_pass_lenght()) {
			$error = 'Your password can not be longer than '.max_pass_lenght().' characters.';
			$status = 0;
		} elseif (strlen($_POST['username']) > max_username_lenght()) {
			$error = 'Your username can not be longer than '.max_username_lenght().' characters.';
			$status = 0;
		} elseif (strlen($_POST['email']) > max_email_lenght()) {
			$error = 'Your Email address can not be longer than '.max_email_lenght().' characters.';
			$status = 0;
		} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$error = 'Invalid Email address.';
			$status = 0;
		} elseif (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['username'])) {
			$error = 'Username can not include special characters.';
			$status = 0;
		} elseif ($_POST['username'] == 'system'
			|| $_POST['username'] == 'admin'
			|| strncasecmp($_POST['username'], guest_name_prefix(), strlen(guest_name_prefix())) == 0
			|| strncasecmp($_POST['username'], 'admin', 5) == 0
			|| strncasecmp($_POST['username'], 'system', 6) == 0
		) {
			$error = 'Invalid username.';
			$status = 0;
		} elseif (!empty($_POST['email'])
			&& strlen($_POST['email']) <= max_email_lenght()
			&& filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
			&& !empty($_POST['pass'])
			&& !empty($_POST['repass'])
			&& strlen($_POST['pass']) <= max_pass_lenght()
			&& strlen($_POST['pass']) >= min_pass_lenght()
			&& ($_POST['pass'] === $_POST['repass'])
			&& !empty($_POST['username'])
			&& strlen($_POST['username']) <= max_username_lenght()
			&& strlen($_POST['username']) >= min_username_lenght()
		) {

			// change character set to utf8 and check it
			if (!mysqli_set_charset(db_connect(), 'utf8')) {
				$error = mysqli_error(db_connect());
				$status = 0;
			}

			// if no connection errors (= working database connection)
			if (!mysqli_errno(db_connect())) {
				$username = db_escape($_POST['username']);    // Username
				$user_email = db_escape(strip_tags($_POST['email'], ENT_QUOTES));    // Email address

				$user_password = $_POST['pass'];    // Password

				$user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);
				$escaped_hash = db_escape($user_password_hash);

				// check if user or email address already exists
				$query_check_email = db_query("SELECT * FROM `members` WHERE `email` = '$user_email'");
				$query_check_user_name = db_query("SELECT * FROM `members` WHERE `username` = '$username'");

				if (mysqli_num_rows($query_check_email) == 1) {
					$error = 'This Email is already in use.';
					$status = 0;
				} elseif (mysqli_num_rows($query_check_user_name) == 1) {
					$error = 'This username is already in use.';
					$status = 0;
				} else {
					$time = time();
					// write new user's data into database
					$register = db_query("INSERT INTO `members` (`username`, `password`, `email`, `registration_date`) VALUES('$username', '$escaped_hash', '$user_email', '$time')");

					// if user has been added successfully
					if ($register) {
						$salt = bin2hex(openssl_random_pseudo_bytes(512));    // Create a new Salt
						$token = bin2hex(openssl_random_pseudo_bytes(32));    // Create a new Token
						$activation_code = bin2hex(openssl_random_pseudo_bytes(24));
						$hash = password_hash($salt, PASSWORD_DEFAULT);    // Crypt the Salt
						$cookie_time = time() + (86400 * 30 * 12);    // Set Cookie Time
						$user_id = mysqli_insert_id(db_connect());    // Get the User ID
						$valid_time = strtotime("+7 days", time());
						if(user_activation() == 1) {
							db_query("INSERT INTO `chat_user_activation` (`user_id`, `activation_code`, `valid_time`, `next_request`) VALUES('$user_id', '$activation_code', '$valid_time', '$time')");
							db_query("UPDATE `members` SET `salt` = '$salt', `token` = '$token', `activation` = '0' WHERE `ID` = $user_id");
						} else {
							db_query("UPDATE `members` SET `salt` = '$salt', `token` = '$token', `activation` = '1' WHERE `ID` = $user_id");
						}
						$userid = ($user_id + 412) * 137;    // Crypt the User ID with a basic Math Operation
						setcookie('key', $hash, $cookie_time, '/');    // Set the Cookie
						setcookie('user_id', $userid, $cookie_time, '/');    // Set the Cookie
						setcookie('login_token', md5(uniqid(mt_rand(), true)), $cookie_time, '/');
						
						if(user_activation() == 1) {
							$mail = new PHPMailer;
							$mail->isSMTP();
							$mail->Host = EMAIL_HOST;
							$mail->SMTPAuth = true;
							$mail->Username = EMAIL_USERNAME;
							$mail->Password = EMAIL_PASSWORD;
							$mail->SMTPSecure = EMAIL_SMTP_SECURE;
							$mail->Port = EMAIL_PORT;

							$mail->setFrom(EMAIL_ADDRESS, EMAIL_NAME.' - Account Verification');
							$mail->addAddress($user_email, $username);
							$mail->isHTML(true);

							$mail->Subject = 'Account Verification';
							if(substr(get_option("URL"), -1) == "/") {
								$url = get_option("URL").'action.php?act=activate&key='.$activation_code.'&id='.$user_id.'&name='.$username;
							} else {
								$url = get_option("URL").'/action.php?act=activate&key='.$activation_code.'&id='.$user_id.'&name='.$username;
							}
							$mail->Body    = 'Hello '.$username.',<br>Click <a href="'.$url.'">here</a> to activate your account.<br><br>Activation Code: '.$activation_code.'<br><br>This link is valid until '.date("d/m/Y H:i", $valid_time);
							$mail->AltBody = 'Hello '.$username.',\nGo to '.$url.' this page to activate your account.\n\nActivation Code: '.$activation_code.'\n\nThis link is valid until '.date("d/m/Y H:i", $valid_time);

							if(!$mail->send()) {
								$status = 0;
								$error = 'An error occured.';
								db_query("DELETE FROM `members` WHERE `ID` = '$user_id'");
							} else {
								$status = 1;
							}
						} else {
							$status = 1;
						}
					} else {
						$error = 'We could not register. Please try again.';
						$status = 0;
					}
				}
			} else {
				$error = 'Could not connect to the database.';
				$status = 0;
			}
		} else {
			$error = 'An unknown error has occured.';
			$status = 0;
		}

		echo json_encode(
				array(
					'stat' => $status,
					'error' => $error,
				)
			);
	}

	//////////////////////////////////////////
	// Search User to Send Personal Message //
	//////////////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'search'
		&& is_ajax()
		&& isset($_POST['search'])
		&& !empty($_POST['search'])
		&& isset($_POST['page'])
		&& !empty($_POST['page'])
	) {
		$search = db_escape($_POST['search']);    // Search Content
		$non_search = $_POST['search'];    // Search Content
		$page = db_escape($_POST['page']);
		$begin = ($page * 10) - 10;
		if(isset($_POST['type']) && $_POST['type'] == "all") {
			$query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' ORDER BY `username` ASC LIMIT $begin, 10");
			$total_query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%'");
		} else {
			$query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `username` != '$username' ORDER BY `username` ASC LIMIT $begin, 10");
			$total_query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `username` != '$username'");
		}
		$total_result = mysqli_num_rows($total_query);
		$total_page = ceil($total_result / 10);

		if ($total_result > 0) {
			if(isset($_POST['type']) && $_POST['type'] == "all") {
				echo '<ul class="collection z-depth-1">';
			} else {
				echo '<ul class="collection">';
			}

			while ($row = mysqli_fetch_array($query)) {
				if(isset($_POST['type']) && $_POST['type'] == "all") {
					echo '<li class="collection-item avatar admin-user-list">';
				} else {
					echo '<li class="collection-item avatar">';
				}
				echo "<div id='user-profile-div' attr-id='".$row['ID']."'>";
				if (empty($row['profile_pic'])) {
					echo '<i id="chat-search" class="clickable z-depth-1 material-icons circle chat-list-photo grey lighten-2">person</i>';
					echo '<img id="chat-img-search" class="clickable z-depth-1 circle hide chat-list-photo" src="">';
				} else {
					echo '<i id="chat-search" class="z-depth-1 material-icons circle chat-list-photo grey lighten-2 hide">person</i>';
					if(isset($_POST['type']) && $_POST['type'] == "all") {
						echo '<img id="chat-img-search" class="z-depth-1 circle chat-list-photo" src=".'.picture_destination().$row['profile_pic'].'">';
					} else {
						echo '<img id="chat-img-search" class="clickable z-depth-1 circle chat-list-photo" src="'.picture_destination().$row['profile_pic'].'">';
					}
				}
				echo "</div>";
				echo '<span class="title truncate">'.$row['username'].'</span>';
				if(isOnline($row['ID'])) {
					echo "<p class='green-text text-darken-2'>Online</p>";
				} else {
					echo "<p class='red-text text-darken-2'>Offline</p>";
				}
				if(isset($_POST['type']) && $_POST['type'] == "all") {
					echo '<a href="#" style="margin-top:15px" attr-id="'.$row['ID'].'" attr-name="'.$row['username'].'" class="secondary-content"><i class="material-icons clickable" id="add_admin">add</i></a>';
				} else {
					echo '<a href="#" style="margin-top:15px" attr-id="'.$row['ID'].'" class="secondary-content pm"><i class="material-icons clickable" id="pm">send</i></a>';
				}
				echo '</li>';
			}

			echo '</ul>';

			echo '<ul class="pagination center-align search-pagination">';
			$next_page = $page + 1;
			$prev_page = $page - 1;
			if ($total_page < 9) {
				if ($page == 1) {
					echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
					echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
				} else {
					echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
					echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
				}
				for ($i = 1; $i <= $total_page; $i++) {
					if ($page == $i) {
						echo "<li class='active'><a attr-page='".$i."'>".$i.'</a></li>';
					} else {
						echo "<li class='waves-effect'><a attr-page='".$i."'>".$i.'</a></li>';
					}
				}
				if ($page == $total_page) {
					echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
					echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
				} else {
					echo "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
					echo "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
				}
			} else {
				if ($page < 6) {
					if ($page == 1) {
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = 1; $i <= 9; $i++) {
						if ($page == $i) {
							echo "<a attr-page='".$i."'><li class='active'>".$i.'</li></a>';
						} else {
							echo "<a attr-page='".$i."'><li class='waves-effect'>".$i.'</li></a>';
						}
					}
					if ($page == $total_page) {
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} elseif ($page > $total_page - 4) {
					if ($page == 1) {
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = $total_page - 8; $i <= $total_page; $i++) {
						if ($page == $i) {
							echo "<li class='active'><a attr-page='".$i."'>".$i.'</a></li>';
						} else {
							echo "<li class='waves-effect'><a attr-page='".$i."'>".$i.'</a></li>';
						}
					}
					if ($page == $total_page) {
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} else {
					$num = $page - 4;
					if ($page == 1) {
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($num; $num <= $page + 4; $num++) {
						if ($page == $num) {
							echo "<li class='active'><a attr-page='".$num."'>".$num.'</a></li>';
						} else {
							echo "<li class='waves-effect'><a attr-page='".$num."'>".$num.'</a></li>';
						}
					}
					if ($page == $total_page) {
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				}
			}
			echo '</ul>';
		} else {    // If there is not any results
			echo '<div class="row">';
			echo '<div class="col s12">';
			echo '<div class="card teal lighten-2">';
			echo '<div class="card-content white-text">';
			echo '<p>We could not find anyone related to your search.</p>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}

	///////////////////////////////
	// Search User to Add Friend //
	///////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'search-friend'
		&& is_ajax()
		&& isset($_POST['search'])
		&& !empty($_POST['search'])
		&& isset($_POST['page'])
		&& !empty($_POST['page'])
		&& enable_friend_system() == 1
		&& (isGuest(user_id()) == 0 || (isGuest(user_id()) == 1 && guest_friends() == 1))
	) {
		$search = db_escape($_POST['search']);    // Search Content
		$non_search = $_POST['search'];    // Search Content
		$page = db_escape($_POST['page']);
		$begin = ($page * 10) - 10;

		$query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' ORDER BY `username` ASC LIMIT $begin, 10");
		$total_query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%'");

		$total_result = mysqli_num_rows($total_query);
		$total_page = ceil($total_result / 10);

		if ($total_result > 0) {
			echo '<ul class="collection">';

			while ($row = mysqli_fetch_array($query)) {
				echo '<li class="collection-item avatar">';
				echo "<div id='user-profile-div' attr-id='".$row['ID']."'>";
				if (empty($row['profile_pic'])) {
					echo '<i id="chat-search" class="clickable z-depth-1 material-icons circle chat-list-photo grey lighten-2">person</i>';
					echo '<img id="chat-img-search" class="clickable z-depth-1 circle hide chat-list-photo" src="">';
				} else {
					echo '<i id="chat-search" class="clickable z-depth-1 material-icons circle chat-list-photo grey lighten-2 hide">person</i>';
					echo '<img id="chat-img-search" class="clickable z-depth-1 circle chat-list-photo" src="'.picture_destination().$row['profile_pic'].'">';
				}
				echo "</div>";
				echo '<span class="title truncate">'.$row['username'].'</span>';
				if(isOnline($row['ID'])) {
					echo "<p class='green-text text-darken-2'>Online</p>";
				} else {
					echo "<p class='red-text text-darken-2'>Offline</p>";
				}
				if(isFriend(user_id(), $row['ID'])) {
					echo '<a href="#" style="margin-top:15px" id="remove-friend" class="secondary-content" attr-id="'.$row['ID'].'">Remove Friend</a>';
				} else {
					echo '<a href="#" style="margin-top:15px" id="add-friend" class="secondary-content" attr-id="'.$row['ID'].'">Add Friend</a>';
				}
				echo '</li>';
			}

			echo '</ul>';

			echo '<ul class="pagination center-align search-pagination">';
			$next_page = $page + 1;
			$prev_page = $page - 1;
			if ($total_page < 9) {
				if ($page == 1) {
					echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
					echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
				} else {
					echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
					echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
				}
				for ($i = 1; $i <= $total_page; $i++) {
					if ($page == $i) {
						echo "<li class='active'><a attr-page='".$i."'>".$i.'</a></li>';
					} else {
						echo "<li class='waves-effect'><a attr-page='".$i."'>".$i.'</a></li>';
					}
				}
				if ($page == $total_page) {
					echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
					echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
				} else {
					echo "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
					echo "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
				}
			} else {
				if ($page < 6) {
					if ($page == 1) {
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = 1; $i <= 9; $i++) {
						if ($page == $i) {
							echo "<a attr-page='".$i."'><li class='active'>".$i.'</li></a>';
						} else {
							echo "<a attr-page='".$i."'><li class='waves-effect'>".$i.'</li></a>';
						}
					}
					if ($page == $total_page) {
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} elseif ($page > $total_page - 4) {
					if ($page == 1) {
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = $total_page - 8; $i <= $total_page; $i++) {
						if ($page == $i) {
							echo "<li class='active'><a attr-page='".$i."'>".$i.'</a></li>';
						} else {
							echo "<li class='waves-effect'><a attr-page='".$i."'>".$i.'</a></li>';
						}
					}
					if ($page == $total_page) {
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} else {
					$num = $page - 4;
					if ($page == 1) {
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($num; $num <= $page + 4; $num++) {
						if ($page == $num) {
							echo "<li class='active'><a attr-page='".$num."'>".$num.'</a></li>';
						} else {
							echo "<li class='waves-effect'><a attr-page='".$num."'>".$num.'</a></li>';
						}
					}
					if ($page == $total_page) {
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				}
			}
			echo '</ul>';
		} else {    // If there is not any results
			echo '<div class="row">';
			echo '<div class="col s12">';
			echo '<div class="card teal lighten-2">';
			echo '<div class="card-content white-text">';
			echo '<p>We could not find anyone related to your search.</p>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}

	///////////////////////////////////////
	// Search User to Create a New Group //
	///////////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'grp-search'
		&& is_ajax()
		&& isset($_POST['search'])
		&& !empty($_POST['search'])
		&& isset($_POST['page'])
		&& !empty($_POST['page'])
	) {
		$search = db_escape($_POST['search']); // Search Content
		$non_search = $_POST['search'];    // Search Content
		$page = db_escape($_POST['page']);
		$begin = ($page * 10) - 10;
		if (invite_guests() == 1) {
			$query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `username` != '$username' ORDER BY `username` ASC LIMIT $begin, 10");
			$total_query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `username` != '$username'");
		} else {
			$query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `username` != '$username' && `guest` = 0 ORDER BY `username` ASC LIMIT $begin, 10");
			$total_query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `username` != '$username' && `guest` = 0");
		}
		$total_result = mysqli_num_rows($total_query);
		$total_page = ceil($total_result / 10);

		if ($total_result > 0) {
			// If there are results

			echo '<ul class="collection grp-search-usrs">';

			while ($row = mysqli_fetch_array($query)) {
				echo '<li class="collection-item avatar">';
				if (empty($row['profile_pic'])) {
					echo '<i id="chat-search" class="z-depth-1 material-icons circle chat-list-photo grey lighten-2">person</i>';
					echo '<img id="chat-img-search" class="z-depth-1 circle hide chat-list-photo" src="">';
				} else {
					echo '<i id="chat-search" class="z-depth-1 material-icons circle chat-list-photo grey lighten-2 hide">person</i>';
					echo '<img id="chat-img-search" class="z-depth-1 circle chat-list-photo" src="'.picture_destination().$row[1].'">';
				}
				echo '<span class="title truncate">'.$row[1].'</span>';
				if(isOnline($row[0])) {
					echo "<p class='green-text text-darken-2'>Online</p>";
				} else {
					echo "<p class='red-text text-darken-2'>Offline</p>";
				}
				echo '<a href="#" style="margin-top:15px" attr-id="'.$row[0].'" attr-un="'.$row[1].'" class="secondary-content add"><i class="material-icons small clickable" style="font-size:2.5rem;" id="add">add</i></a>';
				echo '</li>';
			}

			echo '</ul>';

			echo '<ul class="pagination center-align group-pagination">';
			$next_page = $page + 1;
			$prev_page = $page - 1;
			if ($total_page < 9) {
				if ($page == 1) {
					echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
					echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
				} else {
					echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
					echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
				}
				for ($i = 1; $i <= $total_page; $i++) {
					if ($page == $i) {
						echo "<li class='active'><a attr-page='".$i."'>".$i.'</a></li>';
					} else {
						echo "<li class='waves-effect'><a attr-page='".$i."'>".$i.'</a></li>';
					}
				}
				if ($page == $total_page) {
					echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
					echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
				} else {
					echo "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
					echo "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
				}
			} else {
				if ($page < 6) {
					if ($page == 1) {
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = 1; $i <= 9; $i++) {
						if ($page == $i) {
							echo "<a attr-page='".$i."'><li class='active'>".$i.'</li></a>';
						} else {
							echo "<a attr-page='".$i."'><li class='waves-effect'>".$i.'</li></a>';
						}
					}
					if ($page == $total_page) {
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} elseif ($page > $total_page - 4) {
					if ($page == 1) {
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = $total_page - 8; $i <= $total_page; $i++) {
						if ($page == $i) {
							echo "<li class='active'><a attr-page='".$i."'>".$i.'</a></li>';
						} else {
							echo "<li class='waves-effect'><a attr-page='".$i."'>".$i.'</a></li>';
						}
					}
					if ($page == $total_page) {
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons clickable last'>last_page</i></a></li>";
					}
				} else {
					$num = $page - 4;
					if ($page == 1) {
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($num; $num <= $page + 4; $num++) {
						if ($page == $num) {
							echo "<li class='active'><a attr-page='".$num."'>".$num.'</a></li>';
						} else {
							echo "<li class='waves-effect'><a attr-page='".$num."'>".$num.'</a></li>';
						}
					}
					if ($page == $total_page) {
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				}
			}
			echo '</ul>';
		} else {    // If there is not any results
			echo '<div class="row grp-s-none">';
			echo '<div class="col s12">';
			echo '<div class="card teal lighten-2">';
			echo '<div class="card-content white-text">';
			echo '<p>We could not find anyone related to your search.</p>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}

	/////////////////////////////////////
	// Search User to Invite the Group //
	/////////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'inv-search'
		&& is_ajax()
		&& isset($_POST['search'])
		&& !empty($_POST['search'])
	) {
		if(isset($_POST['type']) && $_POST['type'] == "admin") {
			$admin = 1;
		} else {
			$admin = 0;
		}
		$search = db_escape($_POST['search']);    // Search Content
		if($admin == 1) {
			if (invite_guests() == 1) {
				$search_q = db_query("SELECT `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' ORDER BY `username` ASC");
			} else {
				$search_q = db_query("SELECT `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `guest` = 0 ORDER BY `username` ASC");
			}
		} else {
			if (invite_guests() == 1) {
				$search_q = db_query("SELECT `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `username` != '$username' ORDER BY `username` ASC");
			} else {
				$search_q = db_query("SELECT `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `username` != '$username' && `guest` = 0 ORDER BY `username` ASC");
			}
		}
		$num = mysqli_num_rows($search_q);

		$search = db_escape($_POST['search']); // Search Content
		$non_search = $_POST['search'];    // Search Content
		$page = db_escape($_POST['page']);
		$begin = ($page * 10) - 10;

		if (invite_guests() == 1) {
			if($admin == 1) {
				$query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' ORDER BY `username` ASC LIMIT $begin, 10");
				$total_query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%'");
			} else {
				$query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `username` != '$username' ORDER BY `username` ASC LIMIT $begin, 10");
				$total_query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `username` != '$username'");
			}
		   
		} else {
			if($admin == 1) {
				$query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `guest` = 0 ORDER BY `username` ASC LIMIT $begin, 10");
				$total_query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `guest` = 0");
			} else {
				$query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `username` != '$username' && `guest` = 0 ORDER BY `username` ASC LIMIT $begin, 10");
				$total_query = db_query("SELECT `ID`, `username`, `profile_pic` FROM `members` WHERE `activation` = 1 && `username` LIKE '%$search%' && `username` != '$username' && `guest` = 0");
			}
		}
		$total_result = mysqli_num_rows($total_query);
		$total_page = ceil($total_result / 10);

		if ($total_result > 0) {
			// If there are results

			echo '<ul class="collection grp-search-usrs">';
			while ($row = mysqli_fetch_array($query)) {
				if($admin = 1) {
					echo '<li style="padding-left:20px!important" class="collection-item avatar">';
				} else {
					echo '<li class="collection-item avatar">';
				}
				if (empty($row['profile_pic'])) {
					echo '<i id="chat-search" class="z-depth-1 material-icons circle chat-list-photo grey lighten-2">person</i>';
					echo '<img id="chat-img-search" class="z-depth-1 circle hide chat-list-photo" src="">';
				} else {
					echo '<i id="chat-search" class="z-depth-1 material-icons circle chat-list-photo grey lighten-2 hide">person</i>';
					if(isset($_POST['type']) && $_POST['type'] == "admin") {
						echo '<img id="chat-img-search" class="z-depth-1 circle chat-list-photo" src=".'.picture_destination().$row[2].'">';
					} else {
						echo '<img id="chat-img-search" class="z-depth-1 circle chat-list-photo" src="'.picture_destination().$row[2].'">';
					}
				}
				echo '<span class="title truncate">'.$row[1].'</span>';
				if(isOnline($row[0])) {
					echo "<p class='green-text text-darken-2'>Online</p>";
				} else {
					echo "<p class='red-text text-darken-2'>Offline</p>";
				}
				echo '<a href="#" style="margin-top:15px" attr-id="'.$row[0].'" attr-un="'.$row[1].'" class="secondary-content inv-add"><i class="material-icons small clickable" style="font-size:2.5rem;" id="add">add</i></a>';
				echo '</li>';
			}

			echo '</ul>';

			echo '<ul class="pagination center-align inv-pagination">';
			$next_page = $page + 1;
			$prev_page = $page - 1;
			if ($total_page < 9) {
				if ($page == 1) {
					echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
					echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
				} else {
					echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
					echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
				}
				for ($i = 1; $i <= $total_page; $i++) {
					if ($page == $i) {
						echo "<li class='active'><a attr-page='".$i."'>".$i.'</a></li>';
					} else {
						echo "<li class='waves-effect'><a attr-page='".$i."'>".$i.'</a></li>';
					}
				}
				if ($page == $total_page) {
					echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
					echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
				} else {
					echo "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
					echo "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
				}
			} else {
				if ($page < 6) {
					if ($page == 1) {
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = 1; $i <= 9; $i++) {
						if ($page == $i) {
							echo "<a attr-page='".$i."'><li class='active'>".$i.'</li></a>';
						} else {
							echo "<a attr-page='".$i."'><li class='waves-effect'>".$i.'</li></a>';
						}
					}
					if ($page == $total_page) {
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$i."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} elseif ($page > $total_page - 4) {
					if ($page == 1) {
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($i = $total_page - 8; $i <= $total_page; $i++) {
						if ($page == $i) {
							echo "<li class='active'><a attr-page='".$i."'>".$i.'</a></li>';
						} else {
							echo "<li class='waves-effect'><a attr-page='".$i."'>".$i.'</a></li>';
						}
					}
					if ($page == $total_page) {
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				} else {
					$num = $page - 4;
					if ($page == 1) {
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='1'><i class='material-icons last clickable'>first_page</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$prev_page."'><i class='material-icons clickable'>chevron_left</i></a></li>";
					}
					for ($num; $num <= $page + 4; $num++) {
						if ($page == $num) {
							echo "<li class='active'><a attr-page='".$num."'>".$num.'</a></li>';
						} else {
							echo "<li class='waves-effect'><a attr-page='".$num."'>".$num.'</a></li>';
						}
					}
					if ($page == $total_page) {
						echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
						echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
					} else {
						echo "<li class='waves-effect'><a attr-page='".$next_page."'><i class='material-icons clickable'>chevron_right</i></a></li>";
						echo "<li class='waves-effect'><a attr-page='".$total_page."'><i class='material-icons last clickable'>last_page</i></a></li>";
					}
				}
			}
			echo '</ul>';
		} else {    // If there is not any results
			echo '<div class="row grp-s-none">';
			echo '<div class="col s12">';
			echo '<div class="card teal lighten-2">';
			echo '<div class="card-content white-text">';
			echo '<p>We could not find anyone related to your search.</p>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}

	////////////////////////////////////
	////// Add a Chip of the user //////
	// Insert the user into the array //
	////// On Group Creation Page //////
	////////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'add-chip'
		&& is_ajax()
		&& isset($_POST['uid'])
		&& !empty($_POST['uid'])
		&& isset($_POST['array'])
	) {
		$id = $_POST['uid'];
		$new_array = array();
		if (empty($_POST['array'])) {
			array_push($new_array, $user_id);
			$chip_users = $new_array;
		} else {
			$chip_users = json_decode($_POST['array']);
		}

		if (count($chip_users) < max_group_capacity() - 1) {
			if (!in_array($id, $chip_users)) {
				array_push($chip_users, $id);
				echo json_encode($chip_users);
			} else {
				echo 0;
			}
		} else {
			echo 1;
		}
	}

	////////////////////////////////////
	////// Add a Chip of the user //////
	// Insert the user into the array //
	/////// On Invite User Page ////////
	////////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'inv-add-chip'
		&& is_ajax()
		&& isset($_POST['uid'])
		&& !empty($_POST['uid'])
		&& isset($_POST['chatid'])
		&& !empty($_POST['chatid'])
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
		&& isset($_POST['array'])
	) {
		$uid = db_escape($_POST['uid']);    // Target Username
		$username = db_escape($_POST['username']);    // Username
		if(isset($_POST['userid']) && !empty($_POST['userid']) && userName($_POST['userid']) == $_POST['username'] && isset($_POST['type']) && $_POST['type'] == "admin" && isAdmin($_POST['userid'])) {
			$chat_id = db_escape($_POST['chatid']);
			$admin = 1;
		} else {
			$admin = 0;
			$chat_id = db_escape(chat_id($_POST['chatid']));
		}
		$chip_users = array();

		if (empty($_POST['array'])) {
			if($admin = 1) {
				$query = db_query("SELECT `user_id` FROM `chat_members` WHERE `chat_room` = '$chat_id' && `status` != 0 && `status` != 4");
			} else {
				$query = db_query("SELECT `user_id` FROM `chat_members` WHERE `chat_room` = '$chat_id' && `user_name` != '$username' && `status` != 0 && `status` != 4");
			}
			while ($row = mysqli_fetch_array($query)) {
				array_push($chip_users, $row['user_id']);
			}
		} else {
			foreach (json_decode($_POST['array']) as $val) {
				array_push($chip_users, $val);
			}
		}

		if (count($chip_users) < max_group_capacity() - 1) {
			if (!in_array($uid, $chip_users)) {
				array_push($chip_users, $uid);    // Add the user to the Array
				echo json_encode($chip_users);    // Convert the array into JSON
			} else {
				echo 0;    // user exists
			}
		} else {
			echo 1; // full group
		}
	}

	///////////////////////////////////////////
	// Remove the User from chip & the array //
	///////////////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'remove-chip'
		&& is_ajax()
		&& isset($_POST['uid'])
		&& !empty($_POST['uid'])
		&& isset($_POST['array'])
		&& !empty($_POST['array'])
	) {
		$uid = $_POST['uid'];
		$chip_users = json_decode($_POST['array']);    // User list in an Array

		if (($key = array_search($uid, $chip_users)) !== false) {
			unset($chip_users[$key]);    // Remove user from the array
			echo json_encode($chip_users);    // Conver array into JSON
		} else {
			echo 0; // Username does not exist in array
		}
	}

	////////////////////////
	// Create a New Group //
	////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'create-group'
		&& is_ajax()
		&& isset($_POST['gn'])
		&& !empty($_POST['gn'])
		&& isset($_POST['array'])
		&& !empty($_POST['array'])
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
	) {
		$name = db_escape($_POST['gn']);    // Group Name
		$users = json_decode($_POST['array']); // Selected Users (Added Chips)		
		$time = time();    // Current Time
		$token = db_escape($_POST['token']);        // User's Token
		$user_id = db_escape($_POST['userid']);        // User ID
		$status = 0;
		$error = '';
		$targetPath = '';
		$latest_id_hash = null;

		if (getToken($user_id) == $token) {
			if (max_group_name() >= strlen($name)) {
				if (!empty($_FILES['file']['type'])) {
					if(!file_exists(picture_net_destination())) {
						mkdir(picture_net_destination(), 0777);
					}
					$temporary = explode('.', $_FILES['file']['name']);
					$file_extension = end($temporary);

					$img_name = uniqid().'.'.$file_extension;

					if (in_array($_FILES['file']['type'], img_types())
						&& in_array($file_extension, img_extensions())
					) {
						if ($_FILES['file']['size'] < max_img_size()) {
							if ($_FILES['file']['error'] > 0) {
								$error = 'Return Code: '.$_FILES['file']['error'];
							} else {
								if (file_exists(picture_destination().$img_name)) {
									$error = $img_name.' already exists.';
								} else {
									if (!empty($prev)) {
										unlink(picture_net_destination().$prev);
									}
									$targetPath = picture_destination().$img_name;
									move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
									db_query("INSERT INTO `chat_room`(`owner_id`, `owner_name`, `chat_name`, `type`, `last_message_time`, `time`, `group_pic`) VALUES ('$user_id', '$username', '$name', '0', '$time', '$time', '$img_name')");
									$latest_id = mysqli_insert_id(db_connect());
									$latest_id_hash = keymaker($latest_id);
									db_query("UPDATE `chat_room` SET `id_hash` = '$latest_id_hash' WHERE `ID` = $latest_id");

									foreach ($users as $user) {
										$user_query = db_query("SELECT `username`, `profile_pic` FROM `members` WHERE `ID` = '$user' LIMIT 1");
										$user_row = mysqli_fetch_array($user_query);
										$usr_name = $user_row[0];
										$usr_pp = $user_row[1];
										db_query("INSERT INTO `chat_members`(`chat_room`, `user_name`, `user_id`, `last_message_time`) VALUES ('$latest_id', '$usr_name', '$user', '$time')");
									}
									$status = 1;
								}
							}
						} else {
							$error = 'Invalid file size.';
						}
					} else {
						$error = 'Invalid file type.';
					}
				} else {
					$status = 4;
					db_query("INSERT INTO `chat_room`(`owner_id`, `owner_name`, `chat_name`, `type`, `last_message_time`, `time`) VALUES ('$user_id', '$username', '$name', '0', '$time', '$time')");
					$latest_id = mysqli_insert_id(db_connect());
					$latest_id_hash = keymaker($latest_id);
					db_query("UPDATE `chat_room` SET `id_hash` = '$latest_id_hash' WHERE `ID` = $latest_id");

					foreach ($users as $user) {
						// Add each user to the group
						$user_query = db_query("SELECT `username`, `profile_pic` FROM `members` WHERE `ID` = '$user' LIMIT 1");
						$user_row = mysqli_fetch_array($user_query);
						$usr_name = $user_row[0];
						$usr_pp = $user_row[1];
						db_query("INSERT INTO `chat_members`(`chat_room`, `user_name`, `user_id`, `last_message_time`) VALUES ('$latest_id', '$usr_name', '$user', '$time')");
					}
				}
			} else {
				$error = 'Group name is too long.';
			}
		} else {
			$error = 'Invalid token.';
		}
		echo json_encode(
					array(
						'stat' => $status,
						'error' => $error,
						'file' => $targetPath,
						'chat_id' => $latest_id_hash,
					)
				);
	}

	////////////////////////////////////////////////////
	// Chat Type // 0 - Group // 1 - Personal Message //
	////////////////////////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'chat-type'
		&& is_ajax()
		&& isset($_POST['chat'])
		&& !empty($_POST['chat'])
	) {
		$chat_id = db_escape(chat_id($_POST['chat']));
		echo chatType($chat_id);
	}

	/////////////////////
	// Users in a Chat //
	/////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'chat-details'
		&& is_ajax()
		&& isset($_POST['chat'])
		&& !empty($_POST['chat'])
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
	) {
		$chat_id = db_escape(chat_id($_POST['chat']));
		$username = db_escape($_POST['username']);
		$user_id = db_escape($_POST['userid']);
		$token = db_escape($_POST['token']);
		$roomtype = chatType($chat_id);    // Room Type // 0 - Group // 1 - Personal Message
		$user_chips = array();
		$user_chips2 = array();
		$user_list = chat_users($username, $chat_id, $roomtype);
		$dropdown = "";
		$online = -1;
		
		if ($token === getToken($user_id)) {
			if (userName($user_id) == $username) {
				$query = db_query("SELECT `user_id`, `user_name` FROM `chat_members` WHERE `chat_room` = '$chat_id' && `user_name` != '$username' && `status` != 4 && `status` != 0");
				while ($row = mysqli_fetch_array($query)) {
					array_push($user_chips, $row[0]);
					array_push($user_chips2, $row[1]); 
				}
				
				if($roomtype == 1) {
					$name_q = db_query("SELECT `user_id` FROM `chat_members` WHERE `user_id` != '$user_id' && `chat_room` = '$chat_id' LIMIT 1");
					$name_r = mysqli_fetch_array($name_q);
					$target_id = $name_r['user_id'];
					
					if(isOnline($target_id)) {
						$online = 1;
					} else {
						$online = 0;
					}

					if(enable_friend_system($user_id) == 1 && isGuest($user_id) == 0 || (isGuest($user_id) == 1 && guest_friends() == 1)) {
						if(!isFriend($user_id, $target_id)) {
							$dropdown .= "<li><a href='#' id='add-friend' attr-id='".$target_id."'>Add Friend</a></li>";
						} else {
							$dropdown .= "<li><a href='#' id='remove-friend' attr-id='".$target_id."'>Remove Friend</a></li>";
						}
						$dropdown .= "<li class='divider'></li>";
					}
					$dropdown .= "<li><a href='#' id='leave-delete'>Delete Chat</a></li>";
					$dropdown .= "<li class='divider'></li>";
					$dropdown .= "<li><a href='#' class='btn clear-msg-btn' id='clear-chat'>Clear Chat</a></li>";
				} else {
					$dropdown .= "<li><a href='#' id='users'>Users</a></li>";
					$dropdown .= "<li class='divider'></li>";
					$dropdown .= "<li><a href='#' id='leave-delete'>Leave & Delete Chat</a></li>";
					$dropdown .= "<li class='divider'></li>";
					$dropdown .= "<li><a href='#' class='btn clear-msg-btn' id='clear-chat'>Clear Chat</a></li>";
				}

				if ($roomtype == 1) {
					$img = profilePicture($target_id);

					if (empty($img)) {
						$pic_stat = 0;
					} else {
						$pic_stat = 1;
					}
				} else { // Group
					$img = groupPicture($chat_id);
					if (empty($img)) {
						$pic_stat = 0;
					} else {
						$pic_stat = 1;
					}
				}

				echo json_encode(
						array(
							'user_list' => $user_list,
							'user_chips' => $user_chips,
							'user_chips_names' => $user_chips2,
							'dropdown' => $dropdown,
							'roomtype' => $roomtype,
							'pic' => $pic_stat,
							'url' => picture_destination().$img,
							'error' => "",
							'online' => $online
						)
					);
			} else {
				echo json_encode(array('user_list' => "",'user_chips' => "",'user_chips_names' => "",'dropdown' => "Error", 'roomtype' => "",'pic' => "",'url' => "",'error' => "Invalid Username."));
			}
		} else {
			echo json_encode(array('user_list' => "",'user_chips' => "",'user_chips_names' => "",'dropdown' => "Error", 'roomtype' => "",'pic' => "",'url' => "",'error' => "Invalid Token."));
		}
	}

	////////////////
	// Add Friend //
	////////////////

	elseif (isset($_GET['act'])
	&& $_GET['act'] == "add-friend"
	&& isset($_POST["userid"])
	&& !empty($_POST["userid"])
	&& isset($_POST["username"])
	&& !empty($_POST["username"])
	&& isset($_POST["token"])
	&& !empty($_POST["token"])
	&& isset($_POST["target_id"])
	&& !empty($_POST["target_id"])
	) {
		$username = db_escape($_POST['username']);
		$user_id = db_escape($_POST['userid']);
		$token = db_escape($_POST['token']);
		$target_id = db_escape($_POST['target_id']);

		if ($token === getToken($user_id)) {
			if (userName($user_id) == $username) {
				$query = db_query("SELECT `ID` FROM `chat_friends` WHERE `user_id` = '$user_id' && `friend_id` = '$target_id'");
				if(mysqli_num_rows($query) == 0) {
					db_query("INSERT INTO `chat_friends` (`user_id`, `friend_id`) VALUES ('$user_id', '$target_id')");
					echo "done";
				}
			} else {
				echo "error1";
			}
		} else {
			echo "error2";
		}
	}

	///////////////////
	// Remove Friend //
	///////////////////

	elseif (isset($_GET['act'])
	&& $_GET['act'] == "remove-friend"
	&& isset($_POST["userid"])
	&& !empty($_POST["userid"])
	&& isset($_POST["username"])
	&& !empty($_POST["username"])
	&& isset($_POST["token"])
	&& !empty($_POST["token"])
	&& isset($_POST["target_id"])
	&& !empty($_POST["target_id"])
	) {
		$username = db_escape($_POST['username']);
		$user_id = db_escape($_POST['userid']);
		$token = db_escape($_POST['token']);
		$target_id = db_escape($_POST['target_id']);

		if ($token === getToken($user_id)) {
			if (userName($user_id) == $username) {
				$query = db_query("SELECT `ID` FROM `chat_friends` WHERE `user_id` = '$user_id' && `friend_id` = '$target_id'");
				if(mysqli_num_rows($query) > 0) {
					db_query("DELETE FROM `chat_friends` WHERE `user_id` = '$user_id' && `friend_id` = '$target_id'");
					echo "done";
				}
			} else {
				echo "error1";
			}
		} else {
			echo "error2";
		}
	}

	//////////////////////////////
	// Delete Previous Messages //
	//////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'clear-chat'
		&& is_ajax()
		&& isset($_POST['user_id'])
		&& !empty($_POST['user_id'])
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['chat_id'])
		&& !empty($_POST['chat_id'])
	) {
		$chat_id = db_escape(chat_id($_POST['chat_id']));    // Chat ID
		$received_token = db_escape($_POST['token']);    // User's Token
		$user_id = db_escape($_POST['user_id']);    // User ID
		$status = 1;

		if (getToken($user_id) == $received_token) {
			if (userInRoom($user_id, $chat_id)) {
				$time = time();    // Current Time
				$token = bin2hex(openssl_random_pseudo_bytes(32));    // Create a New Token
				db_query("UPDATE `chat_members` SET `status` = 2, `last_load_time` = '$time' WHERE `user_id` = '$user_id' && `chat_room` = '$chat_id'");
				db_query("UPDATE `members` SET `token` = '$token' WHERE `ID` = '$user_id'");
			} else {
				$status = 0;
			}
		} else {
			$status = 0;
		}

		if ($status == 1) {
			echo $token;
		} else {
			echo 'error';
		}
	}

	/////////////////////////////////
	// Number of your current chat //
	/////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'chat-number'
		&& is_ajax()
		&& isset($_POST['user_id'])
		&& !empty($_POST['user_id'])
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['chatid'])
		&& !empty($_POST['chatid'])
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
	) {
		$received_token = db_escape($_POST['token']);
		$user_id = db_escape($_POST['user_id']);
		$username = db_escape($_POST['username']);
		$chat_id = db_escape(chat_id($_POST['chatid']));
		$stat = 0;
		$chat_num = 0;
		$user_num = 0;
		$error = '';

		if (getToken($user_id) == $received_token) {
			// Check the Tokens
			if($username == userName($user_id)) {
				$chat_number = db_query("SELECT `ID` FROM `chat_members` WHERE `user_id` = '$user_id' && (`status` = 1 || `status` = 2 || `status` = 4 || `status` = 6)");
				$user_number = db_query("SELECT `ID` FROM `chat_members` WHERE `chat_room` = '$chat_id'");
				$chat_num = mysqli_num_rows($chat_number);
				$user_num = mysqli_num_rows($user_number);
				$stat = 1;
			} else {
				$error = "Invalid username.";
			}
		} else {
			$error = 'Invalid token.';
		}

		echo json_encode(
					array(
						'chat' => $chat_num,
						'user' => $user_num,
						'room_type' => chatType($chat_id),
						'stat' => $stat,
						'error' => $error,
					)
				);
	}

	//////////////////////////////////////
	// Check if the user is in the room //
	//////////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'check-user'
		&& is_ajax()
		&& isset($_POST['data'])
		&& !empty($_POST['data'])
		&& isset($_POST['chatid'])
		&& !empty($_POST['chatid'])
	) {
		$user_id = db_escape($_POST['data']);    // User ID
		$chat_id = db_escape(chat_id($_POST['chatid']));    // Chat ID

		if (userInRoom($user_id, $chat_id)) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/////////////////////////////
	// Update User Status to 1 //
	/////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'update-user-stat'
		&& is_ajax()
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
		&& isset($_POST['chatid'])
		&& !empty($_POST['chatid'])
	) {
		$user_id = db_escape($_POST['userid']);    // User ID
		$chat_id = db_escape(chat_id($_POST['chatid']));    // Chat ID

		// Get the User's Status //
		$query = db_query("SELECT `status` FROM `chat_members` WHERE `user_id` = '$user_id' && `chat_room` = '$chat_id'");
		$row = mysqli_fetch_assoc($query);
		$status = $row['status'];
		///////////////////////////

		if ($status == 5) {
			db_query("UPDATE `chat_members` SET `status`=1 WHERE `user_id` = '$user_id' && `chat_room` = '$chat_id'");
		}
	}

	///////////////////////////////////
	// Invite New Users to the Group //
	///////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'invite-group'
		&& is_ajax()
		&& isset($_POST['chatid'])
		&& !empty($_POST['chatid'])
		&& isset($_POST['array'])
		&& !empty($_POST['array'])
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
	) {
		if(isAdmin($user_id) && isset($_POST['type']) && $_POST['type'] == "admin") {
			$chat_id = db_escape($_POST['chatid']);
		} else {
			$chat_id = db_escape(chat_id($_POST['chatid']));
		}
		
		$username = db_escape($_POST['username']); // Username
		$token = db_escape($_POST['token']); // User's Token
		$user_id = db_escape($_POST['userid']); // User ID
		$users = json_decode($_POST['array']); // Selected Users (Added Chips)
		$pics = array(); // Create an empty Array
		$usernames = array(); // Create an empty Array
		$ids = array(); // Create an empty Array
		$onlines = array(); // Create an empty Array
		$friends = array(); // Create an empty Array

		$result = db_query("SELECT `owner_name`, `chat_name` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
		$row = mysqli_fetch_array($result);
		$grp_name = $row[1];
		if (getToken($user_id) == $token) {
			if ($row[0] == $username) {
				$query = db_query("SELECT `user_id` FROM `chat_members` WHERE `chat_room` = '$chat_id' && `status` != 4 && `status` != 0");
				$sql_array = array();

				while ($row = mysqli_fetch_array($query)) {
					array_push($sql_array, $row['user_id']);
				}

				$new_users = array_diff($users, $sql_array);

				foreach ($new_users as $uid) {
					$query2 = db_query("SELECT `profile_pic`, `username`, `ID`, `online` FROM `members` WHERE `ID` = '$uid' LIMIT 1");
					$result2 = mysqli_fetch_assoc($query2);
					$pic = $result2['profile_pic'];
					$un = $result2['username'];
					$id = $result2['ID'];
					$online = $result2['online'];
					if(!empty($pic)) {
						array_push($pics, picture_destination().$pic);
					} else {
						array_push($pics, "");
					}		
					array_push($usernames, $un);
					array_push($ids, $id);
					if($online == 1) {
						array_push($onlines, "<p id='online-status-all' attr-id='".$id."' class='green-text text-darken-2'>Online</p>");
					} else {
						array_push($onlines, "<p id='online-status-all' attr-id='".$id."' class='red-text text-darken-2'>Offline</p>");
					}
					
				}

				echo json_encode(array('users' => $new_users, 'usernames' => $usernames, 'pics' => $pics, 'chat' => keymaker($chat_id), 'grp_name' => $grp_name, 'ids' => $ids, 'onlines' => $onlines));
			} else {
				echo 'error2';
			}
		} else {
			echo 'error';
		}
	}

	////////////
	// Logout //
	////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'logout'
	) {
		session_start();
 		session_destroy();

		header('Location: ../login.php');    // Redirect to main page
		exit;
		
		
	}

	//////////////////////////
	// Upload Profile Photo //
	//////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'upload-pp'
		&& is_ajax()
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
		&& isset($_POST['previous'])
		&& isset($_FILES['file']['type'])
	) {
		$token = db_escape($_POST['token']); // User's Token
		$user_id = db_escape($_POST['userid']); // User ID
		$prev = db_escape($_POST['previous']); // Previous Image
		$status = 0;
		$error = '';
		$targetPath = '';

		if (getToken($user_id) == $token) {
			if(!file_exists(picture_net_destination())) {
				mkdir(picture_net_destination(), 0777);
			}
			$temporary = explode('.', $_FILES['file']['name']);
			$file_extension = end($temporary);

			$img_name = uniqid().'.'.$file_extension;

			if (in_array($_FILES['file']['type'], img_types())
				&& in_array($file_extension, img_extensions())
			) {
				if ($_FILES['file']['size'] < max_img_size()) {
					if ($_FILES['file']['error'] > 0) {
						$error = 'Return Code: '.$_FILES['file']['error'];
					} else {
						if (file_exists(picture_destination().$img_name)) {
							$error = $img_name.' already exists.';
						} else {
							if (!empty($prev)) {
								unlink(picture_net_destination().$prev);
							}
							$targetPath = picture_destination().$img_name;
							move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
							db_query("UPDATE `members` SET `profile_pic` = '$img_name' WHERE `ID` = '$user_id' LIMIT 1"); // Update Profile Picture in Database
							$status = 1;
						}
					}
				} else {
					$error = 'Invalid file size.';
				}
			} else {
				$error = 'Invalid file type.';
			}
		} else {
			$error = 'Invalid token.';
		}

		echo json_encode(
					array(
						'stat' => $status,
						'error' => $error,
						'file' => $targetPath,
					)
				);
	}

	////////////////////////
	// Upload Group Photo //
	////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'upload-chat-pp'
		&& is_ajax()
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
		&& isset($_POST['previous'])
		&& isset($_FILES['file']['type'])
	) {
		if(isset($_POST['chatid']) && !empty($_POST['chatid'])) {
			$chat_id = db_escape(chat_id($_POST['chatid']));
		} elseif(isset($_POST['non_chatid']) && !empty($_POST['non_chatid'])) {
			$chat_id = db_escape($_POST['non_chatid']);
		} else {
			die;
		}
		$token = db_escape($_POST['token']);        // User's Token
		$username = db_escape($_POST['username']);
		$user_id = db_escape($_POST['userid']);        // User ID
	   
		$prev = db_escape($_POST['previous']);    // Previous Image
		$status = 0;
		$error = '';
		$targetPath = '';

		$result = db_query("SELECT `owner_id` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
		$row = mysqli_fetch_assoc($result);

		if (getToken($user_id) == $token && userName($user_id) == $username) {
			// Check the Tokens

			if ($row['owner_id'] == $user_id || isAdmin($user_id)) {
				if(!file_exists(picture_net_destination())) {
					mkdir(picture_net_destination(), 0777);
				}
				
				$temporary = explode('.', $_FILES['file']['name']);
				$file_extension = end($temporary);

				$img_name = uniqid().'.'.$file_extension;

				if (in_array($_FILES['file']['type'], img_types())
					&& in_array($file_extension, img_extensions())
				) {
					if ($_FILES['file']['size'] < max_img_size()) {
						if ($_FILES['file']['error'] > 0) {
							$error = 'Return Code: '.$_FILES['file']['error'];
						} else {
							if (file_exists(picture_destination().$img_name)) {
								$error = $img_name.' already exists.';
							} else {
								if (!empty($prev)) {
									unlink(picture_net_destination().$prev);
								}
								$targetPath = picture_destination().$img_name; // Target path where file is to be stored
								move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
								$check = db_query("UPDATE `chat_room` SET `group_pic` = '$img_name' WHERE `ID` = '$chat_id' LIMIT 1"); // Update Profile Picture in Database
								if($check && mysqli_affected_rows(db_connect()) == 1) {
									$status = 1;
								} else {
									$error = 'An error occured.';
								}
							}
						}
					} else {
						$error = 'Invalid file size.';
					}
				} else {
					$error = 'Invalid file type.';
				}
			} else {
				$error = 'Error.';
			}
		} else {
			$error = 'Invalid token.';
		}

		echo json_encode(
					array(
						'stat' => $status,
						'error' => $error,
						'file' => $targetPath,
					)
				);
	}

	////////////////////////
	// Remove Group Photo //
	////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'remove-chat'
		&& is_ajax()
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
	) {
		$token = db_escape($_POST['token']);
		$user_id = db_escape($_POST['userid']);
		$username = db_escape($_POST['username']);
		
		if(isAdmin($user_id) && isset($_POST['non_chatid']) && !empty($_POST['non_chatid'])) {
			$chat_id = db_escape($_POST['non_chatid']);
		} elseif(isset($_POST['chatid']) && !empty($_POST['chatid'])) {
			$chat_id = db_escape(chat_id($_POST['chatid']));
		} else {
			die;
		}

		$result = db_query("SELECT `owner_id` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
		$row = mysqli_fetch_assoc($result);
		
		if (getToken($user_id) == $token && $username == userName($user_id)) {

			if ($row['owner_id'] == $user_id) {
				$query = db_query("SELECT `group_pic` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
				$result = mysqli_fetch_assoc($query);
				$img = $result['group_pic'];

				$check = db_query("UPDATE `chat_room` SET `group_pic` = '' WHERE `ID` = '$chat_id' LIMIT 1"); // Update Group Picture in Database
				if($check) {
					unlink(picture_net_destination().$img);
				} else {
					echo 'error';
				}
			} else {
				echo 'error';
			}
		} else {
			echo 'error';
		}
	}

	//////////////////////////
	// Remove Profile Photo //
	//////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'remove-pp'
		&& is_ajax()
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
	) {
		$token = db_escape($_POST['token']);        // User's Token
		$user_id = db_escape($_POST['userid']);        // User ID

		if (getToken($user_id) == $token) {
			// Check the Tokens

			$query = db_query("SELECT `profile_pic` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
			$result = mysqli_fetch_assoc($query);
			$img = $result['profile_pic'];

			db_query("UPDATE `members` SET `profile_pic` = '' WHERE `ID` = '$user_id' LIMIT 1"); // Update Profile Picture in Database
			unlink(picture_net_destination().$img);
		} else {
			echo 'error';
		}
	}

	//////////////////////////
	// Gets Chat Picture //
	//////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'chat-pic'
		&& is_ajax()
		&& isset($_POST['chat'])
		&& !empty($_POST['chat'])
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
	) {
		$chat_id = db_escape(chat_id($_POST['chat']));        // Chat ID
		$username = db_escape($_POST['username']);        // Username

		$query = db_query("SELECT `chat_name`, `type` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
		$result = mysqli_fetch_assoc($query);

		if ($result['type'] == 1) {
			$name = explode('|', $result['chat_name']);

			if ($username == $name[0]) {
				$cname = $name[1];
			} else {
				$cname = $name[0];
			}

			$img_query = db_query("SELECT `profile_pic` FROM `members` WHERE `username` = '$cname' LIMIT 1");
			$img_result = mysqli_fetch_assoc($img_query);
			$img = $img_result['profile_pic'];

			if (empty($img)) {
				$pic_stat = 0;
			} else {
				$pic_stat = 1;
			}

			echo json_encode(
					array(
						'pic' => $pic_stat,
						'url' => picture_destination().$img,
					)
				);
		} else { // Group

			$img_query = db_query("SELECT `group_pic` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
			$img_result = mysqli_fetch_assoc($img_query);
			$img = $img_result['group_pic'];

			if (empty($img)) {
				$pic_stat = 0;
			} else {
				$pic_stat = 1;
			}

			echo json_encode(
					array(
						'pic' => $pic_stat,
						'url' => picture_destination().$img,
					)
				);
		}
	}

	////////////////////////
	// Loads User Profile //
	////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'load-user-profile'
		&& is_ajax()
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
	) {
		$user_id = db_escape($_POST['userid']);
		$result = db_query("SELECT `profile_pic`, `username`, `user_status` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
		if(mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);

			$pic = $row['profile_pic'];
			$user_status_text = $row['user_status'];
			$title = 'User Profile';

			if (empty($pic)) {
				$photo = 'person';
				$photo_exist = 0;
			} else {
				$photo_exist = 1;
				$photo = '';
			}
				
			echo "<span class='card-title grey-text text-darken-4'><span class='settings-title'>".$title."</span><i class='material-icons right close-chat-settings clickable-icon'>close</i></span>";
			echo "<div class='edit-chat'>";
				if($photo_exist == 1) {
					echo "<i id='group' class='z-depth-1 material-icons circle big-pp large grey lighten-2 hide'>".$photo."</i>";
					echo "<img id='group-img' class='z-depth-1 circle' width='175' height='175' src='".picture_destination().$pic."'>";
				} else {
					echo "<i id='group' class='z-depth-1 material-icons circle big-pp large grey lighten-2'>".$photo."</i>";
					echo "<img id='group-img' class='z-depth-1 circle hide' width='175' height='175' src=''>";
				}
		
			echo "</div>";
			echo "<h5 class='center'>".$row['username']."</h5>";
			if(enable_user_status() == 1) {
				if(empty($user_status_text)) {
					echo "<div id='user_status' class='user_status'>".get_option("DEFAULT_STATUS")."</div>";
				} else {
					echo "<div id='user_status' class='user_status'>".$user_status_text."</div>";
				}
			}
		} else {
			echo -1;
		}
	}

	/////////////////////////
	// Loads Chat Settings //
	/////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'load-chat-settings'
		&& is_ajax()
		&& isset($_POST['chat'])
		&& !empty($_POST['chat'])
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
	) {
		$chat_id = db_escape(chat_id($_POST['chat']));        // Chat ID
		$username = db_escape($_POST['username']);        // Username
		$user_id = db_escape($_POST['userid']);
		$token = db_escape($_POST['token']);
		if(getToken($user_id) == $token && userName($user_id) == $username) {
			$type = chatType($chat_id);

			if ($type == 0) {
				$result = db_query("SELECT `owner_name`,`group_pic`,`chat_name` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
			} else {
				$name_q = db_query("SELECT `chat_name` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
				$name_res = mysqli_fetch_assoc($name_q);

				$name = explode('|', $name_res['chat_name']);

				if ($username == $name[0]) {
					$cname = $name[1];
				} else {
					$cname = $name[0];
				}

				$grp_name = $cname;

				$result = db_query("SELECT `profile_pic`, `ID` FROM `members` WHERE `username` = '$cname' LIMIT 1");
			}

			$row = mysqli_fetch_assoc($result);

			if ($type == 0) {
				$pic = $row['group_pic'];
				$title = 'Group Profile';
				$grp_name = $row['chat_name'];

				if ($row['owner_name'] == $username) {
					$edit_photo = 1;
				} else {
					$edit_photo = 0;
				}
			} else {
				$pic = $row['profile_pic'];
				$title = 'User Profile';
				$edit_photo = 0;
			}

			if (empty($pic)) {
				$photo_exist = 0;
				if ($type == 0) {
					$photo = 'group';
				} else {
					$photo = 'person';
				}
			} else {
				$photo_exist = 1;
				$photo = '';
			}
			
			echo "<span class='card-title grey-text text-darken-4'><span class='settings-title'>".$title."</span><i class='material-icons right close-chat-settings clickable-icon'>close</i></span>";
			echo "<div class='edit-chat'>";
				if($type == 0 && $edit_photo == 1) {
					echo "<ul id='dropdown5' class='dropdown-content'>";
						echo "<li><a href='#' id='chat-upload-photo'>Upload Photo</a></li>";
						echo "<input type='file' accept='image/*' id='upload-chat' style='display:none;' />";
						echo "<div id='pp-act-grp-div' class='hide'>";
							echo "<li class='divider'></li>";
							echo "<li><a href='#' id='chat-remove-photo'>Remove Photo</a></li>";
						echo "</div>";
					echo "</ul>";
					echo "<a class='dropdown-button cdropdown5' href='#' id='cdropdown5'><div class='chat-change-pp'>Change Group Photo</div></a>";
				}
				if($photo_exist == 1) {
					echo "<i id='group' class='z-depth-1 material-icons circle big-pp large grey lighten-2 hide'>".$photo."</i>";
					echo "<img id='group-img' class='z-depth-1 circle' width='175' height='175' src='".picture_destination().$pic."'>";
				} else {
					echo "<i id='group' class='z-depth-1 material-icons circle big-pp large grey lighten-2'>".$photo."</i>";
					echo "<img id='group-img' class='z-depth-1 circle hide' width='175' height='175' src=''>";
				}
				
			echo "</div>";
			if($type == 0 && $edit_photo == 1) {
				echo "<ul id='save-chat-ul'><li><a id='save-chat-photo' class='waves-effect waves-light btn'>Save</a><a id='discard-chat-photo' class='waves-effect waves-light btn'>Discard</a></li></ul>";
			}
			if($type == 0) {
				echo "<div class='group-name'>";
					echo "<h4>";
						echo "<div id='grp-name' class='truncate'>".$grp_name."</div>";
						if($edit_photo == 1) {
							echo "<input type='text' id='grp-name-input' class='hide'>";
							echo "<a class='waves-effect waves-grey btn-flat circle edit-name-btns hide' id='reset-group-name'><i class='material-icons circle teal-text text-darken-2 clickable' style='font-size:1.6rem;'>close</i></a>";
							echo "<a class='waves-effect waves-grey btn-flat circle edit-name-btns hide' id='confirm-group-name'><i class='material-icons circle teal-text text-darken-2 clickable' style='font-size:1.6rem;'>done</i></a>";
							echo "<a class='waves-effect waves-grey btn-flat circle edit-name-btns' id='edit-group-name'><i class='material-icons circle teal-text text-darken-2 clickable' style='font-size:1.6rem;'>edit</i></a>";
						}
					echo "</h4>";
				echo "</div>";
			} else {
				$user_status_text = user_status($row['ID']);
				if(empty($user_status_text)) {
					echo "<div id='user_status' class='user_status'>".get_option("DEFAULT_STATUS")."</div>";
				} else {
					echo "<div id='user_status' class='user_status'>".user_status($row['ID'])."</div>";
				}
				echo '<p class="card-title">Interests :</p>';
				/* User's interests */
				echo '<ul id="user_interests" class="user_interests">';
					$interests = user_interests($row['ID']);
					if(is_null($interests) || empty($interests)){
						echo '<p>No Interests</p>';
					}else{
						foreach($interests as $interest){
							echo '<li class="interest">'.$interest.'</li>';
						} 
					}
					
				echo '</ul>';
			}
		}
	}

	////////////////////////////////////////////////
	// Deletes Group Picture While Deleting Group //
	////////////////////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'delete-group-pic'
		&& isset($_GET['chat_id'])
		&& !empty($_GET['chat_id'])
		&& isset($_GET['usr_token'])
		&& !empty($_GET['usr_token'])
		&& isset($_GET['chat_token'])
		&& !empty($_GET['chat_token'])
		&& isset($_GET['user_id'])
		&& !empty($_GET['user_id'])
		&& isset($_GET['user_name'])
		&& !empty($_GET['user_name'])
	) {
		$chat_id = db_escape(chat_id($_GET['chat_id']));        // Chat ID
		$usr_token = $_GET['usr_token'];
		$chat_token = $_GET['chat_token'];
		$user_id = $_GET['user_id'];
		$username = $_GET['user_name'];
		$img = groupPicture($chat_id);
		$type = chatType($chat_id);
		$stat = 1;
		if ($usr_token == getToken($user_id)) {
			$usrname_query = db_query("SELECT `username` FROM `members` WHERE `token` = '$usr_token' && `ID` = '$user_id' LIMIT 1");
			$row = mysqli_fetch_array($usrname_query);
			$sql_username = $row[0];
			if($sql_username == $username) {
				$query = db_query("SELECT `temp_token` FROM `chat_room` WHERE `ID` = '$chat_id'");
				$result = mysqli_fetch_assoc($query);
				$temp_token = $result['temp_token'];
				
				if ($chat_token == $temp_token) {
					$id = db_escape($_GET['chat_id']);
					$dir = share_net_destination().'/'.$id.'/';
					$result = db_query("SELECT `ID` FROM `chat_room` WHERE `id_hash` = '$id'");
					if (archive_files() == 0 && file_exists($dir) && mysqli_num_rows($result) == 1) {
						if (!delete_directory($dir)) {
							$stat = 0;
						} else {
							$stat = 1;
						}
					}

					if ($type == 0) {
						$query2 = db_query("SELECT `group_pic` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
						$result2 = mysqli_fetch_assoc($query2);
						$img = $result2['group_pic'];
						if (!empty($img) && file_exists(picture_net_destination().$img)) {
							unlink(picture_net_destination().$img);
						}
						if ($stat == 1) {
							echo 1;
							exit();
						}
					} else {
						if ($stat == 1) {
							echo 1;
							exit();
						}
					}
				} else {
					echo 0;
					exit();
				}
			} else {
				echo 0;
				exit();
			}
		} else {
			echo 0;
			exit();
		}
	}

	////////////////////////////
	// Changes Guest's Status //
	////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'update-guest'
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
	) {
		$token = $_POST['token'];
		$user_id = $_POST['userid'];
		if ($token === getToken($user_id)) {
			db_query("UPDATE `members` SET `guest_confirmation` = 1, `temp_pass` = NULL WHERE `ID` = '$user_id' LIMIT 1");
			echo 1;
		} else {
			echo 0;
		}
	}

	/////////////////
	// Share Photo //
	/////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'share-photo'
		&& is_ajax()
		&& isset($_POST['share-photo-token'])
		&& !empty($_POST['share-photo-token'])
		&& isset($_POST['share-photo-userid'])
		&& !empty($_POST['share-photo-userid'])
		&& isset($_POST['share-photo-username'])
		&& !empty($_POST['share-photo-username'])
		&& isset($_POST['share-photo-room'])
		&& !empty($_POST['share-photo-room'])
	) {
		$token = db_escape($_POST['share-photo-token']);        // User's Token
		$user_id = db_escape($_POST['share-photo-userid']);        // User ID
		$username = db_escape($_POST['share-photo-username']);        // Username
		$chat_id = db_escape(chat_id($_POST['share-photo-room']));        // Username
		$nonchat_id = db_escape($_POST['share-photo-room']);        // Username
		$total = count($_FILES['share-photo']['name']);
		$status = array();
		$stat = 0;
		$error = array();
		$targetPath = '';
		$all_target = array();
		$err = '';
		if (!file_exists(share_destination()."/../")) {
			mkdir(share_destination()."/../", 0777);
		}

		if ($total > 0 && share_photo() == 1) {
			if ($total <= max_photo()) {
				if (getToken($user_id) == $token) {
					foreach ($_FILES['share-photo']['name'] as $key => $val) {
						$temporary = explode('.', $_FILES['share-photo']['name'][$key]);
						$file_extension = end($temporary);

						$img_name = uniqid().'.'.$file_extension;

						if (in_array($_FILES['share-photo']['type'][$key], photo_types())
							&& in_array($file_extension, photo_extensions())
						) {
							if ($_FILES['share-photo']['size'][$key] < max_photo_size()) {
								if ($_FILES['share-photo']['error'][$key] > 0) {
									array_push($error, 'Return Code: '.$_FILES['share-photo']['error'][$key]);
									array_push($status, 0);
									array_push($all_target, '');
								} else {
									if (file_exists(share_destination().$img_name)) {
										$img_name = uniqid().$img_name;
									}
									if (in_array($_FILES['share-photo']['type'][$key], photo_types()) && in_array($file_extension, photo_extensions())) {
										$target = share_destination().'/'.$nonchat_id.'/'.$username;
										if (!file_exists(share_destination())) {
											mkdir(share_destination(), 0777);
										}
										if (!file_exists(share_destination().'/'.$nonchat_id)) {
											mkdir(share_destination().'/'.$nonchat_id, 0777);
										}
										if (!file_exists($target)) {
											mkdir($target, 0777);
										}
										$targetPath = $target.'/'.$img_name; // Target path where file is to be stored
										move_uploaded_file($_FILES['share-photo']['tmp_name'][$key], $targetPath);
										array_push($all_target, $targetPath);
										array_push($status, 1);
									}
								}
							} else {
								array_push($error, 'Invalid file size.');
								array_push($status, 0);
								array_push($all_target, '');
							}
						} else {
							array_push($error, 'Invalid file type.');
							array_push($status, 0);
							array_push($all_target, '');
						}
					}
				} else {
					for ($i = 0;$i < $total;$i++) {
						array_push($status, 0);
						array_push($error, 'Invalid token.');
					}
				}
			} else {
				if (max_photo() == 1) {
					$err = 'You cannot send more than 1 file.';
				} else {
					$err = 'You cannot send more than '.max_photo().' files.';
				}
			}

			echo json_encode(
						array(
							'stat' => $status,
							'error' => $error,
							'file' => $all_target,
							'err' => $err,
						)
					);
		}
	}

	/////////////////
	// Share Video //
	/////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'share-video'
		&& is_ajax()
		&& isset($_POST['share-video-token'])
		&& !empty($_POST['share-video-token'])
		&& isset($_POST['share-video-userid'])
		&& !empty($_POST['share-video-userid'])
		&& isset($_POST['share-video-username'])
		&& !empty($_POST['share-video-username'])
		&& isset($_POST['share-video-room'])
		&& !empty($_POST['share-video-room'])
	) {
		$token = db_escape($_POST['share-video-token']);        // User's Token
		$user_id = db_escape($_POST['share-video-userid']);        // User ID
		$username = db_escape($_POST['share-video-username']);        // Username
		$chat_id = db_escape(chat_id($_POST['share-video-room']));        // Username
		$nonchat_id = db_escape($_POST['share-video-room']);        // Username
		$total = count($_FILES['share-video']['name']);
		$status = array();
		$error = array();
		$targetPath = '';
		$all_target = array();
		$mimes = array();
		$err = '';
		if (!file_exists(share_destination()."/../")) {
			mkdir(share_destination()."/../", 0777);
		}

		if ($total > 0 && share_video() == 1) {
			if ($total <= max_video()) {
				if (getToken($user_id) == $token) {
					foreach ($_FILES['share-video']['name'] as $key => $val) {
						$temporary = explode('.', $_FILES['share-video']['name'][$key]);
						$file_extension = end($temporary);
						$video_name = uniqid().'.'.$file_extension;
						if (in_array($_FILES['share-video']['type'][$key], video_types()) && in_array($file_extension, video_extensions())) {
							if ($_FILES['share-video']['size'][$key] < max_video_size()) {
								if ($_FILES['share-video']['error'][$key] > 0) {
									array_push($error, 'Return Code: '.$_FILES['share-video']['error'][$key]);
									array_push($status, 0);
									array_push($all_target, '');
									array_push($mimes, '');
								} else {
									if (file_exists(share_destination().$video_name)) {
										$video_name = uniqid().$video_name;
									}

									$target = share_destination().'/'.$nonchat_id.'/'.$username;
									if (!file_exists(share_destination())) {
										mkdir(share_destination(), 0777);
									}
									if (!file_exists(share_destination().'/'.$nonchat_id)) {
										mkdir(share_destination().'/'.$nonchat_id, 0777);
									}
									if (!file_exists($target)) {
										mkdir($target, 0777);
									}
									$targetPath = $target.'/'.$video_name; // Target path where file is to be stored
									array_push($all_target, $targetPath);
									move_uploaded_file($_FILES['share-video']['tmp_name'][$key], $targetPath);
									array_push($status, 1);
									array_push($mimes, $_FILES['share-video']['type'][$key]);
								}
							} else {
								array_push($error, 'Invalid file size.');
								array_push($status, 0);
								array_push($all_target, '');
								array_push($mimes, '');
							}
						} else {
							array_push($error, 'Invalid file type.');
							array_push($status, 0);
							array_push($all_target, '');
							array_push($mimes, '');
						}
					}
				} else {
					for ($i = 0;$i < $total;$i++) {
						array_push($status, 0);
						array_push($error, 'Invalid token.');
					}
				}
			} else {
				if (max_video() == 1) {
					$err = 'You cannot send more than 1 file.';
				} else {
					$err = 'You cannot send more than '.max_video().' files.';
				}
			}

			echo json_encode(
						array(
							'stat' => $status,
							'error' => $error,
							'file' => $all_target,
							'type' => $mimes,
							'err' => $err,
						)
					);
		}
	}

	////////////////
	// Share File //
	////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'share-file'
		&& is_ajax()
		&& isset($_POST['share-file-token'])
		&& !empty($_POST['share-file-token'])
		&& isset($_POST['share-file-userid'])
		&& !empty($_POST['share-file-userid'])
		&& isset($_POST['share-file-username'])
		&& !empty($_POST['share-file-username'])
		&& isset($_POST['share-file-room'])
		&& !empty($_POST['share-file-room'])
	) {
		$token = db_escape($_POST['share-file-token']);        // User's Token
		$user_id = db_escape($_POST['share-file-userid']);        // User ID
		$username = db_escape($_POST['share-file-username']);        // Username
		$chat_id = db_escape(chat_id($_POST['share-file-room']));        // Username
		$nonchat_id = db_escape($_POST['share-file-room']);        // Username
		$status = array();
		$error = array();
		$targetPath = '';
		$all_target = array();
		$total = count($_FILES['share-file']['name']);
		$err = '';
		if (!file_exists(share_destination()."/../")) {
			mkdir(share_destination()."/../", 0777);
		}

		if ($total > 0 && share_file() == 1) {
			if ($total <= max_file()) {
				if (getToken($user_id) == $token) {
					foreach ($_FILES['share-file']['name'] as $key => $val) {
						$temporary = explode('.', $_FILES['share-file']['name'][$key]);
						$file_extension = end($temporary);
						$file_name = uniqid().'.'.$file_extension;
						if (in_array($file_extension, file_extensions())) {
							if ($_FILES['share-file']['size'][$key] < max_file_size()) {
								if ($_FILES['share-file']['error'][$key] > 0) {
									array_push($error, 'Return Code: '.$_FILES['share-file']['error'][$key]);
									array_push($status, 0);
									array_push($all_target, '');
								} else {
									if (file_exists(share_destination().$file_name)) {
										$file_name = uniqid().$file_name;
									}
									$target = share_destination().'/'.$nonchat_id.'/'.$username;
									if (!file_exists(share_destination())) {
										mkdir(share_destination(), 0777);
									}
									if (!file_exists(share_destination().'/'.$nonchat_id)) {
										mkdir(share_destination().'/'.$nonchat_id, 0777);
									}
									if (!file_exists($target)) {
										mkdir($target, 0777);
									}
									$targetPath = $target.'/'.$file_name; // Target path where file is to be stored
									array_push($all_target, $targetPath);
									move_uploaded_file($_FILES['share-file']['tmp_name'][$key], $targetPath);
									array_push($status, 1);
								}
							} else {
								array_push($error, 'Invalid file size.');
								array_push($status, 0);
								array_push($all_target, '');
							}
						} else {
							array_push($error, 'Invalid file type.');
							array_push($status, 0);
							array_push($all_target, '');
						}
					}
				} else {
					for ($i = 0;$i < $total;$i++) {
						array_push($status, 0);
						array_push($error, 'Invalid token.');
					}
				}
			} else {
				if (max_file() == 1) {
					$err = 'You cannot send more than 1 file.';
				} else {
					$err = 'You cannot send more than '.max_file().' files.';
				}
			}

			echo json_encode(
						array(
							'stat' => $status,
							'error' => $error,
							'file' => $all_target,
							'err' => $err,
						)
					);
		}
	}

	/////////////////////
	// Send Voice Note //
	/////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'voice-note'
		&& is_ajax()
		&& voice_notes() == 1
		&& isset($_POST['voice-recorder-token'])
		&& !empty($_POST['voice-recorder-token'])
		&& isset($_POST['voice-recorder-userid'])
		&& !empty($_POST['voice-recorder-userid'])
		&& isset($_POST['voice-recorder-username'])
		&& !empty($_POST['voice-recorder-username'])
		&& isset($_POST['voice-recorder-room'])
		&& !empty($_POST['voice-recorder-room'])
	) {
		if (!file_exists(share_destination()."/../")) {
			mkdir(share_destination()."/../", 0777);
		}
		$token = db_escape($_POST['voice-recorder-token']);        // User's Token
		$user_id = db_escape($_POST['voice-recorder-userid']);        // User ID
		$username = db_escape($_POST['voice-recorder-username']);        // Username
		$chat_id = db_escape(chat_id($_POST['voice-recorder-room']));        // Username
		$nonchat_id = db_escape($_POST['voice-recorder-room']);        // Username
		$status = 0;
		$error = "";
		$targetPath = "";
			if (getToken($user_id) == $token) {
				$temporary = explode('.', $_FILES['voice-recorder-file']['name']);
				$voice_name = uniqid().'.wav';
				if ($_FILES['voice-recorder-file']['size'] < max_voice_note_size()) {
					if ($_FILES['voice-recorder-file']['error'] > 0) {
						$error = 'Return Code: '.$_FILES['voice-recorder-file']['error'];
					} else {
						if (file_exists(share_destination().$voice_name)) {
							$voice_name = uniqid().$voice_name;
						}

						$target = share_destination().'/'.$nonchat_id.'/'.$username;
						if (!file_exists(share_destination())) {
							mkdir(share_destination(), 0777);
						}
						if (!file_exists(share_destination().'/'.$nonchat_id)) {
							mkdir(share_destination().'/'.$nonchat_id, 0777);
						}
						if (!file_exists($target)) {
							mkdir($target, 0777);
						}
						$targetPath = $target.'/'.$voice_name;
						move_uploaded_file($_FILES['voice-recorder-file']['tmp_name'], $targetPath);
						$status = 1;
					}
				} else {
					$error = 'Voice note is too long.';
				}
			} else {
				$error = 'Invalid token.';
			}

			echo json_encode(
						array(
							'stat' => $status,
							'error' => $error,
							'file' => $targetPath
						)
					);
	}

	/////////////////
	// Share Music //
	/////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'share-music'
		&& is_ajax()
		&& isset($_POST['share-music-token'])
		&& !empty($_POST['share-music-token'])
		&& isset($_POST['share-music-userid'])
		&& !empty($_POST['share-music-userid'])
		&& isset($_POST['share-music-username'])
		&& !empty($_POST['share-music-username'])
		&& isset($_POST['share-music-room'])
		&& !empty($_POST['share-music-room'])
	) {
		$token = db_escape($_POST['share-music-token']);        // User's Token
		$user_id = db_escape($_POST['share-music-userid']);        // User ID
		$username = db_escape($_POST['share-music-username']);        // Username
		$chat_id = db_escape(chat_id($_POST['share-music-room']));        // Username
		$nonchat_id = db_escape($_POST['share-music-room']);        // Username
		$status = array();
		$error = array();
		$targetPath = '';
		$all_target = array();
		$mimes = array();
		
		if (!file_exists(share_destination()."/../")) {
			mkdir(share_destination()."/../", 0777);
		}

		if (share_music() == 1) {
			if (getToken($user_id) == $token) {
				$temporary = explode('.', $_FILES['share-music']['name']);
				$file_extension = end($temporary);
				$music_name = uniqid().'.'.$file_extension;
				if (in_array($_FILES['share-music']['type'], music_types()) && in_array($file_extension, music_extensions())) {
					if ($_FILES['share-music']['size'] < max_music_size()) {
						if ($_FILES['share-music']['error'] > 0) {
							array_push($error, 'Return Code: '.$_FILES['share-music']['error']);
							array_push($status, 0);
							array_push($all_target, '');
							array_push($mimes, '');
						} else {
							if (file_exists(share_destination().$music_name)) {
								$music_name = uniqid().$music_name;
							}

							$target = share_destination().'/'.$nonchat_id.'/'.$username;
							if (!file_exists(share_destination())) {
								mkdir(share_destination(), 0777);
							}
							if (!file_exists(share_destination().'/'.$nonchat_id)) {
								mkdir(share_destination().'/'.$nonchat_id, 0777);
							}
							if (!file_exists($target)) {
								mkdir($target, 0777);
							}
							$targetPath = $target.'/'.$music_name; // Target path where file is to be stored
							array_push($all_target, $targetPath);
							move_uploaded_file($_FILES['share-music']['tmp_name'], $targetPath);
							array_push($status, 1);
							array_push($mimes, $_FILES['share-music']['type']);
						}
					} else {
						array_push($error, 'Invalid file size.');
						array_push($status, 0);
						array_push($all_target, '');
						array_push($mimes, '');
					}
				} else {
					array_push($error, 'Invalid file type.');
					array_push($status, 0);
					array_push($all_target, '');
					array_push($mimes, '');
				}
			} else {
				array_push($status, 0);
				array_push($error, 'Invalid token.');
			}

			echo json_encode(
						array(
							'stat' => $status,
							'error' => $error,
							'file' => $all_target,
							'type' => $mimes,
						)
					);
		}
	}

	////////////////////////////////
	// Admin Update Profile Photo //
	////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'admin-upload-pp'
		&& is_ajax()
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
		&& isset($_POST['target_id'])
		&& !empty($_POST['target_id'])
		&& isset($_FILES['file']['type'])
		&& isset($_POST['previous'])
	) {
		$token = db_escape($_POST['token']);
		$user_id = db_escape($_POST['userid']);
		$username = db_escape($_POST['username']);
		$prev = db_escape($_POST['previous']);
		$target_id = db_escape($_POST['target_id']);
		$status = 0;
		$error = '';
		$targetPath = '';

		if (getToken($user_id) == $token) {
			$usrname_query = db_query("SELECT `username`, `admin`, `guest` FROM `members` WHERE `token` = '$token' && `ID` = '$user_id' LIMIT 1");
			$row = mysqli_fetch_array($usrname_query);
			$sql_username = $row[0];
			$sql_admin = $row[1];
			$sql_guest = $row[2];
						
			if ($sql_username === $username && $sql_admin == 1 && $sql_guest == 0) {
				if(!file_exists(picture_net_destination())) {
					mkdir(picture_net_destination(), 0777);
				}
				$temporary = explode('.', $_FILES['file']['name']);
				$file_extension = end($temporary);

				$img_name = uniqid().'.'.$file_extension;

				if (in_array($_FILES['file']['type'], img_types())
					&& in_array($file_extension, img_extensions())
				) {
					if ($_FILES['file']['size'] < max_img_size()) {
						if ($_FILES['file']['error'] > 0) {
							$error = 'Return Code: '.$_FILES['file']['error'];
						} else {
							if (file_exists(picture_destination().$img_name)) {
								$error = $img_name.' already exists.';
							} else {
								if (!empty($prev)) {
									unlink(picture_destination().$prev);
								}
								$targetPath = picture_destination().$img_name;
								move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
								db_query("UPDATE `members` SET `profile_pic` = '$img_name' WHERE `ID` = '$target_id' LIMIT 1");
								$status = 1;
							}
						}
					} else {
						$error = 'Invalid file size.';
					}
				} else {
					$error = 'Invalid file type.';
				}
			} else {
				$error = 'Invalid token.';
			}
			echo json_encode(
						array(
							'stat' => $status,
							'error' => $error,
							'file' => $targetPath,
						)
					);
		}
	}


	////////////////////////////////
	// Admin Remove Profile Photo //
	////////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'admin-remove-pp'
		&& is_ajax()
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
		&& isset($_POST['target_id'])
		&& !empty($_POST['target_id'])
	) {
		$token = db_escape($_POST['token']);
		$user_id = db_escape($_POST['userid']);
		$username = db_escape($_POST['username']);
		$target_id = db_escape($_POST['target_id']);

		if (getToken($user_id) == $token) {
			$usrname_query = db_query("SELECT `username`, `admin`, `guest` FROM `members` WHERE `token` = '$token' && `ID` = '$user_id' LIMIT 1");
			$row = mysqli_fetch_array($usrname_query);
			$sql_username = $row[0];
			$sql_admin = $row[1];
			$sql_guest = $row[2];
						
			if ($sql_username === $username && $sql_admin == 1 && $sql_guest == 0) {
				$query = db_query("SELECT `profile_pic` FROM `members` WHERE `ID` = '$target_id' LIMIT 1");
				$result = mysqli_fetch_assoc($query);
				$img = $result['profile_pic'];

				db_query("UPDATE `members` SET `profile_pic` = '' WHERE `ID` = '$target_id' LIMIT 1");
				unlink(picture_net_destination().$img);
			} else {
				echo 'error';
			}
		} else {
			echo 'error';
		}
	}


	/////////////////////////////
	// Admin Create a New User //
	/////////////////////////////

	elseif (isset($_GET['act'])
		&& $_GET['act'] == 'admin-new-user'
		&& is_ajax()
		&& isset($_POST['username'])
		&& !empty($_POST['username'])
		&& isset($_POST['status'])
		&& isset($_POST['token'])
		&& !empty($_POST['token'])
		&& isset($_POST['userid'])
		&& !empty($_POST['userid'])
		&& isset($_POST['target_username'])
		&& !empty($_POST['target_username'])
		&& isset($_POST['target_email'])
		&& !empty($_POST['target_email'])
		&& isset($_POST['target_password'])
		&& !empty($_POST['target_password'])
		&& isset($_POST['target_password_repeat'])
		&& !empty($_POST['target_password_repeat'])
		&& isset($_POST['target_type'])
		&& !empty($_POST['target_type'])
		&& isset($_POST['activation'])
		&& !empty($_POST['activation'])
		&& ($_POST['activation'] == 1 || $_POST['activation'] == 2)
	) {
		$token = db_escape($_POST['token']);
		$user_id = db_escape($_POST['userid']);
		$username = db_escape($_POST['username']);
		$user_status = db_escape($_POST['status']);
		$target_username = db_escape($_POST['target_username']);
		$target_email = db_escape($_POST['target_email']);
		$target_password = db_escape($_POST['target_password']);
		$target_password_repeat = db_escape($_POST['target_password_repeat']);
		$target_type = db_escape($_POST['target_type']);
		if($_POST['activation'] == 1) {
			$ac = 1;
		} else {
			$ac = 0;
		}
		$status = 0;
		$userid = null;
		$error = '';
		$targetPath = '';

		if (getToken($user_id) == $token) {
			$usrname_query = db_query("SELECT `username`, `admin`, `guest` FROM `members` WHERE `token` = '$token' && `ID` = '$user_id' LIMIT 1");
			$row = mysqli_fetch_array($usrname_query);
			$sql_username = $row[0];
			$sql_admin = $row[1];
			$sql_guest = $row[2];
						
			if ($sql_username === $username && $sql_admin == 1 && $sql_guest == 0) {
				if (empty($_POST['target_email'])) {
					$error = 'Please enter Email address.';
				} elseif (empty($_POST['target_password']) || empty($_POST['target_password_repeat'])) {
					$error = 'Please enter password.';
				} elseif ($_POST['target_password'] !== $_POST['target_password_repeat']) {
					$error = 'Passwords are not matched.';
				} elseif (strlen($_POST['target_password']) < min_pass_lenght()) {
					if (min_pass_lenght() == 1) {
						$error = 'Password must be at least 1 character.';
					} else {
						$error = 'Password must be at least '.min_pass_lenght().' characters.';
					}
				} elseif (strlen($_POST['target_username']) < min_username_lenght()) {
					if (min_pass_lenght() == 1) {
						$error = 'Username must be at least 1 character.';
					} else {
						$error = 'Username must be at least '.min_username_lenght().' characters.';
					}
				} elseif (strlen($_POST['target_password']) > max_pass_lenght()) {
					$error = 'Password can not be longer than '.max_pass_lenght().' characters.';
				} elseif (strlen($_POST['target_username']) > max_username_lenght()) {
					$error = 'Username can not be longer than '.max_username_lenght().' characters.';
				} elseif (strlen($_POST['target_email']) > max_email_lenght()) {
					$error = 'Email address can not be longer than '.max_email_lenght().' characters.';
				} elseif (!filter_var($_POST['target_email'], FILTER_VALIDATE_EMAIL)) {
					$error = 'Invalid Email address.';
				} elseif (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['username'])) {
					$error = 'Username cannot include special characters.';
				} elseif ($_POST['target_username'] == 'system'
					|| $_POST['target_username'] == 'admin'
					|| strncasecmp($_POST['target_username'], guest_name_prefix(), strlen(guest_name_prefix())) == 0
					|| strncasecmp($_POST['target_username'], 'admin', 5) == 0
					|| strncasecmp($_POST['target_username'], 'system', 6) == 0
				) {
					$error = 'Invalid username.';
				} elseif(strlen($user_status) > get_option("MAX_STATUS")) {
					$error = 'User status is too long.';
				} elseif (!empty($_POST['target_email'])
					&& strlen($_POST['target_email']) <= max_email_lenght()
					&& filter_var($_POST['target_email'], FILTER_VALIDATE_EMAIL)
					&& !empty($_POST['target_password'])
					&& !empty($_POST['target_password_repeat'])
					&& strlen($_POST['target_password']) <= max_pass_lenght()
					&& strlen($_POST['target_password']) >= min_pass_lenght()
					&& ($_POST['target_password'] === $_POST['target_password_repeat'])
					&& !empty($_POST['target_username'])
					&& strlen($_POST['target_username']) <= max_username_lenght()
					&& strlen($_POST['target_username']) >= min_username_lenght()
					&& strlen($user_status) <= get_option("MAX_STATUS")
				) {
					if(empty($_FILES['file'])) {
						$user_password_hash = password_hash($target_password, PASSWORD_DEFAULT);
						$escaped_hash = db_escape($user_password_hash);

						$query_check_email = db_query("SELECT * FROM `members` WHERE `email` = '$target_email'");
						$query_check_user_name = db_query("SELECT * FROM `members` WHERE `username` = '$target_username'");
						$time = time();
						if (mysqli_num_rows($query_check_user_name) == 1) {
							$error = 'This username is already in use.';
						} elseif (mysqli_num_rows($query_check_email) == 1) {
							$error = 'This Email is already in use.';
						} else {
							if($target_type == "Admin") {
								$register = db_query("INSERT INTO `members` (`username`, `password`, `email`, `guest`, `admin`, `registration_date`, `user_status`, `activation`) VALUES('$target_username', '$escaped_hash', '$target_email', 0, 1, '$time', '$user_status', '$ac')");
							} elseif($target_type == "Member") {
								$register = db_query("INSERT INTO `members` (`username`, `password`, `email`, `guest`, `admin`, `registration_date`, `user_status`, `activation`) VALUES('$target_username', '$escaped_hash', '$target_email', 0, 0, '$time', '$user_status', '$ac')");
							} else {
								$register = db_query("INSERT INTO `members` (`username`, `password`, `email`, `guest`, `admin`, `registration_date`, `user_status`, `activation`) VALUES('$target_username', '$escaped_hash', '$target_email', 1, 0, '$time', '$user_status', '$ac')");
							}

							if ($register) {
								$status = 1;
								$userid = mysqli_insert_id(db_connect());
							} else {
								$error = 'An error occured during registration. Please try again.';
							}
						}
					} else {
						$temporary = explode('.', $_FILES['file']['name']);
						$file_extension = end($temporary);
						$img_name = uniqid().'.'.$file_extension;
						if(!file_exists(picture_net_destination())) {
							mkdir(picture_net_destination(), 0777);
						}

						if (in_array($_FILES['file']['type'], img_types())
							&& in_array($file_extension, img_extensions())
						) {
							if ($_FILES['file']['size'] < max_img_size()) {
								if ($_FILES['file']['error'] > 0) {
									$error = 'Return Code: '.$_FILES['file']['error'];
								} else {
									if (file_exists(picture_destination().$img_name)) {
										$img_name = uniqid().'.'.$file_extension;
									} else {
										$targetPath = picture_destination().$img_name;
										move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
										$user_password_hash = password_hash($target_password, PASSWORD_DEFAULT);
										$escaped_hash = db_escape($user_password_hash);

										$query_check_email = db_query("SELECT * FROM `members` WHERE `email` = '$target_email'");
										$query_check_user_name = db_query("SELECT * FROM `members` WHERE `username` = '$target_username'");

										if (mysqli_num_rows($query_check_user_name) == 1) {
											$error = 'This username is already in use.';
										} elseif (mysqli_num_rows($query_check_email) == 1) {
											$error = 'This Email is already in use.';
										} else {
											$time = time();
											if($target_type == "Admin") {
												$register = db_query("INSERT INTO `members` (`username`, `password`, `email`, `guest`, `admin`, `registration_date`, `profile_pic`) VALUES('$target_username', '$escaped_hash', '$target_email', 0, 1, '$time', '$img_name')");
											} elseif($target_type == "Member") {
												$register = db_query("INSERT INTO `members` (`username`, `password`, `email`, `guest`, `admin`, `registration_date`, `profile_pic`) VALUES('$target_username', '$escaped_hash', '$target_email', 0, 0, '$time', '$img_name')");
											} else {
												$register = db_query("INSERT INTO `members` (`username`, `password`, `email`, `guest`, `admin`, `registration_date`, `profile_pic`) VALUES('$target_username', '$escaped_hash', '$target_email', 1, 0, '$time', '$img_name')");
											}
											
											if ($register) {
												$status = 1;
												$userid = mysqli_insert_id(db_connect());
											} else {
												$error = 'An error occured during registration. Please try again.';
											}
										}
									}
								}
							} else {
								$error = 'Invalid profile photo size.';
							}
						} else {
							$error = 'Invalid profile photo type.';
						}
					}
				} else {
					$error = "An error occured.";
				}
			} else {
				$error = "Invalid username.";
			}
		} else {
			$error = 'Invalid token.';
		}
		echo json_encode(
					array(
						'stat' => $status,
						'error' => $error,
						'uid' => $userid
					)
				);
	}
	
	///////////////////////////
	// Activate User Account //
	///////////////////////////
	elseif (isset($_GET['act']) && (($_GET['act'] == "activate" && user_activation() == 1) || ($_GET['act'] == "reset-password" && forgot_password() == 1)) && isset($_GET['key']) && isset($_GET['name']) && isset($_GET['id']) && !empty($_GET['key']) && !empty($_GET['name']) && !empty($_GET['id'])) {
		$user_id = db_escape($_GET['id']);
		$username = db_escape($_GET['name']);
		$key = stripslashes($_GET['key']);
		$type = $_GET["act"];
		echo "<head>";
			meta_tags();
			css_files();
			js_variables();
			javascript_files();
		echo "</head>";
		if(blockUsers()) {
			echo "<div class='content'>";
				echo "<div class='bg-top'></div>";
				echo "<div class='bg-bottom'></div>";
				echo "<div class='row'>";
					echo "<div class='col s12 m6'>";
						echo "<div class='card blue-grey darken-1 confirmation-card' style='top:30%;'>";
							echo "<div class='card-content white-text'>";
								echo "<span class='card-title'>Suspended IP Address</span>";
								echo "<p>Your IP address has been banned.</p>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		} else {
			echo "<div class='content'>";
			echo "<div class='bg-top'></div>";
			echo "<div class='bg-bottom'></div>";
			echo "<div class='row'>";
			echo "<div class='col s12 m6'>";
			echo "<div class='card blue-grey darken-1 confirmation-card' style='top:30%;'>";
			echo "<div class='card-content white-text'>";
			if($type == "activate") {
				echo "<span class='card-title'>Account Activation</span>";
			} else {
				echo "<span class='card-title'>Reset Password</span>";
			}

			if($username == userName($user_id)) {
				if($type == "activate") {
					$time = time();
					$query = db_query("SELECT `ID` FROM `members` WHERE `ID` = '$user_id' && `activation` = 0 LIMIT 1");
					$query2 = db_query("SELECT `activation_code`, `valid_time` FROM `chat_user_activation` WHERE `user_id` = '$user_id' && `type` = 'account_activation' LIMIT 1");
					if(mysqli_num_rows($query) > 0) {
						if(mysqli_num_rows($query2) > 0) {
							$row = mysqli_fetch_array($query2);
							if($row[1] < $time) {
								echo "<p>Your activation code has expired.</p>";
								echo "<p>Click <a href='#' id='get_new_activation_code'>here</a> to get a new activation code.</p>";
							} elseif($row[0] == $key) {
								db_query("UPDATE `members` SET `activation` = 1 WHERE `ID` = '$user_id'");
								db_query("DELETE FROM `chat_user_activation` WHERE `user_id` = '$user_id' && `type` = 'account_activation'");
								echo "<p>Your account has been successfully activated.</p>";
								echo "<p>Click <a href='".get_option("URL")."'>here</a> to continue.</p>";
							} else {
								echo "<p>An error has occured. Please try again.</p>";
								echo "<p>Click <a href='#' id='get_new_activation_code'>here</a> to get a new activation code.</p>";
							}
						} else {
							echo "<p>We couldn't verify your user ID.</p>";
							echo "<p>Click <a href='#' id='get_new_activation_code'>here</a> to get a new activation code.</p>";
						}
					} else {
						echo "<p>This account has already been activated.</p>";
						echo "<p>Click <a href='".get_option("URL")."'>here</a> to continue.</p>";
					}
				} else {
					$time = time();
					$query = db_query("SELECT `ID` FROM `members` WHERE `ID` = '$user_id' && `username` = '$username' LIMIT 1");
					$query2 = db_query("SELECT `activation_code`, `valid_time` FROM `chat_user_activation` WHERE `user_id` = '$user_id' && `type` = 'reset_password' LIMIT 1");
					if(mysqli_num_rows($query) > 0) {
						if(mysqli_num_rows($query2) > 0) {
							$row = mysqli_fetch_array($query2);
							if($row[1] < $time) {
								echo "<p>Your reset password request has expired.</p>";
								echo "<p>Click <a href='".get_option("URL")."'>here</a> to return main page.</p>";
							} elseif($row[0] == $key) {
								echo "<input id='new_pass_username' name='new_pass_username' value='".$username."' type='text' class='hide' />";
								echo "<input id='new_pass_user_id' name='new_pass_user_id' value='".$user_id."' type='text' class='hide' />";
								echo "<div class='row'>";
									echo "<div class='input-field col s12'>";
										echo "<input id='new_password' name='new_password' type='password' autocomplete='off' class='validate' />";
										echo "<label style='color:white!important' for='new_password'>New Password</label>";
									echo "</div>";
									echo "<div class='input-field col s12'>";
										echo "<input id='new_password2' name='new_password2' type='password' autocomplete='off' class='validate' />";
										echo "<label style='color:white!important' for='new_password2'>New Password (Repeat)</label>";
									echo "</div>";
									echo "<a class='btn' href='#' id='save-forgotten-password'>Update</a>";
								echo "</div>";
								
							} else {
								echo "<p>An error has occured.</p>";
								echo "<p>Click <a href='".get_option("URL")."'>here</a> to return main page.</p>";
							}
						} else {
							echo "<p>We couldn't verify your user ID.</p>";
							echo "<p>Click <a href='".get_option("URL")."'>here</a> to return main page.</p>";
						}
					} else {
						echo "<p>This account has already been activated.</p>";
						echo "<p>Click <a href='".get_option("URL")."'>here</a> to return main page.</p>";
					}
				}
			} else {
				echo "<p>We couldn't verify your username.</p>";
				echo "<p>Click <a href='".get_option("URL")."'>here</a> to return main page.</p>";
			}
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
		}
	}

	/////////////////////////
	// New Activation Code //
	/////////////////////////

	elseif (isset($_GET['act']) && $_GET['act'] == "new-activation-code" && user_activation() == 1 && isset($_POST['userid']) && isset($_POST['username']) && isset($_POST['token']) && !empty($_POST['userid']) && !empty($_POST['username']) && !empty($_POST['token'])) {
		$user_id = db_escape($_POST['userid']);
		$username = db_escape($_POST['username']);
		$token = db_escape($_POST['token']);
		$error = "";
		$stat = 0;
		$time = time();

		if($username == userName($user_id)) {
			if(getToken($user_id) == $token) {
				$query = db_query("SELECT `ID` FROM `members` WHERE `ID` = '$user_id' && `activation` = 0 LIMIT 1");
				$query2 = db_query("SELECT `activation_code`, `valid_time`, `next_request` FROM `chat_user_activation` WHERE `user_id` = '$user_id' && `type` = 'account_activation' LIMIT 1");
				$row = mysqli_fetch_array($query2);
				if(mysqli_num_rows($query) > 0) {
					if($row[2] < $time) {
						$activation_code = bin2hex(openssl_random_pseudo_bytes(24));
						$valid_time = strtotime("+7 days", time());
						$next_request = strtotime("+5 minutes", time());
						if(mysqli_num_rows($query2) == 0) {
							db_query("INSERT INTO `chat_user_activation` (`activation_code`, `valid_time`, `next_request`, `user_id`, `type`) VALUES ('$activation_code', '$valid_time', '$time', '$user_id', 'account_activation')");
						} else {
							db_query("UPDATE `chat_user_activation` SET `activation_code` = '$activation_code', `valid_time` = '$valid_time', `next_request` = '$next_request' WHERE `user_id` = '$user_id' && `type` = 'account_activation'");
						}

						$mail = new PHPMailer;
						$mail->isSMTP();
						$mail->Host = EMAIL_HOST;
						$mail->SMTPAuth = true;
						$mail->Username = EMAIL_USERNAME;
						$mail->Password = EMAIL_PASSWORD;
						$mail->SMTPSecure = EMAIL_SMTP_SECURE;
						$mail->Port = EMAIL_PORT;

						$mail->setFrom(EMAIL_ADDRESS, EMAIL_NAME.' - Account Verification');
						$mail->addAddress(email($user_id), $username);     // Add a recipient
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'Account Verification';
						if(substr(get_option("URL"), -1) == "/") {
							$url = get_option("URL").'action.php?act=activate&key='.$activation_code.'&id='.$user_id.'&name='.$username;
						} else {
							$url = get_option("URL").'/action.php?act=activate&key='.$activation_code.'&id='.$user_id.'&name='.$username;
						}
						$mail->Body    = 'Hello '.$username.',<br>Click <a href="'.$url.'">here</a> to activate your account.<br><br>Activation Code: '.$activation_code.'<br><br>This link is valid until '.date("d/m/Y H:i", $valid_time);
						$mail->AltBody = 'Hello '.$username.',\nGo to '.$url.' this page to activate your account.\n\nActivation Code: '.$activation_code.'\n\nThis link is valid until '.date("d/m/Y H:i", $valid_time);

						if(!$mail->send()) {
							$error = 'An error occured.';
							if(mysqli_num_rows($query2) > 0) {
								$activation_code = $row[0];
								$valid_time = $row[1];
								$next_request = $row[2];
								db_query("UPDATE `chat_user_activation` SET `activation_code` = '$activation_code', `valid_time` = '$valid_time', `next_request` = '$next_request' WHERE `user_id` = '$user_id'  && `type` = 'account_activation'");
							}
						} else {
							$stat = 1;
						}
					} else {
						$error = "You must wait until ".date("d/m/Y H:i", $row[2]).".";
					}
				} else {
					$error = "This account is already activated.";
				}
			} else {
				$error = "Invalid token";
			}
		} else {
			$error = "Invalid username.";
		}
		echo json_encode(
					array(
						'stat' => $stat,
						'error' => $error
					)
				);
	}
	
	////////////////////
	// Reset Password //
	////////////////////

	elseif (isset($_GET['act']) && $_GET['act'] == "reset-password-action" && forgot_password() == 1 && isset($_POST['email']) && !empty($_POST['email'])) {
		$email = db_escape($_POST['email']);
		$error = "";
		$stat = 0;
		$time = time();

		$query = db_query("SELECT `ID`, `username` FROM `members` WHERE `email` = '$email' LIMIT 1");
		$row = mysqli_fetch_array($query);
		$user_id = $row[0];
		$query2 = db_query("SELECT `activation_code`, `valid_time` FROM `chat_user_activation` WHERE `user_id` = '$user_id' && `type` = 'reset_password' LIMIT 1");
		
		$row2 = mysqli_fetch_array($query2);
		if(mysqli_num_rows($query) > 0) {
			$activation_code = bin2hex(openssl_random_pseudo_bytes(24));
			$valid_time = strtotime("+7 days", time());
			if(mysqli_num_rows($query2) == 0) {
				db_query("INSERT INTO `chat_user_activation` (`activation_code`, `valid_time`, `type`, `user_id`) VALUES ('$activation_code', '$valid_time', 'reset_password', '$user_id')");
			} else {
				db_query("UPDATE `chat_user_activation` SET `activation_code` = '$activation_code', `valid_time` = '$valid_time' WHERE `user_id` = '$user_id' && `type` = 'reset_password'");
			}
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = EMAIL_HOST;
			$mail->SMTPAuth = true;
			$mail->Username = EMAIL_USERNAME;
			$mail->Password = EMAIL_PASSWORD;
			$mail->SMTPSecure = EMAIL_SMTP_SECURE;
			$mail->Port = EMAIL_PORT;

			$mail->setFrom(EMAIL_ADDRESS, EMAIL_NAME.' - Reset Password');
			$mail->addAddress($email, $row[1]);     // Add a recipient
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = 'Reset Password';
			if(substr(get_option("URL"), -1) == "/") {
				$url = get_option("URL").'action.php?act=reset-password&key='.$activation_code.'&id='.$user_id.'&name='.$row[1];
			} else {
				$url = get_option("URL").'/action.php?act=reset-password&key='.$activation_code.'&id='.$user_id.'&name='.$row[1];
			}
			$mail->Body    = 'Hello '.$row[1].',<br>Click <a href="'.$url.'">here</a> to reset your password. This link is valid until '.date("d/m/Y H:i", $valid_time);
			$mail->AltBody = 'Hello '.$row[1].',\nGo to '.$url.' page to reset your password.\n\nThis link is valid until '.date("d/m/Y H:i", $valid_time);
				if(!$mail->send()) {
				$error = 'An error occured.';
				if(mysqli_num_rows($query2) > 0) {
					$activation_code = $row2[0];
					$valid_time = $row2[1];
					db_query("UPDATE `chat_user_activation` SET `activation_code` = '$activation_code', `valid_time` = '$valid_time' WHERE `user_id` = '$user_id' && `type` = 'reset_password'");
				}
			} else {
				$stat = 1;
			}
		} else {
			$error = "We couldn't find a user with this email address.";
		}
		echo json_encode(
					array(
						'stat' => $stat,
						'error' => $error
					)
				);
	}
	
	/////////////////////////////
	// Save Forgotten Password //
	/////////////////////////////

	elseif (isset($_GET['act']) && $_GET['act'] == "save-forgotten-password" && forgot_password() == 1 && isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['pass1']) && !empty($_POST['pass1']) && isset($_POST['pass2']) && !empty($_POST['pass2'])) {
		$user_id = db_escape($_POST['user_id']);
		$username = db_escape($_POST['username']);
		$pass1 = db_escape($_POST['pass1']);
		$pass2 = db_escape($_POST['pass2']);
		$stat = 0;
		$error = "";

		$query = db_query("SELECT `ID` FROM `members` WHERE `ID` = '$user_id' && `username` = '$username' LIMIT 1");
		if(mysqli_num_rows($query) > 0) {
			if($pass1 === $pass2) {
				if(strlen($pass1) >= min_pass_lenght()) {
					if(strlen($pass1) <= max_pass_lenght()) {
						$hashed_pass = db_escape(password_hash($pass1, PASSWORD_DEFAULT));
						db_query("UPDATE `members` SET `password` = '$hashed_pass' WHERE `ID` = '$user_id'");
						db_query("DELETE FROM `chat_user_activation` WHERE `user_id` = '$user_id' && `type` = 'reset_password'");
						$stat = 1;
					} else {
						$error = "Your password cannot be longer than ".max_pass_lenght()." characters.";
					}
				} else {
					$error = "Your password must be at least ".min_pass_lenght()." characters.";
				}
			} else {
				$error = "Your passwords are not matched.";
			}
		} else {
			$error = "We couldn't find your account.";
		}
		echo json_encode(
					array(
						'stat' => $stat,
						'error' => $error
					)
				);
	}
	
	/////////////////////
	// Change Password //
	/////////////////////

	elseif (isset($_GET['act']) && $_GET['act'] == "change-password" && isset($_POST['userid']) && !empty($_POST['userid']) && isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['new_pass_1']) && !empty($_POST['new_pass_1']) && isset($_POST['new_pass_2']) && !empty($_POST['new_pass_2']) && isset($_POST['current_pass']) && !empty($_POST['current_pass'])) {
		$user_id = db_escape($_POST['userid']);
		$username = db_escape($_POST['username']);
		$pass1 = db_escape($_POST['new_pass_1']);
		$pass2 = db_escape($_POST['new_pass_2']);
		$current_pass = db_escape($_POST['current_pass']);
		$stat = 0;
		$error = "";

		$query = db_query("SELECT `ID`, `password` FROM `members` WHERE `ID` = '$user_id' && `username` = '$username' LIMIT 1");
		if(mysqli_num_rows($query) > 0) {
			if(isGuest($user_id) == 0) {
				$row = mysqli_fetch_array($query);
				if(password_verify($current_pass, $row[1])) {
					if($pass1 === $pass2) {
						if($pass1 != $current_pass ) {
							if(strlen($pass1) >= min_pass_lenght()) {
								if(strlen($pass1) <= max_pass_lenght()) {
									$hashed_pass = db_escape(password_hash($pass1, PASSWORD_DEFAULT));
									db_query("UPDATE `members` SET `password` = '$hashed_pass' WHERE `ID` = '$user_id'");
									$stat = 1;
								} else {
									$error = "Your password cannot be longer than ".max_pass_lenght()." characters.";
								}
							} else {
								$error = "Your password must be at least ".min_pass_lenght()." characters.";
							}
						} else {
							$error = "Your new password must be different.";
						}
					} else {
						$error = "New passwords are not the same.";
					}
				} else {
					$error = "Your current password is wrong.";
				}
			} else {
				$error = "You don't have permission to change your password.";
			}
		} else {
			$error = "We couldn't find your account.";
		}
		echo json_encode(
					array(
						'stat' => $stat,
						'error' => $error
					)
				);
	}

	///////////////////////////////////////////////////
	// Gets the Crypted Chat ID & Open the Chat Page //
	///////////////////////////////////////////////////

	elseif (isset($_GET['chat'])) {
		chat($_GET['chat']);
	}

	/////////////////////
	// Downloads Files //
	/////////////////////

	elseif (isset($_GET['download']) && isset($_GET['name'])) {
		if (ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		} // For IE

		if (!empty($_GET['download']) && !empty($_GET['name']) && $_GET['name'] != 'undefined' && $_GET['download'] != 'undefined') {
			$file_name = base64_decode($_GET['name']);
			if (file_exists(base64_decode($_GET['download'])) && is_file(base64_decode($_GET['download']))) {
				ob_start();
				header('Pragma: public');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Cache-Control: private', false);
				header('Content-Type: application/force-download');
				header('Content-Disposition: attachment; filename="'.$file_name.'"');
				header('Content-Transfer-Encoding: binary');
				header('Connection: close');
				ob_end_flush();
				readfile(base64_decode($_GET['download']));
				exit;
			} else {
				echo 'File could not found.';
			}
		}
	}
}
