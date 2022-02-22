<?php

require_once '../../init.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permissionType"] < 2) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

$stmt = $con->prepare("
        INSERT INTO `interventions_nb`
            (`interventionState`,
            `interventionDuration`,
            `assignedTo`,
            `interventionDate`,
            `footNote`,
            `lastEditedBy`,
            `version`,
            `createdAt`,
            `updatedAt`)
        VALUES (
            ?, ?, ?, ?, ?, ?,
            '1',
            Now(),
            Now());
        ");

$stmt->bind_param(
    "iissss",
    $interventionState,
    $interventionDuration,
    $assignedTo,
    $interventionDate,
    $footNote,
    $lastEditedBy,
);

if (
    !isset($_POST["interventionState"]) ||
    !isset($_POST["interventionDate"]) || empty($_POST["interventionDate"]) ||
    !isset($_POST["interventionDuration"]) || empty($_POST["interventionDuration"])
) {
    http_response_code(400);
    die('AJAX: Required fields are missing!');
}

// ASSIGN VALUES

$interventionState = htmlspecialchars($_POST["interventionState"]);
$interventionDate = htmlspecialchars($_POST["interventionDate"]);
$interventionDuration = htmlspecialchars($_POST["interventionDuration"]);

$assignedTo = (
    isset($_POST["assignedTo"]) && !empty($_POST["assignedTo"]) ? htmlspecialchars($_POST["assignedTo"]) : null
);

$footNote = (
    isset($_POST["footNote"]) && !empty($_POST["footNote"]) ? htmlspecialchars($_POST["footNote"]) : null
);

$lastEditedBy = $_SESSION["userName"];

$stmt->execute();

if ($stmt->errno) {
    http_response_code(500);
    die('AJAX: MYSQL ERROR MY-'.$stmt->errno);
}

die("AJAX: OK!");
