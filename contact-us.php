
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

  <title>Contact Us | ConnectYou</title>

</head>

<body>


  <div class="page_header">
    <div class="inner_wrapper">
      <a href="/" class="header_logo">
        <img src="dist/img/logos/logo152.png">
        <span>ConnectYou</span>
      </a>
      <ul class="header_links">
        <li><a href="./index.php">Home</a></li>
        <li><a href="./fun-connect.html">Games</a></li>
        <li><a href="./chat.html">Chat</a></li>
        <li><a href="./our-team.html">Our Team</a></li>
      </ul>
      <a href="javascript:void(0);" class="mobile_nav">
        <i class="fas fa-bars"></i>
      </a>
    </div>
  </div>

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

  <div class="contact_us_page">
    <div class="contact_us_header">
      <div class="title">Need help with something?</div>
      <div class="desc">Submit your help request below!</div>
      <div class="bg_image">
        <img src="dist/img/contact_us_bg.jpg">
      </div>
    </div>
    <div class="contact_us_body">
      <div class="contact_card">
        <div class="card_head">
          <div class="title">Send us your enquiries</div>
        </div>
        <div class="card_body">
          <form name="contact-us" id="contact-us">
            <div class="row-col">
              <div class="col-lg-3"></div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-label">Name</label>
                  <input type="text" class="form-input" name="firstname" placeholder="Enter your name">
                </div>
                <div class="form-group">
                  <label class="form-label">Phone</label>
                  <input type="text" class="form-input" name="firstname" placeholder="Enter your phone number">
                </div>
                <div class="form-group">
                  <label class="form-label">Email address</label>
                  <input type="text" class="form-input" name="firstname" placeholder="Enter your email">
                </div>
                <div class="form-group">
                  <label class="form-label">Your message</label>
                  <textarea type="text" class="form-input" name="firstname"></textarea>
                  <label class="form-under-label">We'll never share your email with anyone else.</label>
                </div>
              </div>
              <div class="col-lg-3"></div>
            </div>
          </form>
        </div>
        <div class="card_footer">
          <button class="submit-enquiry">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <?php include_once('include/footer.php');?>

<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/main.js"></script>
</body>

</html>






<!-- -->
