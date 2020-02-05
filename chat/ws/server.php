<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////          /////////////////////////////////////////////////////
////////////////////////////////////////////////  CONFIG  /////////////////////////////////////////////////////
////////////////////////////////////////////////          /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

define('HOST', '127.0.0.1');     // Database domain or IP number
define('USER', 'root');    // Database username
define('PASSWORD', '');    // Database password
define('DATABASE', 'connectyou');    // Database name

$port = '8080'; // Websocket Port

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

$connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
date_default_timezone_set(timezone());
$null = null; // Null Variable

// Create TCP/IP stream socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
// Reuseable port
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

// Bind socket to specified host
socket_bind($socket, 0, $port);

// Listen to port
socket_listen($socket);

// Create & add listning socket to the list
$clients = array($socket);
$user_list = array();
$user_ip_list = array();

// Start endless loop
while (true) {
    // Manage multiple connections
    $changed = $clients;
    // Returns the socket resources in $changed array
    socket_select($changed, $null, $null, 0, 10);

    // Check for new socket
    if (in_array($socket, $changed)) {
        $socket_new = socket_accept($socket); //accpet new socket
        $clients[] = $socket_new; // Add socket to client array

        $header = socket_read($socket_new, 1024); // Read data sent by the socket
        perform_handshaking($header, $socket_new, "127.0.0.1", $port); // Perform websocket handshake

        // Make room for new socket
        $found_socket = array_search($socket, $changed);
        unset($changed[$found_socket]);
    }
    // Loop through all connected sockets
    foreach ($changed as $changed_socket) {

        // Check for any incomming data
        while (@socket_recv($changed_socket, $buf, 16777216, 0) >= 1) {
            $received_text = unmask($buf); // Unmask Data
            $chat_msg = json_decode($received_text); // Decode JSON Data
            $status = 1;
            $msg_exists = 0;
			$send_error = 0;
            if (isset($chat_msg)) {
                $connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
                if (!$connection) {
                    die('Connect Error: '.mysqli_connect_error());
                }
				mysqli_query($connection, "set names 'utf8'");
                if (isset($chat_msg->name)) {
                    $user_name = mysqli_real_escape_string($connection, $chat_msg->name); // Username of the client
                }
                if (isset($chat_msg->message)) {
                    $user_message = mysqli_real_escape_string($connection, htmlentities($chat_msg->message)); // Message
                    $non_escaped_msg = $chat_msg->message; // Message

                    $msg_exists = 1;
                }
                if (isset($chat_msg->room)) {
                    $room = $chat_msg->room; // Hashed Chat ID
                    $non_room = mysqli_real_escape_string($connection, chat_id($room)); // Chat ID
                }
                if (isset($chat_msg->non_room)) {
                    $non_room = mysqli_real_escape_string($connection, $chat_msg->non_room); // Chat ID
					$room = keymaker($non_room);
                }
                if (isset($chat_msg->iduser)) {
                    $user_id = mysqli_real_escape_string($connection, $chat_msg->iduser); // User ID
                }
                if (isset($chat_msg->msgtype)) {
                    $type = mysqli_real_escape_string($connection, $chat_msg->msgtype);    // Message Type // 0 - Kick // 1 - Send Message // 2 - Leave // 3 - Create a New Group // 4 - Invite
                    if ($type == 4 && isset($chat_msg->users)) {
                        $users = $chat_msg->users;
                    }
                    if (($type == 5 && isset($chat_msg->pics)) || ($type == 6 && isset($chat_msg->pics))) {
                        $pics = $chat_msg->pics;
                    }
                    if ($type == 8 && isset($chat_msg->share_media) && isset($chat_msg->file)) {
						if($chat_msg->file == "location") {
							$lat = number_format($chat_msg->share_media->lat, 10);
							$lng = number_format($chat_msg->share_media->lng, 10);
							$media = array("lat" => $lat, "lng" => $lng, "acc" => $chat_msg->share_media->acc);
						} else {
							$media = mysqli_real_escape_string($connection, $chat_msg->share_media);
						}
                        $file = mysqli_real_escape_string($connection, $chat_msg->file);
                        if (isset($chat_msg->mime) && !empty($chat_msg->mime)) {
                            $mime = mysqli_real_escape_string($connection, $chat_msg->mime);
                        }
                        if (isset($chat_msg->file_names) && !empty($chat_msg->file_names)) {
                            $file_name = mysqli_real_escape_string($connection, $chat_msg->file_names);
                        }
                    }
                    if ($type == 10 && isset($chat_msg->new_un)) {
                        $target_new_name = mysqli_real_escape_string($connection, $chat_msg->new_un);
                    }
                    if ($type == 11 && isset($chat_msg->new_email)) {
                        $target_new_email = mysqli_real_escape_string($connection, $chat_msg->new_email);
                    }
					if($type == 14) {
						if(isset($chat_msg->block_year) && !empty($chat_msg->block_year)) {
							$block_year = mysqli_real_escape_string($connection, $chat_msg->block_year);
						} else {
							$block_year = date("Y");
						}
						if(isset($chat_msg->block_month) && !empty($chat_msg->block_month)) {
							$block_month = mysqli_real_escape_string($connection, $chat_msg->block_month);
						} else {
							$block_month = date("m");
						}
						if(isset($chat_msg->block_day) && !empty($chat_msg->block_day)) {
							$block_day = mysqli_real_escape_string($connection, $chat_msg->block_day);
						} else {
							$block_day = date("d");
						}
						if(isset($chat_msg->block_hour) && !empty($chat_msg->block_hour)) {
							$block_hour = mysqli_real_escape_string($connection, $chat_msg->block_hour);
						} else {
							$block_hour = date("H");
						}
						if(isset($chat_msg->block_minute) && !empty($chat_msg->block_minute)) {
							$block_minute = mysqli_real_escape_string($connection, $chat_msg->block_minute);
						} else {
							$block_minute = date("i");
						}
						if(isset($chat_msg->block_reason)) {
							$block_reason = mysqli_real_escape_string($connection, $chat_msg->block_reason);
						} else {
							$block_reason = "";
						}
						if(isset($chat_msg->bantype)) {
							if($chat_msg->bantype == "temp") {
								$bantype = "temp";
							} else {
								$bantype = "perm";
							}
						}
					}
					if($type == 16) {
						if(isset($chat_msg->select_ban)) {
							$select_ban = $chat_msg->select_ban;
						}
					}
					if($type == 19) {
						if(isset($chat_msg->int)) {
							$int = $chat_msg->int;
						}
					}
                } else {
                    $type = -1;
                }
                if (isset($chat_msg->target)) {
                    $target_id = mysqli_real_escape_string($connection, $chat_msg->target);    // Username of Target Client
                }
                if (isset($chat_msg->rmtype)) {
                    $roomtype = mysqli_real_escape_string($connection, $chat_msg->rmtype);    // Room Type // 0 - Personal Message // 1 - Group
                }
                if (isset($chat_msg->time)) {
                    $time = mysqli_real_escape_string($connection, $chat_msg->time);    // Client's Time
                }
                if (isset($chat_msg->chatname)) {
                    $chat_name = mysqli_real_escape_string($connection, $chat_msg->chatname);    // Chat Name
                }
                if (isset($chat_msg->token)) {
                    $token = mysqli_real_escape_string($connection, $chat_msg->token);    // Token
                }
                if (isset($chat_msg->k)) {
                    $k = mysqli_real_escape_string($connection, $chat_msg->k);    // An integer to handle "Resend Message System" in case of losing websocket connection.
                }
                if (isset($chat_msg->chat_img)) {
                    $img = mysqli_real_escape_string($connection, $chat_msg->chat_img);    // Chat Picture
                }
                if (isset($chat_msg->edit_grp_name)) {
                    $grp_name = mysqli_real_escape_string($connection, $chat_msg->edit_grp_name); // Group Name
                    $non_grp_name = $chat_msg->edit_grp_name; // Group Name
                }
                if (isset($chat_msg->target_id)) {
                    $target_id = mysqli_real_escape_string($connection, $chat_msg->target_id); // Target ID
                }
				if (isset($chat_msg->ip_address)) {
					$ip_Address = $chat_msg->ip_address;
				} else {
					$ip_Address = NULL;
				}

                // Prepare Data to Be Sent to Client
                if ($token === getToken($user_id)) {
                    $usrname_query = mysqli_query($connection, "SELECT `username` FROM `members` WHERE `token` = '$token' && `ID` = '$user_id' LIMIT 1");
                    $row = mysqli_fetch_array($usrname_query);
                    $sql_username = $row[0];

                    if ($sql_username === $user_name) {
                        if (!in_array_r($changed_socket, $user_list)) {
                            $user_list[$user_id][] = $changed_socket;
                            mysqli_query($connection, "UPDATE `members` SET `online` = 1 WHERE `ID` = '$user_id' LIMIT 1");
							update_online_status($user_id, 1);
                        }
						if (!in_array_r($ip_Address, $user_ip_list) && !empty($ip_Address)) {
                            $user_ip_list[$user_id][] = $ip_Address;
                        }
						if(!blockUsers($user_id)) {
							/*********************************************/
							/*************** SEND MESSAGE ****************/
							/*********************************************/

							if ($type == 1 && userInRoom($user_id, $non_room)) {
								$query = mysqli_query($connection, "SELECT `ID` FROM `chat_members` WHERE `user_id` = '$user_id' && `chat_room` = '$non_room'");    // Check if the user is in the room
								if (mysqli_num_rows($query) == 1) {
									// Check if the user is in the room

									$timestamp = time();

									if (save_messages() == 1) {
										mysqli_query($connection, "INSERT INTO `chat_messages`(
																		`chat_room`,
																		`message`,
																		`from_id`,
																		`from_name`,
																		`time`,
																		`type`
																	) VALUES (
																		'$non_room',
																		'$user_message',
																		'$user_id',
																		'$user_name',
																		'$timestamp',
																		'user'
																	)");

										$msg_q = mysqli_query($connection, "SELECT `ID` FROM `chat_messages` WHERE `chat_room` = '$non_room' ORDER BY `ID` DESC LIMIT 1");
										$msg_r = mysqli_fetch_assoc($msg_q);
										$msg_id = $msg_r['ID'];
										$users = mysqli_query($connection, "SELECT user_id FROM `chat_members` WHERE `chat_room` = '$non_room'");
										$users_id_array = array();

										while ($row = mysqli_fetch_array($users)) {
											$users_id_array[] = $row[0];
										}

										$users_id = array_diff($users_id_array, array($user_id));

										foreach ($users_id as $usr_id) {
											mysqli_query($connection, "INSERT INTO `chat_unread`(
																			`chat_room`,
																			`msg_id`,
																			`usr_id`
																		) VALUES (
																			'$non_room',
																			'$msg_id',
																			'$usr_id'
																		)");
										}
									}

									mysqli_query($connection, "UPDATE `chat_room` SET `last_message_time` = '$timestamp', `last_message` = '$user_message' WHERE ID = '$non_room'");
									mysqli_query($connection, "UPDATE `chat_members` SET `last_message_time` = '$timestamp' WHERE `chat_room` = '$non_room'");

									/*** Get the Chat Name ***/
									$name_q = mysqli_query($connection, "SELECT `chat_name` FROM `chat_room` WHERE `id_hash` = '$room' LIMIT 1");
									$name_r = mysqli_fetch_assoc($name_q);
									$name = $name_r['chat_name'];
									/*************************/

										if ($roomtype == 1) {
											// Personal Message


											$msgs = mysqli_query($connection, "SELECT `ID` FROM `chat_members` WHERE `chat_room` = '$non_room' && (`status` = 0 || `status` = 3)");

											if (mysqli_num_rows($msgs) == 1) {
												// This is the first message of a PM => Make the chat visible on the other user


												mysqli_query($connection, "UPDATE `chat_members` SET `status` = 1 WHERE `chat_room` = '$non_room' && `user_id` != '$user_id'");

												if(empty(profilePicture($user_id))) {
													$user_pic = "";
												} else {
													$user_pic = picture_destination().profilePicture($user_id);
												}

												$response_text = mask(
																	json_encode(
																			array(
																				'type' => 'usermsg',
																				'name' => $user_name,
																				'message' => stripslashes(ban_word($non_escaped_msg)),
																				'userid' => $user_id,
																				'do' => 1,
																				'chat_id' => $room,
																				'chat_name' => $name,
																				'roomtype' => $roomtype,
																				'time' => $time,
																				'k' => $k,
																				'img' => $user_pic,
																			)
																	)
												);
												send_message($response_text, $non_room);
											} else {    // Chat room is visible for both of the users

												if(empty(profilePicture($user_id))) {
													$user_pic = "";
												} else {
													$user_pic = picture_destination().profilePicture($user_id);
												}

												$response_text = mask(
																	json_encode(
																			array(
																				'type' => 'usermsg',
																				'name' => $user_name,
																				'message' => stripslashes(ban_word($non_escaped_msg)),
																				'userid' => $user_id,
																				'do' => 2,
																				'chat_id' => $room,
																				'chat_name' => $name,
																				'roomtype' => $roomtype,
																				'time' => $time,
																				'k' => $k,
																				'img' => $user_pic,
																			)
																	)
												);
												send_message($response_text, $non_room);
											}
										} elseif ($roomtype == 0) {
											// Group
											if(empty(groupPicture($non_room))) {
												$group_pic = "";
											} else {
												$group_pic = picture_destination().groupPicture($non_room);
											}

											$response_text = mask(
																json_encode(
																		array(
																			'type' => 'usermsg',
																			'name' => $user_name,
																			'message' => stripslashes(ban_word($non_escaped_msg)),
																			'userid' => $user_id,
																			'do' => 2,
																			'chat_id' => $room,
																			'chat_name' => $name,
																			'roomtype' => $roomtype,
																			'time' => $time,
																			'k' => $k,
																			'img' => $group_pic,
																		)
																)
											);
											send_message($response_text, $non_room);
										}
								} else {
									$response_text = mask(
														json_encode(
																array(
																	'type' => 'system',
																	'message' => 'You are not in this conversation.',
																	'userid' => $user_id,
																	'roomtype' => $roomtype,
																	'time' => $time,
																	'k' => $k
																)
														)
										);
									send_message($response_text, $non_room);
								}
							}

							/*********************************************/
							/****************** KICK *********************/
							/*********************************************/

							elseif ($type == 0 && userInRoom($user_id, $non_room)) {

								/*** Get the Group Admin ***/
								$check_owner_query = mysqli_query($connection, "SELECT `owner_id` FROM `chat_room` WHERE `ID` = '$non_room'");
								$check_owner_row = mysqli_fetch_assoc($check_owner_query);
								$check_owner = $check_owner_row['owner_id'];
								/***************************/

								/*** Get the Target ID ***/
								$query = mysqli_query($connection, "SELECT `username` FROM `members` WHERE `ID` = '$target_id'");
								$row = mysqli_fetch_assoc($query);
								$target_name = $row['username'];
								/*************************/

								if ($check_owner == $user_id) {
									// Check the group admin and the user

									if ($roomtype == 0) {
										if (mysqli_num_rows($query) == 1) {
											mysqli_query($connection, "UPDATE `chat_members` SET `status` = 4 WHERE `user_id` = '$target_id' && `chat_room` = '$non_room'");    // Set user status = 4 to show the user that you have kicked.

											$time = time();

											if (save_messages() == 1) {
												mysqli_query($connection, "INSERT INTO `chat_messages`(
																				`chat_room`,
																				`message`,
																				`from_id`,
																				`from_name`,
																				`time`,
																				`type`
																			) VALUES (
																				'$non_room',
																				'$target_name was kicked.',
																				'$user_id',
																				'$user_name',
																				'$time',
																				'system'
																			)");
											}
											mysqli_query($connection, "UPDATE `chat_room` SET `last_message_time` = '$time', `last_message` = '$target_name was kicked.' WHERE `ID` = '$non_room'");

											$response_text = mask(
															json_encode(
																	array(
																		'type' => 'kick',
																		'message' => $target_name.' was kicked.',
																		'chat_id' => $room,
																		'userid' => $user_id,
																		'target' => $target_name,
																		'target_id' => $target_id,
																		'time' => $time
																	)
															)
											);
											send_message($response_text, $non_room);
											send_message_to_user($response_text, $target_id);

										} else {
											echo "Invalid username during the kick progress.\n";
											$send_error = 1;
											$err_text = mask(json_encode(array('error_stat' => 1, 'error' => 'Invalid username.', 'msg_exists' => $msg_exists, 'userid' => $user_id)));
											send_message_to_user($err_text, $user_id);
										}
									} else {
										echo "Invalid room type during the kick progress.\n";
										$send_error = 1;
										$err_text = mask(json_encode(array('error_stat' => 1, 'error' => 'Invalid room type.', 'msg_exists' => $msg_exists, 'userid' => $user_id)));
										send_message_to_user($err_text, $user_id);
									}
								} else {
									echo "An unauthorized user has attempted to kick.\n";
									$send_error = 1;
									$err_text = mask(json_encode(array('error_stat' => 1, 'error' => 'You do not have permission to kick a user.', 'msg_exists' => $msg_exists, 'userid' => $user_id)));
									send_message_to_user($err_text, $user_id);
								}
							}

							/*********************************************/
							/******************* LEAVE *******************/
							/*********************************************/

							elseif ($type == 2 && userInRoom($user_id, $non_room)) {

								/*** Get the Room Type ***/
								$query = mysqli_query($connection, "SELECT `type` FROM `chat_room` WHERE `ID` = '$non_room' LIMIT 1");
								$row = mysqli_fetch_assoc($query);
								$roomType = $row['type'];
								/*************************/

								$token = bin2hex(openssl_random_pseudo_bytes(32));
								mysqli_query($connection, "UPDATE `members` SET `token` = '$token' WHERE `ID` = '$user_id' LIMIT 1");
								if ($roomType == 0) {
									// Group Conversation

									/*** Get User Number in the Group ***/
									$num_query = mysqli_query($connection, "SELECT `ID` FROM `chat_members` WHERE `chat_room` = '$non_room'");
									$number = mysqli_num_rows($num_query);
									/************************************/
									if ($number > 1) {
										// There are more than 1 users in the group

										$time = time(); // Current time
										mysqli_query($connection, "DELETE FROM `chat_members` WHERE `user_id` = '$user_id' && `chat_room` = '$non_room'");

										if (save_messages() == 1) {
											mysqli_query($connection, "INSERT INTO `chat_messages`(
																				`chat_room`,
																				`message`,
																				`from_id`,
																				`from_name`,
																				`time`,
																				`type`
																			) VALUES (
																				'$non_room',
																				'$user_name has left.',
																				'$user_id',
																				'$user_name',
																				'$time',
																				'system'
																			)");
										}

										mysqli_query($connection, "UPDATE chat_room SET last_message_time = '$time', last_message = '$user_name has left.' WHERE ID = '$non_room'");

										$response_text = mask(
													json_encode(
															array(
																'type' => 'left',
																'roomtype' => $roomType,
																'message' => $user_name.' has left.',
																'chat_id' => $room,
																'userid' => $user_id,
																'time' => $time,
																'name' => $user_name,
																'roomtype' => $roomType,
																'token' => $token
															)
													)
										);

										send_message_to_user($response_text, $user_id);
									} else {    // There is only one person in the group
										$temp_token = bin2hex(openssl_random_pseudo_bytes(32));
										$insert = mysqli_query($connection, "UPDATE `chat_room` SET `temp_token` = '$temp_token' WHERE `ID` = '$non_room' LIMIT 1");
										if ($insert) {
											$url = url().'/action.php?act=delete-group-pic&usr_token='.$token.'&chat_token='.$temp_token.'&chat_id='.$room.'&user_id='.$user_id.'&user_name='.$username;
											$curl = curl_init();
											curl_setopt($curl, CURLOPT_URL, $url);
											curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
											curl_setopt($curl, CURLOPT_HEADER, false);
											$del = curl_exec($curl);
											curl_close($curl);
											if ($del == 1) {
												mysqli_query($connection, "DELETE FROM `chat_members` WHERE `chat_room` = '$non_room'");
												mysqli_query($connection, "DELETE FROM `chat_room` WHERE `ID` = '$non_room'");
												if (save_messages() == 1) {
													mysqli_query($connection, "DELETE FROM `chat_messages` WHERE `chat_room` = '$non_room'");
													mysqli_query($connection, "DELETE FROM `chat_unread` WHERE `chat_room` = '$non_room'");
												}

												$response_text = mask(
															json_encode(
																	array(
																		'type' => 'left',
																		'chat_id' => $room,
																		'userid' => $user_id,
																		'token' => $token,
																	)
															)
												);
												send_message_to_user($response_text, $user_id);
											} else {
												echo "Group picture could not be deleted.\n";
												$response_text = mask(
															json_encode(
																	array(
																		'userid' => $user_id,
																		'token' => $token,
																		'error_stat' => 1,
																		'error' => 'Group picture could not be deleted.',
																		'msg_exists' => $msg_exists,
																		'userid' => $user_id,
																	)
															)
													);
												send_message($response_text, $non_room);
											}
										} else {
											$response_text = mask(
															json_encode(
																	array(
																		'userid' => $user_id,
																		'token' => $token,
																	)
															)
												);
											send_message($response_text, $non_room);
										}
									}
								} else {    // Personal Message
									/*** Check if both of the users deleted the chat ***/
									$num_query = mysqli_query($connection, "SELECT `ID` FROM `chat_members` WHERE `chat_room` = '$non_room' && `status` = 3");
									$number = mysqli_num_rows($num_query);
									/***************************************************/

									if ($number == 0) {
										// Noone has deleted the chat yet.

										$time = time(); // Current time
										mysqli_query($connection, "UPDATE `chat_members` SET `status` = 3,`last_load_time` = '$time' WHERE `user_id` = '$user_id' && `chat_room` = '$non_room'");

										$response_text = mask(
													json_encode(
															array(
																'type' => 'left',
																'roomtype' => $roomType,
																'chat_id' => $room,
																'userid' => $user_id,
																'time' => $time,
																'token' => $token,
															)
													)
										);
										send_message($response_text, $non_room);
									} else {    // One of the users has deleted the chat before and now the other user is deleting, too.
										$temp_token = bin2hex(openssl_random_pseudo_bytes(32));
										$insert = mysqli_query($connection, "UPDATE `chat_room` SET `temp_token` = '$temp_token' WHERE `ID` = '$non_room' LIMIT 1");
										if ($insert) {
											$url = url().'/action.php?act=delete-group-pic&usr_token='.$token.'&chat_token='.$temp_token.'&chat_id='.$room.'&user_id='.$user_id.'&user_name='.$user_name;
											$curl = curl_init();
											curl_setopt($curl, CURLOPT_URL, $url);
											curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
											curl_setopt($curl, CURLOPT_HEADER, false);
											$del = curl_exec($curl);
											curl_close($curl);
											if ($del == 1) {
												mysqli_query($connection, "DELETE FROM `chat_members` WHERE `chat_room` = '$non_room'");
												mysqli_query($connection, "DELETE FROM `chat_room` WHERE `ID` = '$non_room'");
												if (save_messages() == 1) {
													mysqli_query($connection, "DELETE FROM `chat_messages` WHERE `chat_room` = '$non_room'");
													mysqli_query($connection, "DELETE FROM `chat_unread` WHERE `chat_room` = '$non_room'");
												}
												$respond_text = mask(
															json_encode(
																	array(
																		'type' => 'left',
																		'chat_id' => $room,
																		'userid' => $user_id,
																		'token' => $token,
																	)
															)
												);
												send_message_to_user($respond_text, $user_id);
											} else {
												echo "Shared files could not be deleted.\n";
												$response_text = mask(
															json_encode(
																	array(
																		'userid' => $user_id,
																		'token' => $token,
																		'error_stat' => 1,
																		'error' => 'Could not deleted.',
																		'msg_exists' => $msg_exists,
																		'userid' => $user_id,
																	)
															)
													);
												send_message($response_text, $non_room);
											}
										}
									}
								}
							}

							/*********************************************/
							/************* CREATE A NEW GROUP ************/
							/*********************************************/

							elseif ($type == 3) {
								$token2 = bin2hex(openssl_random_pseudo_bytes(32));
								mysqli_query($connection, "UPDATE `members` SET `token` = '$token2' WHERE `ID` = '$user_id' LIMIT 1");

								$group_pic = groupPicture($non_room);
								$response_text = mask(
											json_encode(
													array(
														'chat_id' => $room,
														'message' => '',
														'userid' => $user_id,
														'do' => 1,
														'chat_name' => $chat_name,
														'time' => $time,
														'token' => $token2,
														'img' => $group_pic,
													)
											)
								);
								send_message($response_text, $non_room);
							}

							/*******************************************/
							/************* INVITE A NEW USER ***********/
							/*******************************************/

							elseif ($type == 4 && userInRoom($user_id, $non_room)) {
								$time = time();
								$status = 0;

								foreach($users as $usr_id) {
									$user_query = mysqli_query($connection, "SELECT `username` FROM `members` WHERE `ID` = '$usr_id' LIMIT 1");
									if (mysqli_num_rows($user_query) == 1) {
										$user_row = mysqli_fetch_assoc($user_query);
										$user = $user_row['username'];

										$stat_query = mysqli_query($connection, "SELECT `status` FROM `chat_members` WHERE
																											`chat_room` = '$non_room'
																											&& `user_id` = '$usr_id'
																											&& `status` != 2
																											&& `status` != 1
																										");

										if (mysqli_num_rows($stat_query) == 1) {
											mysqli_query($connection, "UPDATE `chat_members` SET `status` = 2, `last_load_time` = '$time' WHERE `chat_room` = '$non_room' && `user_id` = '$usr_id'");
											mysqli_query($connection, "UPDATE `chat_members` SET `last_message_time` = '$time' WHERE `chat_room` = '$non_room'");
											mysqli_query($connection, "UPDATE `chat_room` SET `last_message_time` = '$time', `last_message` = '$user joined.' WHERE `ID` = '$non_room' LIMIT 1");
											if (save_messages() == 1) {
												mysqli_query($connection, "INSERT INTO `chat_messages`(
																						`chat_room`,
																						`message`,
																						`from_id`,
																						`from_name`,
																						`time`,
																						`type`
																					) VALUES (
																						'$non_room',
																						'$user joined.',
																						'$usr_id',
																						'$user',
																						'$time',
																						'system'
																					)");
											}
											if(empty(profilePicture($usr_id))) {
												$user_pic = "";
											} else {
												$user_pic = picture_destination().profilePicture($usr_id);
											}

											if(isOnline($usr_id)) {
												$online_text = "<p id='online-status-all' attr-id='".$usr_id."' class='green-text text-darken-2'>Online</p>";
											} else {
												$online_text = "<p id='online-status-all' attr-id='".$usr_id."' class='red-text text-darken-2'>Offline</p>";
											}

											$inv_text = mask(
														json_encode(
																array(
																	'type' => 'join',
																	'message' => $user.' joined.',
																	'target' => $user,
																	'userid' => $user_id,
																	'do' => 2,
																	'chat_id' => $room,
																	'chat_name' => $chat_name,
																	'roomtype' => 0,
																	'time' => $time,
																	'img' => $user_pic,
																	'online_text' => $online_text
																)
														)
												);
										} else {
											if ($usr_id > 0) {
												mysqli_query($connection, "INSERT INTO `chat_members`(
																					`chat_room`,
																					`user_name`,
																					`user_id`,
																					`last_message_time`,
																					`status`,
																					`last_load_time`
																				) VALUES (
																					'$non_room',
																					'$user',
																					'$usr_id',
																					'$time',
																					2,
																					'$time'
																				)");

												if (save_messages() == 1) {
													mysqli_query($connection, "INSERT INTO `chat_messages`(
																						`chat_room`,
																						`message`,
																						`from_id`,
																						`from_name`,
																						`time`,
																						`type`
																					) VALUES (
																						'$non_room',
																						'$user joined.',
																						'$usr_id',
																						'$user',
																						'$time',
																						'system'
																					)");
												}

												mysqli_query($connection, "UPDATE `chat_room` SET `last_message_time` = '$time',`last_message` = '$user joined.' WHERE `ID` = '$non_room' LIMIT 1");
												mysqli_query($connection, "UPDATE `chat_members` SET `last_message_time` = '$time' WHERE `chat_room` = '$non_room'");

												if(empty(profilePicture($usr_id))) {
													$user_pic = "";
												} else {
													$user_pic = picture_destination().profilePicture($usr_id);
												}

												if(isOnline($usr_id)) {
													$online_text = "<p id='online-status-all' attr-id='".$usr_id."' class='green-text text-darken-2'>Online</p>";
												} else {
													$online_text = "<p id='online-status-all' attr-id='".$usr_id."' class='red-text text-darken-2'>Offline</p>";
												}

												$inv_text = mask(
															json_encode(
																	array(
																		'type' => 'join',
																		'message' => $user.' joined.',
																		'target' => $user,
																		'userid' => $user_id,
																		'do' => 1,
																		'chat_id' => $room,
																		'chat_name' => $chat_name,
																		'roomtype' => 0,
																		'time' => $time,
																		'img' => $user_pic,
																		'online_text' => $online_text
																	)
															)
													);
											}
										}
									}
									send_message($inv_text, $non_room);
								}


								if(empty(profilePicture($user_id))) {
									$user_pic = "";
								} else {
									$user_pic = picture_destination().profilePicture($user_id);
								}

								$response_text = mask(
											json_encode(
													array(
														'chat_id' => $room,
														'message' => '',
														'userid' => $user_id,
														'do' => 2,
														'chat_name' => $chat_name,
														'time' => $time,
														'img' => $user_pic,
													)
											)
								);
								send_message($response_text, $non_room);
							}

							/**********************************************/
							/************* UPDATE PROFILE PHOTO ***********/
							/**********************************************/

							elseif ($type == 5) {
								$status = 0;
								$query = mysqli_query($connection, "SELECT `chat_room` FROM `chat_members` WHERE `user_id` = '$user_id'");
								$result = mysqli_fetch_array($query);

								foreach ($result as $row) {
									$update_text = mask(
												json_encode(
														array(
															'chat_id' => keymaker($row[0]),
															'message' => '',
															'userid' => $user_id,
															'name' => $user_name,
															'do' => 3,
															'img' => $img,
															'roomtype' => 1,
														)
												)
									);
									send_message($update_text, $row[0]);
								}
							}

							/********************************************/
							/************* UPDATE GROUP PHOTO ***********/
							/********************************************/

							elseif ($type == 6 && userInRoom($user_id, $non_room)) {
								if (chatType($non_room) == 0) {
									$owner_query = mysqli_query($connection, "SELECT `owner_id` FROM `chat_room` WHERE `ID` = '$non_room' LIMIT 1");
									$owner_row = mysqli_fetch_assoc($owner_query);

									if ($owner_row['owner_id'] == $user_id || isAdmin($user_id)) {
										$response_text = mask(
													json_encode(
															array(
																'chat_id' => $room,
																'message' => '',
																'userid' => $user_id,
																'name' => $user_name,
																'do' => 4,
																'img' => $img,
																'roomtype' => 0,
															)
														)
													);
										send_message($response_text, $non_room);
										if(isAdmin($user_id)) {
											$response = mask(json_encode(array('admin_type' => "change_group_photo", 'content' => $img)));
											send_message_to_user($response, $user_id);
										}
									} else {
										$err_text = mask(json_encode(array('error_stat' => 1, 'error' => 'You do not have permission to change group picture.', 'msg_exists' => $msg_exists, 'userid' => $user_id)));
										echo "An unauthorized user has attempted to change group picture.\n";
										send_message_to_user($err_text, $user_id);
										$send_error = 1;
									}
								} else {
									$err_text = mask(json_encode(array('error_stat' => 1, 'error' => 'Invalid chat type.', 'msg_exists' => $msg_exists, 'userid' => $user_id)));
									echo "Invalid chat type.\n";
									$send_error = 1;
									send_message_to_user($err_text, $user_id);
								}
							}

							/********************************************/
							/************** CHANGE GROUP NAME ***********/
							/********************************************/

							elseif ($type == 7 && userInRoom($user_id, $non_room)) {
								if (chatType($non_room) == 0) {
									$owner_query = mysqli_query($connection, "SELECT `owner_id`, `owner_name` FROM `chat_room` WHERE `ID` = '$non_room' LIMIT 1");
									$owner_row = mysqli_fetch_assoc($owner_query);

									if ($owner_row['owner_id'] == $user_id && $owner_row['owner_name'] == $user_name) {
										if (strlen($grp_name) <= max_group_name()) {
											$time = time();
											mysqli_query($connection, "UPDATE `chat_room` SET `chat_name` = '$grp_name' WHERE `ID` = '$non_room' LIMIT 1");
											if(save_messages() == 1) {
												mysqli_query($connection, "INSERT INTO `chat_messages`(
																					`chat_room`,
																					`message`,
																					`from_id`,
																					`from_name`,
																					`time`,
																					`type`
																				) VALUES (
																					'$non_room',
																					'Group name has been changed to \"$grp_name\".',
																					'$user_id',
																					'$user_name',
																					'$time',
																					'system'
																				)");
											}
											$response_text = mask(
														json_encode(
																array(
																	'chat_id' => $room,
																	'message' => 'Group name has been changed to "'.$non_grp_name.'".',
																	'type' => 'system',
																	'userid' => $user_id,
																	'do' => 5,
																	'roomtype' => 0,
																	'grp_name' => $non_grp_name,
																)
															)
														);
											send_message($response_text, $non_room);
										} else {
											$err_text = mask(json_encode(array('error_stat' => 1, 'error' => 'Group name is too long.', 'msg_exists' => $msg_exists, 'userid' => $user_id)));
											echo "Group name is too long.\n";
											send_message_to_user($err_text, $user_id);
											$send_error = 1;
										}
									} else {
										$err_text = mask(json_encode(array('error_stat' => 1, 'error' => 'You do not have permission to change group name.', 'msg_exists' => $msg_exists, 'userid' => $user_id)));
										echo "An unauthorized user has attempted to change the group name.\n";
										send_message_to_user($err_text, $user_id);
										$send_error = 1;
									}
								} else {
									$err_text = mask(json_encode(array('error_stat' => 1, 'error' => 'Invalid chat type.', 'msg_exists' => $msg_exists, 'userid' => $user_id)));
									echo "Invalid chat type.\n";
									send_message_to_user($err_text, $user_id);
									$send_error = 1;
								}
							}

							/************************************************************/
							/************** SEND IMAGE / AUDIO / MUSIC / FILE ***********/
							/************************************************************/

							elseif ($type == 8 && userInRoom($user_id, $non_room)) {
								$query = mysqli_query($connection, "SELECT `ID` FROM `chat_members` WHERE `user_id` = '$user_id' && `chat_room` = '$non_room'");    // Check if the user is in the room
								if (mysqli_num_rows($query) == 1) {
									// Check if the user is in the room

									$timestamp = time();

									if (save_messages() == 1) {
										if ($file == 'img') {
											mysqli_query($connection, "INSERT INTO `chat_messages`(
																			`chat_room`,
																			`message`,
																			`from_id`,
																			`from_name`,
																			`time`,
																			`type`,
																			`file_name`
																		) VALUES (
																			'$non_room',
																			'$media',
																			'$user_id',
																			'$user_name',
																			'$timestamp',
																			'user_media_img',
																			'$file_name'
																		)");
										} elseif ($file == 'vid') {
											mysqli_query($connection, "INSERT INTO `chat_messages`(
																			`chat_room`,
																			`message`,
																			`from_id`,
																			`from_name`,
																			`time`,
																			`type`,
																			`mime`,
																			`file_name`
																		) VALUES (
																			'$non_room',
																			'$media',
																			'$user_id',
																			'$user_name',
																			'$timestamp',
																			'user_media_vid',
																			'$mime',
																			'$file_name'
																		)");
										} elseif ($file == 'file') {
											mysqli_query($connection, "INSERT INTO `chat_messages`(
																			`chat_room`,
																			`message`,
																			`from_id`,
																			`from_name`,
																			`time`,
																			`type`,
																			`file_name`
																		) VALUES (
																			'$non_room',
																			'$media',
																			'$user_id',
																			'$user_name',
																			'$timestamp',
																			'user_media_file',
																			'$file_name'
																		)");
										} elseif ($file == 'music') {
											mysqli_query($connection, "INSERT INTO `chat_messages`(
																			`chat_room`,
																			`message`,
																			`from_id`,
																			`from_name`,
																			`time`,
																			`type`,
																			`file_name`,
																			`mime`
																		) VALUES (
																			'$non_room',
																			'$media',
																			'$user_id',
																			'$user_name',
																			'$timestamp',
																			'user_media_music',
																			'$file_name',
																			'$mime'
																		)");
										} elseif ($file == 'voice_note') {
											mysqli_query($connection, "INSERT INTO `chat_messages`(
																			`chat_room`,
																			`message`,
																			`from_id`,
																			`from_name`,
																			`time`,
																			`type`
																		) VALUES (
																			'$non_room',
																			'$media',
																			'$user_id',
																			'$user_name',
																			'$timestamp',
																			'user_media_voice_note'
																		)");
										} elseif ($file == 'location') {
											$db_media = serialize($media);
											mysqli_query($connection, "INSERT INTO `chat_messages`(
																			`chat_room`,
																			`message`,
																			`from_id`,
																			`from_name`,
																			`time`,
																			`type`
																		) VALUES (
																			'$non_room',
																			'$db_media',
																			'$user_id',
																			'$user_name',
																			'$timestamp',
																			'user_media_location'
																		)");
										}

										$msg_q = mysqli_query($connection, "SELECT `ID` FROM `chat_messages` WHERE `chat_room` = '$non_room' ORDER BY `ID` DESC LIMIT 1");
										$msg_r = mysqli_fetch_assoc($msg_q);
										$msg_id = $msg_r['ID'];
										$users = mysqli_query($connection, "SELECT user_id FROM `chat_members` WHERE `chat_room` = '$non_room'");
										$users_id_array = array();

										while ($row = mysqli_fetch_array($users)) {
											$users_id_array[] = $row[0];
										}

										$users_id = array_diff($users_id_array, array($user_id));

										foreach ($users_id as $usr_id) {
											mysqli_query($connection, "INSERT INTO `chat_unread`(
																				`chat_room`,
																				`msg_id`,
																				`usr_id`
																			) VALUES (
																				'$non_room',
																				'$msg_id',
																				'$usr_id'
																			)");
										}
									}

									if ($file == 'img') {
										mysqli_query($connection, "UPDATE `chat_room` SET `last_message_time` = '$timestamp', `last_message` = '<i class=\'material-icons tiny\'>photo<\/i> Image' WHERE ID = '$non_room'");
									} elseif ($file == 'vid') {
										mysqli_query($connection, "UPDATE `chat_room` SET `last_message_time` = '$timestamp', `last_message` = '<i class=\'material-icons tiny\'>local_movies<\/i> Video' WHERE ID = '$non_room'");
									} elseif ($file == 'file') {
										mysqli_query($connection, "UPDATE `chat_room` SET `last_message_time` = '$timestamp', `last_message` = '<i class=\'material-icons tiny\'>insert_drive_file<\/i> File' WHERE ID = '$non_room'");
									} elseif ($file == 'music') {
										mysqli_query($connection, "UPDATE `chat_room` SET `last_message_time` = '$timestamp', `last_message` = '<i class=\'material-icons tiny\'>headset<\/i> Music' WHERE ID = '$non_room'");
									} elseif ($file == 'voice_note') {
										mysqli_query($connection, "UPDATE `chat_room` SET `last_message_time` = '$timestamp', `last_message` = '<i class=\'material-icons tiny\'>keyboard_voice<\/i> Voice Note' WHERE ID = '$non_room'");
									} elseif ($file == 'location') {
										mysqli_query($connection, "UPDATE `chat_room` SET `last_message_time` = '$timestamp', `last_message` = '<i class=\'material-icons tiny\'>location_on<\/i> Location' WHERE ID = '$non_room'");
									}
									mysqli_query($connection, "UPDATE `chat_members` SET `last_message_time` = '$timestamp' WHERE `chat_room` = '$non_room'");

									/*** Get the Chat Name ***/
									$name_q = mysqli_query($connection, "SELECT `chat_name` FROM `chat_room` WHERE `id_hash` = '$room' LIMIT 1");
									$name_r = mysqli_fetch_assoc($name_q);
									$name = $name_r['chat_name'];
									/*************************/

										if ($roomtype == 1) {
											// Personal Message

											$msgs = mysqli_query($connection, "SELECT `ID` FROM `chat_members` WHERE `chat_room` = '$non_room' && (`status` = 0 || `status` = 3)");

											if (mysqli_num_rows($msgs) == 1) {
												// This is the first message of a PM => Make the chat visible on the other user

												mysqli_query($connection, "UPDATE `chat_members` SET `status` = 1 WHERE `chat_room` = '$non_room' && `user_id` != '$user_id'");
												$user_pic = profilePicture($user_id);

												if ($file == 'img') {
													$response_text = mask(
																		json_encode(
																				array(
																					'type' => 'user_media_img',
																					'message' => '<i class="material-icons tiny">photo</i> Image',
																					'name' => $user_name,
																					'userid' => $user_id,
																					'do' => 1,
																					'chat_id' => $room,
																					'chat_name' => $name,
																					'roomtype' => $roomtype,
																					'time' => $time,
																					'k' => $k,
																					'img' => $user_pic,
																					'media' => $media,
																				)
																		)
													);
													send_message($response_text, $non_room);
												} elseif ($file == 'vid') {
													$response_text = mask(
																		json_encode(
																				array(
																					'type' => 'user_media_vid',
																					'message' => '<i class="material-icons tiny">local_movies</i> Video',
																					'name' => $user_name,
																					'userid' => $user_id,
																					'do' => 1,
																					'chat_id' => $room,
																					'chat_name' => $name,
																					'roomtype' => $roomtype,
																					'time' => $time,
																					'k' => $k,
																					'img' => $user_pic,
																					'media' => $media,
																					'mime' => $mime,
																				)
																		)
													);
													send_message($response_text, $non_room);
												} elseif ($file == 'file') {
													$response_text = mask(
																		json_encode(
																				array(
																					'type' => 'user_media_file',
																					'message' => '<i class="material-icons tiny">insert_drive_file</i> File',
																					'name' => $user_name,
																					'userid' => $user_id,
																					'do' => 1,
																					'chat_id' => $room,
																					'chat_name' => $name,
																					'roomtype' => $roomtype,
																					'time' => $time,
																					'k' => $k,
																					'img' => $user_pic,
																					'media' => $media,
																					'filename' => $file_name,
																				)
																		)
													);
													send_message($response_text, $non_room);
												} elseif ($file == 'music') {
													$response_text = mask(
																		json_encode(
																				array(
																					'type' => 'user_media_music',
																					'message' => '<i class="material-icons tiny">headset</i> Music',
																					'name' => $user_name,
																					'userid' => $user_id,
																					'do' => 1,
																					'chat_id' => $room,
																					'chat_name' => $name,
																					'roomtype' => $roomtype,
																					'time' => $time,
																					'k' => $k,
																					'img' => $user_pic,
																					'media' => $media,
																					'filename' => $file_name,
																					'mime' => $mime,
																				)
																		)
													);
													send_message($response_text, $non_room);
												} elseif ($file == 'voice_note') {
													$response_text = mask(
																		json_encode(
																				array(
																					'type' => 'user_media_voice_note',
																					'message' => '<i class="material-icons tiny">keyboard_voice</i> Voice Note',
																					'name' => $user_name,
																					'userid' => $user_id,
																					'do' => 1,
																					'chat_id' => $room,
																					'chat_name' => $name,
																					'roomtype' => $roomtype,
																					'time' => $time,
																					'k' => $k,
																					'img' => $user_pic,
																					'media' => $media
																				)
																		)
													);
													send_message($response_text, $non_room);
												} elseif ($file == 'location') {
													$response_text = mask(
																		json_encode(
																				array(
																					'type' => 'user_media_location',
																					'message' => '<i class="material-icons tiny">location_on</i> Location',
																					'name' => $user_name,
																					'userid' => $user_id,
																					'do' => 1,
																					'chat_id' => $room,
																					'chat_name' => $name,
																					'roomtype' => $roomtype,
																					'time' => $time,
																					'k' => $k,
																					'img' => $user_pic,
																					'media' => json_encode($media)
																				)
																		)
													);
													send_message($response_text, $non_room);
												}
											} else {    // Chat room is visible for both of the users
												$user_pic = profilePicture($user_id);
												if ($file == 'img') {
													$response_text = mask(
																		json_encode(
																				array(
																					'type' => 'user_media_img',
																					'message' => '<i class="material-icons tiny">photo</i> Image',
																					'name' => $user_name,
																					'userid' => $user_id,
																					'do' => 2,
																					'chat_id' => $room,
																					'chat_name' => $name,
																					'roomtype' => $roomtype,
																					'time' => $time,
																					'k' => $k,
																					'img' => $user_pic,
																					'media' => $media,
																				)
																		)
													);
													send_message($response_text, $non_room);
												} elseif ($file == 'vid') {
													$response_text = mask(
																		json_encode(
																				array(
																					'type' => 'user_media_vid',
																					'message' => '<i class="material-icons tiny">local_movies</i> Video',
																					'name' => $user_name,
																					'userid' => $user_id,
																					'do' => 2,
																					'chat_id' => $room,
																					'chat_name' => $name,
																					'roomtype' => $roomtype,
																					'time' => $time,
																					'k' => $k,
																					'img' => $user_pic,
																					'media' => $media,
																					'mime' => $mime,
																				)
																		)
													);
													send_message($response_text, $non_room);
												} elseif ($file == 'file') {
													$response_text = mask(
																		json_encode(
																				array(
																					'type' => 'user_media_file',
																					'message' => '<i class="material-icons tiny">insert_drive_file</i> File',
																					'name' => $user_name,
																					'userid' => $user_id,
																					'do' => 2,
																					'chat_id' => $room,
																					'chat_name' => $name,
																					'roomtype' => $roomtype,
																					'time' => $time,
																					'k' => $k,
																					'img' => $user_pic,
																					'media' => $media,
																					'filename' => $file_name,
																				)
																		)
													);
													send_message($response_text, $non_room);
												} elseif ($file == 'music') {
													$response_text = mask(
																		json_encode(
																				array(
																					'type' => 'user_media_music',
																					'message' => '<i class="material-icons tiny">headset</i> Music',
																					'name' => $user_name,
																					'userid' => $user_id,
																					'do' => 2,
																					'chat_id' => $room,
																					'chat_name' => $name,
																					'roomtype' => $roomtype,
																					'time' => $time,
																					'k' => $k,
																					'img' => $user_pic,
																					'media' => $media,
																					'filename' => $file_name,
																					'mime' => $mime,
																				)
																		)
													);
													send_message($response_text, $non_room);
												} elseif ($file == 'voice_note') {
													$response_text = mask(
																		json_encode(
																				array(
																					'type' => 'user_media_voice_note',
																					'message' => '<i class="material-icons tiny">keyboard_voice</i> Voice Note',
																					'name' => $user_name,
																					'userid' => $user_id,
																					'do' => 2,
																					'chat_id' => $room,
																					'chat_name' => $name,
																					'roomtype' => $roomtype,
																					'time' => $time,
																					'k' => $k,
																					'img' => $user_pic,
																					'media' => $media
																				)
																		)
													);
													send_message($response_text, $non_room);
												} elseif ($file == 'location') {
													$response_text = mask(
																		json_encode(
																				array(
																					'type' => 'user_media_location',
																					'message' => '<i class="material-icons tiny">location_on</i> Location',
																					'name' => $user_name,
																					'userid' => $user_id,
																					'do' => 2,
																					'chat_id' => $room,
																					'chat_name' => $name,
																					'roomtype' => $roomtype,
																					'time' => $time,
																					'k' => $k,
																					'img' => $user_pic,
																					'media' => json_encode($media)
																				)
																		)
													);
													send_message($response_text, $non_room);
												}
											}
										} elseif ($roomtype == 0) {
											// Group

											$group_pic = groupPicture($non_room);
											if ($file == 'img') {
												$response_text = mask(
																	json_encode(
																			array(
																				'type' => 'user_media_img',
																				'name' => $user_name,
																				'message' => '<i class="material-icons tiny">photo</i> Image',
																				'userid' => $user_id,
																				'do' => 2,
																				'chat_id' => $room,
																				'chat_name' => $name,
																				'roomtype' => $roomtype,
																				'time' => $time,
																				'k' => $k,
																				'img' => $group_pic,
																				'media' => $media,
																			)
																	)
												);
												send_message($response_text, $non_room);
											} elseif ($file == 'vid') {
												$response_text = mask(
																	json_encode(
																			array(
																				'type' => 'user_media_vid',
																				'name' => $user_name,
																				'message' => '<i class="material-icons tiny">local_movies</i> Video',
																				'userid' => $user_id,
																				'do' => 2,
																				'chat_id' => $room,
																				'chat_name' => $name,
																				'roomtype' => $roomtype,
																				'time' => $time,
																				'k' => $k,
																				'img' => $group_pic,
																				'media' => $media,
																				'mime' => $mime,
																			)
																	)
												);
												send_message($response_text, $non_room);
											} elseif ($file == 'file') {
												$response_text = mask(
																	json_encode(
																			array(
																				'type' => 'user_media_file',
																				'name' => $user_name,
																				'message' => '<i class="material-icons tiny">insert_drive_file</i> File',
																				'userid' => $user_id,
																				'do' => 2,
																				'chat_id' => $room,
																				'chat_name' => $name,
																				'roomtype' => $roomtype,
																				'time' => $time,
																				'k' => $k,
																				'img' => $group_pic,
																				'media' => $media,
																				'filename' => $file_name,
																			)
																	)
												);
												send_message($response_text, $non_room);
											} elseif ($file == 'music') {
												$response_text = mask(
																	json_encode(
																			array(
																				'type' => 'user_media_music',
																				'name' => $user_name,
																				'message' => '<i class="material-icons tiny">headset</i> Music',
																				'userid' => $user_id,
																				'do' => 2,
																				'chat_id' => $room,
																				'chat_name' => $name,
																				'roomtype' => $roomtype,
																				'time' => $time,
																				'k' => $k,
																				'img' => $group_pic,
																				'media' => $media,
																				'filename' => $file_name,
																				'mime' => $mime,
																			)
																	)
												);
												send_message($response_text, $non_room);
											} elseif ($file == 'voice_note') {
												$response_text = mask(
																	json_encode(
																			array(
																				'type' => 'user_media_voice_note',
																				'name' => $user_name,
																				'message' => '<i class="material-icons tiny">keyboard_voice</i> Voice Note',
																				'userid' => $user_id,
																				'do' => 2,
																				'chat_id' => $room,
																				'chat_name' => $name,
																				'roomtype' => $roomtype,
																				'time' => $time,
																				'k' => $k,
																				'img' => $group_pic,
																				'media' => $media
																			)
																	)
												);
												send_message($response_text, $non_room);
											} elseif ($file == 'location') {
												$response_text = mask(
																	json_encode(
																			array(
																				'type' => 'user_media_location',
																				'name' => $user_name,
																				'message' => '<i class="material-icons tiny">location_on</i> Location',
																				'userid' => $user_id,
																				'do' => 2,
																				'chat_id' => $room,
																				'chat_name' => $name,
																				'roomtype' => $roomtype,
																				'time' => $time,
																				'k' => $k,
																				'img' => $group_pic,
																				'media' => json_encode($media)
																			)
																	)
												);
												send_message($response_text, $non_room);
											}
										}
								} else {
									$err_text = mask(
														json_encode(
																array(
																	'type' => 'system',
																	'message' => 'You are not in this conversation.',
																	'userid' => $user_id,
																	'roomtype' => $roomtype,
																	'time' => $time,
																	'k' => $k,
																)
														)
										);
										$send_error = 1;
										send_message_to_user($token_error, $user_id);
								}
							} elseif($type == 9) {
								 $update = mask(
										json_encode(
												array(
													'do' => 99,
													'userid' => $target_id,
												)
											)
										);
										send_message_to_user($update, $target_id);
							}

							/*
							* Update Username
							*/
							if($type == 10) {
								if(isAdmin($user_id)) {
									$query_check = mysqli_query($connection, "SELECT `ID` FROM `members` WHERE `username` = '$target_new_name'");
									if (mysqli_num_rows($query_check) == 1) {
										$error = mask(json_encode(array('admin_type' => "error", "content" => "This username is already in use.")));
										send_message_to_user($error, $user_id);
									} else {
										mysqli_query($connection, "UPDATE `members` SET `username` = '$target_new_name' WHERE `ID` = '$target_id'");
										mysqli_query($connection, "UPDATE `chat_members` SET `user_name` = '$target_new_name' WHERE `user_id` = '$target_id'");
										mysqli_query($connection, "UPDATE `chat_messages` SET `from_name` = '$target_new_name' WHERE `from_id` = '$target_id'");
										mysqli_query($connection, "UPDATE `chat_room` SET `owner_name` = '$target_new_name' WHERE `owner_id` = '$target_id'");
										$update = mask(json_encode(array('do' => 99, 'userid' => $target_id)));
										$update2 = mask(json_encode(array('admin_type' => "update_username")));
										send_message_to_user($update, $target_id);
										send_message_to_user($update2, $user_id);
									}
								} else {
									$error = mask(json_encode(array('admin_type' => "error", "content" => "You are not authorized.")));
									send_message_to_user($error, $user_id);
								}
							}

							/*
							* Update Email
							*/
							elseif($type == 11) {
								if(isAdmin($user_id)) {
									$query_check = mysqli_query($connection, "SELECT `ID` FROM `members` WHERE `email` = '$target_new_email'");
									if (mysqli_num_rows($query_check) == 1) {
										$error = mask(json_encode(array('admin_type' => "error", "content" => "This email is already in use.")));
										send_message_to_user($error, $user_id);
									} else {
										mysqli_query($connection, "UPDATE `members` SET `email` = '$target_new_email' WHERE `ID` = '$target_id'");
										$update = mask(json_encode(array('do' => 99, 'userid' => $target_id)));
										$update2 = mask(json_encode(array('admin_type' => "update_email")));
										send_message_to_user($update, $target_id);
										send_message_to_user($update2, $user_id);
									}
								} else {
									$error = mask(json_encode(array('admin_type' => "error", "content" => "You are not authorized.")));
									send_message_to_user($error, $user_id);
								}
							}

							/*
							* Update Profile Photo
							*/
							elseif($type == 12) {
								if(isAdmin($user_id)) {
									if(empty($img)) {
										$response = mask(json_encode(array('admin_type' => "update_profile_photo", "content" => "")));
									} else {
										$response = mask(json_encode(array('admin_type' => "update_profile_photo", "content" => $img)));
									}
									send_message_to_user($response, $user_id);
								} else {
									$error = mask(json_encode(array('admin_type' => "error", "content" => "You are not authorized.")));
									send_message_to_user($error, $user_id);
								}
							}

							/*
							* Delete User
							*/
							elseif($type == 13) {
								if(isAdmin($user_id)) {
									$timestamp = time();
									$get_un_query = mysqli_query($connection, "SELECT `username` FROM `members` WHERE `ID` = '$target_id' LIMIT 1");
									$get_un = mysqli_fetch_array($get_un_query);
									$target_name = $get_un[0];
									mysqli_query($connection, "DELETE FROM `members` WHERE `ID` = '$target_id'");
									mysqli_query($connection, "DELETE FROM `chat_unread` WHERE `usr_id` = '$target_id'");
									mysqli_query($connection, "DELETE FROM `chat_members` WHERE `user_id` = '$target_id'");
									$get_chats_query = mysqli_query($connection, "SELECT `ID` FROM `chat_room` WHERE `owner_id` = '$target_id' LIMIT 1");
									while($get_chats = mysqli_fetch_array($get_chats_query)) {
										$cid = $get_chats[0];
										$cquery = mysqli_query($connection, "SELECT `ID` FROM `chat_members` WHERE `chat_room` = '$cid'");
										if(mysqli_num_rows($cquery) == 0) {
											$chat_id = $room;
											$temp_token = bin2hex(openssl_random_pseudo_bytes(32));
											$insert = mysqli_query($connection, "UPDATE `chat_room` SET `temp_token` = '$temp_token' WHERE `ID` = '$cid' LIMIT 1");
											if ($insert) {
												$url = url().'/action.php?act=delete-group-pic&usr_token='.$token.'&chat_token='.$temp_token.'&chat_id='.$chat_id.'&user_id='.$user_id.'&user_name='.$user_name;
												$curl = curl_init();
												curl_setopt($curl, CURLOPT_URL, $url);
												curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
												curl_setopt($curl, CURLOPT_HEADER, false);
												$del = curl_exec($curl);
												curl_close($curl);
												if ($del == 1) {
													mysqli_query($connection, "DELETE FROM `chat_members` WHERE `chat_room` = '$cid'");
													mysqli_query($connection, "DELETE FROM `chat_room` WHERE `ID` = '$cid'");
													if (save_messages() == 1) {
														mysqli_query($connection, "DELETE FROM `chat_messages` WHERE `chat_room` = '$cid'");
														mysqli_query($connection, "DELETE FROM `chat_unread` WHERE `chat_room` = '$cid'");
													}
												} else {
													$error = mask(json_encode(array('admin_type' => "error", "content" => "Group photo couldn't be deleted.")));
													send_message_to_user($error, $user_id);
												}
											} else {
											   $error = mask(json_encode(array('admin_type' => "error", "content" => "An error occured.")));
												send_message_to_user($error, $user_id);
											}
										} else {
											if(save_messages() == 1) {
												mysqli_query($connection, "INSERT INTO `chat_messages`(
																				`chat_room`,
																				`message`,
																				`from_id`,
																				`from_name`,
																				`time`,
																				`type`
																			) VALUES (
																				'$cid',
																				'$target_name has been deleted.',
																				'$user_id',
																				'$user_name',
																				'$timestamp',
																				'system'
																			)");
											}
											$roomtype = chatType($non_room);
											$response_text = mask(
														json_encode(
																array(
																	'type' => 'system',
																	'message' => $target_name.' has been deleted.',
																	'userid' => $user_id,
																	'roomtype' => $roomtype,
																	'time' => $timestamp,
																	'chat_id' => keymaker($non_room)
																)
														)
												);
											send_message($response_text, $cid);
										}
									}
									mysqli_query($connection, "UPDATE `chat_room` SET `owner_name` = NULL, `owner_id` = NULL, `last_message` = '$target_name has been deleted.', `last_message_time` = '$timestamp' WHERE `owner_id` = '$target_id'");

									$response = mask(json_encode(array('admin_type' => "delete_user")));
									send_message_to_user($response, $user_id);
									$update = mask(json_encode(array('do' => 99, 'userid' => $target_id)));
									send_message_to_user($update, $target_id);
								} else {
									$error = mask(json_encode(array('admin_type' => "error", "content" => "You are not authorized.")));
									send_message_to_user($error, $user_id);
								}
							}

							/*
							* Block User
							*/
							elseif($type == 14) {
								if(isAdmin($user_id)) {
									$check_banned = mysqli_query($connection, "SELECT `time` FROM `chat_banned_users` WHERE `userid` = '$target_id' && `time` > 'time()' && `type` = 'temporary'");
									$check_banned2 = mysqli_query($connection, "SELECT `time` FROM `chat_banned_users` WHERE `userid` = '$target_id' && `type` = 'permanent'");
									if(mysqli_num_rows($check_banned2) > 0) {
										$error = mask(json_encode(array('admin_type' => "error", "content" => "This user is already banned.")));
										send_message_to_user($error, $user_id);
									} elseif(mysqli_num_rows($check_banned) > 0) {
										$error = mask(json_encode(array('admin_type' => "error", "content" => "This user is already banned.")));
										send_message_to_user($error, $user_id);
									} else {
										$target_name = userName($target_id);
										if($bantype == "temp") {
											if(is_numeric($block_day)
												&& is_numeric($block_month)
												&& is_numeric($block_year)
												&& is_numeric($block_hour)
												&& is_numeric($block_minute)
												&& $block_minute >= 0
												&& $block_minute <= 59
												&& $block_hour >= 0
												&& $block_hour <= 23
												&& $block_month >= 1
												&& $block_month <= 12
												&& $block_day >= 1
												&& $block_day <= 31
												&& $block_year >= date("Y")
											) {
												$datetime = DateTime::createFromFormat( 'd/m/Y H:i', ''.$block_day.'/'.$block_month.'/'.$block_year.' '.$block_hour.':'.$block_minute.'', new DateTimeZone(timezone()));
												$timestamp = $datetime->getTimestamp();
												if($timestamp > time()) {
													$get_chats_query = mysqli_query($connection, "SELECT `ID` FROM `chat_room` WHERE `owner_id` = '$target_id' LIMIT 1");
													while($get_chats = mysqli_fetch_array($get_chats_query)) {
														$cid = $get_chats[0];
														if(save_messages() == 1) {
															mysqli_query($connection, "INSERT INTO `chat_messages`(
																							`chat_room`,
																							`message`,
																							`from_id`,
																							`from_name`,
																							`time`,
																							`type`
																						) VALUES (
																							'$cid',
																							'$target_name has been banned.',
																							'$user_id',
																							'$user_name',
																							'$timestamp',
																							'system'
																						)");
														}
														$response_text = mask(
																	json_encode(
																			array(
																				'type' => 'system',
																				'message' => $target_name.' has been banned.',
																				'userid' => $user_id,
																				'roomtype' => chatType($cid),
																				'time' => $timestamp,
																				'chat_id' => keymaker($cid)
																			)
																	)
															);
														send_message($response_text, $cid);
													}
													mysqli_query($connection, "UPDATE `chat_room` SET `last_message` = '$target_name has been banned.', `last_message_time` = '$timestamp' WHERE `owner_id` = '$target_id'");
													mysqli_query($connection, "UPDATE `members` SET `online` = 0 WHERE `ID` = '$target_id'");
													mysqli_query($connection, "INSERT INTO `chat_banned_users`(`userid`, `reason`, `time`, `type`) VALUES ('$target_id', '$block_reason', '$timestamp', 'temporary')");
													$response = mask(json_encode(array('admin_type' => "block_user")));
													send_message_to_user($response, $user_id);
													$update = mask(json_encode(array('do' => 99, 'userid' => $target_id)));
													send_message_to_user($update, $target_id);
												} else {
													$error = mask(json_encode(array('admin_type' => "error", "content" => "Invalid date.")));
													send_message_to_user($error, $user_id);
												}
											} else {
												$error = mask(json_encode(array('admin_type' => "error", "content" => "Invalid date.")));
												send_message_to_user($error, $user_id);
											}
										} else {
											$timestamp = time();
											$get_chats_query = mysqli_query($connection, "SELECT `ID` FROM `chat_room` WHERE `owner_id` = '$target_id' LIMIT 1");
											while($get_chats = mysqli_fetch_array($get_chats_query)) {
												$cid = $get_chats[0];
												if(save_messages() == 1) {
													mysqli_query($connection, "INSERT INTO `chat_messages`(
																					`chat_room`,
																					`message`,
																					`from_id`,
																					`from_name`,
																					`time`,
																					`type`
																				) VALUES (
																					'$cid',
																					'$target_name has been banned.',
																					'$user_id',
																					'$user_name',
																					'$timestamp',
																					'system'
																				)");
												}
												$response_text = mask(
															json_encode(
																	array(
																		'type' => 'system',
																		'message' => $target_name.' has been banned.',
																		'userid' => $user_id,
																		'roomtype' => chatType($cid),
																		'time' => $timestamp,
																		'chat_id' => $room
																)
															)
													);
												send_message($response_text, $non_room);
											}
											mysqli_query($connection, "UPDATE `chat_room` SET `last_message` = '$target_name has been banned.', `last_message_time` = '$timestamp' WHERE `owner_id` = '$target_id'");
											mysqli_query($connection, "UPDATE `members` SET `online` = 0 WHERE `ID` = '$target_id'");
											mysqli_query($connection, "INSERT INTO `chat_banned_users`(`userid`, `reason`, `type`) VALUES ('$target_id', '$block_reason', 'permanent')");
											$response = mask(json_encode(array('admin_type' => "perm_user")));
											send_message_to_user($response, $user_id);
											$update = mask(json_encode(array('do' => 99, 'userid' => $target_id)));
											send_message_to_user($update, $target_id);
										}
									}
								} else {
									$error = mask(json_encode(array('admin_type' => "error", "content" => "You are not authorized.")));
									send_message_to_user($error, $user_id);
								}
							}

							/*
							* Logout User
							*/
							elseif($type == 15) {
								if(isAdmin($user_id)) {
									$salt = mysqli_real_escape_string($connection, bin2hex(openssl_random_pseudo_bytes(512)));
									mysqli_query($connection, "UPDATE `members` SET `salt` = '$salt' WHERE `ID` = '$target_id'");
									$response = mask(json_encode(array('admin_type' => "logout_user")));
									send_message_to_user($response, $user_id);
									$update = mask(json_encode(array('do' => 99, 'userid' => $target_id)));
									send_message_to_user($update, $target_id);
								} else {
									$error = mask(json_encode(array('admin_type' => "error", "content" => "You are not authorized.")));
									send_message_to_user($error, $user_id);
								}
							}

							/*
							* Remove Ban of the User
							*/
							elseif($type == 16) {
								if(isAdmin($user_id)) {
									$target_name = userName($target_id);
									$get_chats_query = mysqli_query($connection, "SELECT `ID` FROM `chat_room` WHERE `owner_id` = '$target_id' LIMIT 1");
									while($get_chats = mysqli_fetch_array($get_chats_query)) {
										$cid = $get_chats[0];
										$timestamp = time();
										if(save_messages() == 1) {
											mysqli_query($connection, "INSERT INTO `chat_messages`(
																			`chat_room`,
																			`message`,
																			`from_id`,
																			`from_name`,
																			`time`,
																			`type`
																		) VALUES (
																			'$cid',
																			'$target_name has been unbanned.',
																			'$user_id',
																			'$user_name',
																			'$timestamp',
																			'system'
																		)");
										}
										$response_text = mask(
													json_encode(
															array(
																'type' => 'system',
																'message' => $target_name.' has been unbanned.',
																'userid' => $user_id,
																'roomtype' => chatType($cid),
																'time' => $timestamp,
																'chat_id' => $room
															)
													)
											);
										send_message($response_text, $non_room);
									}
									mysqli_query($connection, "UPDATE `chat_room` SET `last_message` = '$target_name has been unbanned.', `last_message_time` = '$timestamp' WHERE `owner_id` = '$target_id'");
									mysqli_query($connection, "DELETE FROM `chat_banned_users` WHERE `ID` = '$select_ban'");
									$response = mask(json_encode(array('admin_type' => "remove_ban", "content" => $select_ban)));
									send_message_to_user($response, $user_id);
									$update = mask(json_encode(array('do' => 99, 'userid' => $target_id)));
									send_message_to_user($update, $target_id);
								} else {
									$error = mask(json_encode(array('admin_type' => "error", "content" => "You are not authorized.")));
									send_message_to_user($error, $user_id);
								}
							}

							/*
							* Delete Chat Room
							*/
							elseif($type == 17) {
								if(isAdmin($user_id)) {
									$get_users = mysqli_query($connection, "SELECT `user_id` FROM `chat_members` WHERE `chat_room` = '$non_room'");
									$chat_id = $room;
									$temp_token = bin2hex(openssl_random_pseudo_bytes(32));
									$insert = mysqli_query($connection, "UPDATE `chat_room` SET `temp_token` = '$temp_token' WHERE `ID` = '$non_room' LIMIT 1");
									if ($insert) {
										$url = url().'/action.php?act=delete-group-pic&usr_token='.$token.'&chat_token='.$temp_token.'&chat_id='.$chat_id.'&user_id='.$user_id.'&user_name='.$user_name;
										$curl = curl_init();
										curl_setopt($curl, CURLOPT_URL, $url);
										curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
										curl_setopt($curl, CURLOPT_HEADER, false);
										$del = curl_exec($curl);
										curl_close($curl);
										if ($del == 1) {
											mysqli_query($connection, "DELETE FROM `chat_members` WHERE `chat_room` = '$non_room'");
											mysqli_query($connection, "DELETE FROM `chat_room` WHERE `ID` = '$non_room'");
											if (save_messages() == 1) {
												mysqli_query($connection, "DELETE FROM `chat_messages` WHERE `chat_room` = '$non_room'");
												mysqli_query($connection, "DELETE FROM `chat_unread` WHERE `chat_room` = '$non_room'");
											}

											while($get_users_array = mysqli_fetch_array($get_users)) {
												$update = mask(json_encode(array('do' => 98, 'userid' => $get_users_array[0], 'chat_id' => $chat_id)));
												send_message_to_user($update, $get_users_array[0]);
											}

											$response = mask(json_encode(array('admin_type' => "delete_chatroom")));
											send_message_to_user($response, $user_id);
										} else {
											$error = mask(json_encode(array('admin_type' => "error", "content" => "Group photo couldn't be deleted.")));
											send_message_to_user($error, $user_id);
										}
									} else {
									   $error = mask(json_encode(array('admin_type' => "error", "content" => "An error occured.")));
										send_message_to_user($error, $user_id);
									}
								} else {
									$error = mask(json_encode(array('admin_type' => "error", "content" => "You are not authorized.")));
									send_message_to_user($error, $user_id);
								}
							}

							/*
							* Change Group Name
							*/
							elseif($type == 18) {
								if (chatType($non_room) == 0) {
									$owner_query = mysqli_query($connection, "SELECT `owner_id`, `owner_name` FROM `chat_room` WHERE `ID` = '$non_room' LIMIT 1");
									$owner_row = mysqli_fetch_assoc($owner_query);

									if(isAdmin($user_id)) {
										if (strlen($grp_name) <= max_group_name()) {
											$time = time();
											mysqli_query($connection, "UPDATE `chat_room` SET `chat_name` = '$grp_name' WHERE `ID` = '$non_room' LIMIT 1");
											if(save_messages() == 1) {
												mysqli_query($connection, "INSERT INTO `chat_messages`(
																					`chat_room`,
																					`message`,
																					`from_id`,
																					`from_name`,
																					`time`,
																					`type`
																				) VALUES (
																					'$non_room',
																					'Group name has been changed to \"$grp_name\".',
																					'$user_id',
																					'$user_name',
																					'$time',
																					'system'
																				)");
											}
											$response_text = mask(
														json_encode(
																array(
																	'chat_id' => $room,
																	'message' => 'Group name has been changed to "'.$non_grp_name.'".',
																	'type' => 'system',
																	'userid' => $user_id,
																	'do' => 5,
																	'roomtype' => 0,
																	'grp_name' => $non_grp_name
																)
															)
														);
											send_message($response_text, $non_room);
											$response = mask(json_encode(array('admin_type' => "update_groupname")));
											send_message_to_user($response, $user_id);
										} else {
											$error = mask(json_encode(array('admin_type' => 'error', 'content' => 'Group name is too long.')));
											send_message_to_user($error, $user_id);
										}
									} else {
										$error = mask(json_encode(array('admin_type' => "error", "content" => "You are not authorized.")));
										send_message_to_user($error, $user_id);
									}
								} else {
									$error = mask(json_encode(array('admin_type' => "error", "content" => "Invalid room type.")));
									send_message_to_user($error, $user_id);
								}
							}

							/*
							* Kick User
							*/
							elseif($type == 19) {
								if (isAdmin($user_id)) {
									if (chatType($non_room) == 0) {
											mysqli_query($connection, "UPDATE `chat_members` SET `status` = 4 WHERE `user_id` = '$target_id' && `chat_room` = '$non_room'");    // Set user status = 4 to show the user that you have kicked.

											$time = time();
											$target_name = userName($target_id);
											if (save_messages() == 1) {
												mysqli_query($connection, "INSERT INTO `chat_messages`(
																				`chat_room`,
																				`message`,
																				`from_id`,
																				`from_name`,
																				`time`,
																				`type`
																			) VALUES (
																				'$non_room',
																				'$target_name was kicked.',
																				'$user_id',
																				'$user_name',
																				'$time',
																				'system'
																			)");
											}
											mysqli_query($connection, "UPDATE `chat_room` SET `last_message_time` = '$time', `last_message` = '$target_name was kicked.' WHERE `ID` = '$non_room'");

											$response_text = mask(
															json_encode(
																	array(
																		'type' => 'kick',
																		'message' => $target_name.' was kicked.',
																		'chat_id' => $room,
																		'userid' => $user_id,
																		'target' => $target_name,
																		'target_id' => $target_id,
																		'time' => $time
																	)
															)
											);
											send_message($response_text, $non_room);

											$response = mask(json_encode(array('admin_type' => "admin_kick", "content" => $int, "user" => $target_name)));
											send_message_to_user($response, $user_id);

									} else {
										$error = mask(json_encode(array('admin_type' => "error", "content" => "Invalid chat type.")));
										send_message_to_user($error, $user_id);
									}
								} else {
									$error = mask(json_encode(array('admin_type' => "error", "content" => "You are not authorized.")));
									send_message_to_user($error, $user_id);
								}
							}
						} else {
							$token_error = mask(json_encode(array('error_stat' => 1, 'error' => 'Your IP address has been banned.', 'msg_exists' => $msg_exists, 'userid' => $user_id, 'ip_ban' => 1)));
							send_message_to_user($token_error, $user_id);
						}
                    } else {
                        echo "Usernames are not matched.\n";
                        echo 'User ID: '.$user_id."\n";
                        echo 'Token: '.$token."\n";
                        $token_error = mask(json_encode(array('error_stat' => 1, 'error' => 'Invalid username.', 'msg_exists' => $msg_exists, 'userid' => $user_id)));
                        send_message_to_user($token_error, $user_id);
                    }
				} else {    // Tokens are not matched
                    echo "Tokens are not matched.\n";
                    echo 'User ID: '.$user_id."\n";
					echo 'Token: '.$token."\n";
                    $token_error = mask(json_encode(array('error_stat' => 1, 'error' => 'Invalid token.', 'msg_exists' => $msg_exists, 'userid' => $user_id)));
                    send_message_to_user($token_error, $user_id);
                }

                mysqli_close($connection);
            }

            break 2; // Exist this loop
        }
        $buf = @socket_read($changed_socket, 16777216, PHP_NORMAL_READ);
        if ($buf === false) { // Check disconnected client
            // Remove client for $clients array
            $found_socket = array_search($changed_socket, $clients);
			$temp_user_id = NULL;
            foreach ($user_list as $var) {
                $ke = array_search($var, $user_list);
				$temp_user_id = $ke;
                foreach ($var as $key) {
                    if ($key === $clients[$found_socket]) {
						$con123 = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
						mysqli_query($con123, "UPDATE `members` SET `online` = 0 WHERE `ID` = '$ke' LIMIT 1");
						mysqli_close($con123);
                        unset($user_list[$ke]);
						unset($user_ip_list[$temp_user_id]);
						update_online_status($ke, 0);
                    }
                }
            }
            unset($clients[$found_socket]);
        }
    }
}
// Close the listening socket
socket_close($sock);

function send_message($msg, $room = '')
{
    global $clients;
    global $user_list;
    $con = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
    $cli = array();

    if (!empty($room)) {
        $qq = mysqli_query($con, "SELECT `user_id` FROM `chat_members` WHERE `chat_room` = '$room' && `status` != 4");
        while ($row = mysqli_fetch_array($qq)) {
            $us_id = $row[0];
            if (array_key_exists($us_id, $user_list)) {
                foreach ($user_list[$us_id] as $var) {
                    array_push($cli, $var);
                }
            }
        }
        mysqli_close($con);
        foreach ($cli as $changed_socket) {
            @socket_write($changed_socket, $msg, strlen($msg));
        }

        return true;
    } else {
        foreach ($clients as $changed_socket) {
            @socket_write($changed_socket, $msg, strlen($msg));
        }

        return true;
    }
}

function update_online_status($user_id, $stat)
{
    global $clients;
    global $user_list;
    $con = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
    $cli = array();

    $qq = mysqli_query($con, "SELECT `chat_room` FROM `chat_members` WHERE `user_id` = '$user_id'");
	if(mysqli_num_rows($qq) > 0) {
		while ($row = mysqli_fetch_array($qq)) {
			$room_id = $row[0];
			$qq2 = mysqli_query($con, "SELECT `user_id` FROM `chat_members` WHERE `chat_room` = '$room_id'");
			while($row2 = mysqli_fetch_array($qq2)) {
				if (array_key_exists($row2[0], $user_list)) {
					foreach ($user_list[$row2[0]] as $var) {
						$msg = mask(json_encode(array('do' => 97, 'userid' => $user_id, 'chat_id' => keymaker($room_id), 'online' => $stat, 'roomtype' => chatType($room_id))));
						@socket_write($var, $msg, strlen($msg));
					}
				}
			}
		}
	}
    mysqli_close($con);
    return true;
}

function send_message_to_user($msg, $user_id)
{
    global $user_list;
    $cli = array();

    if (array_key_exists($user_id, $user_list)) {
        foreach ($user_list[$user_id] as $var) {
            array_push($cli, $var);
        }
    }
    foreach ($cli as $changed_socket) {
        @socket_write($changed_socket, $msg, strlen($msg));
    }

    return true;
}

// Unmask incoming framed message
function unmask($text)
{
    $length = ord($text[1]) & 127;
    if ($length == 126) {
        $masks = substr($text, 4, 4);
        $data = substr($text, 8);
    } elseif ($length == 127) {
        $masks = substr($text, 10, 4);
        $data = substr($text, 14);
    } else {
        $masks = substr($text, 2, 4);
        $data = substr($text, 6);
    }

    $text = '';

    for ($i = 0; $i < strlen($data); $i++) {
        $text .= $data[$i] ^ $masks[$i % 4];
    }

    return $text;
}

// Encode message for transfering to client.
function mask($text)
{
    $b1 = 0x80 | (0x1 & 0x0f);
    $length = strlen($text);

    if ($length <= 125) {
        $header = pack('CC', $b1, $length);
    } elseif ($length > 125 && $length < 65536) {
        $header = pack('CCn', $b1, 126, $length);
    } elseif ($length >= 65536) {
        $header = pack('CCNN', $b1, 127, $length);
    }

    return $header.$text;
}

// Handshake new client.
function perform_handshaking($receved_header, $client_conn, $host, $port)
{
    $headers = array();
    $lines = preg_split("/\r\n/", $receved_header);
    foreach ($lines as $line) {
        $line = chop($line);
        if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
            $headers[$matches[1]] = $matches[2];
        }
    }
    if (array_key_exists('Sec-WebSocket-Key', $headers)) {
        $secKey = $headers['Sec-WebSocket-Key'];
        $secAccept = base64_encode(pack('H*', sha1($secKey.'258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        //hand shaking header
        $upgrade = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n".
        "Upgrade: websocket\r\n".
        "Connection: Upgrade\r\n".
        "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
        socket_write($client_conn, $upgrade, strlen($upgrade));
    }
}

// Save messages setting
function save_messages()
{
    switch (get_option("SAVE_MESSAGES")) {
        case 1;

            return 1;
        break;
        case 0;

            return 0;
        break;
        default;

            return 0;
        break;
    }
}

// Get user's Token
function getToken($user_id)
{
    global $connection;
    $query = mysqli_query($connection, "SELECT `token` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_array($query);

    return $row['token'];
}

// Converts Crypted Chat ID into Real ID
function chat_id($chat_no)
{
    global $connection;
    $result = mysqli_query($connection, "SELECT `ID` FROM `chat_room` WHERE `id_hash` = '$chat_no' LIMIT 1");
    $row = mysqli_fetch_array($result);

    return $row['ID'];
}

// Crypt Chat ID
function keymaker($id)
{
    $secretkey = get_option("SECRETKEY");
    $key = md5($id.$secretkey);

    return $key;
}

// Check what type room it is
function chatType($chat_id)
{
    $connn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
    $result = mysqli_query($connn, "SELECT `type` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
	if(!$result) {
		echo mysqli_error($connn)."\n";
	}
	$row = mysqli_fetch_array($result);
	return $row[0];
	mysqli_close($connn);
}

// Gets User's Profile Picture
function profilePicture($user_id)
{
    global $connection;
    $query = mysqli_query($connection, "SELECT `profile_pic` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    return $row['profile_pic'];
}

function userName($user_id)
{
    global $connection;
    $query = mysqli_query($connection, "SELECT `username` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    return $row['username'];
}

// Gets Group's Picture
function groupPicture($chat_id)
{
    global $connection;
    $query = mysqli_query($connection, "SELECT `group_pic` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    return $row['group_pic'];
}

// Maximum lenght of group name
function max_group_name()
{
	$max_name = get_option("MAX_LENGHT_GROUP_NAME");
    if ($max_name > 0) {
        return $max_name;
    } else {
        return 50;
    }
}

// Your URL
function url()
{
    return get_option("URL");
}

function in_array_r($val, $val2, $strict = false)
{
    foreach ($val2 as $item) {
        if (($strict ? $item === $val : $item == $val) || (is_array($item) && in_array_r($val, $item, $strict))) {
            return true;
        }
    }

    return false;
}

function isAdmin($user_id)
{
	global $connection;
    $query = mysqli_query($connection, "SELECT `admin`, `guest` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_array($query);
	if($row[0] == 1 && $row[1] == 0) {
		return true;
	} else {
		return false;
	}
}

function picture_destination()
{
    return get_option("PROFILE_PIC_DESTINATION");

}

function get_option($val) {
	$connn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
	$array = array();
	if(!is_array($val)) {
		$val = mysqli_real_escape_string($connn, $val);
		if($val == "banned_words") {
			$query = mysqli_query($connn, "SELECT `val1`, `val2` FROM `chat_banned_items` WHERE `setting` = '$val'");
			$banned_i = 0;
			$array_vals = array();
			while($row = mysqli_fetch_array($query)) {
				array_push($array, preg_quote($row[0]));
				array_push($array_vals, preg_quote($row[1]));
			}
			$all_array = array_combine($array, $array_vals);
			return $all_array;
		} elseif($val == "banned_ip") {
			$query = mysqli_query($connn, "SELECT `val1`, `val2` FROM `chat_banned_items` WHERE `setting` = '$val'");
			while($row = mysqli_fetch_array($query)) {
				array_push($array, stripslashes($row[0]));
			}
			return $array;
		} else {
			$query = mysqli_query($connn, "SELECT `value` FROM `chat_settings` WHERE `setting` = '$val'");
			while($row = mysqli_fetch_array($query)) {
				array_push($array, $row[0]);
			}
			if(count($array) > 1) {
				return $array;
			} elseif(count($array) == 1) {
				return $array[0];
			} else {
				return false;
			}
		}

	} else {
		return false;
	}
}

function isOnline($user_id)
{
	global $connection;
	$user_id = mysqli_real_escape_string($connection, $user_id);
    $query = mysqli_query($connection, "SELECT `online` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_array($query);
	if($row[0] == 1) {
		return true;
	} else {
		return false;
	}
}

function isFriend($user_id, $target_id)
{
	global $connection;
	$user_id = mysqli_real_escape_string($connection, $user_id);
	$target_id = mysqli_real_escape_string($connection, $target_id);
    $query = mysqli_query($connection, "SELECT `ID` FROM `chat_friends` WHERE `user_id` = '$user_id' && `friend_id` = '$target_id' LIMIT 1");
    if(mysqli_num_rows($query) > 0) {
		return true;
	} else {
		return false;
	}
}

function timezone()
{
	if (in_array(get_option("TIMEZONE"), DateTimeZone::listIdentifiers())) {
		return get_option("TIMEZONE");
	} else {
		return "Europe/Istanbul";
	}
}

function filter_words($ban_list, $message, $callback)
{
    return preg_replace_callback('/(?![^<]*>)\b(' . implode('|', array_map('preg_quote', $ban_list)) . ')\b/i', function ($a) use ($callback) { return $callback($a[1]); }, $message);
}

function ban_word($message)
{
	$ban_words = get_option("banned_words");
	if(count($ban_words) > 0) {
		$ban_list_query = array_keys($ban_words);
		return filter_words($ban_list_query, $message, function ($a) use ($ban_words) {
			return $ban_words[strtolower($a)];
		});

		return $ban;
	} else {
		return $message;
	}
}

function blockUsers($user_id)
{
	global $user_ip_list;
	$ipAddresses = get_option("banned_ip");
	if(count($ipAddresses) > 0) {
		$user = explode('.', $user_ip_list[$user_id][0]);
		$Ocount = count($user);

		$block = false;

		foreach ($ipAddresses as $ipAddress) {
			$octe = explode('.', $ipAddress);
			if (count($octe) != $Ocount) {
				continue;
			}

			for ($i = 0; $i < $Ocount; $i++) {
				if ($user[$i] == $octe[$i] || $octe[$i] == '*') {
					continue;
				} else {
					break;
				}
			}

			if ($i == $Ocount) {
				$block = true;
				break;
			}
		}

		return $block;
	} else {
		return false;
	}
}

function userInRoom($user_id, $room)
{
	global $connection;
	$user_id = mysqli_real_escape_string($connection, $user_id);
	$room = mysqli_real_escape_string($connection, $room);

    $query = mysqli_query($connection, "SELECT `ID` FROM `chat_members` WHERE `user_id` = '$user_id'
															&& chat_room = '$room'
															&& (
																status = 1
																|| status = 2
																|| status = 3
																|| status = 5
																|| status = 6
															) LIMIT 1");
    if (mysqli_num_rows($query) == 1) {
        return true;
    } else {
        return false;
    }
}
