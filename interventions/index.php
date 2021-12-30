<?php
/// PAGE INFO ///
$pageid = 4;
$friendlyname = "Gestione Interventi";
$level = 1;
$jsdeps = array('bootstrap-bundle', 'feathericons', 'jquery', 'toastr');
/// PAGE INFO ///

require_once '../init.php';
require_once '../lib/pagetools.php';

openPage($pageid, $friendlyname, $level);
$idInstallationGET = $con->real_escape_string(htmlspecialchars($_GET["idInstallation"]));
$_R_installationExists = $con->query("SELECT `idCustomer`, `installationAddress`, `installationCity`, `heater`, 
                                    `installationType`, `manteinanceContractName`, `toCall`, `monthlyCallInterval`
                                    FROM `installations` WHERE `idInstallation` = '$idInstallationGET'");
$_installationExists = $_R_installationExists->fetch_array(MYSQLI_BOTH);
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
                <?php if (isset($_GET["idInstallation"]) && !empty($_GET["idInstallation"])) { ?>
                <div class="col col-md-auto">
                    <button type="button" class="btn btn-outline-dark" 
                    data-bs-toggle="modal" data-bs-target="#viewCustomerModal" data-bs-vcmCid="<?php echo $_installationExists[0]; ?>">
                        <span data-feather="users"></span>
                        Visualizza scheda cliente
                    </button>
                </div>
                <div class="col col-md-auto">
                    <button type="button" class="btn btn-outline-dark" onclick="location.href = '../installations/?idCustomer=<?php echo $_installationExists[0] ?>'">
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
        <label for="preview.customer" class="form-label">Cliente collegato:</label>
        <input type="text" class="form-control" id="preview.customer" disabled 
        value="<?php 
        $idcg = $_installationExists[0];
        $res = $con->query("SELECT businessName FROM customers WHERE idCustomer = $idcg");
        $fet = $res->fetch_array(MYSQLI_NUM);
        echo $fet[0];
        ?>">
    </div>
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
        <label for="preview.heater" class="form-label">Marca e mod. apparecchio:</label>
        <input type="text" class="form-control" id="preview.heater" disabled 
        value="<?php echo $_installationExists['heater'] ?>">
    </div>
    <div class="col col-md-2">
        <label for="preview.manteinanceContractName" class="form-label">Contratto di manutenzione:</label>
        <input type="text" class="form-control" id="preview.manteinanceContractName" disabled 
        value="<?php echo $_installationExists['manteinanceContractName'] ?>">
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

<h4>tabella che devo ancora fare</h4>
<img class="mt-10" src="../static/img/workinprogress.png" alt="workinprogress">

<?php
closePage($level, $jsdeps);