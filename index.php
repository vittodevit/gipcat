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
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class='h2'>Home Page</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <form action="" method="get">
            <div class="row g-3">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">SELEZIONA GIORNATA:</span>
                        <input type="date" class="form-control" name="date" placeholder="ID Cliente" 
                        <?php if (isset($_GET["date"]) && !empty($_GET["date"])) {
                            echo 'value="' . $_GET["date"] . '"';
                        }else{
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

<img class="mt-10" src="./static/img/workinprogress.png" alt="workinprogress">

<?php
closePage($level, $jsdeps);