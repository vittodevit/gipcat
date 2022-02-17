<?php
require_once '../init.php';
require_once 'pagetools.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permissionType"] < 3) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if ($_SERVER['REQUEST_METHOD'] != "GET") {
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

header("Content-Type: text/plain");
?>
<div class="scrollable-list">
    <?php
    /// START QUERY ///
    $callListProc_query =
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
            AND (t1.associatedCallPosticipationDate IS NULL OR t1.associatedCallPosticipationDate < CURDATE());
        ";
    /// END QUERY ///
    $callListProc = $con->query($callListProc_query);

    if ($con->affected_rows < 1) {
        ?> 
            <br><br><br>
            <center>
                <h5>Nessuna chiamata<br>per la giornata di oggi:</h5>
            </center> 
        <?php
    } else {
        while ($call = $callListProc->fetch_assoc()) {
            printCallCard($call);
        }
    }
    ?>
</div>