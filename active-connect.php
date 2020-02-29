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


  <link rel="stylesheet" href="dist/css/main.css">
  <link rel="icon" href="dist/img/logos/logo48.png" type="image/x-icon" />
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500" rel="stylesheet">
  <link href="dist/fontawesome/releases/v5.11.2/css/all.css" rel="stylesheet">


  <title>ActiveConnect | ConnectYou</title>

</head>

<body>

  <?php include_once('include/page_header.php'); ?>


  <!-- Game Intro Section -->
  <section class="game-intro-section active-connect">
    <div class="container_inner">
      <div class="row-col align-flex-center">
        <div class="col-sm-12 col-md-7 col-lg-6">
          <div class="info">
            <h1>Welcome to Active Connect</h1>
            <p>Active Connect allows students to create common activities based on their preferences.<br>
            Active Connect promotes intergroup communication and establishment of offline friendships.</p>
          </div>
        </div>
        <div class="col-sm-12 col-md-5 col-lg-6">
          <div class="header-image">
            <img src="dist/img/active_connect_p1.gif">
          </div>
        </div>
      </div>
    </div>
  </section>


  <section class="game-container-section">
    <div class="container_inner">
      <div class="info_header">
        <h2>Select an activity</h2>
      </div>
      <div class="game-item-container">

        <div class="single-game-item col-md-4">
          <div class="game-image">
            <img src="dist/img/outdoor_physical_activity_bg.jpg">
          </div>
          <div class="game-body-content">
            <h3 class="game-title">Outdoor Physical Activity</h3>
            <button class="play-btn select-btn" value="ce5a8fde1fcd379b864241aa0e057f95">Select</button>
          </div>
        </div>

        <div class="single-game-item col-md-4">
          <div class="game-image">
            <img src="dist/img/outdoor_nonphysical_activity_bg.jpg">
          </div>
          <div class="game-body-content">
            <h3 class="game-title">Outdoor Non-physical Activity</h3>
            <button class="play-btn select-btn" value="6cfde6c7b53b304871dd717344acf95b">Select</button>
          </div>
        </div>

        <div class="single-game-item col-md-4">
          <div class="game-image">
            <img src="dist/img/indoor_physical_activity_bg.jpg">
          </div>
          <div class="game-body-content">
            <h3 class="game-title">Indoor Physical Activity</h3>
            <button class="play-btn select-btn" value="c820883c7281b80f523876d473f76d00">Select</button>
          </div>
        </div>

        <div class="single-game-item col-md-4">
          <div class="game-image">
            <img src="dist/img/indoor_nonphysical_activity_bg.jpg">
          </div>
          <div class="game-body-content">
            <h3 class="game-title">Indoor Non-physical Activity</h3>
            <button class="play-btn select-btn" value="c31d371c07841da895aa81710be107f5">Select</button>
          </div>
        </div>

        <div class="single-game-item col-md-4">
          <div class="game-image">
            <img src="dist/img/night_activities_noalcohol_bg.jpg">
          </div>
          <div class="game-body-content">
            <h3 class="game-title">Night Activity without alcohol</h3>
            <button class="play-btn select-btn" value="89bab835e0a603282d90383b66f45064">Select</button>
          </div>
        </div>

        <div class="single-game-item col-md-4">
          <div class="game-image">
            <img src="dist/img/night_activities_alcohol_bg.jpg">
          </div>
          <div class="game-body-content">
            <h3 class="game-title">Night Activity with alcohol</h3>
            <button class="play-btn select-btn" value="cbba9784b052e7d15e460c3e67c0b4a0">Select</button>
          </div>
        </div>

      </div>
    </div>
  </section>



  <?php include_once('include/footer.php');?>


<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/main.js"></script>

<?php

  if(is_user_logged_in()){
    echo '<script>var user_id ="'.$_SESSION['user_id'].'", token = "'.getToken($_SESSION['user_id']).'", username = "'.getUsername($_SESSION['user_id']).'"</script>';
  }

  if(is_user_logged_in()){

?>
  <script>

    $(".select-btn").on("click", function(){
      var chat = $(this).val();
      console.log(chat);
      // $.post( "./action.php?act=invite-group", { chatid: chat, array: array_inv, token: token, username: username, userid: userid}).done( function( data ){

      // });
    });

  </script>
<?php
  }else{

?>
  <script>
    $(".select-btn").on("click", function(){
      window.location.href="login.php";
    });

  </script>
<?php
  }
?>


</body>

</html>






<!-- -->
