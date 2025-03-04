<?php
require_once("app/init.php");
$uri = BASE_URL;
header('Location: ' . $uri . 'public/index.php');
exit;