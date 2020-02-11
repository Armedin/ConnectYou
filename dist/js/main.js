/********

    Snackbar for mainly printing out any errors

the codes typeof define === 'function' and define.amd
are used to check the presence of require.js which in a
javascript dependency management library.
amd stands for asynchronous module definition.

Factories are simply function that are used instead of classes.
*******/

(function(root, factory) {
    'use strict';

    if (typeof define === 'function' && define.amd) {
        define([], function() {
            return (root.Snackbar = factory());
        });
    } else if (typeof module === 'object' && module.exports) {
        module.exports = root.Snackbar = factory();
    } else {
        root.Snackbar = factory();
    }
})
(this, function() {

  var Snackbar = {};

  Snackbar.current = null;

  var $defaults = {
    def_text : "Snackbar Default Text",
    textColor: "#fff",
    background_color: "#323232",
    duration : 1500,
    showAction : true,
    position : "top-left",
    actionText_color : "#eeff41",
    actionText : "Dismiss",
    onClick: function(element) {
        element.css("opacity","0");
    },
    onClose: function(element) {}
  };


  Snackbar.showToast = function($options){
    var options = $.extend({}, $defaults, $options);

    if (Snackbar.current) {

        Snackbar.current.css("opacity","0");
        setTimeout(
            function() {
                if ($("body").has($(this)))
                  $(this).remove();

            }.bind(Snackbar.current),
            500
        );
    }

    Snackbar.toast = $("<div></div>");
    Snackbar.toast.attr('id','snackbar_cont');

    var innerSnack = $("<div></div>").addClass("snackbar").text(options.def_text);
    Snackbar.toast.append(innerSnack);


    if(options.showAction){
      var actionBtn = $("<button></button>");
      actionBtn.addClass("actionBtn");
      actionBtn.text(options.actionText);
      actionBtn.css("color",options.actionText_color);
      actionBtn.on("click",function(){
        options.onClick(Snackbar.toast);
      });
      innerSnack.append(actionBtn);
    }

    if(options.duration){
      setTimeout(function(){
         if(Snackbar.current == this){
           Snackbar.current.css("opacity","0");
           Snackbar.current.css("top","-80px");
           Snackbar.current.css("bottom","-80px");
         }
      }.bind(Snackbar.toast),options.duration
    );
  }

    Snackbar.toast.on("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend",function(event){

      if (event.originalEvent.propertyName === 'opacity' && $(this).css("opacity") == '0') {
        if (typeof(options.onClose) === 'function')
            options.onClose($(this));

          $(this).remove();
          if (Snackbar.current === this) {
              Snackbar.current = null;
          }
      }
    }.bind(Snackbar.toast)
  );

    Snackbar.current = Snackbar.toast;

    $("body").append(Snackbar.toast);
    //Just to make sure transition will work for the first time as well !!!
    var $bottom = window.getComputedStyle(document.getElementById('snackbar_cont')).bottom;
    var $top = window.getComputedStyle(document.getElementById('snackbar_cont')).top;
    Snackbar.toast.css("opacity","1");
    Snackbar.toast.attr('id','snackbar_cont');
    Snackbar.toast.addClass("show").addClass(options.position);
  }

  Snackbar.close = function() {
      if (Snackbar.current) {
          Snackbar.current.css("opacity","0");
      }
  };

    return Snackbar;
});

//Snackbar.showToast({def_text:"An error has occured!"});





// Check if the Email is Valid
function isValidEmailAddress( emailAddress ) {
	var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
	return pattern.test( emailAddress );
}


//////////////////////////////////////////////////////////////////
///////////// GETTING USER INPUT (WIZARD FORM)////////////////////
/////////////////////////////////////////////////////////////////

      function isEmptyInput(text_input){
        if(text_input.is("input")){
          return text_input.val().replace(/^\s+|\s+$/g, "").length == 0 ? true : false;
        }
        if(text_input.is("select")){
          return text_input.find("option:not(:disabled)").is(":selected") ? false : true;
        }
      }


      $(".form_wizard_step").on("click",function(e){

        var currentInputs = $(".wizard_form_control.show").find(".form-input, .tag-hiddendata");
        var hasErrors = false;
        $(".form-group").removeClass("has-error");
        for(var i=0;i<currentInputs.length;i++){
          if(isEmptyInput($(currentInputs[i]))){
            hasErrors = true;
            $(currentInputs[i]).closest(".form-group").addClass("has-error");
          }
        }

        if(!hasErrors){
          e.preventDefault();
          var target = $($(this).attr('href'));
          $(".form_wizard_step").removeClass("active");
          $(this).addClass("active");

          $(".wizard_form_control").removeClass("show");
          target.addClass("show");
          target.find("input:eq(0)").focus();

          if($(this).attr('href') == "#step-3"){
            $(".next_step").css("display", "none");
            $(".submit").css("display", "block");
          }

        }



      });

      $(".next_step").on("click", function(e){
        var currentInputs = $(".wizard_form_control.show").find(".form-input, .taginput");
        var hasErrors = false;
        $(".form-group").removeClass("has-error");
        for(var i=0;i<currentInputs.length;i++){
          if(isEmptyInput($(currentInputs[i]))){
            hasErrors = true;
            $(currentInputs[i]).closest(".form-group").addClass("has-error");
          }
        }

        if(!hasErrors){
          var nextStepWizard = $(".wizard_navigation_steps .form_wizard_step.active").next();
          var nextStepForm = $(".wizard_form_control.show").next();

          $(".form_wizard_step").removeClass("active");
          nextStepWizard.addClass("active");
          $(".wizard_form_control").removeClass("show");
          nextStepForm.addClass("show");

        }

      });

      $(".submit").on("click", function(){
        event.preventDefault();

      //Create a FormData to append everything

      var form_data = new FormData();

      var firstname = $("#firstname").val();
      var lastname = $("#lastname").val();
      var age = $("#age").val();
      var university = $("#uni").val();
      var department = $("#department").val();
      var interests = $(".tag-hiddendata").val();


      //Append everything in FormData (also user_id and token from the normal js )
      form_data.append("user_id", user_id);
      form_data.append("firstname", firstname);
      form_data.append("lastname", lastname);
      form_data.append("age", age);
      form_data.append("university", university);
      form_data.append("department", department);
      form_data.append("interests", interests);
      form_data.append("userID",user_id);
      form_data.append("profile_picture", profile_pic_image)


      if(firstname.replace(/^\s+|\s+$/g, "").length == 0){
        Snackbar.showToast({def_text:"Please enter your firstname!"});
      }else if(lastname.replace(/^\s+|\s+$/g, "").length == 0){
        Snackbar.showToast({def_text:"Please enter your lastname!"});
      }else if(age.replace(/^\s+|\s+$/g, "").length == 0){
        Snackbar.showToast({def_text:"Please specify your age!"});
      }else if(university.replace(/^\s+|\s+$/g, "").length == 0){
        Snackbar.showToast({def_text:"Please enter your university!"});
      }else if(department.replace(/^\s+|\s+$/g, "").length == 0){
        Snackbar.showToast({def_text:"Please specify your department!"});
      }else if(interests.replace(/^\s+|\s+$/g, "").length == 0){
        Snackbar.showToast({def_text:"Please select your interests!"});
      }
      else{
        $.ajax({
          url : "include/user-information.php?action=insert-info",
          data: form_data,
  			  processData: false,
  			  contentType: false,
          type : "POST",
          dataType : "json",
          success : function(response){
            if(response.status == 0){
              Snackbar.showToast({def_text : response.error});
            }else if(response.status == 1){
              window.location.href="/index.php";
            }else{
              Snackbar.showToast({def_text : "An unknown error has occured !"});
            }
          },
          error : function(xhr, ajaxOptions, thrownError){
            Snackbar.showToast({def_text : xhr.responseText});
          }
        })
      }
      });

//////////////////////////////////////////////////////////////////
///////////////////////  BUTTONS ///////////////////////////
/////////////////////////////////////////////////////////////////

$(".mobile_nav").first().on("click", function (event) {
  $(".dropdown_list").toggleClass("opened");
});

$("a[data-target], button[data-target]").on("click",function(e){
  e.preventDefault();
  var modal = $("#"+$(this).attr("data-target"));
  modal.css("display", "block");
  window.setTimeout( function() {
    modal.addClass("open");
  }, 100);
  $("body").removeClass("locked_body");
});
$(".close_modal, .close_modal_btn").on("click",function(){
$(".modal").removeClass("open");

$(".modal").one("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd",
function(e){
  $(".modal").css("display","none");
  $("body").removeClass("locked_body");
  $(this).off('webkitTransitionEnd moztransitionend transitionend oTransitionEnd');
});
});


//////////////////////////////////////////////////////////////////
/////////////////////// AJAX REGISTER ///////////////////////////
/////////////////////////////////////////////////////////////////

      $("#register").on("click",function(event){
        event.preventDefault();

        var username = $("#register-username").val();
        var email = $("#register-email").val();
        var password = $("#register-password").val();
        var repassword = $("#register-repassword").val();
        var checkbox = $("#terms-conditions");

        if(username.replace(/^\s+|\s+$/g, "").length == 0){
          $("#register-username").addClass("invalid");
          Snackbar.showToast({def_text:"Please enter your username!"});
        }else if(username.replace(/^\s+|\s+$/g, "").length<6){
          $("#register-username").addClass("invalid");
          Snackbar.showToast({def_text:"Your username must be at least 6 characters long"});
        }else if(email.replace(/^\s+|\s+$/g, "").length == 0){
          $("#register-email").addClass("invalid");
          Snackbar.showToast({def_text:"Please enter your email address"});
        }else if(!isValidEmailAddress(email)){
          $("#register-email").addClass("invalid");
          Snackbar.showToast({def_text:"Invalid email address"});
        }else if(password.replace(/^\s+|\s+$/g, "").length == 0){
          $("#register-password").addClass("invalid");
          $("#register-repassword").addClass("invalid");
          Snackbar.showToast({def_text:"Please enter your password!"});
        }else if(password.replace(/^\s+|\s+$/g, "").length<6){
          $("#register-password").addClass("invalid");
          $("#register-repassword").addClass("invalid");
          Snackbar.showToast({def_text:"Your password must be at least 6 characters long!"});
        }else if(repassword.replace(/^\s+|\s+$/g, "").length == 0){
          $("#register-password").addClass("invalid");
          $("#register-repassword").addClass("invalid");
          Snackbar.showToast({def_text:"Please enter your Password (Confirm) !"});
        }else if(password != repassword){
          $("#register-password").addClass("invalid");
          $("#register-repassword").addClass("invalid");
          Snackbar.showToast({def_text:"Your passwords do not match"});
        }else if(!checkbox.is(":checked")){
          Snackbar.showToast({def_text:"You must agree to the terms & conditions !"});
        }else{
          $.ajax({
            type: "POST",
            url: "include/entry.php?action=register",
            data: {username: username, email: email, password: password, repassword: repassword},
            dataType: "json",
            success: function(response){
              if(response.status == 0){
                Snackbar.showToast({def_text:response.error});
              }else if(response.status == 1){
                window.location.href = "collect-data.php";
              }else{
                Snackbar.showToast({def_text:'An unknown error has occured.'});
              }
            },
            error: function(xhr){
              Snackbar.showToast({def_text:xhr.responseText});
            }

          });
      }
    });


//////////////////////////////////////////////////////////////////
/////////////////////// AJAX LOGIN ///////////////////////////
/////////////////////////////////////////////////////////////////

      $("#login").on("click",function(event){
          event.preventDefault();

          var username = $("#login-email").val();
          var password = $("#login-password").val();


          if(username.replace(/^\s+|\s+$/g, "").length == 0){
            Snackbar.showToast({def_text:"Please enter your username or email address"});
          } else if(password.replace(/^\s+|\s+$/g, "").length == 0){
            Snackbar.showToast({def_text:"Please enter your password"});
          }else{

            $.ajax({
              type: "POST",
              url: "include/entry.php?action=login",
              data: {username: username, password: password},
              dataType: "json",
              success: function(response){
                if(response.status == 0){
                  Snackbar.showToast({def_text:response.error});
                }else if(response.status == 1){
                  Snackbar.showToast({def_text:"Correct!"});
                  window.location.href="/";
                }else{
                  Snackbar.showToast({def_text:"An unknown error has occured"});
                }
              },
              error: function(xhr, ajaxOptions, thrownError){
                Snackbar.showToast({def_text:xhr.responseText});
              }
            });

          }

      });
