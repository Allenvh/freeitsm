<?php
// Loopback-only IMAP protocol fixture. Never prints commands or credentials.
$config=json_decode(fgets(STDIN),true);
$server=stream_socket_server('tcp://127.0.0.1:0',$errno,$error);
echo stream_socket_get_name($server,false)."\n";flush();
$client=stream_socket_accept($server,15);if(!$client)exit(2);
stream_set_timeout($client,15);fwrite($client,"* OK IMAP4rev1 fixture ready\r\n");$authenticated=false;
while(($line=fgets($client))!==false){
 $parts=explode(' ',trim($line),3);$tag=$parts[0];$command=strtoupper($parts[1]??'');$args=$parts[2]??'';
 if($command==='CAPABILITY')fwrite($client,"* CAPABILITY IMAP4rev1\r\n$tag OK capability\r\n");
 elseif($command==='LOGIN'){
  $values=str_getcsv($args,' ','"','\\');$authenticated=($values[0]??'')===$config['username']&&($values[1]??'')===$config['password'];
  fwrite($client,$authenticated?"$tag OK login\r\n":"$tag NO invalid credentials\r\n");
 }elseif($command==='LOGOUT'){fwrite($client,"* BYE closing\r\n$tag OK logout\r\n");break;}
 elseif(!$authenticated)fwrite($client,"$tag NO authenticate first\r\n");
 elseif($command==='LIST')fwrite($client,"* LIST (\\HasNoChildren) \"/\" \"INBOX\"\r\n$tag OK list\r\n");
 elseif($command==='EXAMINE'||$command==='SELECT')fwrite($client,"* FLAGS (\\Seen)\r\n* 1 EXISTS\r\n* 0 RECENT\r\n* OK [UIDVALIDITY 1] valid\r\n$tag OK [READ-ONLY] examined\r\n");
 elseif($command==='STATUS')fwrite($client,"* STATUS INBOX (MESSAGES 1 UNSEEN 1 UIDNEXT 2 UIDVALIDITY 1)\r\n$tag OK status\r\n");
 else fwrite($client,"$tag OK done\r\n");
}
fclose($client);fclose($server);
