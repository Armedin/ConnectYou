<?php
include_once("init_functions.php");

if(isset($_GET['action']) && $_GET['action']="get-univerity" && is_ajax()
  && isset($_POST['term']) && !empty($_POST['term']) ){

  $term = db_escapeString($_POST['term']);
  $output = array();
  $sql = db_query("SELECT * FROM university WHERE name LIKE '%$term%' ");

  if(mysqli_num_rows($sql)>0){
    while($row = mysqli_fetch_assoc($sql)){
      $data['value'] = $row['name'];
      $data['label'] = '<a href="#" class="uni-autocomplete-item">
        <img class="uni-logo" src="dist/img/universities/'.$row['logo'].'">
        <span class="uni-name">'.$row['name'].'</span>
      </a>';
      array_push($output, $data);
    }
  }

  echo json_encode($output);
}



?>
