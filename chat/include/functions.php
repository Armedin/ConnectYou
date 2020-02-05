<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/PHPMailer/PHPMailerAutoload.php';

if(session_id() == '' || !isset($_SESSION)) {
    session_start();
}
	
date_default_timezone_set(timezone());    // Set the timezone

if (version_compare(PHP_VERSION, '5.5.0', '<')) {
	die("<div style='position:absolute;top:0;left:0;background-color:white;width:100%;height:100%;z-index:999;'><b>Error:</b> Your PHP Version cannot be lower than 5.5.0!</div>");
}

/////////////////////////////////////////////
///////////// CONFIG FUNCTIONS //////////////
/////////////////////////////////////////////

function get_option($val) {
	$array = array();
	if(!is_array($val)) {
		$val = db_escape($val);
		if($val == "banned_words") {
			$query = db_query("SELECT `val1`, `val2` FROM `chat_banned_items` WHERE `setting` = '$val'");
			$banned_i = 0;
			$array_vals = array();
			while($row = mysqli_fetch_array($query)) {
				array_push($array, preg_quote($row[0]));
				array_push($array_vals, preg_quote($row[1]));
			}
			$all_array = array_combine($array, $array_vals);
			return $all_array;
		} elseif($val == "banned_ip") {
			$query = db_query("SELECT `val1`, `val2` FROM `chat_banned_items` WHERE `setting` = '$val'");
			while($row = mysqli_fetch_array($query)) {
				array_push($array, stripslashes($row[0]));
			}
			return $array;
		} else {
			$query = db_query("SELECT `value` FROM `chat_settings` WHERE `setting` = '$val'");
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

function edit_option($setting, $val) {
	$array = array();
	if(!is_array($val) && !is_array($setting)) {
		$val = db_escape($val);
		$setting = db_escape($setting);
		$query = db_query("SELECT `value` FROM `chat_settings` WHERE `setting` = '$setting'");
		if(mysqli_num_rows($query) > 0) {
			db_query("UPDATE `chat_settings` SET `value` = '$val' WHERE `setting` = '$setting'");
		} else {
			db_query("INSERT INTO `chat_settings`(`setting`, `value`) VALUES ('$setting', '$val')");
		}
	} else {
		if(count($val) == count($setting)) {
			foreach($val as $key => $value) {
				$value = db_escape($value);
				$set = db_escape($setting[$key]);
				$query = db_query("SELECT `value` FROM `chat_settings` WHERE `setting` = '$set'");
				if(mysqli_num_rows($query) > 0) {
					db_query("UPDATE `chat_settings` SET `value` = '$value' WHERE `setting` = '$set'");
				} else {
					db_query("INSERT INTO `chat_settings`(`setting`, `value`) VALUES ('$set', '$value')");
				}
			}
		} else {
			return false;
		}
	}
}

// Maximum Group Capacity
function max_group_capacity()
{
    if (get_option("MAX_GROUP_CAPACITY") > 0) {
        return get_option("MAX_GROUP_CAPACITY");
    } else {
        return 256;
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
            return 1;
            break;
    }
}

// Timezone
function timezone()
{
	if (in_array(get_option("TIMEZONE"), DateTimeZone::listIdentifiers())) {
		return get_option("TIMEZONE");
	} else {
		return "Europe/Istanbul";
	}
}

// Full path of Group & Profile Photos
function picture_net_destination()
{
    return getcwd().'/include/img/';
}

// Picture Destination
function picture_destination()
{
    return "./include/img/";
}

// Maximum Profile & Group Photo
function max_img_size()
{
    $str = get_option("MAX_IMG_SIZE");
    $byte = substr($str, -1);
    $size = substr_replace($str, '', -1);
    $php_size1 = convertStringToSize(ini_get('post_max_size'));
    $php_size2 = convertStringToSize(ini_get('upload_max_filesize'));
    if (($byte == 'K' || $byte == 'M' || $byte == 'G') && $size > 0) {
        switch ($byte) {
            case 'K':
                $var = $size * 1024;
                break;
            case 'M':
                $var = $size * 1024 * 1024;
                break;
            case 'G':
                $var = $size * 1024 * 1024 * 1024;
                break;
        }

        if ($var > $php_size1 || $var > $php_size2) {
            die("<div style='position:absolute;top:0;left:0;background-color:white;width:100%;height:100%;z-index:999;'><b>Error:</b> Maximum profile / group photo size is larger than \"post_max_size\" or \"upload_max_filesize\" values in \"php.ini\" file.</div>");
        } else {
            return $var;
        }
    } else {
        return 10485760;
    }
}

// Allowed image mime types
function img_types()
{
    $string = get_option("IMG_EXTENSIONS");
    $extensions = explode('|', $string);
    $num = count($extensions);
    $array = array();

    for ($i = 0; $i < $num; $i++) {
        $var = 'image/'.$extensions[$i];
        array_push($array, $var);
    }

    return $array;
}

// Allowed image extensions
function img_extensions()
{
    $string = get_option("IMG_EXTENSIONS");
    $extensions = explode('|', $string);
    $num = count($extensions);
    $array = array();

    for ($i = 0; $i < $num; $i++) {
        array_push($array, $extensions[$i]);
    }

    return $array;
}

// Emoji
function enable_emoji()
{
    /*switch (get_option("ENABLE_EMOJI")) {
        case 0:
            return 0;
            break;
        case 1:
            return 1;
            break;
        default:
            return 1;
            break;
    }*/

    return 0;
}

// Maximum lenght of group name
function max_group_name()
{
    if (get_option("MAX_LENGHT_GROUP_NAME") > 0) {
        return get_option("MAX_LENGHT_GROUP_NAME");
    } else {
        return 50;
    }
}

// Status of guest login system
function guest_login()
{
    // if (get_option("GUEST_LOGIN") == 1) {
    //     return 1;
    // } else {
    //     return 0;
    // }
    return 0;
}

// Prefix of guest usernames
function guest_name_prefix()
{
    return get_option("GUEST_NAME_PREFIX");
}

// Password lenght of guests
function guest_pass_lenght()
{
    if (get_option("GUEST_PASSWORD_LENGHT") > 0) {
        return get_option("GUEST_PASSWORD_LENGHT");
    } else {
        return 8;
    }
}

// Checks if guests can create groups
function guest_groups()
{
    if (get_option("ENABLE_GUEST_GROUPS") == 1) {
        return 1;
    } else {
        return 0;
    }
}

// Checks if guests can send personal messages
function guest_pm()
{
    if (get_option("ENABLE_GUEST_PM") == 1) {
        return 1;
    } else {
        return 0;
    }
}

// Checks if users can create groups
function user_groups()
{
    if (get_option("ENABLE_USER_GROUPS") == 1) {
        return 1;
    } else {
        return 0;
    }
}

// Checks if users can send personal messages
function user_pm()
{
    if (get_option("ENABLE_USER_PM") == 1) {
        return 1;
    } else {
        return 0;
    }
}

// Checks if guests can be invited to groups
function invite_guests()
{
    if (get_option("ENABLE_GUEST_BE_INVITED") == 1) {
        return 1;
    } else {
        return 0;
    }
}

// Minumum word lenght to search
function min_search_lenght()
{
    if (get_option("MIN_SEARCH") > 0) {
        return get_option("MIN_SEARCH");
    } else {
        return min_username_lenght();
    }
}

// Minimum username lenght
function min_username_lenght()
{
    if (get_option("MIN_USERNAME") > 0) {
        return get_option("MIN_USERNAME");
    } else {
        return 3;
    }
}

// Maximum username lenght
function max_username_lenght()
{
    if (get_option("MAX_USERNAME") >= min_username_lenght()) {
        return get_option("MAX_USERNAME");
    } else {
        return min_username_lenght() + 10;
    }
}

// Minimum password lenght
function min_pass_lenght()
{
    if (get_option("MIN_PASSWORD") > 0) {
        return get_option("MIN_PASSWORD");
    } else {
        return 4;
    }
}

// Maximum password lenght
function max_pass_lenght()
{
    if (get_option("MAX_PASSWORD") > min_pass_lenght()) {
        return get_option("MAX_PASSWORD");
    } else {
        return min_pass_lenght() + 10;
    }
}

// Maximum E-Mail lenght
function max_email_lenght()
{
    if (get_option("MAX_EMAIL") > 5) {
        return get_option("MAX_EMAIL");
    } else {
        return 64;
    }
}

function enable_user_status()
{
    if (get_option("ENABLE_USER_STATUS") != 1) {
        return 0;
    } else {
        return 1;
    }
}

function share_photo()
{
    if (get_option("SHARE_PHOTO") != 1) {
        return 0;
    } else {
        return 1;
    }
}

function share_video()
{
    if (get_option("SHARE_VIDEO") != 1) {
        return 0;
    } else {
        return 1;
    }
}

function share_file()
{
    if (get_option("SHARE_FILE") != 1) {
        return 0;
    } else {
        return 1;
    }
}

function share_music()
{
    if (get_option("SHARE_MUSIC") != 1) {
        return 0;
    } else {
        return 1;
    }
}

function share_location()
{
    if (get_option("SHARE_LOCATION") != 1) {
        return 0;
    } else {
		if(protocol() == 1 || getBrowser()["name"] != "Chrome" || (getBrowser()["name"] == "Chrome" && getBrowser()["version"] < 50 && getBrowser()["version"] >= 5) || isLocalhost()) {
			return 1;
		} else {
			return 0;
		}
    }
}

function voice_notes()
{
    if (get_option("VOICE_NOTES") != 1) {
        return 0;
    } else {
		if(protocol() == 1 || getBrowser()["name"] != "Chrome" || (getBrowser()["name"] == "Chrome" && getBrowser()["version"]<50) || isLocalhost()) {
			return 1;
		} else {
			return 0;
		}
    }
}

function google_api_key()
{
    return get_option("GOOGLE_MAPS_API_KEY");
}

function archive_files()
{
    if (get_option("SHARE_ARCHIVE") != 1) {
        return 0;
    } else {
        return 1;
    }
}

function max_photo()
{
    $max_num = ini_get('max_file_uploads');
    if (get_option("MAX_PHOTO") > 0) {
        if (get_option("MAX_PHOTO") > $max_num) {
            die("<div style='position:absolute;top:0;left:0;background-color:white;width:100%;height:100%;z-index:999;'><b>Error:</b> Maximum number of uploaded photos is bigger than \"max_file_uploads\" value in \"php.ini\" file.</div>");
        }
        return get_option("MAX_PHOTO");
    } else {
        return 10;
    }
}

function max_video()
{
    $max_num = ini_get('max_file_uploads');
    if (get_option("MAX_VIDEO") > 0) {
        if (get_option("MAX_VIDEO") > $max_num) {
            die("<div style='position:absolute;top:0;left:0;background-color:white;width:100%;height:100%;z-index:999;'><b>Error:</b> Maximum number of uploaded videos is bigger than \"max_file_uploads\" value in \"php.ini\" file.</div>");
        }

        return get_option("MAX_VIDEO");
    } else {
        return 10;
    }
}

function max_file()
{
    $max_num = ini_get('max_file_uploads');
    if (get_option("MAX_FILE") > 0) {
        if (get_option("MAX_FILE") > $max_num) {
            die("<div style='position:absolute;top:0;left:0;background-color:white;width:100%;height:100%;z-index:999;'><b>Error:</b> Maximum number of uploaded files is bigger than \"max_file_uploads\" value in \"php.ini\" file.</div>");
        }

        return get_option("MAX_FILE");
    } else {
        return 1;
    }
}

function photo_extensions()
{
    $string = get_option("PHOTO_EXTENSIONS");
    $extensions = explode('|', $string);
    $num = count($extensions);
    $array = array();

    for ($i = 0; $i < $num; $i++) {
        array_push($array, $extensions[$i]);
    }

    return $array;
}

function photo_types()
{
    $string = get_option("PHOTO_MIME_TYPES");
    $extensions = explode('|', $string);
    $num = count($extensions);
    $array = array();

    for ($i = 0; $i < $num; $i++) {
        array_push($array, $extensions[$i]);
    }

    return $array;
}

function video_extensions()
{
    $string = get_option("VIDEO_EXTENSIONS");
    $extensions = explode('|', $string);
    $num = count($extensions);
    $array = array();

    for ($i = 0; $i < $num; $i++) {
        array_push($array, $extensions[$i]);
    }

    return $array;
}

function video_types()
{
    $string = get_option("VIDEO_MIME_TYPES");
    $extensions = explode('|', $string);
    $num = count($extensions);
    $array = array();

    for ($i = 0; $i < $num; $i++) {
        array_push($array, $extensions[$i]);
    }

    return $array;
}

function music_extensions()
{
    $string = get_option("MUSIC_EXTENSIONS");
    $extensions = explode('|', $string);
    $num = count($extensions);
    $array = array();

    for ($i = 0; $i < $num; $i++) {
        array_push($array, $extensions[$i]);
    }

    return $array;
}

function music_types()
{
    $string = get_option("MUSIC_MIME_TYPES");
    $extensions = explode('|', $string);
    $num = count($extensions);
    $array = array();

    for ($i = 0; $i < $num; $i++) {
        array_push($array, $extensions[$i]);
    }

    return $array;
}

function file_extensions()
{
    $string = get_option("FILE_EXTENSIONS");
    $extensions = explode('|', $string);
    $num = count($extensions);
    $array = array();

    for ($i = 0; $i < $num; $i++) {
        array_push($array, $extensions[$i]);
    }

    return $array;
}

function max_user_photo_size()
{
    $str = get_option("MAX_USER_PHOTO_SIZE");
    $byte = substr($str, -1);
    $size = substr_replace($str, '', -1);
    $php_size1 = convertStringToSize(ini_get('post_max_size'));
    $php_size2 = convertStringToSize(ini_get('upload_max_filesize'));
    if (($byte == 'K' || $byte == 'M' || $byte == 'G') && $size > 0) {
        switch ($byte) {
            case 'K':
                $var = $size * 1024;
                break;
            case 'M':
                $var = $size * 1024 * 1024;
                break;
            case 'G':
                $var = $size * 1024 * 1024 * 1024;
                break;
        }

        if ($var > $php_size1 || $var > $php_size2) {
            die("<div style='position:absolute;top:0;left:0;background-color:white;width:100%;height:100%;z-index:999;'><b>Error:</b> Maximum user photo size is larger than \"post_max_size\" or \"upload_max_filesize\" values in \"php.ini\" file.</div>");
        } else {
            return $var;
        }
    } else {
        return 10485760;
    }
}

function max_photo_size()
{
    $str = get_option("MAX_PHOTO_SIZE");
    $byte = substr($str, -1);
    $size = substr_replace($str, '', -1);
    $php_size1 = convertStringToSize(ini_get('post_max_size'));
    $php_size2 = convertStringToSize(ini_get('upload_max_filesize'));
    if (($byte == 'K' || $byte == 'M' || $byte == 'G') && $size > 0) {
        switch ($byte) {
            case 'K':
                $var = $size * 1024;
                break;
            case 'M':
                $var = $size * 1024 * 1024;
                break;
            case 'G':
                $var = $size * 1024 * 1024 * 1024;
                break;
        }

        if ($var > $php_size1 || $var > $php_size2) {
            die("<div style='position:absolute;top:0;left:0;background-color:white;width:100%;height:100%;z-index:999;'><b>Error:</b> Maximum photo size is larger than \"post_max_size\" or \"upload_max_filesize\" values in \"php.ini\" file.</div>");
        } else {
            return $var;
        }
    } else {
        return 10485760;
    }
}

function max_video_size()
{
    $str = get_option("MAX_VIDEO_SIZE");
    $byte = substr($str, -1);
    $size = substr_replace($str, '', -1);
    $php_size1 = convertStringToSize(ini_get('post_max_size'));
    $php_size2 = convertStringToSize(ini_get('upload_max_filesize'));
    if (($byte == 'K' || $byte == 'M' || $byte == 'G') && $size > 0) {
        switch ($byte) {
            case 'K':
                $var = $size * 1024;
                break;
            case 'M':
                $var = $size * 1024 * 1024;
                break;
            case 'G':
                $var = $size * 1024 * 1024 * 1024;
                break;
        }

        if ($var > $php_size1 || $var > $php_size2) {
            die("<div style='position:absolute;top:0;left:0;background-color:white;width:100%;height:100%;z-index:999;'><b>Error:</b> Maximum video size is larger than \"post_max_size\" or \"upload_max_filesize\" values in \"php.ini\" file.</div>");
        } else {
            return $var;
        }
    } else {
        return 10485760;
    }
}

function max_file_size()
{
    $str = get_option("MAX_FILE_SIZE");
    $byte = substr($str, -1);
    $size = substr_replace($str, '', -1);
    $php_size1 = convertStringToSize(ini_get('post_max_size'));
    $php_size2 = convertStringToSize(ini_get('upload_max_filesize'));
    if (($byte == 'K' || $byte == 'M' || $byte == 'G') && $size > 0) {
        switch ($byte) {
            case 'K':
                $var = $size * 1024;
                break;
            case 'M':
                $var = $size * 1024 * 1024;
                break;
            case 'G':
                $var = $size * 1024 * 1024 * 1024;
                break;
        }

        if ($var > $php_size1 || $var > $php_size2) {
            die("<div style='position:absolute;top:0;left:0;background-color:white;width:100%;height:100%;z-index:999;'><b>Error:</b> Maximum file size is larger than \"post_max_size\" or \"upload_max_filesize\" values in \"php.ini\" file.</div>");
        } else {
            return $var;
        }
    } else {
        return 10485760;
    }
}

function max_music_size()
{
    $str = get_option("MAX_MUSIC_SIZE");
    $byte = substr($str, -1);
    $size = substr_replace($str, '', -1);
    $php_size1 = convertStringToSize(ini_get('post_max_size'));
    $php_size2 = convertStringToSize(ini_get('upload_max_filesize'));
    if (($byte == 'K' || $byte == 'M' || $byte == 'G') && $size > 0) {
        switch ($byte) {
            case 'K':
                $var = $size * 1024;
                break;
            case 'M':
                $var = $size * 1024 * 1024;
                break;
            case 'G':
                $var = $size * 1024 * 1024 * 1024;
                break;
        }

        if ($var > $php_size1 || $var > $php_size2) {
            die("<div style='position:absolute;top:0;left:0;background-color:white;width:100%;height:100%;z-index:999;'><b>Error:</b> Maximum music size is larger than \"post_max_size\" or \"upload_max_filesize\" values in \"php.ini\" file.</div>");
        } else {
            return $var;
        }
    } else {
        return 10485760;
    }
}

function max_voice_note_size()
{
    $str = get_option("MAX_VOICE_NOTE_SIZE");
    $byte = substr($str, -1);
    $size = substr_replace($str, '', -1);
    $php_size1 = convertStringToSize(ini_get('post_max_size'));
    $php_size2 = convertStringToSize(ini_get('upload_max_filesize'));
    if (($byte == 'K' || $byte == 'M' || $byte == 'G') && $size > 0) {
        switch ($byte) {
            case 'K':
                $var = $size * 1024;
                break;
            case 'M':
                $var = $size * 1024 * 1024;
                break;
            case 'G':
                $var = $size * 1024 * 1024 * 1024;
                break;
        }

        if ($var > $php_size1 || $var > $php_size2) {
            die("<div style='position:absolute;top:0;left:0;background-color:white;width:100%;height:100%;z-index:999;'><b>Error:</b> Maximum voice note size is larger than \"post_max_size\" or \"upload_max_filesize\" values in \"php.ini\" file.</div>");
        } else {
            return $var;
        }
    } else {
        return 10485760;
    }
}

function share_net_destination()
{
    return getcwd().'/include/img/share/';
}

function share_destination()
{
    return "./include/img/share/";
}

function guests_on_user_list()
{
    switch (get_option("SHOW_GUESTS_ON_ONLINE_USER_LIST")) {
        case 1:
            return 1;
            break;
        default:
            return 0;
            break;
    }
}

function user_activation()
{
    return 1;
}

function forgot_password()
{
    switch (get_option("FORGOT_PASSWORD")) {
        case 1:
            return 1;
            break;
        default:
            return 0;
            break;
    }
}

function enable_online_users()
{
    switch (get_option("ENABLE_ONLINE_USERS")) {
        case 1:
            return 1;
            break;
        default:
            return 0;
            break;
    }
}

function enable_friend_system()
{
    switch (get_option("ENABLE_FRIEND_SYSTEM")) {
        case 1:
            return 1;
            break;
        default:
            return 0;
            break;
    }
}

function guest_friends()
{
    switch (get_option("ALLOW_GUEST_TO_ADD_FRIENDS")) {
        case 1:
            return 1;
            break;
        default:
            return 0;
            break;
    }
}

//////////////////////////////////
/////////// DATABASE /////////////
//////////////////////////////////

function db_connect()
{
    static $connection;

    if (!isset($connection)) {
        $connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
    }

    if ($connection === false) {
        return mysqli_connect_error();
    }

    return $connection;
}

function db_query($query)
{
    $connection = db_connect();
    mysqli_query($connection, "set names 'utf8'");
    $result = mysqli_query($connection, $query);

    return $result;
}

function db_escape($value)
{
    return mysqli_real_escape_string(db_connect(), $value);
}

//////////////////////////////////////////////
/////////// REGISTRATION & LOGIN /////////////
//////////////////////////////////////////////

function userName($user_id)
{
    $query = db_query("SELECT `username` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    return $row['username'];
}

function user_status($user_id)
{
    $query = db_query("SELECT `user_status` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    return $row['user_status'];
}

function user_interests($user_id){
    $query = db_query("SELECT `interests` FROM `user_info` WHERE `userID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    if(empty($row['interests'])){
        return false;
    }
    $interests = explode(",", $row['interests']);
    return $interests;
}

function isUserActivated($user_id)
{
    $query = db_query("SELECT `activation` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);
    if($row['activation'] == 1) {
		return true;
	} else {
		return false;
	}
}

// User ID
function user_id()
{
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    }
}

//Username
function user_name()
{
    if(isset($_SESSION['user_id'])){
        $user_id = user_id();
        $result = db_query("SELECT `username` FROM `members` WHERE `ID` = '$user_id'");
        $row = mysqli_fetch_assoc($result);

        return $row['username'];
    }
    
}

// User's Email Address
function email()
{
    if (isset($_SESSION['user_id'])) {
        $user_id = user_id();
        $result = db_query("SELECT `email` FROM `members` WHERE `ID` = '$user_id'");
        $row = mysqli_fetch_assoc($result);

        return $row['email'];
    }
}

// Get user's Token
function getToken($user_id)
{
	$user_id = db_escape($user_id);
    $query = db_query("SELECT `token` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    return $row['token'];
}

// Checks if the user is logged in
function isUserLoggedIn()
{
    if (isset($_SESSION['user_id'])) {
        return true;
    }else{
        return false;
    }
}

// Currently no guests allowed!!!
function isGuest($userID)
{
	return 0;
}

//currently no admin needed!
function isAdmin($user_id)
{
	/*$user_id = db_escape($user_id);
    $query = db_query("SELECT `admin`, `guest` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_array($query);
	if($row[0] == 1 && $row[1] == 0) {
		return true;
	} else {
		return false;
    }*/
    // Only user with id 46 
   if($user_id == 46){
       return true;
   }else{
       return false;
   }
}

function isOnline($user_id)
{
	$user_id = db_escape($user_id);
    $query = db_query("SELECT `online` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_array($query);
	if($row[0] == 1) {
		return true;
	} else {
		return false;
	}
}

function isFriend($user_id, $target_id)
{
	$user_id = db_escape($user_id);
	$target_id = db_escape($target_id);
    $query = db_query("SELECT `ID` FROM `chat_friends` WHERE `user_id` = '$user_id' && `friend_id` = '$target_id' LIMIT 1");
    if(mysqli_num_rows($query) > 0) {
		return true;
	} else {
		return false;
	}
}

function isBanned($user_id)
{
	$user_id = db_escape($user_id);
    $query1 = db_query("SELECT `time` FROM `chat_banned_users` WHERE `userid` = '$user_id' && `type` = 'temporary' ORDER BY `time` DESC LIMIT 1");
	$query2 = db_query("SELECT `ID` FROM `chat_banned_users` WHERE `userid` = '$user_id' && `type` = 'permanent' LIMIT 1");
	$get_res1 = mysqli_fetch_array($query1);
	if(mysqli_num_rows($query1) > 0 && $get_res1[0] > time()) {
		return true;
	} elseif(mysqli_num_rows($query2) > 0) {
		return true;
	} else {
		return false;
	}
}

///////////////////////////////////////////
///////////// CHAT FUNCTIONS //////////////
///////////////////////////////////////////

function blockUsers()
{
	$ipAddresses = get_option("banned_ip");
	if(count($ipAddresses) > 0) {
		$user = explode('.', getRealIpAddr());
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

// Converts Crypted Chat ID into Real ID
function chat_id($chat_no)
{
	$chat_no = db_escape($chat_no);
    $result = db_query("SELECT `ID` FROM `chat_room` WHERE `id_hash` = '$chat_no' LIMIT 1");
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        return $row['ID'];
    }
}

function chat_name($chat_no)
{
	$chat_no = db_escape($chat_no);
    $result = db_query("SELECT `chat_name` FROM `chat_room` WHERE `ID` = '$chat_no' || `id_hash` = '$chat_no' LIMIT 1");
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        return $row['chat_name'];
    }
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
	$chat_id = db_escape($chat_id);
    $result = db_query("SELECT `type` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
    $row = mysqli_fetch_assoc($result);

    return $row['type'];
}

// Checks if the user is in the room
function userInRoom($user_id, $room)
{
	$user_id = db_escape($user_id);
	$room = db_escape($room);
    $query = db_query("SELECT `ID` FROM `chat_members` WHERE `user_id` = '$user_id'
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

// Gets User's Profile Picture
function profilePicture($user_id)
{
	$user_id = db_escape($user_id);
    $query = db_query("SELECT `profile_pic` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    return $row['profile_pic'];
}

// Gets Group's Picture
function groupPicture($chat_id)
{
	$chat_id = db_escape($chat_id);
    $query = db_query("SELECT `group_pic` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    return $row['group_pic'];
}

function filter_words($ban_words_list, $message, $callback)
{
    return preg_replace_callback('/(?![^<]*>)\b(' . implode('|', array_map('preg_quote', $ban_words_list)) . ')\b/i', function ($a) use ($callback) {return $callback($a[1]);}, $message);
}

function ban_word($message)
{
	$ban_words = get_option("banned_words");
	if(count($ban_words) > 0) {
		$ban_list_query = array_keys($ban_words);
		return filter_words($ban_list_query, $message, function ($a) use ($ban_words) {
			return $ban_words[strtolower($a)];
		});
	} else {
		return $message;
	}
}

/////////////////////////////////////////////////////
///////////// OTHER REQUIRED FUNCTIONS //////////////
/////////////////////////////////////////////////////
function js_str($s)
{
    return '"' . addcslashes($s, "\0..\37\"\\") . '"';
}
function js_array($array)
{
    $temp = array_map('js_str', $array);
    return '[' . implode(',', $temp) . ']';
}

// Required Javascript Variables
function js_variables()
{
	if(protocol() == 1) {
		$ws_protocol = "wss://";
	} else {
		$ws_protocol = "ws://";
    }
	
    if (isUserLoggedIn()) {
        $user_name = user_name();
        $userid = user_id();
        $token = getToken($userid);
		
        echo '<script type="text/javascript">var username="'.$user_name.'",
												userid="'.$userid.'",
												max_capacity="'.max_group_capacity().'",
												wsUri="'.$ws_protocol.get_option("WEBSOCKET_URL").'",
												token="'.$token.'",
												img_path="'.picture_destination().'",
												max_group_name="'.max_group_name().'",
												min_search_lenght="'.min_search_lenght().'",
												max_photo="'.max_photo().'",
												max_video="'.max_video().'",
												max_file="'.max_file().'",';
												if(voice_notes() == 1) {
													echo 'voice_notes = 1,';
												} else {
													echo 'voice_notes = 0,';
												}
												echo 'ban_words='.js_array(get_option("banned_words")).',
												ban_words_list='.js_array(array_keys(get_option("banned_words"))).';
		</script>';
    } else {
        echo '<script type="text/javascript">var wsUri="'.$ws_protocol.get_option("WEBSOCKET_URL").'";';
		if(voice_notes() == 1) {
			echo 'var voice_notes = 1;';
		} else {
			echo 'var voice_notes = 0;';
		}
		echo '</script>';
    }
}

// Checks the connection if it is ajax
function is_ajax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function convertStringToSize($var)
{
    $byte = substr($var, -1);
    $size = substr_replace($var, '', -1);
    if (($byte == 'K' || $byte == 'M' || $byte == 'G') && $size > 0) {
        switch ($byte) {
            case 'K':
                return $size * 1024;
                break;
            case 'M':
                return $size * 1024 * 1024;
                break;
            case 'G':
                return $size * 1024 * 1024 * 1024;
                break;
        }
    } else {
        die("<div style='position:absolute;top:0;left:0;background-color:white;width:100%;height:100%;z-index:999;'><b>Error:</b> Invalid size value in \"php.ini\" file.</div>");
    }
}

function delete_directory($dirname)
{
    if (is_dir($dirname)) {
        $dir_handle = opendir($dirname);
    }
    if (!$dir_handle) {
        return false;
    }

    while ($file = readdir($dir_handle)) {
        if ($file != '.' && $file != '..') {
            if (!is_dir($dirname.'/'.$file)) {
                unlink($dirname.'/'.$file);
            } else {
                delete_directory($dirname.'/'.$file);
            }
        }
    }
    closedir($dir_handle);
    rmdir($dirname);

    return true;
}

function check_config_values()
{
    max_photo_size();
    max_video_size();
    max_file_size();
    max_music_size();
    max_photo();
    max_video();
    max_file();
    max_voice_note_size();
    max_user_photo_size();
}

function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $version= "";

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Firefox'; 
        $ub = "Firefox"; 
    }
    elseif(preg_match('/OPR/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'name'      => $bname,
        'version'   => $version
    );
} 

function javascript_files()
{
    echo "<script type='text/javascript' src='./include/js/jquery.min.js'></script>";
    echo "<script type='text/javascript' src='./include/js/jquery.form.js'></script>";
    echo "<script type='text/javascript' src='./include/js/simpleLightbox.min.js'></script>";
    echo "<script type='text/javascript' src='./include/js/materialize.min.js'></script>";
    echo "<script type='text/javascript' src='./include/js/reconnecting-websocket.js'></script>";
	if(voice_notes() == 1 ) {
		echo "<script type='text/javascript' src='./include/js/recorder.js'></script>";
	}
    echo "<script type='text/javascript' src='./include/js/main.js'></script>";
    echo "<script type='text/javascript' src='./include/js/perfect-scrollbar.jquery.js'></script>";
}

function css_files()
{
    echo "<link type='text/css' rel='stylesheet' href='./include/css/materialize.min.css' />";
    echo "<link href='https://fonts.googleapis.com/icon?family=Material+Icons' rel='stylesheet' />";
    echo "<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>";
    echo "<link type='text/css' rel='stylesheet' href='./include/css/chat_style.css' />";
    echo "<link type='text/css' rel='stylesheet' href='./include/css/emoticons.css' />";
    echo "<link type='text/css' rel='stylesheet' href='./include/css/perfect-scrollbar.css' />";
    echo "<link type='text/css' rel='stylesheet' href='./include/css/simplelightbox.min.css' />";
	echo "<style>";
		if(isUserLoggedIn() && ((enable_friend_system() == 1 && isGuest(user_id()) == 0) ||  (isGuest(user_id()) == 1 && guest_friends() == 1))) {
			echo "#dropdown2 {right: 120px!important} @media screen and (max-width: 640px) {#dropdown2 {right: 90px!important}}";
		} else {
			echo "#dropdown2 {right: 70px!important}";
		}
	echo "</style>";
}

function meta_tags()
{
    echo "<meta charset='UTF-8' />";
    echo "<meta http-equiv='X-UA-Compatible' content='IE=edge' />";
    echo "<meta name='HandheldFriendly' content='true' />";
    echo "<meta name='viewport' content='height=device-height, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no' />";
}

function protocol()
{
	if( !empty( $_SERVER['https'] ) )
        return 1;
    if( !empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' )
        return 1;

    return 0;
}

function check_size($input)
{
    $byte = substr($input, -1);
    $size = substr_replace($input, '', -1);
    $php_size1 = convertStringToSize(ini_get('post_max_size'));
    $php_size2 = convertStringToSize(ini_get('upload_max_filesize'));
    if (($byte == 'K' || $byte == 'M' || $byte == 'G') && $size > 0) {
        switch ($byte) {
            case 'K':
                $var = $size * 1024;
                break;
            case 'M':
                $var = $size * 1024 * 1024;
                break;
            case 'G':
                $var = $size * 1024 * 1024 * 1024;
                break;
        }

        if ($var > $php_size1 || $var > $php_size2) {
			return 0;
        } else {
            return 1;
        }
    } else {
        return -1;
    }
}

function check_max_upload($input)
{
    $max_num = ini_get('max_file_uploads');
    if ($input > $max_num) {
		return false;
	} else {
		return true;
	}
}

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) //check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function isLocalhost() {
	if($_SERVER['REMOTE_ADDR'] == "127.0.0.1" || $_SERVER['REMOTE_ADDR'] == "::1" || $_SERVER['REMOTE_ADDR'] == "localhost")
	{
		return true;
	} else {
		return false;
	}
}
