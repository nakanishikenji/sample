<?php
$LOG_FILE = dirname(dirname(dirname(__DIR__))).'/hook.log';
define('PAYLOAD', 'payload');
define('PAYLOAD_REF', 'ref');
define('REF', 'refs/heads/develop');

define('PROJECT_ROOT', '/var/www/lamp/sample');
define('COMMAND', 'cd ' . PROJECT_ROOT . '; git pull origin develop:local;');

ini_set('date.timezone','Asia/Tokyo');
$NOW = (new DateTime('now'))->format('Y/m/d H:i:s');


echo 'header';
echo '<pre>';
var_dump(getallheaders());
echo '</pre>';

if (isset($_POST[PAYLOAD])) {
    $payload = json_decode($_POST[PAYLOAD], true);
    if ($payload[PAYLOAD_REF] === REF) {
        exec(COMMAND);
        file_put_contents($LOG_FILE, $NOW." ".$_SERVER['REMOTE_ADDR']." git pulled: ".$payload['head_commit']['message']."\n", FILE_APPEND|LOCK_EX);
    }
} else {
    file_put_contents($LOG_FILE, $NOW." invalid access: ".$_SERVER['REMOTE_ADDR']."\n", FILE_APPEND|LOCK_EX);
}
