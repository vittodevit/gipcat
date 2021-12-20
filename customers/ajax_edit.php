<?php

require_once '../init.php';

function clean($v)
{
    global $con;
    return $con->real_escape_string(htmlspecialchars($v));
}

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    die('false');
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    die('false');
}

$id = $con->real_escape_string($_POST['customerId']);

$res = $con->query("SELECT `version` FROM `customers` WHERE `idCustomer` = '$id'");

if ($con->affected_rows != 1) {
    die('false');
}

$arr = $res->fetch_assoc();

if ($_POST['version'] != $arr['version']) {
    die('false');
}

$fieldnames = array("businessName", "registeredOfficeAddress", "registeredOfficeCity", "headquartersAddress", "headquartersCity", 
                    "homePhoneNumber", "officePhoneNumber", "privateMobilePhoneNumber", "companyMobilePhoneNumber", 
                    "privateEMail", "companyEMail", "fiscalCode", "vatNumber", "footNote");
                    
$fields = "";

foreach ($fieldnames as $fn){
    if (isset($_POST[$fn]) && !empty($_POST[$fn])) {
        $fields .= "`$fn` = '" . clean($_POST[$fn]) . "', ";
    }
}

if(empty($fields)){
    die('false');
}

$newversion = clean($_POST["version"]) + 1;
$username = $_SESSION["userName"];

$con->query("UPDATE `customers` SET $fields `lastEditedBy` = '$username', `version` = '$newversion', `updatedAt` = now() WHERE `idCustomer` = '$id';");

if ($con->affected_rows > 0) {
    die('true');
} else {
    die('false');
}
