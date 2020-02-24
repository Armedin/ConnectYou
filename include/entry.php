<?php
  require __DIR__.'/init_functions.php';

  if(isset($_GET['action']) && $_GET['action'] == 'register' && is_ajax()){

    $error = '';
		$status = 1;

    if(empty($_POST['email'])){
      $error = 'Please enter your email address.';
      $status = 0;
    } elseif (empty($_POST['password']) || empty($_POST['repassword'])){
      $error = 'Please enter your password.';
      $status = 0;
    } elseif (strlen($_POST['password']) < min_pass_length()){
      $error = 'Your password must be at least' .min_pass_length().' characters.';
      $status =0;
    } elseif (strlen($_POST['username']) < min_username_length()){
      $error = 'Your username must be at least '. min_username_length().'characters';
      $status =0;
    } else if(strlen($_POST['password'])> max_pass_length()){
      $error = 'Your password can not be longer than '.max_pass_length().' characters.';
			$status = 0;
    } else if(strlen($_POST['username'])> max_username_length()){
      $error = 'Your username can not be longer than '.max_username_length().' characters.';
			$status = 0;
    } elseif (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['username'])) {
			$error = 'Username can not include special characters.';
			$status = 0;
		} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$error = 'Invalid Email address.';
			$status = 0;
		} elseif (!empty($_POST['email'])
			&& filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
			&& !empty($_POST['password'])
			&& !empty($_POST['repassword'])
			&& strlen($_POST['password']) <= max_pass_length()
			&& strlen($_POST['password']) >= min_pass_length()
			&& ($_POST['password'] === $_POST['repassword'])
			&& !empty($_POST['username'])
			&& strlen($_POST['username']) <= max_username_length()
			&& strlen($_POST['username']) >= min_username_length()
		) {

        // change character set to utf8 and check for errors
        if (!mysqli_set_charset(db_connect(), 'utf8')) {
            $error = mysqli_error(db_connect());
            $status = 0;
        }

        //if(!mysqli_errno(db_connect())){
          $username = db_escapeString($_POST['username']);
          $user_email = db_escapeString($_POST['email']);

          // Hashing password!
          $user_password = db_escapeString($_POST['password']);
          $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);
				  $escaped_hash = db_escapeString($user_password_hash);

          //Check for duplicate
          $check_email_query = db_query("SELECT * FROM members WHERE email = '$user_email'");
          $check_username_query = db_query("SELECT * FROM members WHERE username = '$username'");


          if(mysqli_num_rows($check_email_query) == 1){
            $error = 'This email is already in use.';
            $status = 0;
          } elseif(mysqli_num_rows($check_username_query) == 1){
            $error = 'This username is already is use';
            $status = 0;
          }else{
            // We are good to go !
            $time = time();
            $register_user = db_query("INSERT INTO members (username, password, email, registration_date)
            VALUES ('$username', '$escaped_hash', '$user_email', '$time')");
          }
        // }else{
        //   $error = 'Database connection problem';
        //   $status = 0;
        // }

    }

    echo json_encode(
				array(
					'status' =>$status ,
					'error' => $error
				)
			);
}

elseif(isset($_GET['action']) && $_GET['action'] == "login" && is_ajax()
    && isset($_POST['username'])
		&& isset($_POST['password'])
		&& !empty($_POST['username'])
		&& !empty($_POST['password'])){

    $status = 1;
    $error = '';

    // change character set to utf8 and check it
		if (!mysqli_set_charset(db_connect(), 'utf8')) {
			$error = mysqli_error(db_connect());
			$status = 0;
		}


    if(!mysqli_errno(db_connect())){
      $username = db_escapeString($_POST['username']);
      $check_username = db_query("SELECT ID, username, password, email FROM members WHERE username = '$username' || email = '$username'");
      if(mysqli_num_rows($check_username) == 1){

        $row = mysqli_fetch_assoc($check_username);
        $user_id = $row['ID'];

        if (password_verify($_POST['password'], $row['password'])) {

          $token = bin2hex(openssl_random_pseudo_bytes(32));
          db_query("UPDATE members SET token = '$token' WHERE ID = '$user_id'");
          $_SESSION['user_id'] = $user_id;
          $_SESSION['user_token'] = $token;

        }else{
          $error = "Wrong password. Please try again";
          $status = 0;
        }
      }else{
        $error = 'We could not find any user.';
				$status = 0;
      }
    }else{
      $error = 'Database connection problem';
      $status = 0;
    }

    echo json_encode(
				array(
					'status' => $status,
					'error' => $error,
				)
			);




}


?>
