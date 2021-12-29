<?php

require_once '../init.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

$stmt = $con->prepare("
        INSERT INTO `users`
            (`userName`,
            `legalName`,
            `legalSurname`,
            `passwordHash`,
            `permissionType`,
            `idCustomer`,
            `lastEditedBy`,
            `version`,
            `createdAt`,
            `updatedAt`)
        VALUES (
            ?, ?, ?, ?, ?, ?, ?,
            '1',
            Now(),
            Now());
        ");

$stmt->bind_param(
    "sssssis",
    $userName,
    $legalName,
    $legalSurname,
    $passwordHash,
    $permissionType,
    $idCustomer,
    $lastEditedBy,
);

// VALIDATION //

if (
    !isset($_POST["userName"]) || empty($_POST["userName"]) ||
    !isset($_POST["password"]) || empty($_POST["password"]) ||
    !isset($_POST["permissionType"]) || empty($_POST["permissionType"])
) {
    http_response_code(400);
    die('AJAX: Required fields are missing!');
}

if(strlen($_POST["password"]) < 7 || strlen($_POST["password"]) > 71 )
{
    http_response_code(400);
    die('AJAX: Invalid password length.');
}

if(!is_numeric($_POST["permissionType"])){
    http_response_code(400);
    die('AJAX: Invalid permission type.');
}

if($_POST["permissionType"] < 1 || $_POST["permissionType"] > 4){
    http_response_code(400);
    die('AJAX: Invalid permission type.');
}

if($_POST["permissionType"] == 1 && empty($_POST["idCustomer"])){
    http_response_code(400);
    die('AJAX: A customer permission level has been set, but no customer id has been bound!');
}

if($_POST["permissionType"] > 2 && $_SESSION["permissionType"] < 2){
    http_response_code(400);
    die('AJAX: You cannot create a user with this permission type.');
}

// DATA //
$userName = htmlspecialchars($_POST["userName"]);

$legalName = (
    isset($_POST["legalName"]) && !empty($_POST["legalName"]) ? htmlspecialchars($_POST["legalName"]) : null
);

$legalSurname = (
    isset($_POST["legalSurname"]) && !empty($_POST["legalSurname"]) ? htmlspecialchars($_POST["legalSurname"]) : null
);

$passwordHash = password_hash($_POST["password"], PASSWORD_BCRYPT);

$permissionType = $_POST["permissionType"];

$idCustomer = $_POST["permissionType"] != 1 ? null : $_POST["idCustomer"];

$lastEditedBy = $_SESSION["userName"];

$stmt->execute();

if ($stmt->errno) {
    http_response_code(500);
    die('AJAX: MYSQL ERROR MY-'.$stmt->errno.' -> '.$stmt->error);
}

die("AJAX: OK!");