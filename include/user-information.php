<?php

require __DIR__.'/init_functions.php';
/*//////////////////////////////////////
          REGISTER USER PRIMARY
/////////////////////////////////////*/

if(isset($_GET['action']) && $_GET['action'] == "insert-info" && is_ajax()
    && isset($_POST['username'])
    && isset($_POST['email'])
    && isset($_POST['password'])
		&& isset($_POST['re_password'])
    && !empty($_POST['username'])
		&& !empty($_POST['email'])
    && !empty($_POST['password'])
  	&& !empty($_POST['re_password'])){

      $username = db_escape($_POST['username']);
      $email = db_escape($_POST['email']);
      $password = db_escape($_POST['password']);
      $re_password = db_escape($_POST['re-password']);

      if(!empty($username)){
        if(!empty($email)){
          if(!empty($password) || !empty($re_password)){

          }else{

          }
        }else{

        }
      }else{

      }

?>
