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
<br>
<center>
    <h3>
        <strong>
            NESSUN CLIENTE SELEZIONATO
        </strong>
    </h3>
</center>
<br>
<br>
<?php closePage($level, $jsdeps); } ?>

<?php
closePage($level, $jsdeps);
