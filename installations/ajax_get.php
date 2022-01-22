<?php

require_once '../init.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permissionType"] < 2) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if($_SERVER['REQUEST_METHOD'] != "GET"){
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

$id = $con->real_escape_string($_GET['idInstallation']);

$res = $con->query("SELECT * FROM `installations` WHERE `idInstallation` = '$id'");

if($con->affected_rows != 1){
    http_response_code(404);
    die('AJAX: Installation not found.');
}

$arr = $res->fetch_assoc();
header("Content-Type: application/json");

die(
    json_encode($arr)
);