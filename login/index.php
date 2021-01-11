<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: ../");
    exit;
}

require_once '../init.php';
require_once '../lib/pagetools.php';

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Inserisci un username.";
    } else {
        $username = trim($_POST["username"]);
        $escaped_username = $con->real_escape_string($username);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Inserisci una password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT legalName, legalSurname, passwordHash, permissionType FROM users WHERE userName = ?";

        if ($stmt = new mysqli_stmt($con, $sql)) {
            $stmt->bind_param("s", $param_username);

            $param_username = $username;

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows() == 1) {
                    $stmt->bind_result($legalName, $legalSurname, $passwordHash, $permissionType);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $passwordHash)) {
                            // no customers allowed
                            if ($permissionType > 1) {
                                session_start();
                                $_SESSION["loggedin"] = true;
                                $_SESSION["userName"] = $username;
                                $_SESSION["legalName"] = $legalName;
                                $_SESSION["legalSurname"] = $legalSurname;
                                $_SESSION["permissionType"] = $permissionType;
                                header("Location: ../");
                            } else {
                                $username_err = "Questo utente non è autorizzato ad accedere a questa sezione";
                            }
                        } else {
                            $password_err = "Utente sconosciuto";
                        }
                    }
                } else {
                    $password_err = "Utente sconosciuto";
                }
            } else {
                echo "Errore interno :/";
            }
        }

        $stmt->close();
    }

    $con->close();
}

loginPage($username_err, $password_err);