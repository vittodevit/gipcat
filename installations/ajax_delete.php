<?php
require_once '../init.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if($_SERVER['REQUEST_METHOD'] != "POST"){
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

if(!isset($_POST["idInstallation"]) || empty($_POST["idInstallation"])){
    http_response_code(400);
    die('AJAX: Required fields are missing.');
}

$id = $con->real_escape_string($_POST["idInstallation"]);
$res = $con->query("DELETE FROM `installations` WHERE ((`idInstallation` = '$id'));");

if($con->affected_rows > 0){
    die('AJAX: OK');
}else{
    http_response_code(404);
    die('AJAX: Installation not found.');
}