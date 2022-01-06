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

function npRelativeToRoot($level = 0)
{
    switch($level){
        case 1:
            return "../";
            break;
        case 2:
            return "../../";
            break;
        case 3:
            return "../../../";
            break;
        default:
            return "";
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

function pexcheck($username)
{
    global $con;
    // permission check code
    // authenticate old username and password combo
    $sql = "SELECT permissionType FROM users WHERE userName = ?";

    if ($stmt = new mysqli_stmt($con, $sql)) {
        $stmt->bind_param("s", $param_username);

        $param_username = $username;

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows() == 1) {
                $stmt->bind_result($permissionType);
                if ($stmt->fetch()) {
                    return $permissionType;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

function convertDate($dbDate){
    return date_format(new DateTime($dbDate), 'd/m/Y H:i');
}