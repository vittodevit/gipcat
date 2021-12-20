<?php
function relativeToRoot($level = 0)
{
    switch($level){
        case 1:
            echo "../";
            break;
        case 2:
            echo "../../";
            break;
        case 3:
            echo "../../../";
            break;
        default:
            echo "";
    }
}

function checkAriaCurr($id, $pageid)
{
    echo 'class="nav-link';
    if ($id == $pageid) {
        echo ' active" aria-current="page"';
    } else {
        echo '"';
    }
}
