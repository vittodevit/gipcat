<?php
/// PAGE INFO ///
$pageid = 2;
$friendlyname = "Gestore Installazioni";
$level = 1;
$jsdeps = array('bootstrap-bundle', 'feathericons', 'jquery', 'toastr');
/// PAGE INFO ///

require_once '../init.php';
require_once '../lib/pagetools.php';

openPage($pageid, $friendlyname, $level);
$idCustomerGET = $con->real_escape_string(htmlspecialchars($_GET["idCustomer"]));
$_R_customerExists = $con->query("SELECT `businessName` FROM `customers` WHERE `idCustomer` = '$idCustomerGET'");
$_customerExists = $_R_customerExists->fetch_array(MYSQLI_NUM);
?>

<!-- DELETE INSTALLATION MODAL -->
<div class="modal fade" id="deleteInstallationModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminazione installazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Sei sicuro di voler eliminare l'installazione n&ordm; <strong id="dim.title"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x-octagon"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-danger" onclick="deleteInstallationAJAX(document.getElementById('dim.title').textContent)">
                    <span data-feather="trash"></span>
                    Elimina
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CREATE INSTALLATION MODAL -->
<div class="modal fade" id="createInstallationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuova installazione per il cliente n&ordm; <u><span id="cim.title"></span></u></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="nscB1">
                    <form>
                        <div class="row">
                            <div class="col">
                                <label for="installationAddress">Indirizzo</label>
                                <input type="text" class="form-control" id="installationAddress" required>
                            </div>
                            <div class="col">
                                <label for="installationCity">Città</label>
                                <input type="text" class="form-control" id="installationCity" required>
                            </div>
                        </div>
                        <br>
                        <div class="mb-3">
                            <label for="heater" class="form-label">Marca e modello apparecchio</label>
                            <input type="text" class="form-control" id="heater">
                        </div>
                        <div class="row mb-3">
                            <div class="col col-md-8">
                                <label for="installationType" class="form-label">Tipo installazione</label>
                                <select class="form-select" id="installationType">
                                    <option value="Caldaia" selected>Caldaia</option>
                                    <option value="Pompa di calore">Pompa di calore</option>
                                    <option value="Ibrido">Ibrido</option>
                                    <option value="Climatizzatore">Climatizzatore</option>
                                    <option value="Altro">Altro (Vedi Note)</option>
                                </select>
                            </div>
                            <div class="col col-md-4">
                                <label for="monthlyCallInterval" class="form-label">Intervallo mensile chiamate</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="checkbox" required id="toCall">
                                        <span style="margin-left: 10px;">Da chiamare?</span>
                                    </div>
                                    <input type="number" class="form-control" id="monthlyCallInterval">
                                </div>  
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col col-md-8">
                                <label for="manteinanceContractName" class="form-label">Contratto di manutenzione</label>
                                <input type="text" class="form-control" id="manteinanceContractName">
                            </div>
                            <div class="col col-md-4">
                                <label for="contractExpiryDate" class="form-label">Data di scadenza</label>
                                <input type="date" class="form-control" id="contractExpiryDate">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="footNote" class="form-label">Annotazioni</label>
                            <textarea class="form-control" id="footNote" rows="3"></textarea>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-success" onclick="createInstallationAJAX()">
                    <span data-feather="save"></span>
                    Salva
                </button>
            </div>
        </div>
    </div>
</div>

<!-- EDIT INSTALLATION MODAL -->
<div class="modal fade" id="editInstallationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifica dell'installazione n&ordm; <u><span id="eim.title"></span></u> 
                del cliente n&ordm; <u><span id="eim.idCustomer"></span></u></h5>
                <div class="spinner-modal-container" id="eim.spinner">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="nscB2">
                    <form>
                        <div class="row">
                            <div class="col">
                                <label for="installationAddress">Indirizzo</label>
                                <input type="text" class="form-control" id="eim.installationAddress" required>
                            </div>
                            <div class="col">
                                <label for="installationCity">Città</label>
                                <input type="text" class="form-control" id="eim.installationCity" required>
                            </div>
                        </div>
                        <br>
                        <div class="mb-3">
                            <label for="heater" class="form-label">Marca e modello apparecchio</label>
                            <input type="text" class="form-control" id="eim.heater">
                        </div>
                        <div class="row mb-3">
                            <div class="col col-md-8">
                                <label for="installationType" class="form-label">Tipo installazione</label>
                                <select class="form-select" id="eim.installationType">
                                    <option value="Caldaia" selected>Caldaia</option>
                                    <option value="Pompa di calore">Pompa di calore</option>
                                    <option value="Ibrido">Ibrido</option>
                                    <option value="Climatizzatore">Climatizzatore</option>
                                    <option value="Altro">Altro (Vedi Note)</option>
                                </select>
                            </div>
                            <div class="col col-md-4">
                                <label for="monthlyCallInterval" class="form-label">Intervallo mensile chiamate</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="checkbox" required id="eim.toCall">
                                        <span style="margin-left: 10px;">Da chiamare?</span>
                                    </div>
                                    <input type="number" class="form-control" id="eim.monthlyCallInterval">
                                </div>  
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col col-md-8">
                                <label for="manteinanceContractName" class="form-label">Contratto di manutenzione</label>
                                <input type="text" class="form-control" id="eim.manteinanceContractName">
                            </div>
                            <div class="col col-md-4">
                                <label for="contractExpiryDate" class="form-label">Data di scadenza</label>
                                <input type="date" class="form-control" id="eim.contractExpiryDate">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="footNote" class="form-label">Annotazioni</label>
                            <textarea class="form-control" id="eim.footNote" rows="3"></textarea>
                        </div>
                        <p>Creazione: <strong id="eim.createdAt">...</strong>  -  
                        Ultima modifica: <strong id="eim.updatedAt">...</strong> da <strong id="eim.lastEditedBy">...</strong>  -  
                        Versione: <strong id="eim.version">...</strong></p>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-success" onclick="editInstallationAjax(document.getElementById('eim.title').innerText, document.getElementById('eim.version').innerText)">
                    <span data-feather="save"></span>
                    Salva
                </button>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class='h2'>Gestore Installazioni</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <form action="" method="get">
            <div class="row g-3">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">NUMERO CLIENTE:</span>
                        <input type="number" class="form-control" name="idCustomer" placeholder="ID Cliente" 
                        <?php if (isset($_GET["idCustomer"]) && !empty($_GET["idCustomer"])) {
                            echo 'value="' . $_GET["idCustomer"] . '"';
                        } ?> aria-label="Ricerca" aria-describedby="button-addon2">
                        <button class="btn btn-outline-dark" type="submit" id="button-addon2">
                            <span data-feather="check"></span>
                            Conferma
                        </button>
                    </div>
                </div>
                <?php if ((isset($_GET["idCustomer"]) && !empty($_GET["idCustomer"])) && $_customerExists != null) { ?>
                <div class="col col-md-auto">
                    <button type="button" class="btn btn-outline-dark" 
                    data-bs-toggle="modal" data-bs-target="#viewCustomerModal" data-bs-vcmCid="<?php echo $idCustomerGET; ?>">
                        <span data-feather="users"></span>
                        Visualizza scheda cliente
                    </button>
                </div>
                <?php } ?>
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

<?php if ($_customerExists == null) { ?>
<br>
<center>
    <h3>
        <strong>
            IL CLIENTE SELEZIONATO <br>
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
        <div class="col col-md-auto">
            <button type="button" class="btn btn-outline-dark" 
                data-bs-toggle="modal" data-bs-target="#createInstallationModal" data-bs-cimIid="<?php echo $idCustomerGET ?>">
                    <span data-feather="box"></span>
                    Aggiungi Installazione
            </button>
        </div>
    </div>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="col-md-1">N&ordm; Installazione</th>
            <th class="col-md-2">Indirizzo Installazione</th>
            <th class="col-md-2">Città Installazione</th>
            <th class="col-md-2">Tipo Installazione</th>
            <th class="col-md-2">Marca e mod. apparecchio</th>
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
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editInstallationModal" data-bs-eimIid="<?php echo $row['idInstallation']; ?>">
                                    <span data-feather="edit"></span>
                                    Visualizza o Modifica
                                </a></li>
                            <li><a class="dropdown-item" onclick="amsLaunch('installation<?php echo $row['idInstallation']; ?>')">
                                    <span data-feather="database"></span>
                                    Visualizza in AMS
                                </a></li>
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteInstallationModal" data-bs-dimIid="<?php echo $row['idInstallation']; ?>">
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
closePage($level, $jsdeps, "installation.index.js");