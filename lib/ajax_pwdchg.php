<?php

require_once '../init.php';
require_once 'miscfun.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permissionType"] < 3) {
    http_response_code(401);
    die('AJAX: You are not authenticated! Please provide a session cookie.');
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(405);
    die('AJAX: This method is not allowed!');
}

if (
    !isset($_POST["userName"]) || empty($_POST["userName"]) ||
    !isset($_POST["newPassword"]) || empty($_POST["newPassword"])
) {
    http_response_code(400);
    die('AJAX: Required fields are missing!');
}

$un = $con->real_escape_string($_POST['userName']);

$res = $con->query("SELECT `version` FROM `users` WHERE `userName` = '$un'");

if ($con->affected_rows != 1) {
    http_response_code(404);
    die('AJAX: User not found!');
}

$arr = $res->fetch_assoc();
$version = $arr['version'];

if ($_POST['version'] != $version) {
    http_response_code(400);
    die('AJAX: The version number for the entry provided by the client does not match
    with the one stored on the server. Try refreshing your page.');
}

function chgpwd($username, $password)
{
    global $con;
    global $version;
    // temp test code for change
    $leb = $_SESSION['userName'];
    $ver = $version + 1;
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $user = $con->real_escape_string($username);
    $query =    "UPDATE `users` SET 
                `passwordHash` = '$passwordHash',
                `lastEditedBy` = '$leb',
                `version` = '$ver',
                `updatedAt` = now()
                WHERE `userName` = '$user'";
    $con->query($query);
    if ($con->errno) {
        http_response_code(500);
        die('AJAX: MYSQL ERROR MY-'.$con->errno.' -> '.$con->error);
    }
    if ($con->affected_rows == 1) {
        die('AJAX: OK!');
    } else {
        http_response_code(404);
        die('AJAX: User not found, update failed.');
    }
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
            chgpwd($_POST["userName"], $_POST["newPassword"]);
        } else {
            http_response_code(403);
            die('AJAX: You are not authorized to edit this user.');
        }
    }
}
