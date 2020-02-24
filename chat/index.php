<?php
require_once './include/chat.php';

// if(!isset($_SESSION['user_id'])){
// 	header("Location: /login.php");
// }else if(!is_user_info_registered()){
//   header('Location: /collect-data.php');
// }
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="icon" href="../dist/img/logos/logo48.png" type="image/x-icon"/>
		<title>Chat | ConnectYou</title>
	</head>
	<body>
		<?php chat_application("full_page"); ?>
	</body>
</html>
