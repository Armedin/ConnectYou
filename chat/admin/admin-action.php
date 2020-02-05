<?php

require_once '../include/chat.php';
date_default_timezone_set(timezone());    // Set the timezone

if(isset($_GET['act']) && is_ajax() && !blockUsers() && isset($_POST['username']) && isset($_POST['userid']) && isset($_POST['token']) && !empty($_POST['username']) && !empty($_POST['userid']) && !empty($_POST['token'])) {
	$username = db_escape($_POST['username']);
	$user_id = db_escape($_POST['userid']);
	$token = db_escape($_POST['token']);
	
	if ($token === getToken($user_id)) {
        $usrname_query = db_query("SELECT `username`, `admin`, `guest` FROM `members` WHERE `token` = '$token' && `ID` = '$user_id' LIMIT 1");
        $row = mysqli_fetch_array($usrname_query);
        $sql_username = $row[0];
        $sql_admin = $row[1];
        $sql_guest = $row[2];
					
        if ($sql_username === $username && $sql_admin == 1 && $sql_guest == 0) {
			if($_GET['act'] == 'ban-ip-address' && isset($_POST['ip_address']) && !empty($_POST['ip_address'])) {
				if($_POST['ip_address'] == getRealIpAddr() || $_POST['ip_address'] == "*.*.*.*") {
					echo -1;
				} else {
					$ip_address = db_escape($_POST['ip_address']);
					$query = db_query("SELECT `ID` FROM `chat_banned_items` WHERE `val1` = '$ip_address' && `setting` = 'banned_ip'");
					if(mysqli_num_rows($query) == 0) {
						db_query("INSERT INTO `chat_banned_items`(`val1`, `setting`) VALUES ('$ip_address', 'banned_ip')");
						echo mysqli_insert_id(db_connect());
					} else {
						echo -2;
					}
				}
				
			}
			
			elseif($_GET['act'] == 'ban-words' && isset($_POST['ban_words_1']) && !empty($_POST['ban_words_1']) && isset($_POST['ban_words_2'])) {
				$ban_words_1 = db_escape($_POST['ban_words_1']);
				$ban_words_2 = db_escape($_POST['ban_words_2']);
				$query = db_query("SELECT `ID` FROM `chat_banned_items` WHERE `val1` = '$ban_words_1' && `setting` = 'banned_words'");
				if(mysqli_num_rows($query) == 0) {
					db_query("INSERT INTO `chat_banned_items`(`val1`, `val2`, `setting`) VALUES ('$ban_words_1', '$ban_words_2', 'banned_words')");
					echo mysqli_insert_id(db_connect());
				} else {
					echo -1;
				}
			}
			
			elseif($_GET['act'] == 'save-status' && isset($_POST['status']) && ($_POST['status'] == 1 || $_POST['status'] == 0) && isset($_POST['target_id']) && !empty($_POST['target_id'])) {
				$status = db_escape($_POST['status']);
				$target_id = db_escape($_POST['target_id']);
				db_query("UPDATE `members` SET `online` = '$status' WHERE `ID` = '$target_id' LIMIT 1");
				echo 1;
			}
			
			elseif($_GET['act'] == 'save-type' && isset($_POST['user_type']) && ($_POST['activated'] == 1 || $_POST['activated'] == 2) && ($_POST['user_type'] == 2 || $_POST['user_type'] == 1 || $_POST['user_type'] == 0) && isset($_POST['target_id']) && !empty($_POST['target_id']) && isset($_POST['activated']) && !empty($_POST['activated'])) {
				$user_type = db_escape($_POST['user_type']);
				$target_id = db_escape($_POST['target_id']);
				$activated = db_escape($_POST['activated']);
				if($activated == 1) {
					$ac = 1;
				} else {
					$ac = 0;
				}
				if($user_type == 2) {
					db_query("UPDATE `members` SET `admin` = 1, `guest` = 0, `activation` = '$ac' WHERE `ID` = '$target_id' LIMIT 1");
					echo 1;
				} elseif($user_type == 1) {
					db_query("UPDATE `members` SET `admin` = 0, `guest` = 0, `activation` = '$ac' WHERE `ID` = '$target_id' LIMIT 1");
					echo 1;
				} else {
					db_query("UPDATE `members` SET `admin` = 0, `guest` = 1, `activation` = '$ac' WHERE `ID` = '$target_id' LIMIT 1");
					echo 1;
				}
				
			}
			
			elseif($_GET['act'] == 'save-password' && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['password2']) && !empty($_POST['password2']) && isset($_POST['target_id']) && !empty($_POST['target_id'])) {
				$password = db_escape($_POST['password']);
				$password2 = db_escape($_POST['password2']);
				$target_id = db_escape($_POST['target_id']);
				
				if($password !== $password2) {
					echo -3;
				} elseif(strlen($password) > 128) {
					echo -1;
				} elseif(strlen($password) < 3) {
					echo -2;
				} else {
					$hashed_password = db_escape(password_hash($password, PASSWORD_DEFAULT)); 
					db_query("UPDATE `members` SET `password` = '$hashed_password' WHERE `ID` = '$target_id' LIMIT 1");
					echo 1;
				}				
			}
			
			elseif($_GET['act'] == 'save-general-settings' && isset($_POST['timezone']) && !empty($_POST['timezone']) && isset($_POST['full_url']) && !empty($_POST['full_url']) && isset($_POST['websocket_url']) && !empty($_POST['websocket_url'])) {
				$timezone = $_POST['timezone'];
				$websocket_url = $_POST['websocket_url'];
				$full_url = $_POST['full_url'];
				
				if (in_array($timezone, DateTimeZone::listIdentifiers())) {
					edit_option(array("WEBSOCKET_URL", "URL", "TIMEZONE"), array($websocket_url, $full_url, $timezone));
					echo 1;
				}
				else {
					echo 2;
				}
			}
			
			elseif($_GET['act'] == 'save-user-settings'
				&& isset($_POST['min_username']) 
				&& ($_POST['min_username'] != "" || $_POST['min_username'] != NULL)
				&& isset($_POST['max_username']) 
				&& ($_POST['max_username'] != "" || $_POST['max_username'] != NULL)
				&& isset($_POST['min_password']) 
				&& ($_POST['min_password'] != "" || $_POST['min_password'] != NULL)
				&& isset($_POST['max_password']) 
				&& ($_POST['max_password'] != "" || $_POST['max_password'] != NULL)
				&& isset($_POST['max_email']) 
				&& ($_POST['max_email'] != "" || $_POST['max_email'] != NULL)
				&& isset($_POST['guest_login']) 
				&& ($_POST['guest_login'] != "" || $_POST['guest_login'] != NULL)
				&& isset($_POST['user_status']) 
				&& ($_POST['user_status'] != "" || $_POST['user_status'] != NULL)
				&& isset($_POST['default_user_status'])
				&& !empty($_POST['default_user_status'])
				&& isset($_POST['user_status_lenght'])
				&& !empty($_POST['user_status_lenght'])
				&& isset($_POST['guest_prefix'])
				&& !empty($_POST['guest_prefix'])
				&& isset($_POST['guest_password']) 
				&& ($_POST['guest_password'] != "" || $_POST['guest_password'] != NULL)
				&& isset($_POST['guest_create_groups']) 
				&& ($_POST['guest_create_groups'] != "" || $_POST['guest_create_groups'] != NULL)
				&& isset($_POST['guest_send_pm']) 
				&& ($_POST['guest_send_pm'] != "" || $_POST['guest_send_pm'] != NULL)
				&& isset($_POST['guest_be_invited']) 
				&& ($_POST['guest_be_invited'] != "" || $_POST['guest_be_invited'] != NULL)
				&& isset($_POST['guest_online_list']) 
				&& ($_POST['guest_online_list'] != "" || $_POST['guest_online_list'] != NULL)
				&& isset($_POST['guest_friends']) 
				&& ($_POST['guest_friends'] != "" || $_POST['guest_friends'] != NULL)
				&& isset($_POST['member_group']) 
				&& ($_POST['member_group'] != "" || $_POST['member_group'] != NULL)
				&& isset($_POST['member_pm']) 
				&& ($_POST['member_pm'] != "" || $_POST['member_pm'] != NULL)
				&& isset($_POST['user_activation']) 
				&& ($_POST['user_activation'] != "" || $_POST['user_activation'] != NULL)
				&& isset($_POST['forgot_password']) 
				&& ($_POST['forgot_password'] != "" || $_POST['forgot_password'] != NULL)
			) {
				if(!is_numeric($_POST['min_username']) 
					|| !is_numeric($_POST['max_username']) 
					|| !is_numeric($_POST['min_password']) 
					|| !is_numeric($_POST['max_password']) 
					|| !is_numeric($_POST['max_email']) 
					|| !is_numeric($_POST['guest_password']) 
					|| !is_numeric($_POST['user_status_lenght']) 
					|| ($_POST['guest_login'] != 1 && $_POST['guest_login'] != 0)
					|| ($_POST['guest_create_groups'] != 1 && $_POST['guest_create_groups'] != 0)
					|| ($_POST['guest_send_pm'] != 1 && $_POST['guest_send_pm'] != 0)
					|| ($_POST['guest_be_invited'] != 1 && $_POST['guest_be_invited'] != 0)
					|| ($_POST['guest_online_list'] != 1 && $_POST['guest_online_list'] != 0)
					|| ($_POST['guest_friends'] != 1 && $_POST['guest_friends'] != 0)
					|| ($_POST['member_group'] != 1 && $_POST['member_group'] != 0)
					|| ($_POST['member_pm'] != 1 && $_POST['member_pm'] != 0)
					|| ($_POST['user_activation'] != 1 && $_POST['user_activation'] != 0)
					|| ($_POST['forgot_password'] != 1 && $_POST['forgot_password'] != 0)
				) {
					echo 2;
				} elseif($_POST['max_email'] > 128 || $_POST['max_email'] < 5) {
					echo 3;
				} elseif($_POST['guest_password'] > 128 || $_POST['guest_password'] < 1) {
					echo 4;
				} elseif($_POST['min_username'] < 1 && $_POST['min_username'] > 40) {
					echo 5;
				} elseif($_POST['max_username'] > 40 && $_POST['max_username'] < $_POST['min_username']) {
					echo 6;
				} elseif(strlen($_POST['guest_prefix']) > 40 && strlen($_POST['guest_prefix']) < 1) {
					echo 7;
				} elseif($_POST['min_password'] < 1) {
					echo 8;
				} elseif($_POST['user_status_lenght'] > 4096) {
					echo 9;
				} elseif(strlen($_POST['default_user_status']) > $_POST['user_status_lenght'] || strlen($_POST['default_user_status']) > 4096) {
					echo 10;
				} else {
					$min_username = $_POST['min_username'];
					$max_username = $_POST['max_username'];
					$min_password = $_POST['min_password'];
					$max_password = $_POST['max_password'];
					$max_email = $_POST['max_email'];
					$guest_login = $_POST['guest_login'];
					$guest_prefix = $_POST['guest_prefix'];
					$guest_password = $_POST['guest_password'];
					$guest_create_groups = $_POST['guest_create_groups'];
					$guest_send_pm = $_POST['guest_send_pm'];
					$guest_be_invited = $_POST['guest_be_invited'];
					$guest_online_list = $_POST['guest_online_list'];
					$guest_friends = $_POST['guest_friends'];
					$member_group = $_POST['member_group'];
					$member_pm = $_POST['member_pm'];
					$user_status = $_POST['user_status'];
					$default_user_status = $_POST['default_user_status'];
					$user_status_lenght = $_POST['user_status_lenght'];
					$user_activation = $_POST['user_activation'];
					$forgot_password = $_POST['forgot_password'];

					edit_option(array("MIN_USERNAME", "MAX_USERNAME", "MAX_EMAIL", "MIN_PASSWORD", "MAX_PASSWORD", "GUEST_LOGIN", "GUEST_NAME_PREFIX", "GUEST_PASSWORD_LENGHT", "ENABLE_GUEST_GROUPS", "ENABLE_GUEST_PM", "ENABLE_GUEST_BE_INVITED", "SHOW_GUESTS_ON_ONLINE_USER_LIST", "ENABLE_USER_GROUPS", "ENABLE_USER_PM", "ALLOW_GUEST_TO_ADD_FRIENDS", "ENABLE_USER_STATUS", "DEFAULT_STATUS",  "MAX_STATUS", "USER_ACTIVATION", "FORGOT_PASSWORD"), array($min_username, $max_username, $max_email, $min_password, $max_password, $guest_login, $guest_prefix, $guest_password, $guest_create_groups, $guest_send_pm, $guest_be_invited, $guest_online_list, $member_group, $member_pm, $guest_friends, $user_status, $default_user_status, $user_status_lenght, $user_activation, $forgot_password));
					echo 1;
				}
			}
			
			elseif($_GET['act'] == 'save-chat-settings'
				&& isset($_POST['max_img_size']) 
				&& ($_POST['max_img_size'] != "" || $_POST['max_img_size'] != NULL)
				&& isset($_POST['group_capacity']) 
				&& ($_POST['group_capacity'] != "" || $_POST['group_capacity'] != NULL)
				&& isset($_POST['max_group_name']) 
				&& ($_POST['max_group_name'] != "" || $_POST['max_group_name'] != NULL)
				&& isset($_POST['min_search']) 
				&& ($_POST['min_search'] != "" || $_POST['min_search'] != NULL)
				&& isset($_POST['img_extensions']) 
				&& ($_POST['img_extensions'] != "" || $_POST['img_extensions'] != NULL)
				&& isset($_POST['max_music_size']) 
				&& ($_POST['max_music_size'] != "" || $_POST['max_music_size'] != NULL)
				&& isset($_POST['max_video_size']) 
				&& ($_POST['max_video_size'] != "" || $_POST['max_video_size'] != NULL)
				&& isset($_POST['max_file_size']) 
				&& ($_POST['max_file_size'] != "" || $_POST['max_file_size'] != NULL)
				&& isset($_POST['max_photo_size']) 
				&& ($_POST['max_photo_size'] != "" || $_POST['max_photo_size'] != NULL)
				&& isset($_POST['max_file']) 
				&& ($_POST['max_file'] != "" || $_POST['max_file'] != NULL)
				&& isset($_POST['max_video']) 
				&& ($_POST['max_video'] != "" || $_POST['max_video'] != NULL)
				&& isset($_POST['max_photo']) 
				&& ($_POST['max_photo'] != "" || $_POST['max_photo'] != NULL)
				&& isset($_POST['max_voice_note']) 
				&& ($_POST['max_voice_note'] != "" || $_POST['max_voice_note'] != NULL)
				&& isset($_POST['google_maps_api']) 
				&& isset($_POST['music_mimes']) 
				&& isset($_POST['music_extensions']) 
				&& isset($_POST['file_extensions']) 
				&& isset($_POST['video_mimes']) 
				&& isset($_POST['video_extensions']) 
				&& isset($_POST['photo_mimes']) 
				&& isset($_POST['photo_extensions']) 
				&& isset($_POST['share_archive']) 
				&& ($_POST['share_archive'] != "" || $_POST['share_archive'] != NULL)
				&& isset($_POST['share_photo']) 
				&& ($_POST['share_photo'] != "" || $_POST['share_photo'] != NULL)
				&& isset($_POST['share_video']) 
				&& ($_POST['share_video'] != "" || $_POST['share_video'] != NULL)
				&& isset($_POST['share_file']) 
				&& ($_POST['share_file'] != "" || $_POST['share_file'] != NULL)
				&& isset($_POST['share_music']) 
				&& ($_POST['share_music'] != "" || $_POST['share_music'] != NULL)
				&& isset($_POST['save_messages']) 
				&& ($_POST['save_messages'] != "" || $_POST['save_messages'] != NULL)
				&& isset($_POST['emoticons']) 
				&& ($_POST['emoticons'] != "" || $_POST['emoticons'] != NULL)
				&& isset($_POST['online_users']) 
				&& ($_POST['online_users'] != "" || $_POST['online_users'] != NULL)
				&& isset($_POST['friends']) 
				&& ($_POST['friends'] != "" || $_POST['friends'] != NULL)
			) {
				if(!is_numeric($_POST['online_users']) 
					|| !is_numeric($_POST['emoticons']) 
					|| !is_numeric($_POST['save_messages']) 
					|| !is_numeric($_POST['share_music']) 
					|| !is_numeric($_POST['share_file']) 
					|| !is_numeric($_POST['share_video']) 
					|| !is_numeric($_POST['share_photo']) 
					|| !is_numeric($_POST['share_archive']) 
					|| !is_numeric($_POST['group_capacity']) 
					|| !is_numeric($_POST['max_group_name']) 
					|| !is_numeric($_POST['min_search']) 
					|| !is_numeric($_POST['max_photo']) 
					|| !is_numeric($_POST['max_video']) 
					|| !is_numeric($_POST['max_file']) 
					|| !is_numeric($_POST['friends']) 
					|| ($_POST['online_users'] != 1 && $_POST['online_users'] != 0)
					|| ($_POST['friends'] != 1 && $_POST['friends'] != 0)
					|| ($_POST['emoticons'] != 1 && $_POST['emoticons'] != 0)
					|| ($_POST['share_music'] != 1 && $_POST['share_music'] != 0)
					|| ($_POST['share_file'] != 1 && $_POST['share_file'] != 0)
					|| ($_POST['share_video'] != 1 && $_POST['share_video'] != 0)
					|| ($_POST['share_photo'] != 1 && $_POST['share_photo'] != 0)
					|| ($_POST['share_archive'] != 1 && $_POST['share_archive'] != 0)
					|| ($_POST['share_location'] != 1 && $_POST['share_location'] != 0)
					|| ($_POST['share_voice'] != 1 && $_POST['share_voice'] != 0)
				) {
					echo 2;
				} elseif(check_size($_POST['max_photo_size']) == 0) {
					echo 3;	// Too Big
				} elseif(check_size($_POST['max_photo_size']) == -1) {
					echo 4; // Invalid
				}  elseif(check_size($_POST['max_file_size']) == 0) {
					echo 5;	// Too Big
				} elseif(check_size($_POST['max_file_size']) == -1) {
					echo 6; // Invalid
				}  elseif(check_size($_POST['max_video_size']) == 0) {
					echo 7;	// Too Big
				} elseif(check_size($_POST['max_video_size']) == -1) {
					echo 8; // Invalid
				}  elseif(check_size($_POST['max_music_size']) == 0) {
					echo 9;	// Too Big
				} elseif(check_size($_POST['max_music_size']) == -1) {
					echo 10; // Invalid
				} elseif(check_size($_POST['max_img_size']) == 0) {
					echo 11;	// Too Big
				} elseif(check_size($_POST['max_img_size']) == -1) {
					echo 12; // Invalid
				} elseif(!check_max_upload($_POST['max_photo'])) {
					echo 13; // Too Much
				} elseif($_POST['max_photo'] < 1) {
					echo 14; // Too Small
				} elseif(!check_max_upload($_POST['max_video'])) {
					echo 15; // Too Much
				} elseif($_POST['max_video'] < 1) {
					echo 16; // Too Small
				} elseif(!check_max_upload($_POST['max_file'])) {
					echo 17; // Too Much
				} elseif($_POST['max_file'] < 1) {
					echo 18; // Too Small
				} elseif($_POST['max_group_name'] > 255 || $_POST['max_group_name'] < 1) {
					echo 19;
				} elseif($_POST['group_capacity'] < 1) {
					echo 20;
				} elseif($_POST['min_search'] < 1) {
					echo 21;
				} elseif(check_size($_POST['max_voice_note']) == -1) {
					echo 22;
				} else {
					$save_messages = $_POST['save_messages'];
					$emoticons = $_POST['emoticons'];
					$group_capacity = $_POST['group_capacity'];
					$max_group_name = $_POST['max_group_name'];
					$min_search = $_POST['min_search'];
					$online_users = $_POST['online_users'];
					$friends = $_POST['friends'];
					$max_img_size = $_POST['max_img_size'];
					$img_extensions = $_POST['img_extensions'];
					$share_photo = $_POST['share_photo'];
					$share_video = $_POST['share_video'];
					$share_file = $_POST['share_file'];
					$share_music = $_POST['share_music'];
					$share_archive = $_POST['share_archive'];
					$photo_extensions = $_POST['photo_extensions'];
					$photo_mimes = $_POST['photo_mimes'];
					$video_extensions = $_POST['video_extensions'];
					$video_mimes = $_POST['video_mimes'];
					$file_extensions = $_POST['file_extensions'];
					$music_extensions = $_POST['music_extensions'];
					$music_mimes = $_POST['music_mimes'];
					$max_photo = $_POST['max_photo'];
					$max_video = $_POST['max_video'];
					$max_file = $_POST['max_file'];
					$max_photo_size = $_POST['max_photo_size'];
					$max_video_size = $_POST['max_video_size'];
					$max_file_size = $_POST['max_file_size'];
					$max_music_size = $_POST['max_music_size'];
					$max_voice_note = $_POST['max_voice_note'];
					$google_maps_api = $_POST['google_maps_api'];
					$share_location = $_POST['share_location'];
					$share_voice = $_POST['share_voice'];

					edit_option(array("SAVE_MESSAGES", "ENABLE_EMOJI", "MAX_GROUP_CAPACITY", "MAX_LENGHT_GROUP_NAME", "MIN_SEARCH", "ENABLE_ONLINE_USERS", "MAX_IMG_SIZE", "IMG_EXTENSIONS", "SHARE_PHOTO", "SHARE_VIDEO", "SHARE_FILE", "SHARE_MUSIC", "SHARE_ARCHIVE", "PHOTO_EXTENSIONS", "PHOTO_MIME_TYPES", "VIDEO_EXTENSIONS", "VIDEO_MIME_TYPES", "FILE_EXTENSIONS", "MUSIC_EXTENSIONS", "MUSIC_MIME_TYPES", "MAX_PHOTO", "MAX_VIDEO", "MAX_FILE", "MAX_PHOTO_SIZE", "MAX_VIDEO_SIZE", "MAX_FILE_SIZE", "MAX_MUSIC_SIZE", "ENABLE_FRIEND_SYSTEM", "MAX_VOICE_NOTE_SIZE", "GOOGLE_MAPS_API_KEY", "SHARE_LOCATION", "VOICE_NOTES"), array($save_messages, $emoticons, $group_capacity, $max_group_name, $min_search, $online_users, $max_img_size, $img_extensions, $share_photo, $share_video, $share_file, $share_music, $share_archive, $photo_extensions, $photo_mimes, $video_extensions, $video_mimes, $file_extensions, $music_extensions, $music_mimes, $max_photo, $max_video, $max_file, $max_photo_size, $max_video_size, $max_file_size, $max_music_size, $friends, $max_voice_note, $google_maps_api, $share_location, $share_voice));
					echo 1;
				}
			}
			
			elseif($_GET['act'] == 'new-secretkey') {
				edit_option("SECRETKEY", bin2hex(openssl_random_pseudo_bytes(32)));
				$all_rooms = db_query("SELECT `ID` FROM $room_table");
				while($room = mysqli_fetch_array($all_rooms)) {
					$hash = keymaker($room[0]);
					$id = $room[0];
					db_query("UPDATE `chat_room` SET `id_hash` = '$hash' WHERE `ID` = '$id' LIMIT 1");
				}
				echo 1;
			}
			
			elseif($_GET['act'] == 'delete-message' && isset($_POST['msg_id']) && !empty($_POST['msg_id']) && is_numeric($_POST['msg_id'])) {
				$msg_id = db_escape($_POST['msg_id']);
				$query = db_query("DELETE FROM `chat_messages` WHERE `ID` = '$msg_id' LIMIT 1");
				if($query) {
					echo 1;
				} else {
					echo 0;
				}
			}
			
			elseif($_GET['act'] == 'delete-ip' && isset($_POST['ip_id']) && !empty($_POST['ip_id']) && is_numeric($_POST['ip_id'])) {
				$ip_id = db_escape($_POST['ip_id']);
				$query = db_query("DELETE FROM `chat_banned_items` WHERE `ID` = '$ip_id' LIMIT 1");
				if($query) {
					echo 1;
				} else {
					echo 0;
				}
			}
			
			elseif($_GET['act'] == 'delete-word' && isset($_POST['word_id']) && !empty($_POST['word_id']) && is_numeric($_POST['word_id'])) {
				$word_id = db_escape($_POST['word_id']);
				$query = db_query("DELETE FROM `chat_banned_items` WHERE `ID` = '$word_id' LIMIT 1");
				if($query) {
					echo 1;
				} else {
					echo 0;
				}
			}
			
			elseif($_GET['act'] == 'change-owner' && isset($_POST['owner_name']) && !empty($_POST['owner_name']) && isset($_POST['owner_id']) && !empty($_POST['owner_id']) && isset($_POST['chat_id']) && !empty($_POST['chat_id']) && is_numeric($_POST['chat_id'])) {
				$owner_name = db_escape($_POST['owner_name']);
				$owner_id = db_escape($_POST['owner_id']);
				$chat_id = db_escape($_POST['chat_id']);
				
				$check_q = db_query("SELECT `ID` FROM `members` WHERE `username` = '$owner_name' && `ID` = '$owner_id' LIMIT 1");
				if(mysqli_num_rows($check_q) > 0) {
					$query = db_query("UPDATE `chat_room` SET `owner_name` = '$owner_name', `owner_id` = '$owner_id' WHERE `ID` = '$chat_id' LIMIT 1");
					if($query) {
						echo 1;
					} else {
						echo 0;
					}
				} else {
					echo 0;
				}
			}
			
			elseif($_GET['act'] == 'change-user-status' && isset($_POST['new_status']) && isset($_POST['target_id']) && !empty($_POST['target_id']) && is_numeric($_POST['target_id'])) {
				$new_status = db_escape($_POST['new_status']);
				$target_id = db_escape($_POST['target_id']);
				$stat = 0;
				$error = "";
				$user_status = "";
				if(strlen($user_status) > get_option("MAX_STATUS")) {
					$error = 'User status is too long.';
				} else {
					$check_q = db_query("SELECT `ID` FROM `members` WHERE `ID` = '$target_id' LIMIT 1");
					if(mysqli_num_rows($check_q) > 0) {
						$query = db_query("UPDATE `members` SET `user_status` = '$new_status' WHERE `ID` = '$target_id' LIMIT 1");
						if($query) {
							$stat = 1;
							if(empty($new_status)) {
								$user_status = get_option("DEFAULT_STATUS");
							} else {
								$user_status = $new_status;
							}
						} else {
							$error = "An error occured.";
						}
					} else {
						$error = "We couldn't find the user.";
					}
				}
				echo json_encode(
						array(
							'stat' => $stat,
							'error' => $error,
							'user_status' => $user_status
						)
					);
			}
			
			elseif($_GET['act'] == 'load-messages' && isset($_POST['chatid']) && !empty($_POST['chatid'])) {
				$chatid = db_escape($_POST['chatid']);
				
				$messages = db_query("SELECT `message`, `from_id`, `from_name`, `ID`, `time`, `type`, `mime`, `file_name` FROM `chat_messages` WHERE `chat_room` = '$chatid' ORDER BY `time` ASC");
				
				if (mysqli_num_rows($messages) > 0) {
					while ($row = mysqli_fetch_array($messages)) {
						$rec_message = html_entity_decode($row['message']);
						$map_i = 0;
						$message_old = preg_replace("/\:\:e\|\|(.*?)\:\:/", "<img ondragstart='return false;' alt='&#x$1' src='../include/web-imgs/blank.jpg' style='background-image: url(\"../include/web-imgs/emojis.png\");' class='emoji-link-small sprite sprite-$1' draggable='false' />", $rec_message);
						$message = preg_replace_callback('/(\ alt=\')(.*?)(\')/', function ($matches) {
							return $matches[1].str_replace('-', '&#x', $matches[2]).$matches[3];
						}, $message_old);
						
						if($row["type"] == "user_media_location") {
							$message = json_decode(json_encode(unserialize($message)), true);
						}

						echo "<div class='chat-msgs'>";

						if ($row['type'] == 'user') {
							echo "<div class='other-msgs'><div class='username'><a href='./index.php?manage-user=".$row['from_id']."'>" . $row['from_name'] . "</a><i attr-id='".$row[3]."' id='delete-message' style='vertical-align:middle' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></div><div class='other-usr-msg'>" . $message . "</div><div class='msg-time left'>" . date('H:i', $row['time']).'</div></div>';
						} elseif ($row['type'] == 'user_media_img') {
							echo "<div class='other-msgs'><div class='username'><a href='./index.php?manage-user=".$row['from_id']."'>" . $row['from_name'] . "</a><i attr-id='".$row[3]."' id='delete-message' style='vertical-align:middle' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></div><div class='other-usr-msg'><a href='." . $message . "' class='image-link'><div class='image-thumb'><img class='shared-images' src='." . $message . "' alt='' title=''/></div></a></div><div class='msg-time left'>" . date('H:i', $row['time']).'</div></div>';
						} elseif ($row['type'] == 'user_media_vid') {
							echo "<div class='other-msgs'><div class='username'><a href='./index.php?manage-user=".$row['from_id']."'>" . $row['from_name'] . "</a><i attr-id='".$row[3]."' id='delete-message' style='vertical-align:middle' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></div><div class='other-usr-msg'>";
							if ($row['mime'] != 'video/mp4' && $row['mime'] != 'video/webm' && $row['mime'] != 'video/ogg') {
								echo "<div class='file-bg'><a href='." . $message . "'><div class='file-bg-text'>Download</div></a></div>";
							} else {
								echo "<a href='." . $message . "' class='video-link'><div class='image-thumb'><video class='shared-vid' src='." . $message . "' type='" . $row['mime'] . "'><div class='file-bg'><div class='file-bg-text'>Download</div></div></video><div class='video-play'><i class='material-icons'>play_circle_outline</i></div></div></a>";
							}
							echo "</div><div class='msg-time left'>" . date('H:i', $row['time']).'</div></div>';
						} elseif ($row['type'] == 'user_media_file') {
							echo "<div class='other-msgs'><div class='username'><a href='./index.php?manage-user=".$row['from_id']."'>" . $row['from_name'] . "</a><i attr-id='".$row[3]."' id='delete-message' style='vertical-align:middle' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></div><div class='other-usr-msg'><p><i name='" . $row['file_name'] . "' href='." . $message . "' class='material-icons clickable shared-file large download-file'>archive</i></p><p class='file-name'>" . $row['file_name'] . "</p></div><div class='msg-time left'>" . date('H:i', $row['time']).'</div></div>';
						} elseif ($row['type'] == 'user_media_music') {
							echo "<div class='other-msgs'><div class='username'><a href='./index.php?manage-user=".$row['from_id']."'>" . $row['from_name'] . "</a><i attr-id='".$row[3]."' id='delete-message' style='vertical-align:middle' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></div><div class='other-usr-msg music-link'>";
							if ($row['mime'] == 'audio/mpeg' || $row['mime'] == 'audio/wav' || $row['mime'] == 'audio/ogg' || $row['mime'] == 'audio/mp3') {
								echo "<p><i href='." . $message . "' name='" . $row['file_name'] . "' class='material-icons clickable shared-file large download-file'>headset</i></p><p class='file-name'>" . $row['file_name'] . "</p><p class='shared-music'><audio src='." . $message . "' type='" . $row['mime'] . "' controls></audio></p>";
							} else {
								echo "<p><i href='." . $message . "' name='" . $row['file_name'] . "' class='material-icons clickable shared-file large download-file'>headset</i></p><p class='file-name'>" . $row['file_name'].'</p>';
							}
							echo "</div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
						} elseif ($row['type'] == 'user_media_voice_note') {
							echo "<div class='other-msgs'><div class='username'><a href='./index.php?manage-user=".$row['from_id']."'>" . $row['from_name'] . "</a><i attr-id='".$row[3]."' id='delete-message' style='vertical-align:middle' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></div><div class='other-usr-msg voice-note'>";
							echo "<p class='shared-music'><audio src='." . $message . "' type='audio/wav' controls></audio></p>";
							echo "</div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
						} elseif ($row['type'] == 'system') {
							echo "<div class='system'>" . $message.'</div>';
						}
						echo "</div>";
					}
				} else {
					echo "There is no message.";
				}
			}

		}
	}
}
