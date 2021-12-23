<?php

require_once '../init.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    die('false');
}

if($_SERVER['REQUEST_METHOD'] != "POST"){
    die('false');
}

$stmt = $con->prepare("
        INSERT INTO `installations`
            (`idCustomer`,
            `installationAddress`,
            `installationCity`,
            `heater`,
            `installationType`,
            `manteinanceContractName`,
            `toCall`,
            `monthlyCallInterval`,
            `contractExpiryDate`,
            `footNote`,
            `lastEditedBy`,
            `version`,
            `createdAt`,
            `updatedAt`)
        VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
            '1',
            Now(),
            Now());
        ");

$stmt->bind_param(
    "isssssiisss",
    $idCustomer,
    $installationAddress,
    $installationCity,
    $heater,
    $installationType,
    $manteinanceContractName,
    $toCall,
    $monthlyCallInterval,
    $contractExpiryDate,
    $footNote,
    $lastEditedBy,
);

if (
    !isset($_POST["idCustomer"]) || empty($_POST["idCustomer"]) ||
    !isset($_POST["installationAddress"]) || empty($_POST["installationAddress"]) ||
    !isset($_POST["installationCity"]) || empty($_POST["installationCity"]) ||
    !isset($_POST["toCall"]) || empty($_POST["toCall"])
) {
    die("false");
}

// ASSIGN VALUES

$idCustomer = htmlspecialchars($_POST["idCustomer"]);
$installationAddress = htmlspecialchars($_POST["installationAddress"]);
$installationCity = htmlspecialchars($_POST["installationCity"]);
$toCall = htmlspecialchars($_POST["toCall"]);

$heater = (
    isset($_POST["heater"]) && !empty($_POST["heater"]) ? htmlspecialchars($_POST["heater"]) : null
);

$installationType = (
    isset($_POST["installationType"]) && !empty($_POST["installationType"]) ? htmlspecialchars($_POST["installationType"]) : null
);

$manteinanceContractName = (
    isset($_POST["manteinanceContractName"]) && !empty($_POST["manteinanceContractName"]) ? htmlspecialchars($_POST["manteinanceContractName"]) : null
);

$monthlyCallInterval = (
    isset($_POST["monthlyCallInterval"]) && !empty($_POST["monthlyCallInterval"]) ? htmlspecialchars($_POST["monthlyCallInterval"]) : null
);

$contractExpiryDate = (
    isset($_POST["contractExpiryDate"]) && !empty($_POST["contractExpiryDate"]) ? htmlspecialchars($_POST["contractExpiryDate"]) : null
);

$footNote = (
    isset($_POST["footNote"]) && !empty($_POST["footNote"]) ? htmlspecialchars($_POST["footNote"]) : null
);

$lastEditedBy = $_SESSION["userName"];

$stmt->execute();

if ($stmt->errno) {
    die("false");
}

die("true");
