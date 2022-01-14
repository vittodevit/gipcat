<?php
require_once '../../init.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permissionType"] < 2) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if($_SERVER['REQUEST_METHOD'] != "POST"){
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

if(!isset($_POST["idIntervention"]) || empty($_POST["idIntervention"])){
    http_response_code(400);
    die('AJAX: Required fields are missing.');
}

$id = $con->real_escape_string($_POST["idIntervention"]);
$res = $con->query("DELETE FROM `interventions` WHERE ((`idIntervention` = '$id'));");

if($con->affected_rows > 0){
    die('AJAX: OK');
}else{
    http_response_code(404);
    die('AJAX: Intervention not found.');
}