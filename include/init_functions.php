<?php

if(session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
}


//Check if php version is lower than 5.5.0 and dispaly error!
if (version_compare(PHP_VERSION, '5.5.0', '<')) {
	die("<div style='position:absolute;top:0;left:0;background-color:white;width:100%;height:100%;z-index:999;'><b>Error:</b> Your PHP Version cannot be lower than 5.5.0!</div>");
}



//////////////////////////////////
/////////// DATABASE /////////////
//////////////////////////////////

function db_connect()
{
    static $connection;
    if (!isset($connection)) {
	  $serverName = "connect-you.mysql.database.azure.com";
		$serverUsername = "Armedin@connect-you";
		$serverPassword = "BrK!G6!2tkEfsQf";
		$serverDatabase="connect-you";
		$connection=mysqli_connect($serverName, $serverUsername, $serverPassword, $serverDatabase);
    }

    if ($connection === false) {
        return mysqli_connect_error();
    }else{
      //return 'works';
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

function db_escapeString($value)
{
    return mysqli_real_escape_string(db_connect(), $value);
}

function is_ajax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function getToken($user_id)
{
	$user_id = db_escapeString($user_id);
    $query = db_query("SELECT `token` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    return $row['token'];
}

function getUsername($user_id){
  $user_id = db_escapeString($user_id);
    $query = db_query("SELECT `username` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    return $row['username'];
}

function is_user_logged_in(){
	return isset($_SESSION['user_id']);
}

function is_user_info_registered(){
    if(is_user_logged_in()){
        $user_id = $_SESSION['user_id'];
        $query = db_query("SELECT * FROM `user_info` WHERE `userID` = '$user_id' LIMIT 1");
        $result = mysqli_num_rows($query);
        if($result > 0){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }

}





// For DEMO purpose
function is_activation_required(){
    return false;
}

//Whether the user has to activate its account
function user_activation_required(){
		return false;
}

function min_pass_length(){
	return 6;
}

function max_pass_length(){
	return 99;
}

function min_username_length(){
	return 6;
}

function max_username_length(){
	return 55;
}

function user_interests($user_id){
  $user_id = db_escapeString($user_id);
  $sql = db_query("SELECT interests FROM user_info WHERE userID = '$user_id' LIMIT 1");

  $interests = array();

  if(mysqli_num_rows($sql)>0){
    $row = mysqli_fetch_assoc($sql);
    if(!empty($row['interests']) || !is_null($row['interests'])){
      $interests = explode(',', $row['interests']);
    }
  }
  return $interests;
}






?>
