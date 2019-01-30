<?php

require_once('UserTools.class.php');
session_start();

$userTools = new UserTools();
$userTools->logout();

header("Location: index.html");

?>
