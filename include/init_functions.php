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
	  $serverName = "127.0.0.1";
		$serverUsername = "root";
		$serverPassword = "";
		$serverDatabase="connectyou";
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



//Whether the user has to activate his/her account_logout_ophf39v4_btn
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

function is_user_logged_in(){
	return isset($_SESSION['user_id']);
}




?>
