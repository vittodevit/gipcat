<?php

require_once '../init.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permissionType"] < 3) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

$stmt = $con->prepare("
        INSERT INTO `customers`
            (`businessName`,
            `registeredOfficeAddress`,
            `registeredOfficeCity`,
            `headquartersAddress`,
            `headquartersCity`,
            `homePhoneNumber`,
            `officePhoneNumber`,
            `privateMobilePhoneNumber`,
            `companyMobilePhoneNumber`,
            `privateEMail`,
            `companyEMail`,
            `fiscalCode`,
            `vatNumber`,
            `footNote`,
            `lastEditedBy`,
            `version`,
            `createdAt`,
            `updatedAt`)
        VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
            '1',
            Now(),
            Now());
        ");

$stmt->bind_param(
    "sssssssssssssss",
    $businessName,
    $registeredOfficeAddress,
    $registeredOfficeCity,
    $headquartersAddress,
    $headquartersCity,
    $homePhoneNumber,
    $officePhoneNumber,
    $privateMobilePhoneNumber,
    $companyMobilePhoneNumber,
    $privateEMail,
    $companyEMail,
    $fiscalCode,
    $vatNumber,
    $footNote,
    $lastEditedBy,
);

if (
    !isset($_POST["businessName"]) || empty($_POST["businessName"]) ||
    !isset($_POST["registeredOfficeAddress"]) || empty($_POST["registeredOfficeAddress"]) ||
    !isset($_POST["registeredOfficeCity"]) || empty($_POST["registeredOfficeCity"])
) {
    http_response_code(400);
    die('AJAX: Required fields are missing!');
}

// ASSIGN VALUES

$businessName = htmlspecialchars($_POST["businessName"]);
$registeredOfficeAddress = htmlspecialchars($_POST["registeredOfficeAddress"]);
$registeredOfficeCity = htmlspecialchars($_POST["registeredOfficeCity"]);
$headquartersAddress = (
    isset($_POST["headquartersAddress"]) && !empty($_POST["headquartersAddress"]) ? htmlspecialchars($_POST["headquartersAddress"]) : null
);

$headquartersCity = (
    isset($_POST["headquartersCity"]) && !empty($_POST["headquartersCity"]) ? htmlspecialchars($_POST["headquartersCity"]) : null
);

$homePhoneNumber = (
    isset($_POST["homePhoneNumber"]) && !empty($_POST["homePhoneNumber"]) ? htmlspecialchars($_POST["homePhoneNumber"]) : null
);

$officePhoneNumber = (
    isset($_POST["officePhoneNumber"]) && !empty($_POST["officePhoneNumber"]) ? htmlspecialchars($_POST["officePhoneNumber"]) : null
);

$privateMobilePhoneNumber = (
    isset($_POST["privateMobilePhoneNumber"]) && !empty($_POST["privateMobilePhoneNumber"]) ? htmlspecialchars($_POST["privateMobilePhoneNumber"]) : null
);

$companyMobilePhoneNumber = (
    isset($_POST["companyMobilePhoneNumber"]) && !empty($_POST["companyMobilePhoneNumber"]) ? htmlspecialchars($_POST["companyMobilePhoneNumber"]) : null
);

$privateEMail = (
    isset($_POST["privateEMail"]) && !empty($_POST["privateEMail"]) ? htmlspecialchars($_POST["privateEMail"]) : null
);

$companyEMail = (
    isset($_POST["companyEMail"]) && !empty($_POST["companyEMail"]) ? htmlspecialchars($_POST["companyEMail"]) : null
);

$fiscalCode = (
    isset($_POST["fiscalCode"]) && !empty($_POST["fiscalCode"]) ? htmlspecialchars($_POST["fiscalCode"]) : null
);

$vatNumber = (
    isset($_POST["vatNumber"]) && !empty($_POST["vatNumber"]) ? htmlspecialchars($_POST["vatNumber"]) : null
);

$footNote = (
    isset($_POST["footNote"]) && !empty($_POST["footNote"]) ? htmlspecialchars($_POST["footNote"]) : null
);

$lastEditedBy = $_SESSION["userName"];

$stmt->execute();

if ($stmt->errno) {
    http_response_code(500);
    die('AJAX: Required fields are missing!');
}

die("AJAX: OK!");
