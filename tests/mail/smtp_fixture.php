<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
// Local protocol fixture only. Credentials are ephemeral and supplied by the runner.
$config = json_decode(file_get_contents($argv[1]), true);
$server = stream_socket_server('tcp://127.0.0.1:0', $errno, $error);
file_put_contents($argv[2], stream_socket_get_name($server, false));
$client = stream_socket_accept($server, 10); if (!$client) exit(2);
stream_set_timeout($client, 10);
fwrite($client, "220 fixture ready\r\n");
$authenticated = !$config['auth']; $authStep = 0; $data = false; $received = false;
while (($line = fgets($client)) !== false) {
    $line = rtrim($line,"\r\n");
    if ($data) { if ($line === '.') { $data=false; $received=true; fwrite($client,"250 queued\r\n"); } continue; }
    if ($authStep === 1) { if (base64_decode($line) !== $config['username']) exit(3); $authStep=2; fwrite($client,"334 password\r\n"); continue; }
    if ($authStep === 2) { $authenticated = base64_decode($line) === $config['password']; $authStep=0; fwrite($client,$authenticated?"235 authenticated\r\n":"535 rejected\r\n"); continue; }
    if (str_starts_with($line,'EHLO')) fwrite($client,"250-fixture\r\n250 AUTH LOGIN\r\n");
    elseif ($line==='AUTH LOGIN') { $authStep=1; fwrite($client,"334 username\r\n"); }
    elseif ($line==='QUIT') { fwrite($client,"221 bye\r\n"); break; }
    elseif (!$authenticated) fwrite($client,"530 authentication required\r\n");
    elseif ($line==='DATA') { $data=true; fwrite($client,"354 send data\r\n"); }
    else fwrite($client,"250 accepted\r\n");
}
fclose($client);fclose($server);
file_put_contents($argv[3],json_encode(['authenticated'=>$authenticated,'received'=>$received]));
