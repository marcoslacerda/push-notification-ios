<?php

// My device token here (without spaces):
$deviceToken = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

// My private key's passphrase here:
$passphrase = 'xxxxxxxxxxx';

// My alert message here:
$message = 'Teste de Push Notification';

//badge
$badge = 1;

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', 'certificate.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
$fp = stream_socket_client(
    'ssl://gateway.sandbox.push.apple.com:2195', $err,
    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
$body['aps'] = array(
    'alert' => $message,
    'badge' => $badge,
    'data' => '{aqui virá o json que iremos definir}'
);

// Encode the payload as JSON
$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result) {
	echo '<br/>';
    echo 'Error, notification not sent' . PHP_EOL;
}
else {
	echo '<br/>';
    echo 'Notification sent!' . PHP_EOL;
}

// Close the connection to the server
fclose($fp);

?>