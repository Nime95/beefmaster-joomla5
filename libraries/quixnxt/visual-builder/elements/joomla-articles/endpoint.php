<?php
define('_JEXEC', 1) or die;

header('Content-Type: application/json');
$config = require(__DIR__ . '/config.php');

echo json_encode($config);
