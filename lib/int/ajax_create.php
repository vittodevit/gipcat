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
        INSERT INTO `interventions`
            (`idInstallation`,
            `interventionType`,
            `interventionState`,
            `interventionDuration`,
            `assignedTo`,
            `countInCallCycle`,
            `interventionDate`,
            `shipmentDate`,
            `protocolNumber`,
            `billingDate`,
            `billingNumber`,
            `paymentDate`,
            `footNote`,
            `lastEditedBy`,
            `version`,
            `createdAt`,
            `updatedAt`)
        VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
            '1',
            Now(),
            Now());
        ");

$stmt->bind_param(
    "isiisissisisss",
    $idInstallation,
    $interventionType,
    $interventionState,
    $interventionDuration,
    $assignedTo,
    $countInCallCycle,
    $interventionDate,
    $shipmentDate,
    $protocolNumber,
    $billingDate,
    $billingNumber,
    $paymentDate,
    $footNote,
    $lastEditedBy,
);

if (
    !isset($_POST["idInstallation"]) || empty($_POST["idInstallation"]) ||
    !isset($_POST["interventionType"]) || empty($_POST["interventionType"]) ||
    !isset($_POST["interventionState"]) ||
    !isset($_POST["interventionDate"]) || empty($_POST["interventionDate"]) ||
    !isset($_POST["interventionDuration"]) || empty($_POST["interventionDuration"]) ||
    !isset($_POST["countInCallCycle"])
) {
    http_response_code(400);
    die('AJAX: Required fields are missing!');
}

// ASSIGN VALUES

$idInstallation = htmlspecialchars($_POST["idInstallation"]);
$interventionType = htmlspecialchars($_POST["interventionType"]);
$interventionState = htmlspecialchars($_POST["interventionState"]);
$interventionDate = htmlspecialchars($_POST["interventionDate"]);
$interventionDuration = htmlspecialchars($_POST["interventionDuration"]);
$countInCallCycle = htmlspecialchars($_POST["countInCallCycle"]);

$assignedTo = (
    isset($_POST["assignedTo"]) && !empty($_POST["assignedTo"]) ? htmlspecialchars($_POST["assignedTo"]) : null
);

$shipmentDate = (
    isset($_POST["shipmentDate"]) && !empty($_POST["shipmentDate"]) ? htmlspecialchars($_POST["shipmentDate"]) : null
);

$protocolNumber = (
    isset($_POST["protocolNumber"]) ? htmlspecialchars($_POST["protocolNumber"]) : null
);

$billingDate = (
    isset($_POST["billingDate"]) && !empty($_POST["billingDate"]) ? htmlspecialchars($_POST["billingDate"]) : null
);

$billingNumber = (
    isset($_POST["billingNumber"]) ? htmlspecialchars($_POST["billingNumber"]) : null
);

$paymentDate = (
    isset($_POST["paymentDate"]) && !empty($_POST["paymentDate"]) ? htmlspecialchars($_POST["paymentDate"]) : null
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
