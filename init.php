<?php
$config = array(
    "DB_HOST" => "10.8.0.10",
    "DB_USER" => "gipcat",
    "DB_PASS" => "gipcat",
    "DB_NAME" => "gipcat",
    "INST_NAME" => "'Cusenza Service' di G. Cusenza"
);

$con = new mysqli($config["DB_HOST"], $config["DB_USER"], $config["DB_PASS"], $config["DB_NAME"]);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}