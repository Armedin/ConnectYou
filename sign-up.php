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


  <!-- FONTS
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Arvo" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Nunito+Sans|Roboto" rel="stylesheet">
	<link rel="stylesheet" id="redux-google-fonts-stm_option-css"
		href="https://fonts.googleapis.com/css?family=Montserrat%3A200%2C500%2C600%2C400%2C700%7COpen+Sans%3A300%2C400%2C600%2C700%2C800%2C300italic%2C400italic%2C600italic%2C700italic%2C800italic&#038;subset=latin&#038;ver=1536658178"
		 type="text/css" media="all" />
	<link rel="stylesheet" id="redux-google-fonts-stm_option-css"
		href="https://fonts.googleapis.com/css?family=Montserrat%3A200%2C500%2C600%2C400%2C700%7COpen+Sans%3A300%2C400%2C600%2C700%2C800%2C300italic%2C400italic%2C600italic%2C700italic%2C800italic&#038;subset=latin&#038;ver=1536658178"
		 type="text/css" media="all" />
  -->

  <title>Sign Up | ConnectYou</title>

</head>

<body>

  <div class="login_and_signup-background">
    <div class="inner_wrapper">
      <div class="register_auth_login">
        <div class="logo">
          <a href="#"><img src="dist/img/icon.png"></a>
        </div>
        <div class="login_register_box">
          <h1 class="title">Sign Up to ConnectYou</h1>
          <form autocomplete="off" class="cy-form" action="return false">
            <div class="form_group">
              <input type="text" name="user_username" id="register-username" class="input_with_borderErrors" placeholder="Username">
              <span class="form_group_icon">
                <i class="far fa-user"></i>
              </span>
            </div>
            <div class="form_group">
              <input type="text" name="user_email" id="register-email" class="input_with_borderErrors" placeholder="Email">
              <span class="form_group_icon">
                <i class="fal fa-envelope"></i>
              </span>
            </div>
            <div class="form_group">
              <input type="password" name="password" id="register-password" class="input_with_borderErrors" placeholder="Password">
              <span class="form_group_icon">
                <i class="fal fa-key"></i>
              </span>
            </div>
            <div class="form_group">
              <input type="password" name="password_confirm" id="register-repassword" class="input_with_borderErrors" placeholder="Confirm Password">
              <span class="form_group_icon">
                <i class="fal fa-key"></i>
              </span>
            </div>
          </form>


          <div class="login_button_container">
            <button class="form_btn" id="register">Sign up</button>
          </div>
          <div class="auth_external_message">
            <span>Already have an account? <a href="login.php">Log in </a> here!</span>
          </div>
        </div>
      </div>
    </div>
  </div>





<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/main.js"></script>
</body>

</html>
