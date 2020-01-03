<?php
include_once('include/init_functions.php');
if(is_user_logged_in()){
  header('Location: index.php');
}

?>

<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta property="og:url" content="http://www.connectyou.com"/>
	<meta property="og:type" content="non_profit"/>
	<meta property="og:title" content="ConnectYou"/>
	<meta property="og:description" content="This is the description">

	<meta name="twitter:site" content="@connectyou">
	<meta name="twitter:title" content="ConnectYou">
	<meta name="twitter:description" content="This is the description">

	<meta itemprop="name" content="ConnectYou">
	<meta itemprop="description" content="This is the description.">


  <link rel="stylesheet" href="dist/css/main.css">
  <link rel="icon" href="dist/img/icon.png" type="image/x-icon" />
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500" rel="stylesheet">
  <link href="dist/fontawesome/releases/v5.11.2/css/all.css" rel="stylesheet">


  <title>Login | ConnectYou</title>

</head>

<body>
  <div class="login_and_signup-background">
    <div class="inner_wrapper">
      <div class="register_auth_login">
        <div class="logo">
          <a href="#"><img src="dist/img/icon.png"></a>
        </div>
        <div class="login_register_box">
          <h1 class="title">Log in to ConnectYou</h1>
          <form autocomplete="off" class="cy-form" action="return false">
            <div class="form_group">
              <input type="text" name="user_email" id="login-email" class="input_with_borderErrors" placeholder="Email">
              <span class="form_group_icon">
                <i class="fal fa-envelope"></i>
              </span>
            </div>
            <div class="form_group">
              <input type="password" name="user_password" id="login-password" class="input_with_borderErrors" placeholder="Password">
              <span class="form_group_icon">
                <i class="fal fa-key"></i>
              </span>
            </div>
          </form>

          <div class="login_other_options">
            <div class="row-col">
              <div class="col-sm-6">
                <!-- !TODO Remember me button-->
              </div>
              <div class="col-sm-6">
                <div class="forgot_password_cont">
                  <a href="reset-password.php">Forget Password?</a>
                </div>
              </div>
            </div>
          </div>

          <div class="login_button_container">
            <button class="form_btn" id="login">Log in</button>
          </div>
          <div class="auth_external_message">
            <span>Don't have an account yet? <a href="sign-up.php">Sign Up</a> now!</span>
          </div>
        </div>
      </div>
    </div>
  </div>





<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/main.js"></script>
</body>

</html>
