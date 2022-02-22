<?php

require_once '../../init.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permissionType"] < 2) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if($_SERVER['REQUEST_METHOD'] != "GET"){
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

$id = $con->real_escape_string($_GET['idIntervention']);

$res = $con->query("SELECT * FROM `interventions_np` WHERE `idIntervention` = '$id'");

if($con->affected_rows != 1){
    http_response_code(404);
    die('AJAX: Intervention not found.');
}

$arr = $res->fetch_assoc();

$interventionUnixTime = strtotime($arr['interventionDate']);

// formatting for gui
$arr['interventionDate'] = gmdate("Y-m-d", $interventionUnixTime);
$arr['interventionTime'] = gmdate("H:i", $interventionUnixTime);

header("Content-Type: application/json");

die(
    json_encode($arr)
);