<?php
$LOG_FILE = __DIR__.'/hook.log';
echo "start\n<pre>";
var_dump($_POST);
if (isset($_POST['payload']) ) {
    $payload = json_decode($_POST['payload'], true);
    if ($_POST['payload'] === 'refs/heads/develop') {
        echo "execute : \n";
        exec(`cd /var/www/lamp/sample; git pull origin develop:local 2>&1`, $array);
        var_dump($array);
        file_put_contents($LOG_FILE, date("[Y-m-d H:i:s]")." ".$_SERVER['REMOTE_ADDR']." git pulled: ".$payload['head_commit']['message']."\n", FILE_APPEND|LOCK_EX);
    }
} else {
    file_put_contents($LOG_FILE, date("[Y-m-d H:i:s]")." invalid access: ".$_SERVER['REMOTE_ADDR']."\n", FILE_APPEND|LOCK_EX);
}
echo "</pre>end\n";
