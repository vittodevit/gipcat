<?php

require_once '../init.php';
require_once 'miscfun.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

if (
    !isset($_POST["userName"]) || empty($_POST["userName"])
) {
    http_response_code(400);
    die('AJAX: Required fields are missing!');
}

if(pexcheck($_POST["userName"]) >= $_SESSION["permissionType"]){
    http_response_code(400);
    die('AJAX: You cannot delete a user with this permission type.');
}

$username = $con->real_escape_string($_POST["userName"]);
$res = $con->query("DELETE FROM `users` WHERE `userName` = '$username';");

if($con->affected_rows > 0){
    die('AJAX: OK');
}else{
    http_response_code(404);
    die('AJAX: User not found.');
}