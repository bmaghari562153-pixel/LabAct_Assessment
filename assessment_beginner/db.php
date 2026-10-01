<?php
// db.php - legacy procedural connection ($conn), backed by the OOP Database class
require_once __DIR__ . "/classes/Database.php";
$conn = Database::connect();
