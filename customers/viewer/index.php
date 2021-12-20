<?php
/// PAGE INFO ///
$pageid = 1;
$friendlyname = "Scheda Cliente";
$level = 2;
$jsdeps = array('bootstrap-bundle', 'feathericons');
/// PAGE INFO ///

require_once '../init.php';
require_once '../lib/pagetools.php';

openPage($pageid, $friendlyname, $level);
?>

<?php
closePage($level, $jsdeps);
