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

$id = $con->real_escape_string($_POST['idInstallation']);

$res = $con->query("SELECT `version` FROM `installations` WHERE `idInstallation` = '$id'");

if ($con->affected_rows != 1) {
    die('false');
}

$arr = $res->fetch_assoc();

if ($_POST['version'] != $arr['version']) {
    die('false');
}

$fieldnames = array("installationAddress", "installationCity", "heater", "installationType", 
"manteinanceContractName", "toCall", "monthlyCallInterval", "contractExpiryDate", "footNote");
                    
$fields = "";

foreach ($fieldnames as $fn){
    // super ugly bugfix, ill fix it when i can
    if($fn == "toCall"){
        if (isset($_POST[$fn])) {
            $fields .= "`$fn` = '" . clean($_POST[$fn]) . "', ";
        }
    }else{
        if (isset($_POST[$fn]) && !empty(strval($_POST[$fn]))) {
            $fields .= "`$fn` = '" . clean($_POST[$fn]) . "', ";
        }
    }
}

if(empty($fields)){
    die('false');
}

$newversion = clean($_POST["version"]) + 1;
$username = $_SESSION["userName"];

$query = "UPDATE `installations` SET $fields `lastEditedBy` = '$username', `version` = '$newversion', `updatedAt` = now() WHERE `idInstallation` = '$id';";
$con->query($query);

if ($con->affected_rows > 0) {
    die('true');
} else {
    die('false');
}
