<?php
ignore_user_abort(true);
set_time_limit(0);
header("Content-type:text/html; charset=utf-8");
include_once 'sync.php';
$test =new sync();
$test->sync_all();
?>
