<?php

require_once '../../init.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if($_SERVER['REQUEST_METHOD'] != "GET"){
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

if (
    !isset($_GET["interventionDate"]) || empty($_GET["interventionDate"]) ||
    !isset($_GET["interventionDuration"]) || empty($_GET["interventionDuration"])
) {
    http_response_code(400);
    die('AJAX: Required fields are missing!');
}

$interventionDate = new DateTime($_GET['interventionDate']);
$interventionDuration = $_GET['interventionDuration'];

$start = $interventionDate->format("Y-m-d H:i:s");
$end = (clone $interventionDate)->add(new DateInterval("PT{$interventionDuration}M"))->format("Y-m-d H:i:s");

$query =
"SELECT
    users.userName,
    users.legalName,
    users.legalSurname,
    users.color,
    (
        SELECT
            COUNT(0)
        FROM
            interventions
        WHERE
            interventions.assignedTo = users.userName
            AND 
            ( 
                interventions.interventionDate BETWEEN \"{$start}\" AND \"{$end}\"
                OR interventions.interventionDate + INTERVAL interventions.interventionDuration MINUTE BETWEEN \"{$start}\" AND \"{$end}\"
            )
    ) AS isBusy
FROM
    users
WHERE
    users.permissionType = 2;
";

$arr = array();

$res = $con->query($query);
while($row = $res->fetch_assoc()){
    array_push($arr, $row);
}

header("Content-Type: application/json");

die(
    json_encode($arr)
);