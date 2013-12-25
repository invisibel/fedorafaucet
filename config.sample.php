<?php
$CONFIG = array();
$CONFIG['address_ver'] = "21"; // 00 for bitcoin, 7D for StableCoin, 21 for FedoraCoin

$CONFIG['rpc_method'] = "http";
$CONFIG['rpc_user'] = "user";
$CONFIG['rpc_pass'] = "pass";
$CONFIG['rpc_host'] = "127.0.0.1";
$CONFIG['rpc_port'] = 22888;

$CONFIG['mysql_db'] = "database";
$CONFIG['mysql_user'] = "username";
$CONFIG['mysql_pass'] = "password";
$CONFIG['mysql_host'] = "127.0.0.1";

$CONFIG['account_name'] = "nickname";
$CONFIG['account_limit'] = 1000000;

$CONFIG['testing'] = false;

$CONFIG['min_amount'] = 1;
$CONFIG['max_amount'] = 1000;

$CONFIG['time_limit'] = 12 * 60 * 60;

$CONFIG['recaptcha_private'] = "----private key from google----";
$CONFIG['recaptcha_public'] = "----public key from google----";
?>
