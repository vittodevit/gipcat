<?php

require_once '../init.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

function chgpwd($username, $password)
{
    global $con;
    // temp test code for change
    http_response_code(418);
    die('AJAX: temp test');
}

function selfcheck($username, $password)
{
    global $con;
    // self check code
    // authenticate old username and password combo
    $sql = "SELECT passwordHash FROM users WHERE userName = ?";

    if ($stmt = new mysqli_stmt($con, $sql)) {
        $stmt->bind_param("s", $param_username);

        $param_username = $username;

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows() == 1) {
                $stmt->bind_result($passwordHash);
                if ($stmt->fetch()) {
                    if (password_verify($password, $passwordHash)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
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

if (strcmp($_SESSION["userName"], $_POST["userName"]) == 0) {
    // session user name and required mod username are the same
    // check "self" password
    if (selfcheck($_SESSION["userName"], $_POST["oldPassword"])) {
        chgpwd($_SESSION["userName"], $_POST["newPassword"]);
    } else {
        http_response_code(403);
        die('AJAX: You are not authorized to edit this user.');
    }
} else {
    // check if the target user is in the required pex level for the requesting user
    if ($_SESSION["permissionType"] == 4) {
        // requesting user is a superadmin, no further checks required
        chgpwd($_POST["userName"], $_POST["newPassword"]);
    } else {
        // requesting user is NOT a superadmin, checking if target is customer or technician
        if (pexcheck($_POST["userName"]) == 1 || pexcheck($_POST["userName"]) == 2) {
            // request is authenticated
            chgpwd($_SESSION["userName"], $_POST["newPassword"]);
        } else {
            http_response_code(403);
            die('AJAX: You are not authorized to edit this user.');
        }
    }
}
