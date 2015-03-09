<?php
$LOG_FILE = dirname(dirname(dirname(__DIR__))).'/hook.log';
define('PAYLOAD', 'payload');
define('PAYLOAD_REF', 'ref');
define('REF', 'refs/heads/develop');
define('ACCESSKEY', 'X-Hub-Signature');
define('ALG', 'sha1');

// editable
define('SECRETKEY', 'framelunchssucproject');
define('PROJECT_ROOT', '/var/www/lamp/ssuc');
define('COMMAND', 'cd ' . PROJECT_ROOT . '; git pull origin develop:local;');

// log content
ini_set('date.timezone','Asia/Tokyo');
$NOW = (new DateTime('now'))->format('Y/m/d H:i:s');
$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

// auth
$access_key = getallheaders()[ACCESSKEY];
$postdata = file_get_contents("php://input");

echo '02';
echo 'hash' . "\n";
echo hash_hmac(ALG, $postdata,SECRETKEY);

if (isset($_POST[PAYLOAD])) {
    $payload = json_decode($_POST[PAYLOAD], true);
    if ($payload[PAYLOAD_REF] === REF) {
        exec(COMMAND);
        file_put_contents($LOG_FILE, $NOW.' '.$REMOTE_ADDR." git pulled: ".$payload['head_commit']['message']."\n", FILE_APPEND|LOCK_EX);
    }
} else {
    file_put_contents($LOG_FILE, $NOW." invalid access: ".$REMOTE_ADDR."\n", FILE_APPEND|LOCK_EX);
}
