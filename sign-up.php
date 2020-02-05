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
  <link rel="icon" href="dist/img/logos/logo48.png" type="image/x-icon" />
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
          <a href="#"><img src="dist/img/logos/logo152.png"></a>
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
            <div class="form_group">
              <input type="checkbox" name="terms_and_cond" id="terms-conditions" class="hidden_checkbox">
              <label for="terms-conditions" class="checkbox_label">I agree to the <a data-target="terms_condition_modal">Terms & Conditions</a></label>
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

  <div class="modal" id="terms_condition_modal">
    <div class="modal_container">
      <div class="modal_content">
        <div class="modal_header">
          <h4>Terms & Conditions</h4>
          <button type="button" aria-label="Close" class="close_modal">
            <span class="icon_bar"></span>
            <span class="icon_bar"></span>
          </button>
        </div>
        <div class="modal_body">
          <p>
          Welcome to our website and thank you for your interest to become a part of the social movement of ConnectYou! ConnectYou (“us” or “we”) and our 
          services are developed available through the mobile-friendly website (the “Website” or the “Site”). Unless it is not specified, all references 
          to the Service or Services involve the set of services accessible through the ConnectYou Website, as well as any other software or social media 
          that ConnectYou offers that allows to get the Services. The term “you” indicates the user of the Service. The further Terms of Service are a form 
          of legal binding contract between you and ConnectYou as with the user of the Services. ConnectYou is owned and operated by ConnectYou Company, 
          a British Limited Liability Company.
          <br><br>
          Please read the following Terms of Service ("Terms") carefully before accessing or using the Service. Each time you access or use the Service, you, 
          or if you are acting on behalf of a third party or your employer, such third party or employer, agree to be bound by these Terms whether or not you 
          register with us. If you do not agree to be bound by all of these Terms, you may not access or use the Service. ConnectYou may change this 
          Agreement at any time by posting an updated Terms of Service on this site. If any amendment to these Terms is unacceptable to you, you shall 
          cease using this Site. If you continue using the Site, you will be conclusively deemed to have accepted the changes.
          </p>
        </div>
        <div class="modal_footer">
          <button class="close_modal_btn">Close</button>
        </div>
      </div>
    </div>
  </div>





<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/main.js"></script>
<script>
  


</script>
</body>

</html>
