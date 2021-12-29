<?php
/// PAGE INFO ///
$pageid = 7;
$friendlyname = "Gestione Globale Utenze";
$level = 1;
$jsdeps = array('bootstrap-bundle', 'feathericons', 'jquery', 'toastr');
/// PAGE INFO ///

require_once '../init.php';
require_once '../lib/pagetools.php';

openPage($pageid, $friendlyname, $level);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class='h2'>Gestione Globale Utenze</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#userCreateUnlockedModal">
            <span data-feather="user-plus"></span>
            Nuovo utente
        </button>
    </div>
</div>

<!-- CREATE USER FOR CUSTOMER MODAL -->
<div class="modal fade" id="userCreateUnlockedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuovo utente piattaforma</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <form>
                        <div class="mb-3">
                            <label for="userName" class="form-label">Nome utente:</label>
                            <input type="text" class="form-control" id="ucum.userName" required>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="legalName" class="form-label">Nome:</label>
                                <input type="text" class="form-control" id="ucum.legalName">
                            </div>
                            <div class="col">
                                <label for="legalSurname" class="form-label">Cognome:</label>
                                <input type="text" class="form-control" id="ucum.legalSurname">
                            </div>
                        </div>
                        <br>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Nuova password:</label>
                            <input type="password" class="form-control" id="ucum.newPassword" placeholder="Minimo 8 caratteri">
                            <div class="mt-2">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="ucum.passMeter"
                                            role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Conferma nuova password:</label>
                            <input type="password" class="form-control" id="ucum.confirmPassword">
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="ucum.permissionType" class="form-label">Tipo Permesso:</label>
                                <select class="form-select" id="ucum.permissionType">
                                    <option selected>Seleziona permesso...</option>
                                    <option value="1">1 &#8594; Cliente</option>
                                    <option value="2">2 &#8594; Tecnico</option>
                                    <option value="3">3 &#8594; Help Desk</option>
                                    <option value="3">4 &#8594; Superadmin</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="legalSurname" class="form-label">ID Cliente collegato:</label>
                                <input type="text" class="form-control" id="ucum.idCustomer" disabled>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-success" onclick="userCreateUnlockedAJAX()">
                    <span data-feather="folder-plus"></span>
                    Crea
                </button>
            </div>
        </div>
    </div>
</div>

<div>
    <form action="" method="get">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="query" value="<?php echo htmlspecialchars($_GET['query']); ?>"
            placeholder="Ricerca" aria-label="Ricerca" aria-describedby="button-addon2">
            <button class="btn btn-outline-dark" type="submit" id="button-addon2">
                <span data-feather="search"></span>
                Cerca
            </button>
            <a class="btn btn-outline-dark" href="./">
                <span data-feather="refresh-ccw"></span>
                Ricarica
            </a>
        </div>
    </form>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="col-md-3">Nome Utente</th>
            <th class="col-md-2">Nome</th>
            <th class="col-md-2">Cognome</th>
            <th class="col-md-2">Livello Permessi</th>
            <th class="col-md-2">ID Cliente Collegato</th>
            <th class="col-md-1">Operazioni</th>
        </tr>
    </thead>
    <tbody>
        <?php

        if (isset($_GET['query']) && $_GET['query'] != "") {
            $additionalQuery = "";

            $additionalQuery = "WHERE LOWER(
                                    CONCAT(
                                        IFNULL(userName, ''),
                                        '',
                                        IFNULL(legalName, ''),
                                        '',
                                        IFNULL(legalSurname, ''),
                                        '',
                                        IFNULL(permissionType, ''),
                                        '',
                                        IFNULL(idCustomer, '')
                                    )
                                ) LIKE LOWER(\"%";
            $additionalQuery .= $con->real_escape_string($_GET["query"]);
            $additionalQuery .= "%\")";
        }

        if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
            $page_no = $_GET['page_no'];
        } else {
            $page_no = 1;
        }

        $total_records_per_page = 10;
        $offset = ($page_no - 1) * $total_records_per_page;
        $previous_page = $page_no - 1;
        $next_page = $page_no + 1;
        $adjacents = "2";

        $result_count = $con->query("SELECT COUNT(*) As total_records FROM users $additionalQuery");
        $total_records = $result_count->fetch_array();
        $total_records = $total_records['total_records'];
        $total_no_of_pages = ceil($total_records / $total_records_per_page);
        $second_last = $total_no_of_pages - 1; // total page minus 1

        $pt = array(
            "1" => "Cliente",
            "2" => "Tecnico",
            "3" => "Help Desk",
            "4" => "Superadmin"
        );

        $result = $con->query("SELECT userName, legalName, legalSurname, permissionType, idCustomer 
                                FROM users 
                                $additionalQuery 
                                LIMIT $offset, $total_records_per_page");
        while ($row = $result->fetch_array()) {
        ?>
            <tr>
                <td> <?php echo $row['userName']; ?> </td>
                <td> <?php echo $row['legalName']; ?> </td>
                <td> <?php echo $row['legalSurname']; ?> </td>
                <td> <?php echo $row['permissionType']." &#8594; ".$pt[$row['permissionType']]?> </td>
                <td> <?php echo empty($row['idCustomer']) ? "Nessuno" : $row['idCustomer'];?> </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-outline-dark dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <span data-feather="menu"></span>
                            Operazioni
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="userMenu">
                                <?php if(!empty($row['idCustomer'])) { ?>
                                    <li><a class="dropdown-item" href="../installations/?idCustomer=<?php echo $row['idCustomer']; ?>">
                                    <span data-feather="users"></span>
                                    <?php echo $row['userName']; ?> <b>(<?php echo $row['idCustomer']; ?>)</b>
                                    </a></li>
                                <?php } else { ?>
                                    <li>
                                    <span class="dropdown-item">
                                        <span data-feather="users"></span>
                                        <strong>Ness. anagrafica coll.</strong>
                                    </span>
                                    </li>
                                <?php } ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#userPassChangeModal" data-bs-username="<?php echo $row['userName']; ?>">
                                        <span data-feather="user-check"></span>
                                        Cambia Password
                                    </a></li>
                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#userDeleteModal" data-bs-username="<?php echo $row['userName']; ?>">
                                        <span data-feather="delete"></span>
                                        Elimina
                                </a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<strong>Page <?php echo $page_no . " of " . $total_no_of_pages; ?></strong>
<br><br>
<nav aria-label="Page navigation">
    <ul class="pagination">
        <?php
        paginationButton(($page_no > 1), $previous_page, "<span aria-hidden=\"true\">&laquo;</span>", $_GET["query"], "Vai alla pagina precedente");

        if ($total_no_of_pages <= 10) {
            for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                paginationButton(($counter != $page_no), $counter, $counter, $_GET["query"], "Vai a pagina $counter");
            }
        } elseif ($total_no_of_pages > 10) {

            if ($page_no <= 4) {
                for ($counter = 1; $counter < 8; $counter++) {
                    paginationButton(($counter != $page_no), $counter, $counter, $_GET["query"], "Vai a pagina $counter");
                }
                paginationButton(false, "", "...", $_GET["query"], "Altre pagine");
                paginationButton(true, $second_last, $second_lasts, $_GET["query"], "Vai a pagina $second_lasts");
                paginationButton(true, $total_no_of_pages, $total_no_of_pages, $_GET["query"], "Vai a pagina $total_no_of_pages");
            } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                paginationButton(true, "1", "1", $_GET["query"], "Vai a pagina 1");
                paginationButton(true, "1", "2", $_GET["query"], "Vai a pagina 2");
                paginationButton(false, "", "...", $_GET["query"], "Altre pagine");
                for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                    paginationButton(($counter != $page_no), $counter, $counter, $_GET["query"], "Vai a pagina $counter");
                }
                paginationButton(false, "", "...", $_GET["query"], "Altre pagine");
                paginationButton(true, $second_last, $second_lasts, $_GET["query"], "Vai a pagina $second_lasts");
                paginationButton(true, $total_no_of_pages, $total_no_of_pages, $_GET["query"], "Vai a pagina $total_no_of_pages");
            } else {
                paginationButton(true, "1", "1", $_GET["query"], "Vai a pagina 1");
                paginationButton(true, "1", "2", $_GET["query"], "Vai a pagina 2");
                paginationButton(false, "", "...", $_GET["query"], "Altre pagine");

                for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                    paginationButton(($counter != $page_no), $counter, $counter, $_GET["query"], "Vai a pagina $counter");
                }
            }
        }
        paginationButton(($page_no < $total_no_of_pages), $next_page, "<span aria-hidden=\"true\">&raquo;</span>", $_GET["query"], "Vai alla prossima pagina");
        paginationButton(($page_no < $total_no_of_pages), $total_no_of_pages, "Ultima", $_GET["query"], "Vai all'ultima pagina");
        ?>
    </ul>
</nav>

<?php
closePage($level, $jsdeps, "users.index.js");