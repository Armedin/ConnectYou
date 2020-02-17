<?php
include_once("init_functions.php");

if(isset($_GET['action']) && $_GET['action']=="get-user-list" && is_ajax()
  && isset($_POST['user_id']) && !empty($_POST['user_id'])) {

    $status = 1;
    $error = "";
    $output = "";

    $user_id = db_escapeString($_POST['user_id']);
    if($_SESSION['user_id'] == $user_id){
      $sql = db_query("SELECT * FROM members INNER JOIN user_info ON members.ID = user_info.userID WHERE userID <> '$user_id' ORDER BY RAND() LIMIT 3");
      if(mysqli_num_rows($sql)>0){

        $output = '<div class="demo_user_list_column">
                    <div class="inner_wrapper">
                      <div class="row-col">
        ';

        while($row = mysqli_fetch_assoc($sql)){
          $output = $output .
          '<div class="col-lg-4">
            <div class="user_column_card">
              <div class="card_header"></div>
              <div class="card_body">
                <div class="user_header">
                  <div class="user_info">
                    <span class="user_initials">'.$row['firstname'].' '.$row['lastname'].'</span>
                  </div>
                </div>
                <div class="user_body">
                  <div class="user_about">

                  </div>
                  <ul class="user_details_list">
                    <li>
                      <span class="label">University: </span>
                      <span class="detail">'.$row['university'].'</span>
                    </li>
                    ';
                    $interests = user_interests($row['userID']);
                    $count = 0;
                    foreach($interests as $interest){
                      $output = $output . '
                      <li>
                      <span class="label">Interest: </span>
                      <span class="detail">'.$interest.'</span>
                      </li>
                      ';
                      $count++;
                    }
                    $output = $output . '
                  </ul>
                </div>
                <div class="user_footer">
                  <button class="connect_btn blue_theme" value="'.$row['userID'].'">Online Connect</button>
                </div>
              </div>
            </div></div>
          ';
        }
        $output = $output. '</div></div></div>';
      }else{
        $status = 0;
        $error = "No users found. Try again later!";
      }
    }else{
      $status = 0;
      $error = "An unknown error has occurred!";
    }

  echo json_encode(array(
    'status' => $status,
    'error' => $error,
    'output' => $output
  ));

}



?>
