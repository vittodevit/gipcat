<?php
require_once '../init.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    die('false');
}

if($_SERVER['REQUEST_METHOD'] != "POST"){
    die('false');
}

if(!isset($_POST["idInstallation"]) || empty($_POST["idInstallation"])){
    die('false');
}

$id = $con->real_escape_string($_POST["idInstallation"]);
$res = $con->query("DELETE FROM `installations` WHERE ((`idInstallation` = '$id'));");

if($con->affected_rows > 0){
    die('true');
}else{
    die('false');
}