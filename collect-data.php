<!-- DOCTYPE html is the real devil. better not include! -->

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
          <div class="form_wizard_step" href="#step-4">
            <div class="step_icon">
              <i class="fad fa-star"></i>
            </div>
            <div class="step_label">
              <div class="label_title">Complete</div>
              <div class="label_desc">Review and Submit</div>
            </div>
          </div>

        </div>
      </div>
      <div class="wizard_right_content">
        <form id="form-user-info" name="form-user-info" onsubmit="return false;">
          <!-- WIZARD STEP 1  -->
          <div class="wizard_form_control show" id="step-1">
            <div class="form-heading">Enter Your Account Details</div>
            <div class="form-group">
              <label class="form-label">First Name</label>
              <input type="text" class="form-input" name="firstname" placeholder="First Name">
              <label class="form-under-label">Please enter your first name.</label>
            </div>
            <div class="form-group">
              <label class="form-label">Last Name</label>
              <input type="text" class="form-input" name="lastname" placeholder="Last Name">
              <label class="form-under-label">Please enter your last name.</label>
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
                  <input id="uni" type="text" class="form-input" name="university" placeholder="Ex: University of Bath" autocomplete="off">
                  <label class="form-under-label">Please select your university.</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-label">Department</label>
                  <input type="text" class="form-input" name="department" placeholder="Ex: Computer Science" autocomplete="off">
                  <label class="form-under-label">Please speficy your department.</label>
                </div>
              </div>
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

          <!-- WIZARD STEP 2  -->
          <div class="wizard_form_control" id="step-3">
            <div class="form-heading">Choose a Profile Avatar</div>
            <div class="select_profile_avatar">
              <img class="avatar_selection" src="dist/img/users/avatar_5.png"></img>
            </div>
            <div class="choose_btn_container">
              <button class="choose_avatar">Select Avatar</button>
            </div>
          </div>

          <div class="form-actions">
            <button class="next_step">Next Step</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <div class="modal-main" id="select-avatar">
    <div class="modal-container">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Select Avatar</h4>
          <button type="button" aria-label="Close" class="close-modal">
            <span class="icon_bar"></span>
            <span class="icon_bar"></span>
          </button>
        </div>
        <div class="modal-body">
          <p class="avatar_change_text">*You can change your profile avatar on the settings menu anytime.</p>
          <div class="avatar_select_container">
            <div class="single_avatar_box">
              <div class="inner_box">
                <img src="dist/img/users/avatar_1.png">
              </div>
            </div>
            <div class="single_avatar_box selected">
              <div class="inner_box">
                <img src="dist/img/users/avatar_2.png">
              </div>
            </div>
            <div class="single_avatar_box">
              <div class="inner_box">
                <img src="dist/img/users/avatar_3.png">
              </div>
            </div>
            <div class="single_avatar_box">
              <div class="inner_box">
                <img src="dist/img/users/avatar_4.png">
              </div>
            </div>
            <div class="single_avatar_box">
              <div class="inner_box">
                <img src="dist/img/users/avatar_5.png">
              </div>
            </div>
            <div class="single_avatar_box">
              <div class="inner_box">
                <img src="dist/img/users/avatar_6.png">
              </div>
            </div>
            <div class="single_avatar_box">
              <div class="inner_box">
                <img src="dist/img/users/avatar_7.png">
              </div>
            </div>
            <div class="single_avatar_box">
              <div class="inner_box">
                <img src="dist/img/users/avatar_8.png">
              </div>
            </div>
            <div class="single_avatar_box">
              <div class="inner_box">
                <img src="dist/img/users/avatar_9.png">
              </div>
            </div>
            <div class="single_avatar_box">
              <div class="inner_box">
                <img src="dist/img/users/avatar_10.png">
              </div>
            </div>
            <div class="single_avatar_box">
              <div class="inner_box">
                <img src="dist/img/users/avatar_11.png">
              </div>
            </div>
            <div class="single_avatar_box">
              <div class="inner_box">
                <img src="dist/img/users/avatar_12.png">
              </div>
            </div>
          </div>
          <button class="select_avatar_btn">Select Avatar</button>
        </div>
        <div class="modal-footer">
          <button class="close-modal">Close</button>
        </div>
      </div>
    </div>
  </div>

<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/main.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="dist/js/tagsinput.js"></script>
<script>

$("#taginput").taginput({
  'autocomplete':{
						source: [
							'apple',
							'banana',
							'orange',
							'pizza'
						]
					}
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
/* make sure nothing weird happens ... Like avatar button triggering hmmm*/
$(".form-input").on("keypress", function(e){
  if(event.which==13){
    e.preventDefault();
    return false;
  }
});
$(".close-modal").on("click",function(){
  $("#select-avatar").removeClass("open");

  $("#select-avatar").one("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd",
  function(e){
    $("#select-avatar").css("display","none");
    $("body").removeClass("locked_body");
    $(this).off('webkitTransitionEnd moztransitionend transitionend oTransitionEnd');
  });
});
$(".choose_avatar").on("click",function(e){
  $("#select-avatar").css("display", "block");

  window.setTimeout( function() {
    $("#select-avatar").addClass("open");
  }, 100);
  $("body").addClass("locked_body");
});





</script>
</body>

</html>
