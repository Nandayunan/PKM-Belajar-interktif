<?php
require __DIR__ . '/vendor/autoload.php';
use Illuminate\Database\Capsule\Manager;
$capsule = new Manager();
$capsule->addConnection(require __DIR__ . '/config/database.php');
$conn = $capsule->getConnection();
$res = $conn->select("SHOW FULL COLUMNS FROM questions LIKE 'type'");
var_dump($res);
