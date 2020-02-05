<?php

require __DIR__.'/init_functions.php';
/*//////////////////////////////////////
          REGISTER USER PRIMARY
/////////////////////////////////////*/

if(isset($_GET['action']) && $_GET['action'] == "insert-info" && is_ajax()
    && isset($_POST['user_id'])
    && isset($_POST['firstname'])
    && isset($_POST['lastname'])
    && isset($_POST['age'])
    && isset($_POST['university'])
    && isset($_POST['department'])
    && isset($_POST['interests'])
    && !empty($_POST['user_id'])
    && !empty($_POST['firstname'])
		&& !empty($_POST['lastname'])
    && !empty($_POST['age'])
    && !empty($_POST['department'])
    && !empty($_POST['interests'])
  	&& !empty($_POST['university'])){

      $user_id = db_escapeString($_POST['user_id']);
      $firstname = db_escapeString($_POST['firstname']);
      $lastname = db_escapeString($_POST['lastname']);
      $age = db_escapeString($_POST['age']);
      $university = db_escapeString($_POST['university']);
      $department = db_escapeString($_POST['department']);
      $interests = db_escapeString($_POST['interests']);

      $image = $_POST['profile_picture'];

      $status = 1;
      $error = '';
    
      if($_SESSION['user_id'] == $user_id){
        
        $insert_info = db_query("INSERT INTO user_info (userID, firstname, lastname, age, university, department, interests)
            VALUES ('$user_id', '$firstname', '$lastname', '$age', '$university', '$department', '$interests')");

        if($image == null || $image == "undefined" || $image == "" || empty($image)){
        }else{
          $image_arr_1 = explode(";", $image);
          $image_arr_2 = explode(",",$image_arr_1[1]);
          $data = base64_decode($image_arr_2[1]);
          $image_name = uniqid() . ".png";
          //Default place to store profile images
          $destination = $_SERVER['DOCUMENT_ROOT']."/chat/include/img/" . $image_name;
          if(file_put_contents($destination, $data)){
            db_query("UPDATE members SET profile_pic = '$image_name' WHERE ID = '$user_id'");
          }else{
            $status = 0;
            $error = "An unknown error has occurred!";
          }
          
        }
      }else{
        $status = 0;
        $error = "An unknown error has occurred!";
      }

      echo json_encode(array(
        'status' => $status,
        'error' => $error
      ));

    }

?>
