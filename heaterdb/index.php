<?php
/// PAGE INFO ///
$pageid = 5;
$friendlyname = "Lista Caldaie";
$level = 1;
$jsdeps = array('bootstrap-bundle', 'feathericons');
/// PAGE INFO ///

require_once '../init.php';
require_once '../lib/pagetools.php';

openPage($pageid, $friendlyname, $level);
?>

<?php
closePage($level, $jsdeps);