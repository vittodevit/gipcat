<?php
/// PAGE INFO ///
$pageid = 3;
$friendlyname = "Anagrafiche Tecnici";
$level = 1;
$jsdeps = array('bootstrap-bundle', 'feathericons');
/// PAGE INFO ///

require_once '../init.php';
require_once '../lib/pagetools.php';

openPage($pageid, $friendlyname, $level);
?>

<?php
closePage($level, $jsdeps);