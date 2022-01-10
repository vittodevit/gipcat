<?php
/// PAGE INFO ///
$pageid = 0;
$friendlyname = "Home";
$level = 0;
$jsdeps = array('bootstrap-bundle', 'feathericons', 'jquery', 'toastr');
/// PAGE INFO ///

require_once './init.php';
require_once './lib/pagetools.php';

openPage($pageid, $friendlyname, $level);
printInterventionsModals();
?>

<!-- DO NOT CALL ANYMORE MODAL -->
<div class="modal fade" id="doNotCallModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Non chiamare più</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Sei sicuro di voler disabilitare le chiamate per l'installazione n&ordm; <strong id="dnc.title"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x-octagon"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-danger" onclick="editInstallationDNC_AJAX(document.getElementById('dnc.title').textContent)">
                    <span data-feather="slash"></span>
                    Disabilita
                </button>
            </div>
        </div>
    </div>
</div>


<table class="table table-bordered mt-4">
    <thead>
        <tr>
            <th class="col col-md-6">
                <h5 class="h5t">Chiamate in sospeso</h5>
            </th>
            <th class="col col-md-6">
                <div class="row">
                    <div class="col">
                        <h5 class="h5t">Calendario del giorno</h5>
                    </div>
                    <div class="col">
                        <form action="" method="GET" class="form-inline">
                            <div class="input-group">
                                <!-- <span class="input-group-text" id="basic-addon1">SELEZIONA GIORNATA:</span> -->
                                <input type="date" class="form-control" name="date" placeholder="ID Cliente" <?php if (isset($_GET["date"]) && !empty($_GET["date"])) {
                                    echo 'value="' . $_GET["date"] . '"';
                                } else {
                                    echo 'value="' . date("Y-m-d") . '"';
                                } ?> aria-label="Ricerca">
                                <button class="btn btn-outline-dark" type="submit">
                                    <span data-feather="calendar"></span>
                                    Conferma
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
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
                        AND t1.interventionDate < DATE_ADD(CURDATE(), INTERVAL - installations.monthlyCallInterval MONTH);
                    ";
                    /// END QUERY ///
                    $callListProc = $con->query($callListProc_query);
                    
                    if($con->affected_rows < 1) {
                        ?> <br><br><br>
                        <center><h5>Nessuna chiamata<br>per la giornata di oggi:</h5></center> <?php
                    } else {
                        while ($call = $callListProc->fetch_assoc()) {
                            printCallCard($call);
                        }
                    }
                ?>
                </div>
            </td>
            <td>
                <div class="scrollable-list">
                    <?php 
                    if (isset($_GET["date"]) && !empty($_GET["date"])){
                        $date = $con->real_escape_string($_GET["date"]);
                    }else{
                        $date = date("Y-m-d");
                    }
                    $intq = 
                    "SELECT
                        interventions.idIntervention,
                        interventions.idInstallation,
                        installations.idCustomer,
                        interventions.interventionType,
                        DATE_FORMAT(interventions.interventionDate,'%H:%i') interventionTimeStart,
                        DATE_FORMAT(
                            interventions.interventionDate + INTERVAL interventions.interventionDuration MINUTE,
                            '%H:%i'
                        ) interventionTimeEnd,
                        interventions.interventionState,
                        installations.installationAddress,
                        installations.installationCity,
                        installations.heaterBrand,
                        installations.heater,
                        installations.installationType,
                        customers.businessName,
                        users.userName,
                        users.legalName,
                        users.legalSurname,
                        users.color
                    FROM
                        interventions
                    INNER JOIN installations ON(
                            interventions.idInstallation = installations.idInstallation
                        )
                    INNER JOIN customers ON(
                            installations.idCustomer = customers.idCustomer
                        )
                    LEFT JOIN users ON(
                            interventions.assignedTo = users.userName
                        )
                    WHERE
                        interventions.interventionDate LIKE '%$date%'
                    ORDER BY 
                        interventions.interventionDate ASC;
                    ";
                    $interventionsToday = $con->query($intq);
                    if($con->affected_rows < 1) {
                        ?> <br><br><br>
                        <center><h5>Nessun intervento<br>per la giornata selezionata</h5></center> <?php
                    } else {
                        while ($intervention = $interventionsToday->fetch_assoc()) {
                            printInterventionsCard($intervention);
                        }
                    }
                    ?>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<?php
closePage($level, $jsdeps, "home.js");
