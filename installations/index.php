<?php
/// PAGE INFO ///
$pageid = 2;
$friendlyname = "Gestore Installazioni";
$level = 1;
$jsdeps = array('bootstrap-bundle', 'feathericons');
/// PAGE INFO ///

require_once '../init.php';
require_once '../lib/pagetools.php';

openPage($pageid, $friendlyname, $level);
$idCustomerGET = $con->real_escape_string(htmlspecialchars($_GET["idCustomer"]));
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class='h2'>Gestore Installazioni</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <form action="" method="get">
            <div class="row g-3">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">ID CLIENTE:</span>
                        <input type="text" class="form-control" name="idCustomer" placeholder="ID Cliente" 
                        <?php if (isset($_GET["idCustomer"]) && !empty($_GET["idCustomer"])) {
                            echo 'value="' . $_GET["idCustomer"] . '"';
                        } ?> aria-label="Ricerca" aria-describedby="button-addon2">
                        <button class="btn btn-outline-dark" type="submit" id="button-addon2">
                            <span data-feather="check"></span>
                            Conferma
                        </button>
                    </div>
                </div>
                <div class="col col-md-auto">
                    <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#newInstallationModal">
                        <span data-feather="box"></span>
                        Aggiungi Installazione
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (!isset($_GET["idCustomer"]) || empty($_GET["idCustomer"])) { ?>
<br>
<center>
    <h3>
        <strong>
            NESSUN CLIENTE SELEZIONATO
        </strong>
    </h3>
</center>
<br>
<?php closePage($level, $jsdeps); } ?>

<div>
    <form action="" method="get">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="query" value="<?php echo htmlspecialchars($_GET['query']); ?>"
            placeholder="Ricerca" aria-label="Ricerca" aria-describedby="button-addon2">
            <input type="hidden" name="idCustomer" value="<?php echo $idCustomerGET; ?>">
            <button class="btn btn-outline-dark" type="submit" id="button-addon2">
                <span data-feather="search"></span>
                Cerca
            </button>
            <a class="btn btn-outline-dark" href="./?idCustomer=<?php echo $idCustomerGET; ?>">
                <span data-feather="refresh-ccw"></span>
                Ricarica
            </a>
        </div>
    </form>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="col-md-1">ID Installazione</th>
            <th class="col-md-2">Indirizzo Installazione</th>
            <th class="col-md-2">Città Installazione</th>
            <th class="col-md-2">Tipo Installazione</th>
            <th class="col-md-2">Caldaia</th>
            <th class="col-md-1">Operazioni</th>
        </tr>
    </thead>
    <tbody>
        <?php

        if (isset($_GET['query']) && $_GET['query'] != "") {
            $additionalQuery = "";

            $additionalQuery = "AND LOWER(
                                    CONCAT(
                                        IFNULL(idInstallation, ''),
                                        '',
                                        IFNULL(installationType, ''),
                                        '',
                                        IFNULL(installationAddress, ''),
                                        '',
                                        IFNULL(installationCity, ''),
                                        '',
                                        IFNULL(heater, '')
                                    )
                                ) LIKE LOWER(\"%";
            $additionalQuery .= $con->real_escape_string($_GET["query"]);
            $additionalQuery .= "%\")";
        }
        
        $result = $con->query("SELECT idInstallation, installationType, installationAddress, installationCity, heater 
                                FROM installations 
                                WHERE idCustomer = $idCustomerGET
                                $additionalQuery");
        while ($row = $result->fetch_array()) {
        ?>
            <tr>
                <td> <?php echo $row['idInstallation']; ?> </td>
                <td> <?php echo $row['installationAddress']; ?> </td>
                <td> <?php echo $row['installationCity']; ?> </td>
                <td> <?php echo $row['installationType']; ?> </td>
                <td> <?php echo $row['heater']; ?> </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-outline-dark dropdown-toggle" type="button" id="actionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <span data-feather="menu"></span>
                            Operazioni
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="actionsDropdown">
                            <li><a class="dropdown-item" href="../interventions/?idInstallation=<?php echo $row['idInstallation']; ?>">
                                    <span data-feather="calendar"></span>
                                    Gestisci Interventi
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editInstallationModal" data-bs-eimIid="<?php echo $row['idInstallation']; ?>"">
                                    <span data-feather="edit"></span>
                                    Visualizza o Modifica
                                </a></li>
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteInstallationModal" data-bs-dimIid="<?php echo $row['idInstallation']; ?>"">
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