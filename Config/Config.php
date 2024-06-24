<?php
include_once(__DIR__ . '/../Classes/Database.php');

$database = new Database();
$db = $database->getConnection();