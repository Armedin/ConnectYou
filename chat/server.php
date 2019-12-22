<?php
//////////////////////////////////////
/////////////// CONFIG ///////////////
/////////////////////////////////////

define('HOST','127.0.0.1'); // Database domain or IP number
define('USER','root'); // Database username
define('PASSWORD',''); // Database password
define('DATABASE','connectyou'); // Database name

// Websocket Port
$port = '8080';
// Create TCP/IP stream socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
// Reuseable port
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

// Bind socket to specified host
socket_bind($socket, 0, $port);

// Listen to port
socket_listen($socket);


// Create & add listning socket to the list
$clients = array($socket);

while (true) {
	// Manage multipal connections
	$changed = $clients;
	// returns the socket resources in $changed array
	socket_select($changed, $null, $null, 0, 10);

	//check for new socket
	if (in_array($socket, $changed)) {
		$socket_new = socket_accept($socket); //accept new socket
		$clients[] = $socket_new; //add socket to client array

		$header = socket_read($socket_new, 1024); //read data sent by the socket
		perform_handshaking($header, $socket_new, HOST, $port); //perform websocket handshake

    /* Simple sending message
		$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' connected'))); //prepare json data
		send_message($response); //notify all users about new connection
    */

		// Make room for new socket
		$found_socket = array_search($socket, $changed);
		unset($changed[$found_socket]);
	}

	// Loop through all connected sockets
	foreach ($changed as $changed_socket) {

		//check for any incoming data
		while(@socket_recv($changed_socket, $buf, 16777216, 0) >= 1)
		{
			$received_text = unmask($buf); //unmask data
			$chat_msg = json_decode($received_text, true); //decode json data

      if(isset($chat_msg)){
        $connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
        if (!$connection) {
            die('Connection Error: '.mysqli_connect_error());
        }
        mysqli_query($connection, "set names 'utf8'");



        $response_text = mask(json_encode(array('type'=>'usermsg', 'message'=>'lolicon')));
        send_message($response_text); //send data
  			//break 2; //exist this loop */

      }


      /* Simple sending message!
      $user_message = $chat_msg['message']; //message text
			//prepare data to be sent to client
			$response_text = mask(json_encode(array('type'=>'usermsg', 'message'=>$user_message)));
			send_message($response_text); //send data
			break 2; //exist this loop */
		}

		$buf = @socket_read($changed_socket, 16777216, PHP_NORMAL_READ);
		if ($buf === false) { // check disconnected client
			// remove client for $clients array
			$found_socket = array_search($changed_socket, $clients);
			unset($clients[$found_socket]);
		}
	}
}

// Close the listening socket
socket_close($socket);

// Send message to client
function send_message($msg)
{
	global $clients;
	foreach($clients as $changed_socket)
	{
		@socket_write($changed_socket,$msg,strlen($msg));
	}
	return true;
}

// Unmask incoming framed message
function unmask($text)
{
    $length = ord($text[1]) & 127;
    if ($length == 126) {
        $masks = substr($text, 4, 4);
        $data = substr($text, 8);
    } elseif ($length == 127) {
        $masks = substr($text, 10, 4);
        $data = substr($text, 14);
    } else {
        $masks = substr($text, 2, 4);
        $data = substr($text, 6);
    }

    $text = '';

    for ($i = 0; $i < strlen($data); $i++) {
        $text .= $data[$i] ^ $masks[$i % 4];
    }

    return $text;
}

// Encode message for transfering to client.
function mask($text)
{
    $b1 = 0x80 | (0x1 & 0x0f);
    $length = strlen($text);

    if ($length <= 125) {
        $header = pack('CC', $b1, $length);
    } elseif ($length > 125 && $length < 65536) {
        $header = pack('CCn', $b1, 126, $length);
    } elseif ($length >= 65536) {
        $header = pack('CCNN', $b1, 127, $length);
    }

    return $header.$text;
}

//handshake new client.
function perform_handshaking($receved_header,$client_conn, $host, $port)
{
	$headers = array();
	$lines = preg_split("/\r\n/", $receved_header);
	foreach($lines as $line)
	{
		$line = chop($line);
		if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
		{
			$headers[$matches[1]] = $matches[2];
		}
	}
	$secKey = $headers['Sec-WebSocket-Key'];
	$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	//hand shaking header
	$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
	"Upgrade: websocket\r\n" .
	"Connection: Upgrade\r\n" .
	"WebSocket-Origin: $host\r\n" .
	"WebSocket-Location: ws://$host:$port/demo/shout.php\r\n".
	"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
	socket_write($client_conn,$upgrade,strlen($upgrade));
}






?>
