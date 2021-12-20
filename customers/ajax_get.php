<?php

require_once '../init.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    die('false');
}

if($_SERVER['REQUEST_METHOD'] != "GET"){
    die('false');
}

$id = $con->real_escape_string($_GET['customerId']);

$res = $con->query("SELECT * FROM `customers` WHERE `idCustomer` = '$id'");

if($con->affected_rows != 1){
    die('false');
}

$arr = $res->fetch_assoc();
header("Content-Type: application/json");

die(
    json_encode($arr)
);