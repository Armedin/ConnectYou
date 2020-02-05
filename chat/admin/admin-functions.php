<?php
require_once '../include/functions.php';
error_reporting(E_ALL);

function search_card($typ = "user")
{
	switch($typ) {
		case "user":
			$type = 1;
			break;
		case "chat":
			$type = 2;
			$url = "chat-search=";
			break;
		case "msg":
			$type = 3;
			$url = "chat-search=";
			break;
		default:
			$type = 1;
			break;
	}
    echo "<div class='row'>";
    echo "<div class='col s12'>";
    echo "<div class='card'>";
    echo "<form autocomplete='off'>";
    echo "<div class='card-content'>";
    echo "<div class='row user-search-row'>";
    echo "<div class='input-field col s12 user-search-form'>";
    echo "<input id='user-search' name='user-search' type='text' autocomplete='off' class='validate' />";
	if($type == 1) {
		echo "<label for='user-search'>Seach Username / E-Mail / User ID</label>";
	} elseif($type == 2) {
		echo "<label for='user-search'>Seach Chat Name / Chat ID / Encrypted Chat ID</label>";
	} elseif($type == 3) {
		echo "<label for='user-search'>Seach a Message</label>";
	}
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "<div class='card-action'>";
	if($type == 1) {
		echo "<input type='submit' class='user-submit btn-flat' name='user-submit' value='Search' />";
	} elseif($type == 2) {
		echo "<input type='submit' class='user-submit-chat btn-flat' name='user-submit-chat' value='Search' />";
	} elseif($type == 3) {
		echo "<input type='submit' class='user-submit-msg btn-flat' name='user-submit-chat' value='Search' />";
	}
    echo "</div>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

function last_registered_card($width = "s12 m4")
{
    $query = db_query('SELECT `username`, `ID` FROM `members` ORDER BY `registration_date` DESC LIMIT 10');
    $i = 1;

    echo "<div class='col ".$width."'>";
    echo "<div class='card'>";
    echo "<div class='card-content'>";
    if (mysqli_num_rows($query) == 0) :
        echo "<p>There is no registered user.</p>"; 
	else :
        echo "<span class='card-title'>Last Registered Users</span>";
		echo "<table class='bordered'>";
		echo "<thead>";
		echo "<tr>";
		echo "<th data-field='id'>#</th>";
		echo "<th data-field='id'>Username</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";

		while ($row = mysqli_fetch_array($query)) {
			echo "<tr>";
			echo '<td>'.$i.'</td>';
			echo "<td><a href='./index.php?manage-user=".$row[1]."'>".$row[0].'</a></td>';
			echo "</tr>";
			$i++;
		}

		echo "</tbody>";
		echo "</table>";
    endif;
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

function convertMonth( $num ) {	
	switch($num) {
		case "01":
			$month = "Jan";
			break;
		case "02":
			$month = "Feb";
			break;
		case "03":
			$month = "Mar";
			break;
		case "04":
			$month = "Apr";
			break;
		case "05":
			$month = "May";
			break;
		case "06":
			$month = "June";
			break;
		case "07":
			$month = "July";
			break;
		case "08":
			$month = "Aug";
			break;
		case "09":
			$month = "Sept";
			break;
		case "10":
			$month = "Oct";
			break;
		case "11":
			$month = "Nov";
			break;
		case "12":
			$month = "Dec";
			break;
	}
	return $month;
}

function user_chart_card($width = "s12 m8")
{
    $date = date('y-m-d 00:00:00', strtotime('today - 30 days'));
    $chart_query = db_query("SELECT `registration_date` FROM `members` WHERE `registration_date` > '$date'");
    $last_days = array();
    $row_array = array();

    for ($i = 30; $i >= 0; $i--) {
        array_push($last_days, date('m-d', strtotime('today - '.$i.' days')));
    }

    array_fill_keys($last_days, 0);

    while ($row2 = mysqli_fetch_array($chart_query)) {
        $row_date = date('m-d', $row2[0]);
        array_push($row_array, $row_date);
    }

    $array_vars = array_count_values($row_array);
    $last_vars = array_count_values($last_days);

    foreach ($last_vars as $key => $value) {
        $last_vars[$key] = 0;
    }

    $total_array = array_replace($last_vars, $array_vars);

    echo "<div class='col ".$width."'>";
    echo "<div class='card'>";
    echo "<div class='card-content'>";
    echo "<span class='card-title'>Registered Users</span>";
    echo "<script type='text/javascript'>";				
		echo "var config = {
            type: 'line',
            data: {
                labels: ["; 
					$date = date('d-m-Y');
					for ($i = 30;$i >= 0;$i--) {
						$mo = convertMonth(date('m', strtotime('today - '.$i.' days')));
						$da = date('d', strtotime($date. ' - '.$i.' days'));
						echo '"'.$mo.", ".$da.'",';
					}
				echo "],
                datasets: [{
                    label: 'Registered User',
					lineTension: 0.05,
                    data: [";
					for ($i = 30;$i >= 0;$i--) {
						echo '"'.$total_array[date('m-d', strtotime('today - '.$i.' days'))].'",';
					}
					echo "],
                }]
            },
            options: {
                responsive: true,
				gridLines: false,
				legend: {
					display: false
				},
                title: {
                    display:false
                },
                tooltips: {
                    mode: 'label',
					
                },
                hover: {
                    mode: 'label'
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            show: true,
                            labelString: 'Month'
                        },
						gridLines : {
							display : false
						}
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            show: true,
                            labelString: 'Registered Users'
                        },
                        ticks: {
                            beginAtZero: true,
							stepSize: 1
						},
						
                    }]
                }
            }
        };

        $.each(config.data.datasets, function(i, dataset) {
            dataset.borderColor = 'rgb(69, 90, 100)';
            dataset.backgroundColor = 'rgba(96, 125, 139, .5)';
            dataset.pointBorderColor = 'rgb(69, 90, 100)';
            dataset.pointBackgroundColor = 'rgb(69, 90, 100)';
            dataset.pointBorderWidth = 3;
        });

        window.onload = function() {
            var ctx = document.getElementById('canvas').getContext('2d');
            window.myLine = new Chart(ctx, config);
        };	
	</script>";
    echo "<canvas id='canvas'></canvas>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

function admin_header($get)
{
	echo "<div class='navbar-fixed'>";
		echo "<nav style='z-index: 1;'>";
			echo "<div class='nav-wrapper blue-grey darken-2'>";
				echo "<a href='#' data-activates='mobile-demo' class='button-collapse'><i class='material-icons'>menu</i></a>";
				if(empty(profilePicture(user_id()))) {
					echo "<i id='chat-main-rev' class='z-depth-1 material-icons circle medium pp grey lighten-2 left valign hide-on-med-and-down'>person</i>";
				} else {
					echo "<img id='chat-img-main-rev' class='z-depth-1 circle left pp valign hide-on-med-and-down' src='.".picture_destination().profilePicture(user_id())."'>";
				}
				echo "<h5 class='chat-nav-un left truncate hide-on-med-and-down'>".user_name()."</h5>";
				echo "<ul class='right'>";
					echo "<li><a href='../'>Main Page</a></li>";
					echo "<li><a href='../action.php?act=logout&v=" . time() . "&token='>Logout</a></li>";
				echo "</ul>";
				echo "<ul class='collapsible side-nav left-nav blue-grey' data-collapsible='accordion' id='mobile-demo'>";
				echo "<div style='width:100%;height:150px;' class='center-align'>";
					if(empty(profilePicture(user_id()))) {
					echo "<i id='in-chat-main-rev' class='z-depth-1 material-icons circle medium pp grey lighten-2 valign'>person</i>";
				} else {
					echo "<img id='in-chat-img-main-rev' class='z-depth-1 circle pp valign' src='.".picture_destination().profilePicture(user_id())."'>";
				}
				echo "<h5 class='in-chat-nav-un truncate'>".user_name()."</h5>";
				echo "</div>";
					echo "<a href='./index.php' class='waves-effect "; if(!isset($get['action']) && empty($get)) {echo "active-side-tab";} echo " waves-light'><li><i class='material-icons'>dashboard</i>Dashboard</li></a>";
						echo "<li class='blue-grey collap'>";
							if((isset($get['action']) && !empty($get) && ($get['action'] == "manage-users" || $get['action'] == "create-user")) || isset($get['manage-user']) && !empty($get)) {
								echo "<div class='blue-grey collapsible-header white-text active waves-effect waves-light'><i class='material-icons'>people</i>Users</div>";
							} else {
								echo "<div class='blue-grey collapsible-header white-text waves-effect waves-light'><i class='material-icons'>people</i>Users</div>";
							}
							echo "<div class='collapsible-body active-side-tab2 blue-grey z-depth-1-inset'>";
								echo "<a href='./index.php?action=manage-users' class='alt-tab "; if((isset($get['action']) && !empty($get) && $get['action'] == "manage-users") || (!empty($get) && isset($get['manage-user']))) {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>Manage Users</a>";
								echo "<a href='./index.php?action=create-user' class='alt-tab "; if(isset($get['action']) && !empty($get) && $get['action'] == "create-user") {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>Create a User</a>";
							echo "</div>";
						echo "</li>";
						
						echo "<li class='blue-grey collap'>";
							if(!empty($get) && (isset($get['action']) && ($get['action'] == "manage-chats" || $get['action'] == "create-group" || $get['action'] == "messages")) || isset($get['chat-search']) || isset($get['manage-chat']) || isset($get['message'])) {
								echo "<div class='blue-grey collapsible-header white-text active waves-effect waves-light'><i class='material-icons'>chat</i>Conversations</div>";
							} else {
								echo "<div class='blue-grey collapsible-header white-text waves-effect waves-light'><i class='material-icons'>chat</i>Conversations</div>";
							}
							echo "<div class='collapsible-body blue-grey active-side-tab2 z-depth-1-inset'>";
								echo "<a href='./index.php?action=manage-chats' class='alt-tab "; if((isset($get['action']) && !empty($get) && $get['action'] == "manage-chats") || (!empty($get) && isset($get['chat-search'])) || (!empty($get) && isset($get['manage-chat']))) {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>Manage Chats</a>";
								if(save_messages() == 1) { echo "<a href='./index.php?action=messages' class='alt-tab "; if((isset($get['action']) && !empty($get) && $get['action'] == "messages") || isset($get['message'])) {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>Messages</a>"; }
							echo "</div>";
						echo "</li>";
						
						echo "<li class='blue-grey collap'>";
							if(isset($get['action']) && !empty($get) && ($get['action'] == "settings" || $get['action'] == "user-settings" || $get['action'] == "chat-settings" || $get['action'] == "ban-settings")) {
								echo "<div class='blue-grey collapsible-header white-text active waves-effect waves-light'><i class='material-icons'>settings</i>Settings</div>";
							} else {
								echo "<div class='blue-grey collapsible-header white-text waves-effect waves-light'><i class='material-icons'>settings</i>Settings</div>";
							}
							echo "<div class='collapsible-body blue-grey active-side-tab2 z-depth-1-inset'>";
								echo "<a href='./index.php?action=settings' class='alt-tab "; if(isset($get['action']) && !empty($get) && $get['action'] == "settings") {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>General Settings</a>";
								echo "<a href='./index.php?action=user-settings' class='alt-tab "; if(isset($get['action']) && !empty($get) && $get['action'] == "user-settings") {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>User Settings</a>";
								echo "<a href='./index.php?action=chat-settings' class='alt-tab "; if(isset($get['action']) && !empty($get) && $get['action'] == "chat-settings") {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>Chat Settings</a>";
								echo "<a href='./index.php?action=ban-settings' class='alt-tab "; if(isset($get['action']) && !empty($get) && $get['action'] == "ban-settings") {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>Ban Settings</a></div>";
							echo "</div>";
						echo "</li>";
				echo "</ul>";
			echo "</div>";
		echo "</nav>";
		echo "<ul class='collapsible left-nav custom-nav hide-on-med-and-down blue-grey' data-collapsible='accordion'>";
			echo "<a href='./index.php' class='waves-effect "; if(!isset($get['action']) && empty($get)) {echo "active-side-tab";} echo " waves-light'><li><i class='material-icons'>dashboard</i>Dashboard</li></a>";
			echo "<li class='blue-grey collap'>";
				if((isset($get['action']) && !empty($get) && ($get['action'] == "manage-users" || $get['action'] == "create-user")) || isset($get['manage-user']) && !empty($get)) {
					echo "<div class='blue-grey collapsible-header white-text active waves-effect waves-light'><i class='material-icons'>people</i>Users</div>";
				} else {
					echo "<div class='blue-grey collapsible-header white-text waves-effect waves-light'><i class='material-icons'>people</i>Users</div>";
				}
				echo "<div class='collapsible-body active-side-tab2 blue-grey z-depth-1-inset'>";
					echo "<a href='./index.php?action=manage-users' class='alt-tab "; if((isset($get['action']) && !empty($get) && $get['action'] == "manage-users") || (!empty($get) && isset($get['manage-user']))) {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>Manage Users</a>";
					echo "<a href='./index.php?action=create-user' class='alt-tab "; if(isset($get['action']) && !empty($get) && $get['action'] == "create-user") {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>Create a User</a>";
				echo "</div>";
			echo "</li>";
			
			echo "<li class='blue-grey collap'>";
				if(!empty($get) && (isset($get['action']) && ($get['action'] == "manage-chats" || $get['action'] == "create-group" || $get['action'] == "messages")) || isset($get['chat-search']) || isset($get['manage-chat']) || isset($get['message'])) {
					echo "<div class='blue-grey collapsible-header white-text active waves-effect waves-light'><i class='material-icons'>chat</i>Conversations</div>";
				} else {
					echo "<div class='blue-grey collapsible-header white-text waves-effect waves-light'><i class='material-icons'>chat</i>Conversations</div>";
				}
				echo "<div class='collapsible-body blue-grey active-side-tab2 z-depth-1-inset'>";
					echo "<a href='./index.php?action=manage-chats' class='alt-tab "; if((isset($get['action']) && !empty($get) && $get['action'] == "manage-chats") || (!empty($get) && isset($get['chat-search'])) || (!empty($get) && isset($get['manage-chat']))) {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>Manage Chats</a>";
					if(save_messages() == 1) { echo "<a href='./index.php?action=messages' class='alt-tab "; if((isset($get['action']) && !empty($get) && $get['action'] == "messages") || isset($get['message'])) {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>Messages</a>"; }
				echo "</div>";
			echo "</li>";
			
			echo "<li class='blue-grey collap'>";
				if(isset($get['action']) && !empty($get) && ($get['action'] == "settings" || $get['action'] == "user-settings" || $get['action'] == "chat-settings" || $get['action'] == "ban-settings")) {
					echo "<div class='blue-grey collapsible-header white-text active waves-effect waves-light'><i class='material-icons'>settings</i>Settings</div>";
				} else {
					echo "<div class='blue-grey collapsible-header white-text waves-effect waves-light'><i class='material-icons'>settings</i>Settings</div>";
				}
				echo "<div class='collapsible-body blue-grey active-side-tab2 z-depth-1-inset'>";
					echo "<a href='./index.php?action=settings' class='alt-tab "; if(isset($get['action']) && !empty($get) && $get['action'] == "settings") {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>General Settings</a>";
					echo "<a href='./index.php?action=user-settings' class='alt-tab "; if(isset($get['action']) && !empty($get) && $get['action'] == "user-settings") {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>User Settings</a>";				
					echo "<a href='./index.php?action=chat-settings' class='alt-tab "; if(isset($get['action']) && !empty($get) && $get['action'] == "chat-settings") {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>Chat Settings</a>";
					echo "<a href='./index.php?action=ban-settings' class='alt-tab "; if(isset($get['action']) && !empty($get) && $get['action'] == "ban-settings") {echo "active-side-tab";} echo " waves-effect waves-light'><i class='material-icons small'>chevron_right</i>Ban Settings</a></div>";
				echo "</div>";
			echo "</li>";
		echo "</ul>";
	echo "</div>";
}

function admin_search($get_search = "", $typ = "user")
{
	switch($typ) {
		case "user":
			$type = 1;
			$url = "search=";
			break;
		case "chat":
			$type = 2;
			$url = "chat-search=";
			break;
		case "msg":
			$type = 3;
			$url = "message=";
			break;
		default:
			$type = 1;
			$url = "search=";
			break;
	}
	
        search_card($typ);
        if(empty($get_search)) {
            echo "<div class='row'>";
            echo "<div class='col s12 m12'>";
            echo "<div class='card'>";
            echo "<div class='card-content'>";
            echo "<span class='card-title'>Search Results</span>";
			if($type == 1) {
				echo "<p>Please search a user.</p>";
			} elseif($type == 2) {
				echo "<p>Please search a chat room.</p>";
			} elseif($type == 3) {
				echo "<p>Please search a message.</p>";
			}
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        } else {
            $search = db_escape(htmlentities($get_search));
            $non_search = $get_search;
            if (!isset($_GET['page'])) {
                $page = 1;
            } else {
                $page = db_escape(htmlentities($_GET['page']));
            }
			if($type == 3) {
				$begin = ($page * 15) - 15;
			} else {
				$begin = ($page * 10) - 10;
			}
            
            $int = $begin + 1;
			if($type == 1) {
				$query = db_query("SELECT `ID`, `username`, `email`, `guest`, `admin` FROM `members` WHERE `email` LIKE '%$search%' || `username` LIKE '%$search%' || `ID` = '$search' LIMIT $begin, 10");
				$total_query = db_query("SELECT `ID` FROM `members` WHERE `email` LIKE '%$search%' || `username` LIKE '%$search%' || `ID` = '$search'");
			} elseif($type == 2) {
				$query = db_query("SELECT `ID`, `chat_name`, `owner_name`, `owner_id`, `type` FROM `chat_room` WHERE `chat_name` LIKE '%$search%' || `ID` = '$search' || `id_hash` = '$search' LIMIT $begin, 10");
				$total_query = db_query("SELECT `ID` FROM `chat_room` WHERE `chat_name` LIKE '%$search%' || `ID` = '$search' || `id_hash` = '$search'");
			} elseif($type == 3) {
				$query = db_query("SELECT `chat_room`, `message`, `from_id`, `from_name`, `time`, `ID` FROM `chat_messages` WHERE `message` LIKE '%$search%' && `type` = 'user' LIMIT $begin, 15");
				$total_query = db_query("SELECT `ID` FROM `chat_messages` WHERE `message` LIKE '%$search%' && `type` = 'user'");
			}

            $total_result = mysqli_num_rows($total_query);
			if($type == 3) {
				$total_page = ceil($total_result / 15);
			} else {
				$total_page = ceil($total_result / 10);
			}
            
            if ($total_result == 0) {
                echo "<div class='row'>";
                echo "<div class='col s12 m12'>";
                echo "<div class='card'>";
                echo "<div class='card-content'>";
                echo "<span class='card-title'>Search Results</span>";
				if($type == 1) {
					echo "<p>Could not find any users.</p>";
				} elseif($type == 2) {
					echo "<p>Could not find any chat rooms.</p>";
				} elseif($type == 3) {
					echo "<p>Could not find any messages.</p>";
				}
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            } else {
				if($type == 2) {
					echo "<div class='row'>";
					echo "<div class='col s12'>";
					echo "<div class='card'>";
					echo "<div class='card-content'>";
						echo "<blockquote class='flow-text'>Room names like \"TEST1|TEST2\" show usernames for private conversations.</blockquote>";
					echo "</div>";
					echo "</div>";
					echo "</div>";
					echo "</div>";
				}
            	echo "<div class='row'>";
				echo "<div class='col s12 m12'>";
				echo "<div class='card'>";
				echo "<div class='card-content' style='overflow: auto'>";
				echo "<span class='card-title'>Search Results</span>";
				echo "<table class='bordered'>";
				echo "<thead>";
				echo "<tr>";
				if($type == 1) {
					echo "<th data-field='id'>#</th>";
					echo "<th data-field='id'>Username</th>";
					echo "<th data-field='id'>Email</th>";
					echo "<th data-field='id'>User Type</th>";
				} elseif($type == 2) {
					echo "<th class='hide-on-small-only' data-field='id'>#</th>";
					echo "<th data-field='id'>Room Name</th>";
					echo "<th data-field='id'>Owner Name</th>";
					echo "<th data-field='id'>Owner ID</th>";
					echo "<th data-field='id'>Type</th>";
				} elseif($type == 3) {
					echo "<th data-field='id'>Message</th>";
					echo "<th data-field='id'>Room Name</th>";
					echo "<th data-field='id'>Username</th>";
					echo "<th data-field='id'>Time</th>";
				}
				echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
				
				if($type == 1) {
					while ($row = mysqli_fetch_array($query)) {
						if($row[3] == 0 && $row[4] == 0) {
							$usertype = "Member";
						} elseif($row[3] == 0 && $row[4] == 1) {
							$usertype = "Admin";
						} else {
							$usertype = "Guest";
						}
						echo "<tr>";
						echo "<td>".$int."</td>";
						echo "<td><a href='./index.php?manage-user=".$row[0]."'>".$row[1]."</a></td>";
						echo "<td><a href='./index.php?manage-user=".$row[0]."'>".$row[2]."</a></td>";
						echo "<td><p>".$usertype."</p></td>";
						echo "</tr>";
						$int++;
					}
				} elseif($type == 2) {
					while ($row = mysqli_fetch_array($query)) {
						if($row[4] == 1) {
							$gtype = "Private";
						} else {
							$gtype = "Group";
						}
						echo "<tr>";
						echo "<td class='hide-on-small-only'>".$int."</td>";
						echo "<td><a href='./index.php?manage-chat=".$row[0]."'>".$row[1]."</a></td>";
						echo "<td><a href='./index.php?manage-user=".$row[3]."'>".$row[2]."</a></td>";
						echo "<td><a href='./index.php?manage-user=".$row[3]."'>".$row[3]."</a></td>";
						echo "<td><p>".$gtype."</p></td>";
						echo "</tr>";
						$int++;
					}
				} elseif($type == 3) {
					while ($row = mysqli_fetch_array($query)) {
						if($row[4] == 1) {
							$gtype = "Private";
						} else {
							$gtype = "Group";
						}
						echo "<tr>";
						echo "<td><p>".$row[1]."</p></td>";
						echo "<td><a href='./index.php?manage-chat=".$row[0]."'>".chat_name($row[0])."</a></td>";
						echo "<td><a href='./index.php?manage-user=".$row[2]."'>".$row[3]."</a></td>";
						if(date("d/m/Y", $row[4]) == date("d/m/Y")) {
							echo "<td><p>Today<br>".date("H:i", $row[4])."</p></td>";
						} elseif(date("d/m/Y", $row[4]) == date('d/m/Y',strtotime("-1 days"))) {
							echo "<td><p>Yesterday<br>".date("H:i", $row[4])."</p></td>";
						} else {
							echo "<td><p>".date("d/m/Y", $row[4])."<br>".date("H:i", $row[4])."</p></td>";
						}
						echo "<td><p><i attr-id='".$row[5]."' id='delete-message' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></p></td>";
						echo "</tr>";
					}
				}
				echo "</tbody>";
				echo "</table>";
				echo "<ul class='pagination center-align'>";
				
				$next_page = $page + 1;
                $prev_page = $page - 1;
                if ($total_page < 9) {
                    if ($page == 1) {
                        echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
                        echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
                    } else {
                        echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search."&page=1'><i class='material-icons last'>first_page</i></a></li>";
                        echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
                    }
                    for ($i = 1; $i <= $total_page; $i++) {
                        if ($page == $i) {
                            echo "<li class='active'><a href='./index.php?".$url.$non_search.'&page='.$i."'>".$i.'</a></li>';
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$i."'>".$i.'</a></li>';
                        }
                    }
                    if ($page == $total_page) {
                        echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
                        echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
                    } else {
                        echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
                        echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$total_page."'><i class='material-icons last'>last_page</i></a></li>";
                    }
                } else {
                    if ($page < 6) {
                        if ($page == 1) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search."&page=1'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
                        }
                        for ($i = 1; $i <= 9; $i++) {
                            if ($page == $i) {
                                echo "<a href='./index.php?".$url.$non_search.'&page='.$i."'><li class='active'>".$i.'</li></a>';
                            } else {
                                echo "<a href='./index.php?".$url.$non_search.'&page='.$i."'><li class='waves-effect'>".$i.'</li></a>';
                            }
                        }
                        if ($page == $total_page) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$total_page."'><i class='material-icons last'>last_page</i></a></li>";
                        }
                    } elseif ($page > $total_page - 4) {
                        if ($page == 1) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search."&page=1'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
                        }
                        for ($i = $total_page - 8; $i <= $total_page; $i++) {
                            if ($page == $i) {
                                echo "<li class='active'><a href='./index.php?".$url.$non_search.'&page='.$i."'>".$i.'</a></li>';
                            } else {
                                echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$i."'>".$i.'</a></li>';
                            }
                        }
                        if ($page == $total_page) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$total_page."'><i class='material-icons last'>last_page</i></a></li>";
                        }
                    } else {
                        $num = $page - 4;
                        if ($page == 1) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search."&page=1'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
                        }
                        for ($num; $num <= $page + 4; $num++) {
                            if ($page == $num) {
                                echo "<li class='active'><a href='./index.php?".$url.$non_search.'&page='.$num."'>".$num.'</a></li>';
                            } else {
                                echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$num."'>".$num.'</a></li>';
                            }
                        }
                        if ($page == $total_page) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?".$url.$non_search.'&page='.$total_page."'><i class='material-icons last'>last_page</i></a></li>";
                        }
                    }
                }
				echo "</ul>";	
				echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}	
		}
}

function all_chats()
{			
			$url = "chat-search=";
            if (!isset($_GET['page'])) {
                $page = 1;
            } else {
                $page = db_escape(htmlentities($_GET['page']));
            }
            $begin = ($page * 10) - 10;
            $int = $begin + 1;

			$query = db_query("SELECT `ID`, `chat_name`, `owner_name`, `owner_id`, `type` FROM `chat_room` ORDER BY `last_message_time` DESC LIMIT $begin, 10");
			$total_query = db_query("SELECT `ID` FROM `chat_room`");

            $total_result = mysqli_num_rows($total_query);
            $total_page = ceil($total_result / 10);
            if ($total_result > 0) {
					echo "<div class='row'>";
					echo "<div class='col s12'>";
					echo "<div class='card'>";
					echo "<div class='card-content'>";
						echo "<blockquote class='flow-text'>Room names like \"TEST1|TEST2\" show usernames for private conversations.</blockquote>";
					echo "</div>";
					echo "</div>";
					echo "</div>";
				echo "<div class='col s12 m12'>";
				echo "<div class='card'>";
				echo "<div class='card-content' style='overflow: auto'>";
				echo "<span class='card-title'>All Conversations</span>";
				echo "<table class='bordered'>";
				echo "<thead>";
				echo "<tr>";
				echo "<th class='hide-on-small-only' data-field='id'>#</th>";
				echo "<th data-field='id'>Room Name</th>";
				echo "<th data-field='id'>Owner Name</th>";
				echo "<th data-field='id'>Owner ID</th>";
				echo "<th data-field='id'>Type</th>";
				echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
				while ($row = mysqli_fetch_array($query)) {
					if($row[4] == 1) {
						$gtype = "Private";
					} else {
						$gtype = "Group";
					}
					echo "<tr>";
					echo "<td class='hide-on-small-only'>".$int."</td>";
					echo "<td><a href='./index.php?manage-chat=".$row[0]."'>".$row[1]."</a></td>";
					echo "<td><a href='./index.php?manage-user=".$row[3]."'>".$row[2]."</a></td>";
					echo "<td><a href='./index.php?manage-user=".$row[3]."'>".$row[3]."</a></td>";
					echo "<td><p>".$gtype."</p></td>";
					echo "</tr>";
					$int++;
				}
				
				echo "</tbody>";
				echo "</table>";
				echo "<ul class='pagination center-align'>";
				
				$next_page = $page + 1;
                $prev_page = $page - 1;
                if ($total_page < 9) {
                    if ($page == 1) {
                        echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
                        echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
                    } else {
                        echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=1'><i class='material-icons last'>first_page</i></a></li>";
                        echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
                    }
                    for ($i = 1; $i <= $total_page; $i++) {
                        if ($page == $i) {
                            echo "<li class='active'><a href='./index.php?action=manage-chats&page=".$i."'>".$i.'</a></li>';
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$i."'>".$i.'</a></li>';
                        }
                    }
                    if ($page == $total_page) {
                        echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
                        echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
                    } else {
                        echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
                        echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$total_page."'><i class='material-icons last'>last_page</i></a></li>";
                    }
                } else {
                    if ($page < 6) {
                        if ($page == 1) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=1'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
                        }
                        for ($i = 1; $i <= 9; $i++) {
                            if ($page == $i) {
                                echo "<a href='./index.php?action=manage-chats&page=".$i."'><li class='active'>".$i.'</li></a>';
                            } else {
                                echo "<a href='./index.php?action=manage-chats&page=".$i."'><li class='waves-effect'>".$i.'</li></a>';
                            }
                        }
                        if ($page == $total_page) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$total_page."'><i class='material-icons last'>last_page</i></a></li>";
                        }
                    } elseif ($page > $total_page - 4) {
                        if ($page == 1) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=1'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
                        }
                        for ($i = $total_page - 8; $i <= $total_page; $i++) {
                            if ($page == $i) {
                                echo "<li class='active'><a href='./index.php?action=manage-chats&page=".$i."'>".$i.'</a></li>';
                            } else {
                                echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$i."'>".$i."</a></li>";
                            }
                        }
                        if ($page == $total_page) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$total_page."'><i class='material-icons last'>last_page</i></a></li>";
                        }
                    } else {
                        $num = $page - 4;
                        if ($page == 1) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=1'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
                        }
                        for ($num; $num <= $page + 4; $num++) {
                            if ($page == $num) {
                                echo "<li class='active'><a href='./index.php?action=manage-chats&page=".$num."'>".$num."</a></li>";
                            } else {
                                echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$num."'>".$num."</a></li>";
                            }
                        }
                        if ($page == $total_page) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?action=manage-chats&page=".$total_page."'><i class='material-icons last'>last_page</i></a></li>";
                        }
                    }
                }
				echo "</ul>";	
				echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}
}

function total_messages($width, $color = "")
{
	$today = strtotime('tomorrow midnight');
	$yesterday = strtotime('today midnight');
	$total_query_today = db_query("SELECT `ID` FROM `chat_messages` WHERE `type` = 'user' && `time` <= '$today' && `time` >= '$yesterday'");
	$total_query = db_query("SELECT `ID` FROM `chat_messages` WHERE `type` = 'user'");
	echo "<div class='col ".$width."'>";
	echo "<div class='card ".$color."'>";
	echo "<div class='card-content center-align'>";
		echo "<p class='statistic_text_num'>".mysqli_num_rows($total_query)."</p>";
		echo "<p class='statistic_text_text'>Messages Sent</p>";
		echo "<div class='divider'></div>";
		echo "<p class='left-align statistic_text_bottom'>Today: ".mysqli_num_rows($total_query_today)."</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function total_files($width, $color = "")
{
	$today = strtotime('tomorrow midnight');
	$yesterday = strtotime('today midnight');
	$total_query_today = db_query("SELECT `ID` FROM `chat_messages` WHERE (`type` = 'user_media_vid' || `type` = 'user_media_img' || `type` = 'user_media_file' || `type` = 'user_media_music') && `time` <= '$today' && `time` >= '$yesterday'");
	$total_query = db_query("SELECT `ID` FROM `chat_messages` WHERE `type` = 'user_media_vid' || `type` = 'user_media_img' || `type` = 'user_media_file' || `type` = 'user_media_music'");
	echo "<div class='col ".$width."'>";
	echo "<div class='card ".$color."'>";
	echo "<div class='card-content center-align'>";
		echo "<p class='statistic_text_num'>".mysqli_num_rows($total_query)."</p>";
		echo "<p class='statistic_text_text'>Files Sent</p>";
		echo "<div class='divider'></div>";
		echo "<p class='left-align statistic_text_bottom'>Today: ".mysqli_num_rows($total_query_today)."</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function total_chats($width, $color = "")
{
	$today = strtotime('tomorrow midnight');
	$yesterday = strtotime('today midnight');
	$total_query2_today = db_query("SELECT `ID` FROM `chat_room` WHERE `time` <= '$today' && `time` >= '$yesterday'");
	$total_query2 = db_query("SELECT `ID` FROM `chat_room`");
	echo "<div class='col ".$width."'>";
	echo "<div class='card ".$color."'>";
	echo "<div class='card-content center-align'>";
		echo "<p class='statistic_text_num'>".mysqli_num_rows($total_query2)."</p>";
		echo "<p class='statistic_text_text'>Rooms Created</p>";
		echo "<div class='divider'></div>";
		echo "<p class='left-align statistic_text_bottom'>Today: ".mysqli_num_rows($total_query2_today)."</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function total_member($width, $color = "")
{
	$today = strtotime('tomorrow midnight');
	$yesterday = strtotime('today midnight');
	$total_member_today = db_query("SELECT `ID` FROM `members` WHERE `guest` = 0 && `admin` = 0 && `registration_date` <= '$today' && `registration_date` >= '$yesterday'");
	$total_member = db_query("SELECT `ID` FROM `members` WHERE `guest` = 0 && `admin` = 0");
	echo "<div class='col ".$width."'>";
	echo "<div class='card ".$color."'>";
	echo "<div class='card-content center-align'>";
		echo "<p class='statistic_text_num'>".mysqli_num_rows($total_member)."</p>";
		echo "<p class='statistic_text_text'>Total Members</p>";
		echo "<div class='divider'></div>";
		echo "<p class='left-align statistic_text_bottom'>Registered Today: ".mysqli_num_rows($total_member_today)."</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function online_member($width, $color = "")
{
	$total_member = db_query("SELECT `ID` FROM `members` WHERE `guest` = 0 && `admin` = 0 && `online` = 1");
	echo "<div class='col ".$width."'>";
	echo "<div class='card ".$color."'>";
	echo "<div class='card-content center-align'>";
		echo "<p class='statistic_text_num'>".mysqli_num_rows($total_member)."</p>";
		echo "<p class='statistic_text_text'>Online Members</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function total_user($width, $color = "")
{
	$today = strtotime('tomorrow midnight');
	$yesterday = strtotime('today midnight');
	$total_member_today = db_query("SELECT `ID` FROM `members` WHERE `registration_date` <= '$today' && `registration_date` >= '$yesterday'");
	$total_member = db_query("SELECT `ID` FROM `members`");
	echo "<div class='col ".$width."'>";
	echo "<div class='card ".$color."'>";
	echo "<div class='card-content center-align'>";
		echo "<p class='statistic_text_num'>".mysqli_num_rows($total_member)."</p>";
		echo "<p class='statistic_text_text'>Total Accounts</p>";
		echo "<div class='divider'></div>";
		echo "<p class='left-align statistic_text_bottom'>Registered Today: ".mysqli_num_rows($total_member_today)."</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function online_user($width, $color = "")
{
	$total_member = db_query("SELECT `ID` FROM `members` WHERE `online` = 1");
	echo "<div class='col ".$width."'>";
	echo "<div class='card ".$color."'>";
	echo "<div class='card-content center-align'>";
		echo "<p class='statistic_text_num'>".mysqli_num_rows($total_member)."</p>";
		echo "<p class='statistic_text_text'>Online Accounts</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function total_guest($width, $color = "")
{
	$today = strtotime('tomorrow midnight');
	$yesterday = strtotime('today midnight');
	$total_guest_today = db_query("SELECT `ID` FROM `members` WHERE `guest` = 1 && `admin` = 0 && `registration_date` <= '$today' && `registration_date` >= '$yesterday'");
	$total_guest = db_query("SELECT `ID` FROM `members` WHERE `guest` = 1 && `admin` = 0");
	echo "<div class='col ".$width."'>";
	echo "<div class='card ".$color."'>";
	echo "<div class='card-content center-align'>";
		echo "<p class='statistic_text_num'>".mysqli_num_rows($total_guest)."</p>";
		echo "<p class='statistic_text_text'>Total Guests</p>";
		echo "<div class='divider'></div>";
		echo "<p class='left-align statistic_text_bottom'>Registered Today: ".mysqli_num_rows($total_guest_today)."</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function online_guest($width, $color = "")
{
	$total_guest = db_query("SELECT `ID` FROM `members` WHERE `guest` = 1 && `admin` = 0 && `online` = 1");
	echo "<div class='col ".$width."'>";
	echo "<div class='card ".$color."'>";
	echo "<div class='card-content center-align'>";
		echo "<p class='statistic_text_num'>".mysqli_num_rows($total_guest)."</p>";
		echo "<p class='statistic_text_text'>Online Guests</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function total_admin($width, $color = "")
{
	$today = strtotime('tomorrow midnight');
	$yesterday = strtotime('today midnight');
	$total_admin_today = db_query("SELECT `ID` FROM `members` WHERE `guest` = 0 && `admin` = 1 && `registration_date` <= '$today' && `registration_date` >= '$yesterday'");
	$total_admin = db_query("SELECT `ID` FROM `members` WHERE `guest` = 0 && `admin` = 1");
	echo "<div class='col ".$width."'>";
	echo "<div class='card ".$color."'>";
	echo "<div class='card-content center-align'>";
		echo "<p class='statistic_text_num'>".mysqli_num_rows($total_admin)."</p>";
		echo "<p class='statistic_text_text'>Total Admins</p>";
		echo "<div class='divider'></div>";
		echo "<p class='left-align statistic_text_bottom'>Registered Today: ".mysqli_num_rows($total_admin_today)."</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function online_admin($width, $color = "")
{
	$total_admin = db_query("SELECT `ID` FROM `members` WHERE `guest` = 0 && `admin` = 1 && `online` = 1");
	echo "<div class='col ".$width."'>";
	echo "<div class='card ".$color."'>";
	echo "<div class='card-content center-align'>";
		echo "<p class='statistic_text_num'>".mysqli_num_rows($total_admin)."</p>";
		echo "<p class='statistic_text_text'>Online Admins</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function websocket_connection($width, $color = "")
{
	echo "<div class='col ".$width."'>";
	echo "<div class='card ".$color."'>";
	echo "<div class='card-content center-align'>";
		echo "<div id='websocket' class='connection_stat red darken-3'></div>";
		echo "<p class='statistic_text_text'>WebSocket Connection Status</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function database_connection($width, $color = "")
{
	echo "<div class='col ".$width."'>";
	echo "<div class='card ".$color."'>";
	echo "<div class='card-content center-align'>";
		if(db_connect()) {
			echo "<div id='websocket' class='connection_stat green darken-3'></div>";
		} else {
			echo "<div id='websocket' class='connection_stat red darken-3'></div>";
		}
		echo "<p class='statistic_text_text'>Database Connection Status</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function all_messages()
{
			$url = "action=messages";
            if (!isset($_GET['page'])) {
                $page = 1;
            } else {
                $page = db_escape(htmlentities($_GET['page']));
            }
            $begin = ($page * 15) - 15;

			$query = db_query("SELECT `chat_room`, `message`, `from_id`, `from_name`, `time`, `ID` FROM `chat_messages` WHERE `type` = 'user' ORDER BY `time` DESC LIMIT $begin, 15");
			$total_query = db_query("SELECT `ID` FROM `chat_messages` WHERE `type` = 'user'");

            $total_result = mysqli_num_rows($total_query);
            $total_page = ceil($total_result / 15);
			
			echo "<div class='row'>";
				total_messages("s6", "blue-grey darken-1 white-text");
				total_chats("s6", "blue-grey darken-1 white-text");
			echo "</div>";
			
			echo "<div class='row'>";
				search_card("msg");
			echo "</div>";

            if ($total_result > 0) {
            	echo "<div class='row'>";
				echo "<div class='col s12 m12'>";
				echo "<div class='card'>";
				echo "<div class='card-content' style='overflow: auto'>";
				echo "<span class='card-title'>All Messages</span>";
				echo "<table class='bordered'>";
				echo "<thead>";
				echo "<tr>";
				echo "<th data-field='id'>Message</th>";
				echo "<th data-field='id'>Room Name</th>";
				echo "<th data-field='id'>Username</th>";
				echo "<th data-field='id'>Time</th>";
				echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
				while ($row = mysqli_fetch_array($query)) {
					$message_old = preg_replace("/\:\:e\|\|(.*?)\:\:/", "<img ondragstart='return false;' alt='&#x$1' src='../include/web-imgs/blank.jpg' style='background-image: url(\"../include/web-imgs/emojis.png\");' class='emoji-link-small sprite sprite-$1' draggable='false' />", $row[1]);
					$message = preg_replace_callback('/(\ alt=\')(.*?)(\')/', function ($matches) {
						return $matches[1].str_replace('-', '&#x', $matches[2]).$matches[3];
					}, $message_old);
					echo "<tr>";
					echo "<td><p>".$message."</p></td>";
					echo "<td><a href='./index.php?manage-chat=".$row[0]."'>".chat_name($row[0])."</a></td>";
					echo "<td><a href='./index.php?manage-user=".$row[2]."'>".$row[3]."</a></td>";
					if(date("d/m/Y", $row[4]) == date("d/m/Y")) {
						echo "<td><p>Today<br>".date("H:i", $row[4])."</p></td>";
					} elseif(date("d/m/Y", $row[4]) == date('d/m/Y',strtotime("-1 days"))) {
						echo "<td><p>Yesterday<br>".date("H:i", $row[4])."</p></td>";
					} else {
						echo "<td><p>".date("d/m/Y", $row[4])."<br>".date("H:i", $row[4])."</p></td>";
					}
					echo "<td><p><i attr-id='".$row[5]."' id='delete-message' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></p></td>";
					echo "</tr>";
				}
				
				echo "</tbody>";
				echo "</table>";
				echo "<ul class='pagination center-align'>";
				
				$next_page = $page + 1;
                $prev_page = $page - 1;
                if ($total_page < 9) {
                    if ($page == 1) {
                        echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
                        echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
                    } else {
                        echo "<li class='waves-effect'><a href='./index.php?".$url."&page=1'><i class='material-icons last'>first_page</i></a></li>";
                        echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
                    }
                    for ($i = 1; $i <= $total_page; $i++) {
                        if ($page == $i) {
                            echo "<li class='active'><a href='./index.php?".$url."&page=".$i."'>".$i.'</a></li>';
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$i."'>".$i.'</a></li>';
                        }
                    }
                    if ($page == $total_page) {
                        echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
                        echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
                    } else {
                        echo "<li class='waves-effect'><a href='./index.php?action=".$url."&page=".$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
                        echo "<li class='waves-effect'><a href='./index.php?action=".$url."&page=".$total_page."'><i class='material-icons last'>last_page</i></a></li>";
                    }
                } else {
                    if ($page < 6) {
                        if ($page == 1) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url."&page=1'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
                        }
                        for ($i = 1; $i <= 9; $i++) {
                            if ($page == $i) {
                                echo "<li class='active'><a href='./index.php?".$url."&page=".$i."'>".$i.'</a></li>';
                            } else {
                                echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$i."'>".$i.'</a></li>';
                            }
                        }
                        if ($page == $total_page) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$total_page."'><i class='material-icons last'>last_page</i></a></li>";
                        }
                    } elseif ($page > $total_page - 4) {
                        if ($page == 1) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url."&page=1'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
                        }
                        for ($i = $total_page - 8; $i <= $total_page; $i++) {
                            if ($page == $i) {
                                echo "<li class='active'><a href='./index.php?".$url."&page=".$i."'>".$i.'</a></li>';
                            } else {
                                echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$i."'>".$i."</a></li>";
                            }
                        }
                        if ($page == $total_page) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$total_page."'><i class='material-icons last'>last_page</i></a></li>";
                        }
                    } else {
                        $num = $page - 4;
                        if ($page == 1) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url."&page=1'><i class='material-icons last'>first_page</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
                        }
                        for ($num; $num <= $page + 4; $num++) {
                            if ($page == $num) {
                                echo "<li class='active'><a href='./index.php?".$url."&page=".$num."'>".$num."</a></li>";
                            } else {
                                echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$num."'>".$num."</a></li>";
                            }
                        }
                        if ($page == $total_page) {
                            echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
                        } else {
                            echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
                            echo "<li class='waves-effect'><a href='./index.php?".$url."&page=".$total_page."'><i class='material-icons last'>last_page</i></a></li>";
                        }
                    }
                }
				echo "</ul>";	
				echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}
}

function edit_user($user_id) 
{
	$search = db_escape($user_id);
	$query = db_query("SELECT `username`, `email`, `profile_pic`, `registration_date`, `guest`, `guest_confirmation`, `online`, `admin`, `temp_pass`, `user_status`, `activation` FROM `members` WHERE `ID` = '$search' LIMIT 1");
	if(mysqli_num_rows($query) > 0 && !empty($search) && is_numeric($_GET['manage-user'])) {
		$row = mysqli_fetch_array($query);
		$username = $row[0];
		$email = $row[1];
		$profile_pic = $row[2];
		$reg_date = date("d/m/Y H:i", $row[3]);
		$guest = $row[4];
		$admin = $row[7];
		$temp_pass = $row[8];
		$status = $row[9];
		$activation = $row[10];
		if($guest == 1) {
			$user_type = "Guest";
		} elseif($admin == 1) {
			$user_type = "Admin";
		} else {
			$user_type = "Member";
		}
		$guest_confirm = $row[5];
		$online = $row[6];

		echo "<style>[type='radio'].with-gap:checked+label:after,[type='radio']:checked+label:after {background-color:#fff!important;border:2px solid #fff!important}[type='radio'].with-gap+label:before{border:2px solid #fff!important;}</style>";

		echo "<div class='row'>";
		echo "<div class='col s12 m12 l12'>";
		echo "<div class='card'>";
		echo "<div class='card-content'>";
			echo "<div class='row'>";
			echo "<div class='col s12 m12 l12'>";
			echo "<div class='card grey darken-2 center-align white-text'>";
			echo "<div class='card-content'>";
				echo "<span class='card-title' id='username-title'>".$username."</span>";
				if(!isBanned($_GET['manage-user'])) {
					if($online == 1) {
						echo "<p id='title-status' class='green-text text-lighten-1'>Online</p>";
					} else {
						echo "<p id='title-status' class='red-text text-lighten-2'>Offline</p>";
					}
				} else {
					echo "<p id='title-status' class='red-text text-lighten-2'>BANNED</p>";
				}
				echo "<p style='font-weight:300;margin-top:5px;'>Registration Date: ".$reg_date."</p>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div>";

			echo "<div class='row'>";
			echo "<div class='col s12 m6 l4'>";
			echo "<div class='card blue-grey darken-2 center-align white-text' style='height:360px'>";
			echo "<div class='card-content'>";
				echo "<div class='card-title'>Profile Photo</div>";
				echo "<ul id='change-pp-dropdown' class='dropdown-content'>";
					echo "<li><a href='#' id='upload-photo'>Upload Photo</a></li>";
					echo "<input type='file' accept='image/*' id='upload-pp-admin' style='display:none;' />";
					if (empty($profile_pic)) {
						echo "<div id='pp-act-div' class='hide'>";
					} else {
						echo "<div id='pp-act-div'>";
					}
					echo "<li class='divider'></li>";
					echo "<li><a href='#' id='remove-photo'>Remove Photo</a></li>";
					echo "</div>";
				echo "</ul>";
				echo "<a class='dropdown-button' href='#' id='cchange-pp-dropdown'><div class='change-pp'>Change Profile Photo</div></a>";
				if(empty($profile_pic)) {
					echo "<i id='pp-main' class='valign material-icons circle big-pp grey lighten-2'>person</i>";
					echo "<img id='pp-img-main' class='valign circle pp hide big-pp' width='175' height='175' src=''>";
				} else {
					echo "<i id='pp-main' class='valign material-icons circle big-pp grey lighten-2 hide'>person</i>";
					echo "<img id='pp-img-main' class='valign circle pp big-pp' width='175' height='175' src='".".".picture_destination().$profile_pic."'>";
				}
				echo "<ul id='save-pp-ul'><li><a id='save-pp' style='margin:0 7px' class='waves-effect waves-light btn'><i style='font-size:2rem' class='material-icons large'>save</i></a><a style='margin:0 7px' id='discard-pp' class='waves-effect waves-light btn'><i style='font-size:2rem' class='material-icons large'>close</i></a></li></ul>";
				echo "</div>";
			echo "</div>";
			echo "</div>";

			echo "<div class='col s12 m6 l8'>";
			echo "<div class='card blue-grey darken-2 center-align white-text' style='height:360px'>";
			echo "<div class='card-content'>";
				echo "<span class='card-title'>Profile</span>";
				echo "<div class='row'>";
					echo "<form class='col s12'>";
					echo "<div class='row'>";
						echo "<div class='input-field col s10'>";
						echo "<input disabled value='".$username."' id='username' type='text' class='white-text'>";
						echo "<label for='username' class='white-text'>Username</label>";
						echo "</div>";
						echo "<div class='input-field col s2'>";
						echo "<a id='edit-username' class='waves-effect waves-blue-grey btn-flat' style='padding: 0 10px;'><i class='material-icons small white-text'>edit</i></a>";
						echo "<a id='confirm-username' class='waves-effect waves-blue-grey btn-flat hide' style='padding: 0 10px;'><i class='material-icons small white-text'>done</i></a>";
						echo "<a id='reset-username' class='waves-effect waves-blue-grey btn-flat hide' style='padding: 0 10px;'><i class='material-icons small white-text'>close</i></a>";
						echo "</div>";
						echo "</div>";
						echo "<div class='row'>";
						echo "<div class='input-field col s10'>";
						echo "<input disabled value='".$email."' id='user-email' type='text' class='white-text'>";
						echo "<label for='user-email' class='white-text'>Email</label>";
						echo "</div>";
						echo "<div class='input-field col s2'>";
						echo "<a id='edit-email' class='waves-effect waves-blue-grey btn-flat' style='padding: 0 10px;'><i class='material-icons small white-text'>edit</i></a>";
						echo "<a id='confirm-email' class='waves-effect waves-blue-grey btn-flat hide' style='padding: 0 10px;'><i class='material-icons small white-text'>done</i></a>";
						echo "<a id='reset-email' class='waves-effect waves-blue-grey btn-flat hide' style='padding: 0 10px;'><i class='material-icons small white-text'>close</i></a>";
						echo "</div>";
						echo "</div>";
						echo "<div class='row'>";
						echo "<div class='input-field col s10'>";
						if(empty($status)) {
							echo "<input disabled value='".get_option("DEFAULT_STATUS")."' id='user-status' type='text' class='white-text'>";
						} else {
							echo "<input disabled value='".$status."' id='user-status' type='text' class='white-text'>";
						}
						echo "<label for='user-status' class='white-text'>Status</label>";
						echo "</div>";
						echo "<div class='input-field col s2'>";
						echo "<a id='edit-status' class='waves-effect waves-blue-grey btn-flat' style='padding: 0 10px;'><i class='material-icons small white-text'>edit</i></a>";
						echo "<a id='confirm-status' class='waves-effect waves-blue-grey btn-flat hide' style='padding: 0 10px;'><i class='material-icons small white-text'>done</i></a>";
						echo "<a id='reset-status' class='waves-effect waves-blue-grey btn-flat hide' style='padding: 0 10px;'><i class='material-icons small white-text'>close</i></a>";
						echo "</div>";
						echo "</div>";

						echo "</form>";
						echo "</div>";
						echo "</div>";
						echo "</div>";
						echo "</div>";
						echo "</div>";
							
						echo "<div class='row'>";
						echo "<div class='col s12 m6 l4'>";
						echo "<div class='card blue-grey darken-2 center-align white-text'>";
						echo "<div class='card-content'>";
						echo "<div class='card-title'>Status</div>";
						echo "<div class='row left-align'>";
						echo "<div class='input-field col s12'>";
						if($online == 1) {
							echo "<p>";
							echo "<input class='with-gap' name='online' type='radio' id='on' checked/>";
							echo "<label class='white-text' for='on'>Online</label>";
							echo "</p><p>";
							echo "<input class='with-gap' name='online' type='radio' id='off' />";
							echo "<label class='white-text' for='off'>Offline</label>";
							echo "</p>";
						} else {
							echo "<p>";
							echo "<input class='with-gap' name='online' type='radio' id='on' />";
							echo "<label class='white-text' for='on'>Online</label>";
							echo "</p><p>";
							echo "<input class='with-gap' name='online' type='radio' id='off' checked/>";
							echo "<label class='white-text' for='off'>Offline</label>";
							echo "</p>";
						}
						echo "</div>";
						echo "</div>";
						echo "</div>";
						echo "<div class='card-action'>";
							echo "<a href='#' class='white-text' id='save-status'>Save</a>";
						echo "</div>";
						echo "</div>";
						echo "</div>";

						echo "<div class='col s12 m6 l4'>";
						echo "<div class='card blue-grey darken-2 center-align white-text'>";
						echo "<div class='card-content'>";
						echo "<div class='card-title'>User Type</div>";
						echo "<div class='row'>";
						echo "<input value='".$user_type."' id='user_stat' type='text' class='hide'>";
						echo "<div class='input-field col s12'>";
						echo "<select id='select_user_type'>";
						if($user_type == "Admin") {
							echo "<option value='Admin' selected>Admin</option>";
							echo "<option value='Member'>Member</option>";
							echo "<option value='Guest'>Guest</option>";
						} elseif($user_type == "Member") {
							echo "<option value='Admin'>Admin</option>";
							echo "<option value='Member' selected>Member</option>";
							echo "<option value='Guest'>Guest</option>";
						} else {
							echo "<option value='Admin'>Admin</option>";
							echo "<option value='Member'>Member</option>";
							echo "<option value='Guest' selected>Guest</option>";
						}								
						echo "</select>";
						echo "<label class='white-text'>User Type</label>";
						echo "</div>";
						echo "</div>";
						if(get_option("USER_ACTIVATION") == 1) {
							echo "<div class='row left-align'>";
								echo "<p>";
								if($activation == 1) {
									echo "<input class='with-gap' name='email_activation' type='radio' id='on-ac' checked/>";
								} else {
									echo "<input class='with-gap' name='email_activation' type='radio' id='on-ac'/>";
								}
								echo "<label class='white-text' for='on-ac'>Activated</label>";
								echo "</p><p>";
								if($activation == 0) {
									echo "<input class='with-gap' name='email_activation' type='radio' id='off-ac' checked/>";
								} else {
									echo "<input class='with-gap' name='email_activation' type='radio' id='off-ac' />";
								}
								echo "<label class='white-text' for='off-ac'>Not Activated (Requires Email Activation)</label>";
								echo "</p>";
							echo "</div>";
						} else {
							echo "<input class='hide' name='email_activation' type='radio' id='on-ac' checked/>";
						}
						echo "</div>";
						echo "<div class='card-action'>";
							echo "<a href='#' class='white-text' id='save-type'>Save</a>";
						echo "</div>";
						echo "</div>";
						echo "</div>";

						echo "<div class='col s12 m12 l4'>";
						echo "<div class='card blue-grey darken-2 center-align white-text'>";
						echo "<div class='card-content'>";
						echo "<div class='card-title'>Change Password</div>";
						echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
						echo "<input id='new_pass' type='password' class='white-text'>";
						echo "<label for='new_pass' class='white-text'>New Password</label>";
						echo "</div>";
						echo "</div>";
						echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
						echo "<input id='new_pass_2' type='password' class='white-text'>";
						echo "<label for='new_pass_2' class='white-text'>Repeat</label>";
						echo "</div>";
						echo "</div>";
					echo "</div>";
					echo "<div class='card-action'>";
						echo "<a href='#' class='white-text' id='save-password'>Save</a>";
					echo "</div>";
					echo "</div>";
					echo "</div>";
					echo "</div>";
					$check_banned = db_query("SELECT `time` FROM `chat_banned_users` WHERE `userid` = '$search' && `time` > 'time()' && `type` = 'temporary'");
					$check_banned2 = db_query("SELECT `time` FROM `chat_banned_users` WHERE `userid` = '$search' && `type` = 'permanent'");
					if(mysqli_num_rows($check_banned2) > 0 || mysqli_num_rows($check_banned) > 0) {
						echo "<div id='table-row' class='row'>";
						echo "<div class='col s12' style='margin: 35px 0;'>";
						echo "<table>";
						echo "<thead>";
						echo "<tr>";
						echo "<th>Type</th>";
						echo "<th>Reason</th>";
						echo "<th>Expiration Date</th>";
						echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
						$ban_list = db_query("SELECT `type`, `reason`, `time`, `ID` FROM `chat_banned_users` WHERE `userid` = '$search'");
						while($ban_row = mysqli_fetch_array($ban_list)) {
							if($ban_row[0] == "temporary") {
								$btype = "Temporarily Banned";
							} else {
								$btype = "Permanently Banned";
							}
							if(empty($ban_row[2])) {
								$date_time = "";
							} else {
								$date_time = date("d/m/Y H:i", $ban_row[2]);
							}
							echo "<tr attr-id='".$ban_row[3]."'>";
								echo "<td>".$btype."</td>";
								echo "<td>".$ban_row[1]."</td>";
								echo "<td>".$date_time."</td>";
								echo "<td><i id='delete_ban' class='material-icons small clickable'>delete_forever</i></td>";
							echo "</tr>";
						}
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
				echo "</div>";
			}
			echo "<div class='row center-align'>";
				echo "<a id='delete-user' style='margin: 10px 20px' class='waves-effect waves-light btn'>DELETE USER</a>";
				echo "<a id='block-user' style='margin: 10px 20px' class='waves-effect waves-light btn'>TEMPORARILY BAN USER</a>";
				echo "<a id='perm-user' style='margin: 10px 20px' class='waves-effect waves-light btn'>PERMANENTLY BAN  USER</a>";
				echo "<a id='logout-user' style='margin: 10px 20px' class='waves-effect waves-light btn'>LOGOUT USER</a>";
			echo "</div>";
			echo "</div>";

		echo "</div>";
		echo "</div>";
		echo "</div>";
	} else {
		echo "<div class='row'>";
		echo "<div class='col s12 m12'>";
		echo "<div class='card'>";
		echo "<div class='card-content'>";
		echo "<span class='card-title'>Could not find the user.</span>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
	}
}

function edit_chat($chat_id) 
{	
	$search = db_escape($chat_id);
	$query = db_query("SELECT `chat_name`, `group_pic`, `owner_id`, `owner_name`, `type`, `time` FROM `chat_room` WHERE `ID` = '$search' LIMIT 1");
	$query2 = db_query("SELECT `user_name`, `user_id` FROM `chat_members` WHERE `chat_room` = '$search' && `status` != 4 && `status` != 0");
	if(mysqli_num_rows($query) > 0 && !empty($search) && is_numeric($_GET['manage-chat'])) {
		$row = mysqli_fetch_array($query);
		$chat_name = $row[0];
		$profile_pic = $row[1];
		$owner_id = $row[2];
		$owner_name = $row[3];
		$type = $row[4];
		$date = $row[5];
		if($type == 1) {
			$chat_type = "PRIVATE";
		} else {
			$chat_type = "GROUP";
		}

		echo "<style>[type='radio'].with-gap:checked+label:after,[type='radio']:checked+label:after {background-color:#fff!important;border:2px solid #fff!important}[type='radio'].with-gap+label:before{border:2px solid #fff!important;}</style>";

		echo "<div class='row'>";
		echo "<div class='col s12'>";
		echo "<div class='card'>";
		echo "<div class='card-content'>";
			echo "<div class='row'>";
			echo "<div class='col s12 m12 l12'>";
			echo "<div class='card grey darken-2 center-align white-text'>";
			echo "<div class='card-content'>";
				if($type == 1) {
					$name = explode('|', $chat_name);
					echo "<div class='card-title' id='username-title' style='margin-bottom:10px;'>".$name[0]."<i class='material-icons small' style='margin: 0 15px;vertical-align:middle;line-height: 29px;'>swap_horiz</i>".$name[1]."</div>";
				} else {
					echo "<div class='card-title' id='username-title' style='margin-bottom:10px;'>".$chat_name."</div>";
				}
				echo "<p id='title-status'>".$chat_type."</p>";
				echo "<p id='title-status'>Created: ".date("d/m/Y H:i", $date)."</p>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div>";

			echo "<div class='row'>";
			if($type == 0) {
				echo "<div class='col s12 m6 l4'>";
				echo "<div class='card blue-grey darken-2 center-align white-text' style='height:327px'>";

				echo "<div class='card-content'>";
					echo "<div class='card-title'>Group Photo</div>";
					echo "<ul id='change-chat-dropdown' class='dropdown-content'>";
						echo "<li><a href='#' id='upload-photo-chat'>Upload Photo</a></li>";
						echo "<input type='file' accept='image/*' id='upload-chat-admin' style='display:none;' />";
						if (empty($profile_pic)) {
							echo "<div id='chat-act-div' class='hide'>";
						} else {
							echo "<div id='chat-act-div'>";
						}
						echo "<li class='divider'></li>";
						echo "<li><a href='#' id='remove-photo-chat'>Remove Photo</a></li>";
						echo "</div>";
					echo "</ul>";
					echo "<a class='dropdown-button' href='#' id='cchange-chat-dropdown'><div class='change-chat'>Change Group Photo</div></a>";
					if(empty($profile_pic)) {
						echo "<i id='chat-main' class='valign material-icons circle big-pp grey lighten-2'>people</i>";
						echo "<img id='chat-img-main' class='valign circle pp hide big-pp' width='175' height='175' src=''>";
					} else {
						echo "<i id='chat-main' class='valign material-icons circle big-pp grey lighten-2 hide'>people</i>";
						echo "<img id='chat-img-main' class='valign circle pp big-pp' width='175' height='175' src='".".".picture_destination().$profile_pic."'>";
					}
					echo "<ul id='save-chat-ul'><li><a id='save-chat' style='margin:0 7px' class='waves-effect waves-light btn'><i style='font-size:2rem' class='material-icons large'>save</i></a><a style='margin:0 7px' id='discard-chat' class='waves-effect waves-light btn'><i style='font-size:2rem' class='material-icons large'>close</i></a></li></ul>";
					echo "</div>";
				echo "</div>";
				echo "</div>";

				echo "<div class='col s12 m6 l8'>";
				echo "<div class='card blue-grey darken-2 center-align white-text' style='height:327px'>";
				echo "<div class='card-content'>";
					echo "<span class='card-title'>Profile</span>";
					echo "<div class='row'>";
						echo "<form class='col s12'>";
						echo "<div class='row'>";
								echo "<div class='input-field col s10'>";
								echo "<input disabled value='".$chat_name."' id='groupname' type='text' class='white-text'>";
								echo "<label for='groupname' class='white-text'>Group Name</label>";
								echo "</div>";
								echo "<div class='input-field col s2'>";
								echo "<a id='edit-groupname' class='waves-effect waves-blue-grey btn-flat' style='padding: 0 10px;'><i class='material-icons small white-text'>edit</i></a>";
								echo "<a id='confirm-groupname' class='waves-effect waves-blue-grey btn-flat hide' style='padding: 0 10px;'><i class='material-icons small white-text'>done</i></a>";
								echo "<a id='reset-groupname' class='waves-effect waves-blue-grey btn-flat hide' style='padding: 0 10px;'><i class='material-icons small white-text'>close</i></a>";
								echo "</div>";
						echo "</div>";
						echo "<div class='row'>";
						echo "<div class='input-field col s10'>";
						echo "<input disabled value='".$owner_name."' id='owner-name' type='text' class='white-text'>";
						echo "<input disabled value='".$owner_id."' id='owner-name-id' type='text' class='white-text hide'>";
						echo "<label for='owner-name' class='white-text'>Owner</label>";
						echo "</div>";
						echo "<div class='input-field col s2'>";
						echo "<a id='edit-owner' class='waves-effect waves-blue-grey btn-flat' style='padding: 0 10px;'><i class='material-icons small white-text'>edit</i></a>";
						echo "<a id='confirm-owner' class='waves-effect waves-blue-grey btn-flat hide' style='padding: 0 10px;'><i class='material-icons small white-text'>done</i></a>";
						echo "<a id='reset-owner' class='waves-effect waves-blue-grey btn-flat hide' style='padding: 0 10px;'><i class='material-icons small white-text'>close</i></a>";
						echo "</div>";
						echo "</div>";

					echo "</form>";
					echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='col s12 hide' id='admin_user_search_results'>";
					echo "<div class='card-content center-align'>";
						echo "<span class='card-title'>Search Results</span>";
					echo "</div>";
					echo "<div class='search_result'></div>";
				echo "</div>";
				
				echo "<div class='col s12'>";
					echo "<div class='card-content center-align'>";
						echo "<span class='card-title'>Users</span>";
					echo "</div>";
					if(mysqli_num_rows($query2) == 0) {
						echo "<p class='flow-text'>There is no user in this room.</p>";
					} else {
						echo "<ul class='z-depth-1 collection left-align black-text'>";
						$i = 0;
						while ($users_row = mysqli_fetch_array($query2)) {
							$ppic = profilePicture($users_row[1]);
							echo "<li class='collection-item avatar admin-user-list'>";
							if (empty($ppic)) {
								echo "<i id='chat-search' class='z-depth-1 material-icons circle chat-list-photo grey lighten-2'>person</i>";
							} else {
								echo "<img id='chat-img-search' class='z-depth-1 circle chat-list-photo' src='.".picture_destination().$ppic."'>";
							}
							echo "<span class='title'>".$users_row[0].'</span>';
							echo "<a href='#' attr-id='".$users_row[1]."' attr-name='".$users_row[0]."' attr-i='".$i."' class='secondary-content' id='kick_admin'><i class='material-icons clickable'>cancel</i></a>";
							echo "</li>";
							$i++;
						}
						echo "</ul>";
					}
				echo "</div>";
			} else {
				echo "<div class='col s12'>";
					echo "<div class='card-content center-align'>";
						echo "<span class='card-title'>Users</span>";
					echo "</div>";
					echo "<ul class='z-depth-1 collection left-align black-text'>";
					if(mysqli_num_rows($query2) == 0) {
						echo "<p>There is no user in this room.</p>";
					} else {
						while ($users_row = mysqli_fetch_array($query2)) {
							$ppic = profilePicture($users_row[1]);
							echo "<li class='collection-item avatar admin-user-list'>";
							if (empty($ppic)) {
								echo "<i id='chat-search' class='z-depth-1 material-icons circle chat-list-photo grey lighten-2'>person</i>";
							} else {
								echo "<img id='chat-img-search' class='z-depth-1 circle chat-list-photo' src='.".picture_destination().$ppic."'>";
							}
							echo "<span class='title'>".$users_row[0].'</span>';
							echo "</li>";
						}
					}
					echo "</ul>";
				echo "</div>";
			}
			echo "</div>";
			echo "</div>";
			echo "<div class='card-action center-align'>";
				if(save_messages() == 1) { echo "<a id='show-messages' style='margin: 10px 20px' class='waves-effect waves-light btn'>SHOW MESSAGES</a>"; }
				if($type == 0) { echo "<a id='invite-users' style='margin: 10px 20px' class='waves-effect waves-light btn'>INVITE NEW USERS TO THE GROUP</a>"; }
				echo "<a id='delete-chatroom' style='margin: 10px 20px' class='waves-effect waves-light btn'>DELETE CHAT ROOM</a>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
	} else {
		echo "<div class='row'>";
		echo "<div class='col s12'>";
		echo "<div class='card'>";
		echo "<div class='card-content'>";
		echo "<span class='card-title'>Could not find the chat room.</span>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
	}
}

function invite_reveal($chat_id) 
{
	$chat_id = db_escape($chat_id);
	$query = db_query("SELECT `user_name`, `user_id` FROM `chat_members` WHERE `chat_room` = '$chat_id' && `status` != 0 && `status` != 4");
	$users = mysqli_num_rows($query);
			echo "<div id='invite-modal' class='modal modal-fixed-footer'>";
			echo "<div class='modal-content'>";
				echo "<span class='card-title grey-text text-darken-4'>Invite More People</span>";
				echo "<div class='row' style='margin-top:15px;'>";
					echo "<div class='col s12'>";
						echo "<div class='card blue-grey'>";
							echo "<div class='card-content white-text'>";
								echo "<div class='input-field'>";
									echo "<input id='inv-s' type='text' style='border-bottom:1px solid #fff!important;' class='validate'>";
									echo "<label for='inv-s' style='color:#fff!important;'>Search a User</label>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "<li class='divider'></li>";
				echo "<div class='inv-users' style='margin-top:15px;'>";
				while($row = mysqli_fetch_array($query)) {
					echo "<div class='b-chip' attr-id='".$row[0]."'>".$row[0]."</div>";
				}
				echo "<div class='right inv-capacity'>".$users."/" . max_group_capacity()."</div></div>";
				echo "<li class='divider'></li>";
				echo "<div class='inv-search-content' style='margin-top:15px;display:none;'></div>";
			echo "</div>";
			echo "<div class='modal-footer'>";
				echo "<a id='invite-btn' class='modal-close modal-action waves-effect waves-green btn-flat'>Invite</a>";
				echo "<a class='modal-close modal-action waves-effect waves-green btn-flat'>Close</a>";
			echo "</div>";
		echo "</div>";
		
		echo "<script type='text/javascript'>var t = ".$users.";</script>";
}

function general_settings()
{
	echo "<div class='row'>";
	echo "<div class='col s12 m12 l12'>";
	echo "<div class='card'>";
	echo "<div class='card-content center-align'>";
		echo "<span class='card-title'>General Settings</span>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";

	echo "<div class='row'>";
	echo "<div class='col s12'>";
	echo "<div class='card'>";
	echo "<div class='card-content center-align'>";
		echo "<div class='row'>";
		echo "<div class='col s12'>";
		echo "<div class='card blue-grey darken-2 white-text'>";
		echo "<div class='card-content center-align'>";
			echo "<blockquote class='left-align' style='font-size:1.15rem;font-weight:300;'>Do not include HTTP:// or HTTPS://</blockquote>";
			echo "<blockquote class='left-align' style='font-size:1.15rem;font-weight:300;'>If you want to use secure connection (HTTPS and WSS), you need to configure your web service.<br>Configuration is instructed in the documentation.</blockquote>";
			echo "<div class='row'>";
				echo "<div class='input-field col s12'>";
					echo "<input value='".get_option("WEBSOCKET_URL")."' id='websocket_url' type='text'>";
					echo "<label for='websocket_url' class='white-text'>WebSocket Listener URL</label>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "<div class='row'>";
		echo "<div class='col s12'>";
		echo "<div class='card blue-grey darken-2 white-text'>";
		echo "<div class='card-content center-align'>";
			echo "<div class='row'>";
				echo "<div class='input-field col s12'>";
					echo "<input value='".get_option("URL")."' id='full_url' type='text'>";
					echo "<label for='full_url' class='white-text'>Full URL of Main Directory of the Script</label>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "<div class='row'>";
		echo "<div class='col s12 m6'>";
		echo "<div class='card blue-grey darken-2 white-text' style='height:136px'>";
		echo "<div class='card-content center-align'>";
			echo "<div class='row'>";
				echo "<div class='input-field col s12'>";
					echo "<input value='".get_option("TIMEZONE")."' id='timezone' type='text'>";
					echo "<label for='timezone' class='white-text'>Timezone</label>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "<div class='col s12 m6'>";
	echo "<div class='card blue-grey darken-2 white-text'>";
	echo "<div class='card-content center-align'>";
		echo "<blockquote class='left-align' style='font-size:1.15rem;font-weight:300;'>Secret key is used for encrypting Chat IDs.</blockquote>";
		echo "<a id='new-secretkey' style='' class='waves-effect waves-light btn'>CREATE A NEW SECRET KEY</a>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	
	echo "<div class='row center-align'>";
	echo "<a id='save-general-settings' style='margin: 10px 20px' class='waves-effect waves-light btn'>SAVE</a>";
	echo "</div>";

	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function user_settings()
{
	echo "<style>[type='checkbox']+label:before {border:2px solid #fff;}</style>";

	echo "<div class='row'>";
	echo "<div class='col s12 m12 l12'>";
	echo "<div class='card'>";
	echo "<div class='card-content center-align'>";
		echo "<span class='card-title'>User Settings</span>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "<div class='row'>";
	echo "<div class='col s12 m12 l12'>";
	echo "<div class='card'>";
	echo "<div class='card-content center-align'>";
		echo "<blockquote class='left-align flow-text'>While setting file sizes use \"K\" instead of \"KB\", \"M\" instead of \"MB\" and \"G\" instead of \"GB\".</blockquote>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";

	echo "<div class='row'>";
	echo "<div class='col s12'>";
	echo "<div class='card'>";
	echo "<div class='card-content center-align'>";
		echo "<div class='row'>";
		echo "<div class='col s12'>";
		echo "<div class='card blue-grey darken-2 white-text'>";
		echo "<div class='card-content center-align'>";
			echo "<span class='card-title'>Registration Settings</span>";
			echo "<br><br>";
			echo "<div class='row'>";
				echo "<div class='input-field col s12 m6'>";
					echo "<input value='".get_option("MIN_USERNAME")."' id='min_username' type='text'>";
					echo "<label for='min_username' class='white-text'>Minimum Username Lenght</label>";
				echo "</div>";
				echo "<div class='input-field col s12 m6'>";
					echo "<input value='".get_option("MAX_USERNAME")."' id='max_username' type='text'>";
					echo "<label for='max_username' class='white-text'>Maximum Username Lenght</label>";
				echo "</div>";
			echo "</div>";
			echo "<div class='row'>";
				echo "<div class='input-field col s12 m6'>";
					echo "<input value='".get_option("MIN_PASSWORD")."' id='min_password' type='text'>";
					echo "<label for='min_password' class='white-text'>Minimum Password Lenght</label>";
				echo "</div>";
				echo "<div class='input-field col s12 m6'>";
					echo "<input value='".get_option("MAX_PASSWORD")."' id='max_password' type='text'>";
					echo "<label for='max_password' class='white-text'>Maximum Password Lenght</label>";
				echo "</div>";
			echo "</div>";
			echo "<div class='row'>";
				echo "<div class='input-field col s12'>";
					echo "<input value='".get_option("MAX_EMAIL")."' id='max_email' type='text'>";
					echo "<label for='max_email' class='white-text'>Maximum Email Lenght</label>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		
		echo "<div class='col s12 m6'>";
		echo "<div class='card blue-grey darken-2'>";
		echo "<div class='card-content center-align white-text'>";
			echo "<span class='card-title white-text'>User Status</span>";
			echo "<div class='switch'>";
				echo "<label>Off";
				if(get_option("ENABLE_USER_STATUS") == 1) {
					echo "<input id='user_status' type='checkbox' checked='checked'>";
					$u_status = 1;
				} else {
					$u_status = 0;
					echo "<input id='user_status' type='checkbox'>";
				} 
				echo "<span class='lever'></span>On</label>";
			echo "</div>";
			if($u_status == 1) {
				echo "<div id='more_user_status'>";
			} else {
				echo "<div id='more_user_status' class='hide'>";
			}
				echo "<br><br><div class='row'>";
					echo "<div class='input-field col s12'>";
						echo "<input value='".get_option("DEFAULT_STATUS")."' id='default_user_status' type='text'>";
						echo "<label for='default_user_status' class='white-text'>Default User Status</label>";
					echo "</div>";
				echo "</div>";
				echo "<div class='row'>";
					echo "<div class='input-field col s12'>";
						echo "<input value='".get_option("MAX_STATUS")."' id='user_status_lenght' type='text'>";
						echo "<label for='user_status_lenght' class='white-text'>Maximum Status Lenght</label>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";

		echo "<div class='col s12 m6'>";
		echo "<div class='card blue-grey darken-2 white-text'>";
		echo "<div class='card-content center-align'>";
			echo "<span class='card-title'>Member Settings</span>";
			echo "<div class='row'>";
				echo "<div class='row left-align'>";
					echo "<div class='input-field col s12'>";
						if(get_option("ENABLE_USER_GROUPS") == 1) {
							echo "<input type='checkbox' id='member_group' checked='checked' />";
						} else {
							echo "<input type='checkbox' id='member_group' />";
						}
						echo "<label for='member_group' class='white-text'>Allow Members to Create Groups</label>";
					echo "</div>";
					echo "<div class='input-field col s12'>";
						if(get_option("ENABLE_USER_PM") == 1) {
							echo "<input type='checkbox' id='member_pm' checked='checked' />";
						} else {
							echo "<input type='checkbox' id='member_pm' />";
						}
						echo "<label for='member_pm' class='white-text'>Allow Members to Send Personal Messages</label>";
					echo "</div>";
					echo "<div class='input-field col s12 left-align'>";
						if(get_option("USER_ACTIVATION") == 1) {
							echo "<input type='checkbox' id='user_activation' checked='checked' />";
						} else {
							echo "<input type='checkbox' id='user_activation' />";
						}
						echo "<label for='user_activation' class='white-text'>Email Activation on Registration</label>";
					echo "</div>";
					echo "<div class='input-field col s12 left-align'>";
						if(get_option("FORGOT_PASSWORD") == 1) {
							echo "<input type='checkbox' id='forgot_password' checked='checked' />";
						} else {
							echo "<input type='checkbox' id='forgot_password' />";
						}
						echo "<label for='forgot_password' class='white-text'>Enable Forgot Password System</label>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";

		echo "<div class='col s12 m6'>";
		echo "<div class='card blue-grey darken-2 white-text'>";
		echo "<div class='card-content center-align'>";
			echo "<span class='card-title'>Guest Settings</span>";
				echo "<div class='switch'>";
					echo "<label>Off";
					if(get_option("GUEST_LOGIN") == 1) {
						echo "<input id='guest_login' type='checkbox' checked='checked'>";
						$g_login = 1;
					} else {
						$g_login = 0;
						echo "<input id='guest_login' type='checkbox'>";
					} 
					echo "<span class='lever'></span>On</label>";
				echo "</div>";
			if($g_login == 1) {
				echo "<div id='more_guest'>";
			} else {
				echo "<div id='more_guest' class='hide'>";
			}
				echo "<br><br>";
				echo "<div class='row left-align'>";
					echo "<div class='input-field col s12 m6'>";
						echo "<input value='".get_option("GUEST_NAME_PREFIX")."' id='guest_prefix' type='text'>";
						echo "<label for='guest_prefix' class='white-text'>Guest Name Prefix (E.g. GUEST123)</label>";
					echo "</div>";
					echo "<div class='input-field col s12 m6'>";
						echo "<input value='".get_option("GUEST_PASSWORD_LENGHT")."' id='guest_password' type='text'>";
						echo "<label for='guest_password' class='white-text'>Guest User Password Lenght</label>";
					echo "</div>";
					echo "<div class='input-field col s12'>";
						if(get_option("ENABLE_GUEST_GROUPS") == 1) {
							echo "<input type='checkbox' id='guest_create_groups' checked='checked' />";
						} else {
							echo "<input type='checkbox' id='guest_create_groups' />";
						}
						echo "<label for='guest_create_groups' class='white-text'>Allow Guests to Create Groups</label>";
					echo "</div>";
					echo "<div class='input-field col s12'>";
						if(get_option("ENABLE_GUEST_PM") == 1) {
							echo "<input type='checkbox' id='guest_send_pm' checked='checked' />";
						} else {
							echo "<input type='checkbox' id='guest_send_pm' />";
						}
						echo "<label for='guest_send_pm' class='white-text'>Allow Guests to Send Personal Messages</label>";
					echo "</div>";
					echo "<div class='input-field col s12'>";
						if(get_option("ENABLE_GUEST_BE_INVITED") == 1) {
							echo "<input type='checkbox' id='guest_be_invited' checked='checked' />";
						} else {
							echo "<input type='checkbox' id='guest_be_invited' />";
						}
						echo "<label for='guest_be_invited' class='white-text'>Allow Guests to Be Invited to Groups</label>";
					echo "</div>";
					echo "<div class='input-field col s12'>";
						if(get_option("SHOW_GUESTS_ON_ONLINE_USER_LIST") == 1) {
							echo "<input type='checkbox' id='guest_online_list' checked='checked' />";
						} else {
							echo "<input type='checkbox' id='guest_online_list' />";
						}
						echo "<label for='guest_online_list' class='white-text'>Allow Guests to Be Shown on Online User List</label>";
					echo "</div>";
					echo "<div class='input-field col s12'>";
						if(get_option("ALLOW_GUEST_TO_ADD_FRIENDS") == 1) {
							echo "<input type='checkbox' id='guest_friends' checked='checked' />";
						} else {
							echo "<input type='checkbox' id='guest_friends' />";
						}
						echo "<label for='guest_friends' class='white-text'>Allow Guests to Add Friends</label>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		
	
	echo "<div class='row center-align'>";
	echo "<a id='save-user-settings' style='margin: 10px 20px' class='waves-effect waves-light btn'>SAVE</a>";
	echo "</div>";

	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function chat_settings()
{
	echo "<style>[type='checkbox']+label:before {border:2px solid #fff;}</style>";

	echo "<div class='row'>";
	echo "<div class='col s12 m12 l12'>";
	echo "<div class='card'>";
	echo "<div class='card-content center-align'>";
		echo "<span class='card-title'>Chat Settings</span>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "<div class='row'>";
	echo "<div class='col s12 m12 l12'>";
	echo "<div class='card'>";
	echo "<div class='card-content center-align'>";
		echo "<blockquote class='left-align flow-text'>While setting file sizes use \"K\" instead of \"KB\", \"M\" instead of \"MB\" and \"G\" instead of \"GB\".</blockquote>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";

	echo "<div class='row'>";
	echo "<div class='col s12'>";
	echo "<div class='card'>";
	echo "<div class='card-content center-align'>";
		echo "<div class='row'>";
		echo "<div class='col s12'>";
		echo "<div class='card blue-grey darken-2 white-text'>";
		echo "<div class='card-content center-align'>";
			echo "<span class='card-title'>General Chat Settings</span>";
			echo "<br><br>";
			echo "<div class='row left-align'>";
				echo "<div class='input-field col s12'>";
					if(get_option("SAVE_MESSAGES") == 1) {
						echo "<input type='checkbox' id='save_messages' checked='checked' />";
					} else {
						echo "<input type='checkbox' id='save_messages' />";
					}
					echo "<label for='save_messages' class='white-text'>Store Messages</label>";
				echo "</div>";
				echo "<div class='input-field col s12'>";
					if(get_option("ENABLE_EMOJI") == 1) {
						echo "<input type='checkbox' id='emoticons' checked='checked' />";
					} else {
						echo "<input type='checkbox' id='emoticons' />";
					}
					echo "<label for='emoticons' class='white-text'>Enable Emoticons</label>";
				echo "</div>";
				echo "<div class='input-field col s12'>";
					if(get_option("ENABLE_ONLINE_USERS") == 1) {
						echo "<input type='checkbox' id='online_users' checked='checked' />";
					} else {
						echo "<input type='checkbox' id='online_users' />";
					}
					echo "<label for='online_users' class='white-text'>Enable Online User List</label>";
				echo "</div>";
				echo "<div class='input-field col s12'>";
					if(get_option("ENABLE_FRIEND_SYSTEM") == 1) {
						echo "<input type='checkbox' id='friend_system' checked='checked' />";
					} else {
						echo "<input type='checkbox' id='friend_system' />";
					}
					echo "<label for='friend_system' class='white-text'>Enable Friend System</label>";
				echo "</div>";
			echo "</div>";
			echo "<br><br>";
			echo "<div class='row'>";
				echo "<div class='input-field col s12 m6'>";
					echo "<input value='".get_option("MAX_GROUP_CAPACITY")."' id='group_capacity' type='text'>";
					echo "<label for='group_capacity' class='white-text'>Group Capacity</label>";
				echo "</div>";
				echo "<div class='input-field col s12 m6'>";
					echo "<input value='".get_option("MAX_LENGHT_GROUP_NAME")."' id='max_group_name' type='text'>";
					echo "<label for='max_group_name' class='white-text'>Maximum Group Name Lenght</label>";
				echo "</div>";
			echo "</div>";
			echo "<div class='row'>";
				echo "<div class='input-field col s12'>";
					echo "<input value='".get_option("MIN_SEARCH")."' id='min_search' type='text'>";
					echo "<label for='min_search' class='white-text'>Minimum Required Character Number to Search Users</label>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";

		echo "<div class='col s12'>";
		echo "<div class='card blue-grey darken-2'>";
		echo "<div class='card-content center-align'>";
			echo "<span class='card-title white-text'>Sharing Settings</span>";
			echo "<br><br>";
			echo "<p class='white-text'>Archive Deleted Files</p>";
				
				echo "<div class='switch'>";
					echo "<label>Off";
					if(get_option("SHARE_ARCHIVE") == 1) {
						echo "<input id='share_archive' type='checkbox' checked='checked'>";
					} else {
						echo "<input id='share_archive' type='checkbox'>";
					} 
					echo "<span class='lever'></span>On</label>";
				echo "</div>";
				echo "<br>";
			echo "<div class='row'>";
				echo "<div class='col s12 m6'>";
				echo "<div class='card'>";
				echo "<div class='card-content grey lighten-4 center-align'>";
					echo "<span class='card-title'>Photo Sharing</span>";
					echo "<div class='row'>";
						echo "<div class='switch'>";
							echo "<label>Off";
							if(get_option("SHARE_PHOTO") == 1) {
								echo "<input id='share_photo' type='checkbox' checked='checked'>";
								$s_photo = 1;
							} else {
								$s_photo = 0;
								echo "<input id='share_photo' type='checkbox'>";
							} 
							echo "<span class='lever'></span>On</label>";
						echo "</div>";
					echo "</div>";
					if($s_photo == 1) {
						echo "<div id='more_share_photo'>";
					} else {
						echo "<div id='more_share_photo' class='hide'>";
					}
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Allowed Photo Extensions. Use \"|\" to Split.</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("PHOTO_EXTENSIONS")."' id='photo_extensions' type='text'>";
							echo "</div>";
						echo "</div>";
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Allowed Photo Mime Types. Use \"|\" to Split.</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("PHOTO_MIME_TYPES")."' id='photo_mimes' type='text'>";
							echo "</div>";
						echo "</div>";
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Maximum Allowed Number of Photos that can Be Uploaded at a Time</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("MAX_PHOTO")."' id='max_photo' type='text'>";
							echo "</div>";
						echo "</div>";
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Maximum Allowed Size of a Photo</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("MAX_PHOTO_SIZE")."' id='max_photo_size' type='text'>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";

				echo "<div class='col s12 m6'>";
				echo "<div class='card'>";
				echo "<div class='card-content grey lighten-4 center-align'>";
					echo "<span class='card-title'>Video Sharing</span>";
					echo "<div class='row'>";
						echo "<div class='switch'>";
							echo "<label>Off";
							if(get_option("SHARE_VIDEO") == 1) {
								echo "<input id='share_video' type='checkbox' checked='checked'>";
								$s_video = 1;
							} else {
								$s_video = 0;
								echo "<input id='share_video' type='checkbox'>";
							} 
							echo "<span class='lever'></span>On</label>";
						echo "</div>";
					echo "</div>";
					if($s_video == 1) {
						echo "<div id='more_share_video'>";
					} else {
						echo "<div id='more_share_video' class='hide'>";
					}
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Allowed Video Extensions. Use \"|\" to Split.</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("VIDEO_EXTENSIONS")."' id='video_extensions' type='text'>";
							echo "</div>";
						echo "</div>";
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Allowed Video Mime Types. Use \"|\" to Split.</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("VIDEO_MIME_TYPES")."' id='video_mimes' type='text'>";
							echo "</div>";
						echo "</div>";
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Maximum Allowed Number of Videos that can Be Uploaded at a Time</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("MAX_VIDEO")."' id='max_video' type='text'>";
							echo "</div>";
						echo "</div>";
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Maximum Allowed Size of a Video</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("MAX_VIDEO_SIZE")."' id='max_video_size' type='text'>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";

				echo "<div class='col s12 m6'>";
				echo "<div class='card'>";
				echo "<div class='card-content grey lighten-4 center-align'>";
					echo "<span class='card-title'>File Sharing</span>";
					echo "<div class='row'>";
						echo "<div class='switch'>";
							echo "<label>Off";
							if(get_option("SHARE_FILE") == 1) {
								echo "<input id='share_file' type='checkbox' checked='checked'>";
								$s_file = 1;
							} else {
								$s_file = 0;
								echo "<input id='share_file' type='checkbox'>";
							} 
							echo "<span class='lever'></span>On</label>";
						echo "</div>";
					echo "</div>";
					if($s_file == 1) {
						echo "<div id='more_share_file'>";
					} else {
						echo "<div id='more_share_file' class='hide'>";
					}
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Allowed File Extensions. Use \"|\" to Split.</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("FILE_EXTENSIONS")."' id='file_extensions' type='text'>";
							echo "</div>";
						echo "</div>";
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Maximum Allowed Number of Files that can Be Uploaded at a Time</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("MAX_FILE")."' id='max_file' type='text'>";
							echo "</div>";
						echo "</div>";
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Maximum Allowed Size of a File</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("MAX_FILE_SIZE")."' id='max_file_size' type='text'>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";

				echo "<div class='col s12 m6'>";
				echo "<div class='card'>";
				echo "<div class='card-content grey lighten-4 center-align'>";
					echo "<span class='card-title'>Audio Sharing</span>";
					echo "<div class='row'>";
						echo "<div class='switch'>";
							echo "<label>Off";
							if(get_option("SHARE_MUSIC") == 1) {
								echo "<input id='share_music' type='checkbox' checked='checked'>";
								$s_music = 1;
							} else {
								$s_music = 0;
								echo "<input id='share_music' type='checkbox'>";
							} 
							echo "<span class='lever'></span>On</label>";
						echo "</div>";
					echo "</div>";
					if($s_music == 1) {
						echo "<div id='more_share_music'>";
					} else {
						echo "<div id='more_share_music' class='hide'>";
					}
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Allowed Audio Extensions. Use \"|\" to Split.</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("MUSIC_EXTENSIONS")."' id='music_extensions' type='text'>";
							echo "</div>";
						echo "</div>";
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Allowed Audio Mime Types. Use \"|\" to Split.</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("MUSIC_MIME_TYPES")."' id='music_mimes' type='text'>";
							echo "</div>";
						echo "</div>";
						echo "<div class='divider'></div>";
						echo "<div class='row'>";
							echo "<br><p style='padding-left: 10px;font-weight:300;font-size:1.15rem' class='left-align'>Maximum Allowed Size of an Audio</p>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("MAX_MUSIC_SIZE")."' id='max_music_size' type='text'>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='col s12 m6'>";
				echo "<div class='card'>";
				echo "<div class='card-content grey lighten-4 center-align'>";
					echo "<span class='card-title'>Location Sharing</span>";
					echo "<div class='row'>";
						echo "<div class='switch'>";
							echo "<label>Off";
							if(get_option("SHARE_LOCATION") == 1) {
								echo "<input id='share_location' type='checkbox' checked='checked'>";
								$s_location = 1;
							} else {
								$s_location = 0;
								echo "<input id='share_location' type='checkbox'>";
							} 
							echo "<span class='lever'></span>On</label>";
						echo "</div>";
					echo "</div>";
					if($s_location == 1) {
						echo "<div id='more_share_location'>";
					} else {
						echo "<div id='more_share_location' class='hide'>";
					}
						echo "<div class='left-align' style='margin-top: 35px;'>Click <a href='https://developers.google.com/maps/documentation/android-api/signup' target='_blank'>here</a> to get a Google API key.</div>";
						echo "<br><div class='row'>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("GOOGLE_MAPS_API_KEY")."' id='google_maps_api' type='text'>";
								echo "<label for='google_maps_api' class='black-text'>Google Maps API Key</label>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='col s12 m6'>";
				echo "<div class='card'>";
				echo "<div class='card-content grey lighten-4 center-align'>";
					echo "<span class='card-title'>Voice Notes</span>";
					echo "<div class='row'>";
						echo "<div class='switch'>";
							echo "<label>Off";
							if(get_option("VOICE_NOTES") == 1) {
								echo "<input id='share_voice' type='checkbox' checked='checked'>";
								$s_voice = 1;
							} else {
								$s_voice = 0;
								echo "<input id='share_voice' type='checkbox'>";
							} 
							echo "<span class='lever'></span>On</label>";
						echo "</div>";
					echo "</div>";
					if($s_voice == 1) {
						echo "<div id='more_share_voice'>";
					} else {
						echo "<div id='more_share_voice' class='hide'>";
					}
						echo "<br><div class='row'>";
							echo "<div class='input-field col s12'>";
								echo "<input value='".get_option("MAX_VOICE_NOTE_SIZE")."' id='max_voice_note' type='text'>";
								echo "<label for='max_voice_note' class='black-text'>Maximum Voice Note Size</label>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";

			echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";

		echo "<div class='col s12'>";
		echo "<div class='card blue-grey darken-2'>";
		echo "<div class='card-content center-align white-text'>";
			echo "<span class='card-title white-text'>Profile / Group Photo Settings</span>";
			echo "<br><br>";
			echo "<div class='row'><br>";
				echo "<div class='input-field col s12'>";
					echo "<input value='".get_option("IMG_EXTENSIONS")."' id='img_extensions' type='text'>";
					echo "<label for='img_extensions' class='white-text'>Allowed Profile / Group Photo Extensions. Use \"|\" to Split.</label>";
				echo "</div>";
			echo "</div>";
			echo "<div class='row'><br>";
				echo "<div class='input-field col s12'>";
					echo "<input value='".get_option("MAX_IMG_SIZE")."' id='max_img_size' type='text'>";
					echo "<label for='max_img_size' class='white-text'>Maximum Allowed Size of Profile / Group Photo</label>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
	
	echo "<div class='row center-align'>";
	echo "<a id='save-chat-settings' style='margin: 10px 20px' class='waves-effect waves-light btn'>SAVE</a>";
	echo "</div>";

	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function ban_settings()
{
	echo "<style>[type='checkbox']+label:before {border:2px solid #fff;}</style>";

	echo "<div class='row'>";
	echo "<div class='col s12 m12 l12'>";
	echo "<div class='card'>";
	echo "<div class='card-content center-align'>";
		echo "<span class='card-title'>Ban Settings</span>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";

	echo "<div class='row'>";
	echo "<div class='col s12'>";
	echo "<div class='card'>";
	echo "<div class='card-content center-align'>";
		echo "<div class='row'>";
		echo "<div class='col s12'>";
		echo "<div class='card blue-grey darken-2 white-text'>";
		echo "<div class='card-content center-align'>";
			echo "<span class='card-title'>Word Filter</span>";
			echo "<div class='row'>";
				echo "<div class='input-field col s12 m5'>";
					echo "<input id='ban_words_1' type='text'>";
					echo "<label for='ban_words_1' class='white-text'>Word</label>";
				echo "</div>";
				echo "<div class='input-field col s12 m5'>";
					echo "<input id='ban_words_2' type='text'>";
					echo "<label for='ban_words_2' class='white-text'>Filtered Word</label>";
				echo "</div>";
				echo "<div class='col s12 m2'><a href='#' id='add-word-filter' class='waves-effect waves-light btn'><i class='material-icons small'>add</i></a></div>";
			echo "</div>";
					if (!isset($_GET['word-page'])) {
						$word_page = 1;
					} else {
						$word_page = db_escape(htmlentities($_GET['word-page']));
					}
					$word_begin = ($word_page * 10) - 10;

					$word_query = db_query("SELECT `val1`, `val2`, `ID` FROM `chat_banned_items` WHERE `setting` = 'banned_words' ORDER BY `ID` DESC LIMIT $word_begin, 10");
					$total_word_query = db_query("SELECT `ID` FROM `chat_banned_items` WHERE `setting` = 'banned_words'");

					$total_word_result = mysqli_num_rows($total_word_query);
					$total_word_page = ceil($total_word_result / 10);

						echo "<div id='banned_word_list' class='row ";
						if ($total_word_result == 0) { echo "hide"; }
						echo "'>";
						echo "<div class='col s12 m12'>";
						echo "<div class='card'>";
						echo "<div class='card-content' style='overflow: auto'>";
						echo "<span class='card-title black-text'>Filtered Words</span>";
						echo "<table class='bordered'>";
						echo "<thead>";
						echo "<tr>";
						echo "<th data-field='id'>Word</th>";
						echo "<th data-field='id'>Filtered Word</th>";
						echo "</tr>";
						echo "</thead>";
						echo "<tbody id='banned_words_tbody'>";
						while ($row = mysqli_fetch_array($word_query)) {
							echo "<tr>";
							echo "<td>".$row[0]."</td>";
							echo "<td>".$row[1]."</td>";
							echo "<td><p><i attr-id='".$row[2]."' id='delete-word' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></p></td>";
							echo "</tr>";
						}
						
						echo "</tbody>";
						echo "</table>";
						echo "<ul class='pagination center-align'>";
						
						$next_page_word = $word_page + 1;
						$prev_page_word = $word_page - 1;
						if ($total_word_page < 9) {
							if ($word_page == 1) {
								echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
								echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
							} else {
								echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=1'><i class='material-icons last'>first_page</i></a></li>";
								echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$prev_page_word."'><i class='material-icons'>chevron_left</i></a></li>";
							}
							for ($i = 1; $i <= $total_word_page; $i++) {
								if ($word_page == $i) {
									echo "<li class='active'><a href='./index.php?action=ban-settings&word-page=".$i."'>".$i.'</a></li>';
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$i."'>".$i.'</a></li>';
								}
							}
							if ($word_page == $total_word_page) {
								echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
								echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
							} else {
								echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$next_page_word."'><i class='material-icons'>chevron_right</i></a></li>";
								echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$total_word_page."'><i class='material-icons last'>last_page</i></a></li>";
							}
						} else {
							if ($word_page < 6) {
								if ($word_page == 1) {
									echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
									echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=1'><i class='material-icons last'>first_page</i></a></li>";
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$prev_page_word."'><i class='material-icons'>chevron_left</i></a></li>";
								}
								for ($i = 1; $i <= 9; $i++) {
									if ($word_page == $i) {
										echo "<a href='./index.php?action=ban-settings&word-page=".$i."'><li class='active'>".$i.'</li></a>';
									} else {
										echo "<a href='./index.php?action=ban-settings&word-page=".$i."'><li class='waves-effect'>".$i.'</li></a>';
									}
								}
								if ($word_page == $total_word_page) {
									echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
									echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$next_page_word."'><i class='material-icons'>chevron_right</i></a></li>";
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$total_word_page."'><i class='material-icons last'>last_page</i></a></li>";
								}
							} elseif ($word_page > $total_word_page - 4) {
								if ($word_page == 1) {
									echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
									echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=1'><i class='material-icons last'>first_page</i></a></li>";
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$prev_page_word."'><i class='material-icons'>chevron_left</i></a></li>";
								}
								for ($i = $total_word_page - 8; $i <= $total_word_page; $i++) {
									if ($word_page == $i) {
										echo "<li class='active'><a href='./index.php?action=ban-settings&word-page=".$i."'>".$i.'</a></li>';
									} else {
										echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$i."'>".$i."</a></li>";
									}
								}
								if ($word_page == $total_word_page) {
									echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
									echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$next_page_word."'><i class='material-icons'>chevron_right</i></a></li>";
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$total_word_page."'><i class='material-icons last'>last_page</i></a></li>";
								}
							} else {
								$num = $word_page - 4;
								if ($word_page == 1) {
									echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
									echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=1'><i class='material-icons last'>first_page</i></a></li>";
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$prev_page_word."'><i class='material-icons'>chevron_left</i></a></li>";
								}
								for ($num; $num <= $word_page + 4; $num++) {
									if ($word_page == $num) {
										echo "<li class='active'><a href='./index.php?action=ban-settings&word-page=".$num."'>".$num."</a></li>";
									} else {
										echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$num."'>".$num."</a></li>";
									}
								}
								if ($word_page == $total_word_page) {
									echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
									echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$next_page_word."'><i class='material-icons'>chevron_right</i></a></li>";
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&word-page=".$total_word_page."'><i class='material-icons last'>last_page</i></a></li>";
								}
							}
						}
						echo "</ul>";	
						echo "</div>";
						echo "</div>";
						echo "</div>";
						echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";

		echo "<div class='col s12'>";
		echo "<div class='card blue-grey darken-2 white-text'>";
		echo "<div class='card-content center-align'>";
			echo "<span class='card-title white-text'>Ban IP Addresses</span>";
						echo "<blockquote class='left-align flow-text'>E.g. \"127.0.0.*\" or \"127.0.0.1\"</blockquote>";
						echo "<div class='row'>";
							echo "<div class='input-field col s12 m10'>";
								echo "<input id='ban_ip_input' type='text'>";
								echo "<label for='ban_ip_input' class='white-text'>IP Address</label>";
							echo "</div>";
							echo "<div class='col s12 m2'><a href='#' id='add-ip-address' class='waves-effect waves-light btn'><i class='material-icons small'>add</i></a></div>";
						echo "</div>";
					if (!isset($_GET['ip-page'])) {
						$ip_page = 1;
					} else {
						$ip_page = db_escape(htmlentities($_GET['ip-page']));
					}
					$ip_begin = ($ip_page * 10) - 10;

					$ip_query = db_query("SELECT `val1`, `ID` FROM `chat_banned_items` WHERE `setting` = 'banned_ip' ORDER BY `ID` DESC LIMIT $ip_begin, 10");
					$total_ip_query = db_query("SELECT `ID` FROM `chat_banned_items` WHERE `setting` = 'banned_ip'");

					$total_ip_result = mysqli_num_rows($total_ip_query);
					$total_ip_page = ceil($total_ip_result / 10);

						echo "<div id='banned_ip_list' class='row ";
						if ($total_ip_result == 0) { echo "hide"; }
						echo "'>";
						echo "<div class='col s12 m12'>";
						echo "<div class='card'>";
						echo "<div class='card-content' style='overflow: auto'>";
						echo "<span class='card-title black-text'>Banned IP Addresses</span>";
						echo "<table class='bordered'>";
						echo "<thead>";
						echo "<tr>";
						echo "<th data-field='id'>IP Address</th>";
						echo "</tr>";
						echo "</thead>";
						echo "<tbody id='banned_ip_list_tbody'>";
						while ($row = mysqli_fetch_array($ip_query)) {
							echo "<tr>";
							echo "<td>".$row[0]."</td>";
							echo "<td><p><i attr-id='".$row[1]."' id='delete-ip' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></p></td>";
							echo "</tr>";
						}
						
						echo "</tbody>";
						echo "</table>";
						echo "<ul class='pagination center-align'>";
						
						$next_page = $ip_page + 1;
						$prev_page = $ip_page - 1;
						if ($total_ip_page < 9) {
							if ($ip_page == 1) {
								echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
								echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
							} else {
								echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=1'><i class='material-icons last'>first_page</i></a></li>";
								echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
							}
							for ($i = 1; $i <= $total_ip_page; $i++) {
								if ($ip_page == $i) {
									echo "<li class='active'><a href='./index.php?action=ban-settings&ip-page=".$i."'>".$i.'</a></li>';
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$i."'>".$i.'</a></li>';
								}
							}
							if ($ip_page == $total_ip_page) {
								echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
								echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
							} else {
								echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
								echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$total_ip_page."'><i class='material-icons last'>last_page</i></a></li>";
							}
						} else {
							if ($ip_page < 6) {
								if ($ip_page == 1) {
									echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
									echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=1'><i class='material-icons last'>first_page</i></a></li>";
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
								}
								for ($i = 1; $i <= 9; $i++) {
									if ($ip_page == $i) {
										echo "<a href='./index.php?action=ban-settings&ip-page=".$i."'><li class='active'>".$i.'</li></a>';
									} else {
										echo "<a href='./index.php?action=ban-settings&ip-page=".$i."'><li class='waves-effect'>".$i.'</li></a>';
									}
								}
								if ($ip_page == $total_ip_page) {
									echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
									echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$total_ip_page."'><i class='material-icons last'>last_page</i></a></li>";
								}
							} elseif ($ip_page > $total_ip_page - 4) {
								if ($ip_page == 1) {
									echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
									echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=1'><i class='material-icons last'>first_page</i></a></li>";
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
								}
								for ($i = $total_ip_page - 8; $i <= $total_ip_page; $i++) {
									if ($ip_page == $i) {
										echo "<li class='active'><a href='./index.php?action=ban-settings&ip-page=".$i."'>".$i.'</a></li>';
									} else {
										echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$i."'>".$i."</a></li>";
									}
								}
								if ($ip_page == $total_ip_page) {
									echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
									echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$total_ip_page."'><i class='material-icons last'>last_page</i></a></li>";
								}
							} else {
								$num = $ip_page - 4;
								if ($ip_page == 1) {
									echo "<li class='disabled'><a href='#'><i class='material-icons last'>first_page</i></a></li>";
									echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_left</i></a></li>";
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=1'><i class='material-icons last'>first_page</i></a></li>";
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$prev_page."'><i class='material-icons'>chevron_left</i></a></li>";
								}
								for ($num; $num <= $ip_page + 4; $num++) {
									if ($ip_page == $num) {
										echo "<li class='active'><a href='./index.php?action=ban-settings&ip-page=".$num."'>".$num."</a></li>";
									} else {
										echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$num."'>".$num."</a></li>";
									}
								}
								if ($ip_page == $total_ip_page) {
									echo "<li class='disabled'><a href='#'><i class='material-icons'>chevron_right</i></a></li>";
									echo "<li class='disabled'><a href='#'><i class='material-icons last'>last_page</i></a></li>";
								} else {
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$next_page."'><i class='material-icons'>chevron_right</i></a></li>";
									echo "<li class='waves-effect'><a href='./index.php?action=ban-settings&ip-page=".$total_ip_page."'><i class='material-icons last'>last_page</i></a></li>";
								}
							}
						}
						echo "</ul>";	
						echo "</div>";
						echo "</div>";
						echo "</div>";
						echo "</div>";

		echo "</div>";
		echo "</div>";
		echo "</div>";

	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}

function new_user() 
{
		echo "<style>[type='radio'].with-gap:checked+label:after,[type='radio']:checked+label:after {background-color:#fff!important;border:2px solid #fff!important}[type='radio'].with-gap+label:before{border:2px solid #fff!important;}</style>";
			
		echo "<div class='row'>";
		echo "<div class='col s12 m12 l12'>";
		echo "<div class='card'>";
		echo "<div class='card-content center-align'>";
			echo "<span class='card-title'>Create a New User</span>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
	
		echo "<div class='row'>";
		echo "<div class='col s12 m12 l12'>";
		echo "<div class='card'>";
		echo "<div class='card-content'>";

			echo "<div class='row'>";
			echo "<div class='col s12 m6 l4'>";
			echo "<div class='row'>";
			echo "<div class='col s12'>";
			echo "<div class='card blue-grey darken-2 center-align white-text'>";
			echo "<div class='card-content'>";
				echo "<div class='card-title'>Profile Photo</div>";
				echo "<ul id='change-pp-create-dropdown' class='dropdown-content'>";
					echo "<li><a href='#' id='upload-photo-create'>Upload Photo</a></li>";
					echo "<input type='file' accept='image/*' id='upload-pp-create' style='display:none;' />";
					echo "<div id='pp-act-div-create' class='hide'>";
					echo "<li class='divider'></li>";
					echo "<li><a href='#' id='remove-photo-create'>Remove Photo</a></li>";
					echo "</div>";
				echo "</ul>";
				echo "<a class='dropdown-button' href='#' id='cchange-pp-create-dropdown'><div class='change-pp-create'>Change Profile Photo</div></a>";
					echo "<i id='pp-main-create' class='valign material-icons circle big-pp grey lighten-2'>person</i>";
					echo "<img id='pp-img-main-create' class='valign circle pp hide' width='175' height='175' src=''>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
						
						echo "<div class='col s12'>";
						echo "<div class='card blue-grey darken-2 center-align white-text'>";
						echo "<div class='card-content'>";
						echo "<div class='card-title'>User Type</div>";
						echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
						echo "<select id='select_user_type'>";
							echo "<option value='Admin'>Admin</option>";
							echo "<option value='Member' selected>Member</option>";
							echo "<option value='Guest'>Guest</option>";
						echo "</select>";
						echo "<label class='white-text'>User Type</label>";
						echo "</div>";
						echo "</div>";
						if(get_option("USER_ACTIVATION") == 1) {
							echo "<div class='row left-align'>";
								echo "<p>";
								echo "<input class='with-gap' name='email_activation' type='radio' id='on-ac-new' checked/>";
								echo "<label class='white-text' for='on-ac-new'>Activated</label>";
								echo "</p><p>";
								echo "<input class='with-gap' name='email_activation' type='radio' id='off-ac-new' />";
								echo "<label class='white-text' for='off-ac-new'>Not Activated (Requires Email Activation)</label>";
								echo "</p>";
							echo "</div>";
						} else {
							echo "<input class='hide' name='email_activation' type='radio' id='on-ac-new' checked/>";
						}
						echo "</div>";
						echo "</div>";
						echo "</div>";
			echo "</div>";
			echo "</div>";

			echo "<div class='col s12 m6 l8'>";
			echo "<div class='card blue-grey darken-2 center-align white-text'>";
			echo "<div class='card-content'>";
				echo "<span class='card-title'>Profile</span>";
				echo "<div class='row'>";
					echo "<form class='col s12'>";
					echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
						echo "<input id='username' type='text' class='white-text'>";
						echo "<label for='username' class='white-text'>Username</label>";
						echo "</div>";
						echo "<div class='input-field col s12'>";
						echo "<input id='user-email' type='text' class='white-text'>";
						echo "<label for='user-email' class='white-text'>Email</label>";
						echo "</div>";
						echo "<div class='input-field col s12'>";
						echo "<input id='user-status' type='text' class='white-text'>";
						echo "<label for='user-status' class='white-text'>Status</label>";
						echo "</div>";
						echo "</div>";
						echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
						echo "<input id='pass' type='password' class='white-text'>";
						echo "<label for='pass' class='white-text'>Password</label>";
						echo "</div>";
						echo "<div class='input-field col s12'>";
						echo "<input id='pass_repeat' type='password' class='white-text'>";
						echo "<label for='pass_repeat' class='white-text'>Repeat Password</label>";
						echo "</div>";
						echo "</div>";
					
					echo "</div>";
				echo "</div>";
			echo "</div>";
			echo "</div>";						
			echo "</div>";
			
			echo "<div class='row center-align'>";
				echo "<button class='waves-effect waves-light btn' type='submit' id='create-user' style='margin: 10px 20px' name='create-user'>CREATE</button>";
				echo "</form>";
			echo "</div>";
			echo "</div>";	

	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}
