<?php
$LOG_FILE = __DIR__.'/hook.log';

if (isset($_POST['payload']) ) {
    $payload = json_decode($_POST['payload'], true);
    if ($payload['ref'] === 'refs/heads/develop') {
        exec(`cd /var/www/sample; git pull origin develop:local`);
        file_put_contents($LOG_FILE, date("[Y-m-d H:i:s]")." ".$_SERVER['REMOTE_ADDR']." git pulled: ".$payload['head_commit']['message']."\n", FILE_APPEND|LOCK_EX);
    }
} else {
    file_put_contents($LOG_FILE, date("[Y-m-d H:i:s]")." invalid access: ".$_SERVER['REMOTE_ADDR']."\n", FILE_APPEND|LOCK_EX);
}