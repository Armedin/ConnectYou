<?php
include_once('include/init_functions.php');
if(!is_user_logged_in()){
  header('Location: login.php');
}else if(!is_user_info_registered()){
  header('Location: collect-data.php');
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
  <meta name="description" content="Meet and chat to students who are at your university and share your interests.">
  <meta name="keywords" content="connect, university, interests, games, chat, make friends, ">

  <link rel="stylesheet" href="dist/css/main.css">
  <link rel="icon" href="dist/img/logos/logo48.png" type="image/x-icon"/>
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

  <title>ConnectYou</title>

</head>

<body class="landing_page">

  <?php include_once('include/page_header.php'); ?>

  <!-- Page Sidebar -->
  <div class="page-sidebar">
    <div class="header-logo">
      <a href="/" class="logo-image">
        <img src="dist/img/logos/logo152.png">
        <span>ConnectYou</span>
      </a>
  	</div>
  	<div class="sidebar-content">
  	   <div class="sidebar-userContainer">
  		     <div class="sidebar-userInfo">

  		     </div>
  	    </div>
        <div class="main-menu">
  			 <ul>
  		      <li class="list-header"><span>Applications</span></li>
  					<li><a href="#" id="home"><i class="fab fa-dashcube"></i>Home</a></li>
  					<li><a href="#" id="games"><i class="fas fa-gamepad-alt"></i>Games</a></li>
  					<li><a href="#" id="chat"><i class="fad fa-comments-alt"></i>Chat</a></li>
  			    <li><a href="./our-team.html" id="our-team"><i class="fas fa-users-crown"></i>Our Team</a></li>
  			  </ul>
        </div>

  		</div>
  </div>

  <section class="home_section">
  </section>

  <section class="connect_you_info_section">
    <h1 class="title">ConnectYou is a platform which connects like-minded people.</h1>
    <p class="connect_txt">If you wish to be connected with people who you share the same interests with, click ConnectMe button below</p>
    <div class="button_container">
      <button class="connect_me-btn" id="connect-me">ConnectMe</button>
    </div>
  </section>



<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/main.js"></script>
<?='<script>var user_id ="'.$_SESSION['user_id'].'", token = "'.getToken($_SESSION['user_id']).'", username = "'.getUsername($_SESSION['user_id']).'"</script>'?>
<script>
  $("#connect-me").on("click", function(){
    $.ajax({
      type: "POST",
      url: "include/get-user-list.php?action=get-user-list",
      data: {user_id: user_id},
      dataType: "json",
      success: function(response){
        if(response.status == 0){
          Snackbar.showToast({def_text:response.error});
        }else if(response.status == 1){
          $("body").html(response.output);
        }else{
          Snackbar.showToast({def_text:'An unknown error has occured.'});
        }
      },
      error: function(xhr){
        Snackbar.showToast({def_text:xhr.responseText});
      }

    });
  });

  $("body").on("click", ".connect_btn", function(){
    var target_id = $(this).val();

    $.post( "./chat/action.php?act=pm", { uid: target_id, ui: user_id, token: token, username: username, method: 'connect'}).done( function( data ){
      var response = jQuery.parseJSON(data);
      token = response.token;
      if( response.check_stat !== "error" ){
        window.location.href="./chat/";
      }else{
        Snackbar.showToast({def_text:'An unknown error has occured.'});
      }
    });
  });
</script>

</body>

</html>


<!-- -->
