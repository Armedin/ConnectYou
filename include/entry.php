<?php

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
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
          
        
          if(send_email()){
            
            $mail = new PHPMailer(true);


            try {
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

              $url = 'https://testwschat.tk?action=activate';
              $mail->Body = '<head>
              <title></title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <style type="text/css">
              #outlook a { padding: 0; }
              .ReadMsgBody { width: 100%; }
              .ExternalClass { width: 100%; }
              .ExternalClass * { line-height:100%; }
              body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
              table, td { border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
              img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
              p { display: block; margin: 13px 0; }
            </style>
            <!--[if !mso]><!-->
            <style type="text/css">
              @media only screen and (max-width:480px) {
                @-ms-viewport { width:320px; }
                @viewport { width:320px; }
              }
            </style>
            <!--<![endif]-->
            <!--[if mso]>
            <xml>
              <o:OfficeDocumentSettings>
                <o:AllowPNG/>
                <o:PixelsPerInch>96</o:PixelsPerInch>
              </o:OfficeDocumentSettings>
            </xml>
            <![endif]-->
            <style type="text/css">
              @media only screen and (min-width:480px) {
                .mj-column-per-100, * [aria-labelledby="mj-column-per-100"] { width:100%!important; }
            .mj-column-per-80, * [aria-labelledby="mj-column-per-80"] { width:80%!important; }
            .mj-column-per-30, * [aria-labelledby="mj-column-per-30"] { width:30%!important; }
            .mj-column-per-70, * [aria-labelledby="mj-column-per-70"] { width:70%!important; }
              }
            </style>
            </head>
            <body style="background: #E3E5E7;">
              <div style="margin:0 auto;max-width:600px;background:#222228;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#222228;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:20px 0px;"><!--[if mso | IE]>
                  <table border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:480px;">
                  <![endif]--><div aria-labelledby="mj-column-per-80" class="mj-column-per-80" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" style="vertical-align:top;" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:10px 25px;padding-top:30px;" align="center"><table cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:80px;"><p><img src="https://www.kukaacademy.com/dist/img/facilities/rocket.png" alt="Code Rocket" title="None" width="150" style="height: auto;"></p></td></tr></tbody></table></td></tr><tr><td style="word-break:break-word;font-size:0px;padding:0px 20px 0px 20px;" align="center"><div style="cursor:auto;color:white;font-family:\'Avenir Next\', Avenir, sans-serif;font-size:32px;line-height:60ps;">
                        Verify Your Account
                      </div></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]-->
                  <!--[if mso | IE]>
                  <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                    <tr>
                      <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                  <![endif]--><div style="margin:0 auto;max-width:600px;background:white;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:white;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:40px 25px 0px;"><!--[if mso | IE]>
                  <table border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:600px;">
                  <![endif]--><div aria-labelledby="mj-column-per-100" class="mj-column-per-100" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:0px 0px 25px;" align="left"><div style="cursor:auto;color:#222228;font-family:\'Avenir Next\', Avenir, sans-serif;font-size:18px;font-weight:500;line-height:30px;">
                        Your account information
                      </div></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td><td style="vertical-align:top;width:180px;">
                  <![endif]--><div aria-labelledby="mj-column-per-30" class="mj-column-per-30" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:0px 0px 10px;" align="left"><div style="cursor:auto;color:#222228;font-family:\'Avenir Next\', Avenir, sans-serif;font-size:16px;line-height:30px;">
                        <strong style="font-weight: 500; white-space: nowrap;">Account</strong>
                      </div></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td><td style="vertical-align:top;width:420px;">
                  <![endif]--><div aria-labelledby="mj-column-per-70" class="mj-column-per-70" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:0px 0px 10px;" align="left"><div style="cursor:auto;color:#222228;font-family:\'Avenir Next\', Avenir, sans-serif;font-size:16px;line-height:30px;">
                       '.$username.'
                      </div></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td><td style="vertical-align:top;width:180px;">
                  <![endif]--><div aria-labelledby="mj-column-per-30" class="mj-column-per-30" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:0px 0px 10px;" align="left"><div style="cursor:auto;color:#222228;font-family:\'Avenir Next\', Avenir, sans-serif;font-size:16px;line-height:30px;">
                        <strong style="font-weight: 500; white-space: nowrap;">Verify Link</strong>
                      </div></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td><td style="vertical-align:top;width:420px;">
                  <![endif]--><div aria-labelledby="mj-column-per-70" class="mj-column-per-70" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:0px 0px 25px;" align="left"><div style="cursor:auto;color:#222228;font-family:\'Avenir Next\', Avenir, sans-serif;font-size:16px;line-height:30px;">
                        <a href="'.$url.'" style="color:#0a84ae; text-decoration:none" target="_blank">'.$url.'</a>
                      </div></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]-->
                  <!--[if mso | IE]>
                  <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                    <tr>
                      <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                  <![endif]--><div style="margin:0 auto;max-width:600px;background:white;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:white;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:0px 30px;"><!--[if mso | IE]>
                  <table border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:undefined;width:600px;">
                  <![endif]--><p style="font-size:1px;margin:0 auto;border-top:1px solid #E3E5E7;width:100%;"></p><!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" style="font-size:1px;margin:0 auto;border-top:1px solid #E3E5E7;width:100%;" width="600"><tr><td style="height:0;line-height:0;">.</td></tr></table><![endif]--><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]-->
                  <!--[if mso | IE]>
                  <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                    <tr>
                      <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                  <![endif]--><div style="margin:0 auto;max-width:600px;background:white;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:white;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:20px 0px;"><!--[if mso | IE]>
                  <table border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:600px;">
                  <![endif]--><div aria-labelledby="mj-column-per-100" class="mj-column-per-100" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:10px 25px;" align="center"><table cellpadding="0" cellspacing="0" align="center" border="0"><tbody><tr><td style="border-radius:3px;color:white;cursor:auto;" align="center" valign="middle" bgcolor="#EB5424"><a href="'.$url.'" style="display:inline-block;text-decoration:none;background:#EB5424;border-radius:3px;color:white;font-family:\'Avenir Next\', Avenir, sans-serif;font-size:14px;font-weight:500;line-height:35px;padding:10px 25px;margin:0px;" target="_blank">
                        VERIFY YOUR ACCOUNT
                      </a></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]-->
                  <!--[if mso | IE]>
                  <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                    <tr>
                      <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                  <![endif]--><div style="margin:0 auto;max-width:600px;background:white;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:white;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:20px 0px;"><!--[if mso | IE]>
                  <table border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:600px;">
                  <![endif]--><div aria-labelledby="mj-column-per-100" class="mj-column-per-100" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" style="vertical-align:top;" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:0px 25px 15px;" align="left"><div style="cursor:auto;color:#222228;font-family:\'Avenir Next\', Avenir, sans-serif;font-size:16px;line-height:30px;">
                        If you are having any issues with your account, please don\'t hesitate to contact us by replying to this mail.
                        <br>Thanks!
                      </div></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]-->
                  <!--[if mso | IE]>
                  <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                    <tr>
                      <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                  <![endif]--><div style="margin:0 auto;max-width:600px;background:#F5F7F9;"><table cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#F5F7F9;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;font-size:0px;padding:20px 0px;"><!--[if mso | IE]>
                  <table border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:600px;">
                  <![endif]--><div aria-labelledby="mj-column-per-100" class="mj-column-per-100" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%;"><table cellpadding="0" cellspacing="0" style="vertical-align:top;" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:0px 20px;" align="center"><div style="cursor:auto;color:#222228;font-family:\'Avenir Next\', Avenir, sans-serif;font-size:13px;line-height:20px;">
                        You’re receiving this email because you have an account in ConnectYou.
                        If you are not sure why you’re receiving this, please contact us.
                      </div></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]-->
                  <!--[if mso | IE]>
                  <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                    <tr>
                      <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                  <![endif]--><div></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]-->
            
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="640" align="center" style="width:640px;">
                    <tr>
                      <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
            <div style="margin:0px auto;max-width:640px;background:transparent;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:transparent;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:40px 0px;"><!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:640px;">
                  <![endif]--><div aria-labelledby="mj-column-per-100" class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:138px;"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
                  </td></tr></table>
                  <![endif]-->
                  <!--[if mso | IE]>
            
                  </div>
            
            
            
            </body>';
                if(!$mail->send()) {
                  $status = 0;
                  $error = $mail->ErrorInfo;
                } 
              } // End Try
              catch (Exception $e) {
                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
              }

              
          }

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
