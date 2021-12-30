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
                    data-bs-toggle="modal" data-bs-target="#createInterventionModal" data-bs-cimIid="<?php echo $idInstallationGET ?>">
                        <span data-feather="calendar"></span>
                        Aggiungi Intervento
                    </button>
                </div>
                <?php } ?>
            </div>
        </form>
    </div>
</div>

<img class="mt-10" src="../static/img/workinprogress.png" alt="workinprogress">

<?php
closePage($level, $jsdeps);