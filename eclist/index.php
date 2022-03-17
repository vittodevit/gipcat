<?php
/// PAGE INFO ///
$pageid = 5;
$friendlyname = "Esporta Elenco Chiamate";
$level = 1;
$jsdeps = array('bootstrap-bundle', 'feathericons', 'jquery', 'toastr');
/// PAGE INFO ///

require_once '../init.php';
require_once '../lib/pagetools.php';
require_once '../lib/miscfun.php';

openPage($pageid, $friendlyname, $level);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class='h2'>Esporta Elenco Chiamate</h1>
</div>

<div class="row justify-content-center" style="margin-top: 8%">
    <div class="col col-md-auto">
        <div class="card" style="width: 30rem;">
            <div class="card-body" id="cardbody">

            </div>
        </div>
    </div>
</div>

<?php
closePage($level, $jsdeps, "eclist.js");
