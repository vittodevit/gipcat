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

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class='h2'>Home Page</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <form action="" method="get">
            <div class="row g-3">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">SELEZIONA GIORNATA:</span>
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
                </div>
            </div>
        </form>
    </div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th class="col col-md-6 text-center">
                <h5 class="h5t">Calendario del giorno</h5>
            </th>
            <th class="col col-md-6 text-center">
                <h5 class="h5t">Chiamate in sospeso</h5>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <div class="scrollable-list">
                    <?php 
                    if (isset($_GET["date"]) && !empty($_GET["date"])){
                        $date = $con->real_escape_string($_GET["date"]);
                    }else{
                        $date = date("Y-m-d");
                    }
                    $interventionsToday = $con->query("SELECT `idIntervention` FROM `interventions` WHERE `interventionDate` LIKE '%$date%'");
                    if($con->affected_rows < 1){
                        ?> <br><br><br>
                        <center><h5>Nessun intervento<br>per la giornata selezionata</h5></center> <?php
                    }else{
                        while ($intervention = $interventionsToday->fetch_array(MYSQLI_NUM)){
                            printInterventionsCard($intervention[0]);
                        }
                    }
                    ?>
                </div>
            </td>
            <td>
                Work in progress
            </td>
        </tr>
    </tbody>
</table>

<?php
closePage($level, $jsdeps);
