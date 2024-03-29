<?php
/// PAGE INFO ///
$pageid = 4;
$friendlyname = "Gestione Interventi";
$level = 1;
$jsdeps = array('bootstrap-bundle', 'feathericons', 'jquery', 'toastr');
/// PAGE INFO ///

require_once '../init.php';
require_once '../lib/pagetools.php';
require_once '../lib/miscfun.php';

openPage($pageid, $friendlyname, $level);
$idInstallationGET = $con->real_escape_string(htmlspecialchars($_GET["idInstallation"]));
$_Q_installationExists =
"SELECT
    installations.idCustomer,
    installations.installationAddress,
    installations.installationCity,
    installations.heater,
    installations.heaterBrand,
    installations.installationType,
    installations.manteinanceContractName,
    installations.toCall,
    installations.monthlyCallInterval,
    installations.heaterSerialNumber,
    customers.businessName
FROM
    installations
INNER JOIN customers ON
(
    installations.idCustomer = customers.idCustomer
)
WHERE
    idInstallation = '$idInstallationGET';
";
$_R_installationExists = $con->query($_Q_installationExists);
$_installationExists = $_R_installationExists->fetch_assoc();

printInterventionsModals();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class='h2'>Gestore Interventi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <form action="" method="get">
            <div class="row g-3">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">NUMERO INSTALLAZIONE:</span>
                        <input type="number" class="form-control" name="idInstallation" placeholder="ID Installazione" 
                        <?php if (isset($_GET["idInstallation"]) && !empty($_GET["idInstallation"])) {
                            echo 'value="' . $_GET["idInstallation"] . '"';
                        } ?> aria-label="Ricerca">
                        <button class="btn btn-outline-dark" type="submit">
                            <span data-feather="check"></span>
                            Conferma
                        </button>
                    </div>
                </div>
                <?php if (isset($_GET["idInstallation"]) && !empty($_GET["idInstallation"]) && $_installationExists != null) { ?>
                <div class="col col-md-auto">
                    <div class="input-group">
                        <span class="input-group-text">
                            <?php echo $_installationExists['businessName'] ?>
                        </span>
                        <button type="button" class="btn btn-outline-dark" 
                        data-bs-toggle="modal" data-bs-target="#viewCustomerModal" data-bs-vcmCid="<?php echo $_installationExists['idCustomer']; ?>">
                            <span data-feather="users"></span>
                            Visualizza scheda cliente
                        </button>
                    </div>
                </div>
                <div class="col col-md-auto">
                    <button type="button" class="btn btn-outline-dark" onclick="location.href = '../installations/?idCustomer=<?php echo $_installationExists['idCustomer'] ?>'">
                        <span data-feather="box"></span>
                        Torna alle installazioni
                    </button>
                </div>
                <?php } ?>
            </div>
        </form>
    </div>
</div>

<?php if (!isset($_GET["idInstallation"]) || empty($_GET["idInstallation"])) { ?>
<br>
<center>
    <h3>
        <strong>
            NESSUNA INSTALLAZIONE SELEZIONATA
        </strong>
    </h3>
</center>
<br>
<?php closePage($level, $jsdeps); } ?>

<?php if ($_installationExists == null) { ?>
<br>
<center>
    <h3>
        <strong>
            L'INSTALLAZIONE SELEZIONATA <br>
            NON ESISTE
        </strong>
    </h3>
</center>
<br>
<?php closePage($level, $jsdeps); } ?>

<div>
    <div class="row mb-3 g-3">
        <div class="col">
            <form action="" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="query" value="<?php echo htmlspecialchars($_GET['query']); ?>"
                    placeholder="Ricerca" aria-label="Ricerca" aria-describedby="button-addon2">
                    <input type="hidden" name="idInstallation" value="<?php echo $idInstallationGET; ?>">
                    <button class="btn btn-outline-dark" type="submit" id="button-addon2">
                        <span data-feather="search"></span>
                        Cerca
                    </button>
                    <a class="btn btn-outline-dark" href="./?idInstallation=<?php echo $idInstallationGET; ?>">
                        <span data-feather="refresh-ccw"></span>
                        Ricarica
                    </a>
                </div>
            </form>
        </div>
        <div class="col col-md-auto">
            <button type="button" class="btn btn-outline-dark" 
                data-bs-toggle="modal" data-bs-target="#createInterventionModal" data-bs-cimIid="<?php echo $idInstallationGET ?>">
                    <span data-feather="calendar"></span>
                    Aggiungi Intervento
            </button>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col col-md-2">
        <label for="preview.installationAddress" class="form-label">Indirizzo installazione:</label>
        <input type="text" class="form-control" id="preview.installationAddress" disabled 
        value="<?php echo $_installationExists['installationAddress'] ?>">
    </div>
    <div class="col col-md-2">
        <label for="preview.installationCity" class="form-label">Città installazione:</label>
        <input type="text" class="form-control" id="preview.installationCity" disabled 
        value="<?php echo $_installationExists['installationCity'] ?>">
    </div>
    <div class="col col-md-2">
        <label for="preview.installationType" class="form-label">Tipo installazione:</label>
        <input type="text" class="form-control" id="preview.installationType" disabled 
        value="<?php echo $_installationExists['installationType'] ?>">
    </div>
    <div class="col col-md-2">
        <label for="preview.heater" class="form-label">Marca e mod. apparecchio:</label>
        <input type="text" class="form-control" id="preview.heater" disabled 
        value="<?php echo $_installationExists['heaterBrand']." ".$_installationExists['heater'] ?>">
    </div>
    <div class="col col-md-2">
        <label for="preview.sn" class="form-label">Matricola:</label>
        <input type="text" class="form-control" id="preview.sn" disabled 
        value="<?php echo $_installationExists['heaterSerialNumber'] ?>">
    </div>
    <div class="col col-md-2">
        <label for="preview.callinterval" class="form-label">Intervallo chiamate:</label>
        <input type="text" class="form-control" id="preview.callinterval" disabled 
        value="<?php if($_installationExists['toCall'] == '0'){
            echo 'NON CHIAMARE';
        }else{
            echo $_installationExists['monthlyCallInterval']." MESI";
        } ?>
        ">
    </div>
</div>

<!-- <h4>tabella che devo ancora fare</h4>
<img class="mt-10" src="../static/img/workinprogress.png" alt="workinprogress"> -->

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="col-md-1">N&ordm; Intervento Uni.</th>
            <th class="col-md-2">Tipo Intervento</th>
            <th class="col-md-2">Stato Intervento</th>
            <th class="col-md-2">Assegnato a</th>
            <th class="col-md-2">Data ed Ora Intervento</th>
            <th class="col-md-1">Operazioni</th>
        </tr>
    </thead>
    <tbody>
        <?php

        if (isset($_GET['query']) && $_GET['query'] != "") {
            $additionalQuery = "";

            $additionalQuery = "AND LOWER(
                                    CONCAT(
                                        IFNULL(idIntervention, ''),
                                        '',
                                        IFNULL(interventionType, ''),
                                        '',
                                        IFNULL(interventionState, ''),
                                        '',
                                        IFNULL(assignedTo, ''),
                                        '',
                                        IFNULL(interventionDate, '')
                                    )
                                ) LIKE LOWER(\"%";
            $additionalQuery .= $con->real_escape_string($_GET["query"]);
            $additionalQuery .= "%\")";
        }

        $IS = array(
            array("Programmato", "orange"),
            array("Eseguito", "green"),
            array("Annullato", "red")
        );

        $tq = 
        "SELECT
            interventions.idIntervention,
            interventions.interventionType,
            interventions.interventionState,
            interventions.assignedTo,
            interventions.interventionDate,
            users.userName,
            users.legalName,
            users.legalSurname,
            users.color
        FROM
            interventions
        LEFT JOIN users ON
            (
                interventions.assignedTo = users.userName
            )
        WHERE
            idInstallation = '$idInstallationGET'
        $additionalQuery
        ORDER BY
            interventionDate ASC;
        ";

        $result = $con->query($tq);
        while ($row = $result->fetch_array()) {
        ?>
            <tr>
                <td> <?php echo $row['idIntervention']; ?> </td>
                <td> <?php echo $row['interventionType']; ?> </td>
                <td> <b><span style="color:<?php echo $IS[$row['interventionState']][1] ?> ;"><?php echo $IS[$row['interventionState']][0] ?></span></b> </td>
                <?php
                if($row['userName'] == null){
                    $at = "Nessuno";
                }else{
                    $at = "[".$row['userName']."] ".$row['legalName']." ".$row['legalSurname'];
                    if($row['color'] != null){
                        $color = $row['color'];
                        $at .= " <span style='color: $color;'>&#9632;</span>";
                    }
                }
                ?>
                <td> <?php echo $at ?> </td>
                <td> <?php echo convertDate($row['interventionDate']); ?> </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-outline-dark dropdown-toggle" type="button" id="actionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <span data-feather="menu"></span>
                            Operazioni
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="actionsDropdown">
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editInterventionModal" data-bs-eimIid="<?php echo $row['idIntervention']; ?>">
                                    <span data-feather="edit"></span>
                                    Visualizza o Modifica
                                </a></li>
                            <!-- <li><a class="dropdown-item" onclick="amsLaunch('intervention<?php echo $row['idIntervention']; ?>')">
                                    <span data-feather="database"></span>
                                    Visualizza in AMS
                                </a></li> -->
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteInterventionModal" data-bs-dimIid="<?php echo $row['idIntervention']; ?>">
                                <span data-feather="delete"></span>
                                    Elimina
                                </a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php
closePage($level, $jsdeps);