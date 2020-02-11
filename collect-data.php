<?php
  include_once('include/init_functions.php');
  if(!is_user_logged_in()){
    header('Location: login.php');
  }
  if(is_user_info_registered()){
    header('Location: login.php');
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


  <link rel="stylesheet" href="dist/css/croppie.min.css">
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

  <title>Collect Data | ConnectYou</title>

</head>

<body>

  <div class="collect-data-page">
    <div class="collect-data-wizard">
      <div class="wizard_left_aside">
        <div class="wizard_navigation_steps">

          <div class="form_wizard_step active" href="#step-1">
            <div class="step_icon">
              <i class="fad fa-user-cog"></i>
            </div>
            <div class="step_label">
              <div class="label_title">Account Settings</div>
              <div class="label_desc">Setup Your Account Details</div>
            </div>
          </div>
          <div class="form_wizard_step" href="#step-2">
            <div class="step_icon">
              <i class="fad fa-dice-d20"></i>
            </div>
            <div class="step_label">
              <div class="label_title">Interests</div>
              <div class="label_desc">Add Your Personal Interests</div>
            </div>
          </div>
          <div class="form_wizard_step" href="#step-3">
            <div class="step_icon">
              <i class="fad fa-user-astronaut"></i>
            </div>
            <div class="step_label">
              <div class="label_title">Choose Avatar</div>
              <div class="label_desc">Select an avatar for your profile</div>
            </div>
          </div>
          <!-- <div class="form_wizard_step" href="#step-4">
            <div class="step_icon">
              <i class="fad fa-star"></i>
            </div>
            <div class="step_label">
              <div class="label_title">Complete</div>
              <div class="label_desc">Review and Submit</div>
            </div>
          </div> -->

        </div>
      </div>
      <div class="wizard_right_content">
        <form id="form-user-info" name="form-user-info" onsubmit="return false;">
          <!-- WIZARD STEP 1  -->
          <div class="wizard_form_control show" id="step-1">
            <div class="form-heading">Enter Your Account Details</div>
            <div class="form-group">
              <label class="form-label">First Name</label>
              <input type="text" class="form-input" id="firstname" name="firstname" placeholder="First Name">
              <label class="form-under-label">Please enter your first name.</label>
            </div>
            <div class="form-group">
              <label class="form-label">Last Name</label>
              <input type="text" class="form-input" id="lastname" name="lastname" placeholder="Last Name">
              <label class="form-under-label">Please enter your last name.</label>
            </div>

            <div class="row-col">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-label">Age</label>
                  <input type="text" class="form-input" name="age" id="age" placeholder="Your age" autocomplete="off">
                  <label class="form-under-label">Please enter your age.</label>
                </div>
              </div>
            </div>

            <div class="row-col">
              <!-- <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-label">Gender</label>
                  <select class="form-input" name="gender">
                    <option disabled selected value>  Speficy your gender  </option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                    <option>Prefer not to say</option>
                  </select>
                  <label class="form-under-label">Please speficy your gender.</label>
                </div>
              </div> -->
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-label">University name</label>
                  <input id="uni" type="text" class="form-input" id="university" name="university" placeholder="Ex: University of Bath" autocomplete="off">
                  <label class="form-under-label">Please select your university.</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-label">Department</label>
                  <input type="text" class="form-input" name="department" id="department" placeholder="Ex: Computer Science" autocomplete="off">
                  <label class="form-under-label">Please speficy your department.</label>
                </div>
              </div>
            </div>


            <div class="form_group">
              <input type="checkbox" name="consent_form" id="consent-form" class="hidden_checkbox">
              <label for="consent-form" class="checkbox_label">I agree with the <a data-target="consent_form_modal">Consent Form</a></label>
            </div>

          </div>
            <!-- WIZARD STEP 2  -->
          <div class="wizard_form_control" id="step-2">
            <div class="form-heading">Your Interests</div>
            <div class="form-group">
              <label class="form-label">Select your interests</label>
              <input type="text" class="form-input" id="taginput">
              <label class="form-under-label">Please select at least 4 categories</label>
            </div>
          </div>

          <!-- WIZARD STEP 3  -->
          <div class="wizard_form_control" id="step-3">
            <div class="form-heading">Upload Profile Picture </div>
            <div class="select_profile_avatar">
              <img class="avatar_selection" src="dist/img/default.png"></img>
            </div>
            <div class="choose_btn_container">
              <button class="choose_avatar">Upload Picture
                <input type="file" id="upload_image" name="upload_image" accept="image/x-png,image/jpeg">
              </button>
              <button class="upload_pic_button_hidden" data-target="select-profile-picture"></button>
            </div>
            <p><strong>Uploading photos are optional.</strong> Your photo will appear exclusively on the chatting system, and will not be used in the matching process.</p>
            <p style="font-weight: 400;font-style: italic;">"We want to match people based on common interest not on prior conviction"</p>
          </div>

          <div class="form-actions">
            <button class="next_step">Next Step</button>
            <button class="submit">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <div class="modal" id="select-profile-picture">
    <div class="modal_container">
      <div class="modal_content">
        <div class="modal_header">
          <h4>Profile Picture</h4>
          <button type="button" aria-label="Close" class="close_modal">
            <span class="icon_bar"></span>
            <span class="icon_bar"></span>
          </button>
        </div>
        <div class="modal_body">
          <p class="avatar_change_text">Crop your profile picture according to your preferences.</p>
          <div class="crop_image_container">
            <form>
              <div id="image_demo" style="width:440px;height:auto;margin-top:30px"></div>
              <div class="btn_cont">
                <button class="submit_cropCont_image crop_image">Crop Image</button>
              </div>
            </form>
          </div>
        </div>
        <div class="modal_footer">
          <button class="close_modal_btn">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal terms_cond_container" id="consent_form_modal">
    <div class="modal_container">
      <div class="modal_content">
        <div class="modal_header">
          <h4>Consent Form</h4>
          <button type="button" aria-label="Close" class="close_modal">
            <span class="icon_bar"></span>
            <span class="icon_bar"></span>
          </button>
        </div>
        <div class="modal_body">
          <p>Welcome to our website and thank you for your interest to become a part of the social movement of ConnectYou! ConnectYou (“us” or “we”) and our services are developed available through the mobile-friendly
          website (the “Website” or the “Site”). Unless it is not specified, all references to the Service or Services involve the set of services accessible through the ConnectYou Website, as well as any other software or
          social media that ConnectYou offers that allows to get the Services. The term “you” indicates the user of the Service. The further Terms of Service are a form of legal binding contract between you and ConnectYou as
          with the user of the Services. ConnectYou is owned and operated by ConnectYou Company, a British Limited Liability Company.
          </p>
          <p>
          Please read the following Terms of Service ("Terms") carefully before accessing or using the Service. Each time you access or use the Service, you, or if you are acting on behalf of a third party or your employer, such
          third party or employer, agree to be bound by these Terms whether or not you register with us. If you do not agree to be bound by all of these Terms, you may not access or use the Service. ConnectYou may change this Agreement
          at any time by posting an updated Terms of Service on this site. If any amendment to these Terms is unacceptable to you, you shall cease using this Site. If you continue using the Site, you will be conclusively deemed to have accepted the changes.
          </p>
          <p>
          <strong>Hey, the newcomer!</strong> <br>
          By joining ConnectYou, you agree with the following simple rules:
          </p>

          <h5 class="sub_title">Personal information</h5>
          <ul class="list_of_points">
            <li>I confirm that I am a current student. </li>
            <li>
            I agree that I am at least 16 years old. <strong>NOTE: Users who are at least 16 and have entered a university, may use this Site and Services of this Site by notifying parents or other people accountable for them. Therefore, we are not responsible
            for any negative outcome experienced if the user younger than 18 did not notify in any form their parents. Users who are 18 years-old are allowed to use the Site without any notification. </strong>
            </li>
            <li>
            I agree to provide accurate information about which University/College I attend.
            </li>
            <li>
            I agree to be honest about my interests as it can help match me with other like-minded people.
            </li>
            <li>
            I understand that the personal information I provide on my profile page will be seen by other users.
            </li>
            <li>
            I understand that I have a right to provide only the information I feel comfortable providing.
            </li>
          </ul>


          <h5 class="sub_title">Tolerance and respect on ConnectYou</h5>
          <ul class="list_of_points">
            <li>
            I agree to show respect to people of different cultural and social backgrounds and abstain from any potentially prejudiced and discriminatory behaviors
            </li>
            <li>
            I agree to treat everyone equally regardless of their gender, age, sexual orientation, religion, race, socio-economic status, political beliefs, or other unique characteristics.
            </li>
            <li>
            I agree to report any discriminatory behaviours I may encounter on ConnectYou.
            </li>
          </ul>


          <h5 class="sub_title">Confidentiality and Security</h5>
          <ul class="list_of_points">
            <li>
            I understand that my communication with other users will be confidential and be securely stored on an encrypted database.
            </li>
            <li>
            I understand that the only way ConnectYou can gain access to my personal chats is if I report the other user as potentially violating regulations stated here.
            </li>
            <li>
            I confirm that I will not share personal information about other users and treat every piece of information they share with me with respect and understanding.
            </li>
            <li>
            I agree to report any potentially harmful for student community cases I may obtain knowledge about such as already committed or planned discriminatory/ aggressive/ violent/ suicidal/
            socially inappropriate actions.
            </li>
          </ul>


          <h5 class="sub_title">How to start using ConnectYou</h5>
          <ul class="list_of_points">
            <li>
            Register and complete verification via your student email.
            </li>
            <li>
            Read the regulations of ConnectYou and give consent if you agree to proceed further (please contact one of the team members if you have any questions, see ConnectUs).
            </li>
            <li>
            Choose your main interests and set up your profile.
            </li>
            <li>
            Start searching people in ConnectYou section and get matched with like-minded individuals.
            </li>
            <li>
            Continue onto Online Connect to chat with people you have been matched with.
            </li>
            <li>
            Find like-minded people in ActiveConnect and meet with them in person.
            </li>
          </ul>




        </div>
        <div class="modal_footer">
          <div class="close_modal_btn">Close</div>
        </div>
      </div>
    </div>
  </div>

  <?=  '<script>var user_id = '.$_SESSION['user_id'].'</script>';?> <!-- only for user id -->

<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/main.js"></script>
<script src="dist/js/croppie.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="dist/js/tagsinput.js"></script>
<script>

$("#taginput").taginput({
  'autocomplete':{},
  'ajaxUrl': 'include/get-autocomplete-components.php?action=get-interests'

});

$("#uni").autocomplete({
  source: function(request, response) {
    $.ajax({
      url: 'include/get-autocomplete-components.php?action=get-university',
      method: 'POST',
      data: {term: request.term},
      dataType: 'json',
      success: function(data){
        response(data);
      },
      error: function(err){
        console.log(err.responseText);
      }
    })
  },
  minLength: 2,
  select: function(event, ui){
    $("#uni").val(ui.item.value);
  },
  change: function(event, ui){
    $(this).val((ui.item ? ui.item.value : "")); //allow only the selected ones!
  }
}).data("ui-autocomplete")._renderItem = function(ul, item){
    return $('<li class="ui-autocomplete-row"></li>')
      .data("item.autocomplete", item)
      .append(item.label)
      .appendTo(ul);
};

/* make sure nothing weird happens ... Like avatar button triggering */
$(".form-input").on("keypress", function(e){
  if(event.which==13){
    e.preventDefault();
    return false;
  }
});

</script>

<script>



  var profile_pic_image = ""; // Image data:

  $(document).ready(function(){

    $image_crop = $('#image_demo').croppie({
      enableExif: true,
      viewport: {
        width:200,
        height:200,
        type:'square' //square
      },
      boundary:{
        width:300,
        height:300
      }
    });

    $('#upload_image').on('change', function(e){

      var reader = new FileReader();
      reader.onload = function (event) {
        $image_crop.croppie('bind', {
          url: event.target.result
        }).then(function(){
          //console.log('jQuery bind complete');

        });
    }

    // Check if cancel is clicked
    if(!($("#upload_image").get(0).files.length == 0)){
      reader.readAsDataURL(this.files[0]);
      $('.upload_pic_button_hidden').trigger('click');
    }

  });

  $(".submit_cropCont_image.crop_image").on("click",function(event){
    event.preventDefault();
    $image_crop.croppie('result', {
      type: 'canvas',
      size: 'original',
      format: 'png',
      quality: 1
    }).then(function(response){
      profile_pic_image = response;
      $(".avatar_selection").attr("src",response);
      $(".close_modal").trigger('click');
    });
  });

  $("#age").on("keydown", function(e){
    if($.inArray(e.keyCode, [8, 9, 13, 27, 46, 190]) !== -1 ||
          (e.keyCode == 65 && e.ctrlKey === true) ||
          (e.keyCode >= 35 && e.keyCode <= 39)) {
            return;
    }
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }

  });



  });
  </script>

</body>

</html>
