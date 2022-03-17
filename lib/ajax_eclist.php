<?php
require_once '../init.php';
//require_once 'pagetools.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permissionType"] < 3) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if ($_SERVER['REQUEST_METHOD'] != "GET") {
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

if($_GET["action"] == "getintervals")
{
    $intervals_query =
    "SELECT DISTINCT
        DATE_FORMAT(t1.interventionDate, '%Y-%m') `normalized`,
        DATE_FORMAT(t1.interventionDate, '%Y') `year`,
        DATE_FORMAT(t1.interventionDate, '%m') `month`
    FROM
        interventions t1
    LEFT OUTER JOIN interventions t2 ON
        (
            t1.idInstallation = t2.idInstallation AND t1.interventionDate < t2.interventionDate AND t2.countInCallCycle = 1
        )
    INNER JOIN installations ON(
            t1.idInstallation = installations.idInstallation
        )
    INNER JOIN customers ON(
            installations.idCustomer = customers.idCustomer
        )
    WHERE
        t2.idInstallation IS NULL 
        AND installations.toCall = '1' 
        AND t1.interventionDate < DATE_ADD(CURDATE(), INTERVAL - installations.monthlyCallInterval MONTH) 
        AND
        (
            t1.associatedCallPosticipationDate IS NULL OR t1.associatedCallPosticipationDate < CURDATE()
        )
    ORDER BY
        normalized ASC;
    ";

    // load intervals
    $intervals = array();
    $intervalsList = $con->query($intervals_query);

    if ($con->affected_rows < 1) {
        http_response_code(404);
        die('AJAX: No intervals found.'); 
    } else {
        while ($interval = $intervalsList->fetch_assoc()) {
            array_push($intervals, $interval);
        }
    }  
    
    // print json
    header("Content-Type: application/json");
    die(json_encode($intervals));
}
elseif($_GET["action"] == "getcalls")
{
    if(!isset($_GET["start"]) || !isset($_GET["end"])){
        http_response_code(400);
        die('AJAX: Invalid request, missing parameters.');
    }

    $start = $con->real_escape_string($_GET["start"]);
    $end = $con->real_escape_string($_GET["end"]);

    $calls_query =
    "SELECT
        t1.idIntervention,
        t1.idInstallation,
        installations.idCustomer,
        t1.interventionType,
        t1.interventionDate,
        t1.interventionDuration,
        t1.associatedCallNote,
        t1.associatedCallPosticipationDate,
        installations.installationAddress,
        installations.installationCity,
        installations.heaterBrand,
        installations.heater,
        installations.installationType,
        installations.manteinanceContractName,
        installations.monthlyCallInterval,
        customers.businessName
    FROM
        interventions t1
    LEFT OUTER JOIN interventions t2 ON
        (
            t1.idInstallation = t2.idInstallation 
            AND t1.interventionDate < t2.interventionDate
            AND t2.countInCallCycle = 1
        )
    INNER JOIN installations ON 
        (
            t1.idInstallation = installations.idInstallation
        )
    INNER JOIN customers ON 
        (
        installations.idCustomer = customers.idCustomer
        )
    WHERE
        t2.idInstallation IS NULL
        AND installations.toCall = '1'
        AND t1.interventionDate < DATE_ADD(
            CURDATE(), INTERVAL - installations.monthlyCallInterval MONTH
        )
        AND (
            t1.associatedCallPosticipationDate IS NULL 
            OR t1.associatedCallPosticipationDate < CURDATE()
        )
        AND t1.interventionDate > '$start'
        AND t1.interventionDate < '$end'
    ORDER BY
        t1.interventionDate ASC;
    ";

    // load calls
    $calls = array();
    $callsList = $con->query($calls_query);

    if ($con->affected_rows < 1) {
        http_response_code(404);
        die('AJAX: No intervals found.'); 
    } else {
        while ($call = $callsList->fetch_array(MYSQLI_ASSOC)) {
            array_push($calls, $call);
        }
    }  
    
    // print json
    header("Content-Type: application/json");
    die(json_encode($calls));

}
else
{
    http_response_code(405);
    die('AJAX: This method is not allowed!'); 
}

$query_chiamate = 
"SELECT
    t1.idIntervention,
    t1.idInstallation,
    installations.idCustomer,
    t1.interventionType,
    t1.interventionDate,
    t1.interventionDuration,
    t1.associatedCallNote,
    t1.associatedCallPosticipationDate,
    installations.installationAddress,
    installations.installationCity,
    installations.heaterBrand,
    installations.heater,
    installations.installationType,
    installations.manteinanceContractName,
    installations.monthlyCallInterval,
    customers.businessName
FROM
    interventions t1
LEFT OUTER JOIN interventions t2 ON
    (
        t1.idInstallation = t2.idInstallation 
        AND t1.interventionDate < t2.interventionDate
        AND t2.countInCallCycle = 1
    )
INNER JOIN installations ON 
    (
        t1.idInstallation = installations.idInstallation
    )
INNER JOIN customers ON 
    (
    installations.idCustomer = customers.idCustomer
    )
WHERE
    t2.idInstallation IS NULL
    AND installations.toCall = '1'
    AND t1.interventionDate < DATE_ADD(CURDATE(), INTERVAL - installations.monthlyCallInterval MONTH)
    AND (t1.associatedCallPosticipationDate IS NULL OR t1.associatedCallPosticipationDate < CURDATE());
";

