<?php

require_once '../init.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if($_SERVER['REQUEST_METHOD'] != "GET"){
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

$username = $con->real_escape_string($_GET['userName']);

$res = $con->query(
    "SELECT 
    `userName`, `legalName`, `legalSurname`, `permissionType`, `idCustomer`, `color`, `lastEditedBy`, `version`, `createdAt`, `updatedAt`  
    FROM `users` WHERE `userName` = '$username'
");

if($con->affected_rows != 1){
    http_response_code(404);
    die('AJAX: User not found.');
}

$arr = $res->fetch_assoc();
header("Content-Type: application/json");

die(
    json_encode($arr)
);