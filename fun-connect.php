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

  <title>FunConnect | ConnectYou</title>

</head>

<body>

  <?php include_once('include/page_header.php'); ?>


  <!-- Game Intro Section -->
  <section class="game-intro-section">
    <div class="container_inner">
      <div class="row-col align-flex-center">
        <div class="col-sm-12 col-md-7 col-lg-6">
          <div class="info">
            <h1>Welcome to the world of fun</h1>
            <p>Welcome to FunConnect - where students play games to have fun and make new friends. Try playing one game now!</p>
          </div>
        </div>
        <div class="col-sm-12 col-md-5 col-lg-6">
          <div class="header-image">
            <img src="dist/img/funconnect_bg.png">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Games Body Section -->
  <section class="game-container-section">
    <div class="container_inner">
      <div class="info_header">
        <h2>Select a game</h2>
      </div>
      <div class="game-item-container">

        <div class="single-game-item col-md-6">
          <div class="game-image">
            <img src="dist/img/hangman_bg.png">
          </div>
          <div class="game-body-content">
            <h3 class="game-title">Hangman Game</h3>
            <div class="game-metadata">
              <span class="game-status available">
                <i class="fad fa-lock-open-alt"></i> Accessible
              </span>
            </div>
            <p class="game-description">
              This is a classic word game in which you must guess as many secret words as you can! But the
              difference in our game is that two players choose a word from the list of categories and then play
              against each other trying to guess the word! So you will know that you play against a real person not
              a robot! In the end, you will be able to connect with the person you have been playing with and get to know each other better!
            </p>
            <button class="play-btn">Play Game</button>
          </div>
        </div>

        <div class="single-game-item col-md-6">
          <div class="game-image">
            <img src="dist/img/maze_bg.png">
          </div>
          <div class="game-body-content">
            <h3 class="game-title">Maze Game</h3>
            <div class="game-metadata">
              <span class="game-status available">
                <i class="fad fa-lock-open-alt"></i> Accessible
              </span>
            </div>
            <p class="game-description">
              In this game, you will be able to compete with another real person for achieving the best result while being chased
              by a MONSTER! Get ready to be quick in collecting coins and outracing your opponent! Here is a small hint for you:
              maybe a collaborative play will be more useful for both of you to collect more coins and outsmart the monster? Connect
              with your opponent after the game and discuss how well you have both done!
            </p>
            <button class="play-btn">Play Game</button>
          </div>
        </div>


      </div>
    </div>
  </section>



  <?php include_once('include/footer.php');?>


<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/main.js"></script>
</body>

</html>






<!-- -->
