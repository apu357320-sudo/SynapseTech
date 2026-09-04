<?php

$host = 'localhost';
$username = 'admin';
$password = 'adminadminhola123';
$database = 'synapsetech_db';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_errno) {
	die('Database connection failed: ' . $conn->connect_error);

$conn->set_charset('utf8mb4');
