<?php
/// PAGE INFO ///
$pageid = 1;
$friendlyname = "Anagrafiche Clienti";
$level = 1;
$jsdeps = array('bootstrap-bundle', 'feathericons', 'jquery', 'toastr');
/// PAGE INFO ///

require_once '../init.php';
require_once '../lib/pagetools.php';

openPage($pageid, $friendlyname, $level);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class='h2'>Anagrafiche Clienti</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
            <span data-feather="folder-plus"></span>
            Nuova scheda cliente
        </button>
    </div>
</div>

<!-- DELETE CUSTOMER MODAL -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminazione scheda cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Sei sicuro di voler eliminare la scheda cliente n&ordm; <strong id="deleteCustomerModalBody"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x-octagon"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-danger" onclick="deleteCustomerAJAX(document.getElementById('deleteCustomerModalBody').textContent)">
                    <span data-feather="trash"></span>
                    Elimina
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CREATE CUSTOMER MODAL -->
<div class="modal fade" id="createCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuova scheda cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="nscB1">
                    <form>
                        <div class="mb-3">
                            <label for="businessName" class="form-label">Ragione sociale</label>
                            <input type="text" class="form-control" id="businessName" required>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="registeredOfficeAddress">Indirizzo</label>
                                <input type="text" class="form-control" id="registeredOfficeAddress" required>
                            </div>
                            <div class="col">
                                <label for="registeredOfficeCity">Città</label>
                                <input type="text" class="form-control" id="registeredOfficeCity" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="headquartersAddress">Indirizzo di fatturazione</label>
                                <input type="text" class="form-control" id="headquartersAddress">
                            </div>
                            <div class="col">
                                <label for="headquartersCity">Città (fatturazione)</label>
                                <input type="text" class="form-control" id="headquartersCity">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="homePhoneNumber">Telefono Fisso (Personale)</label>
                                <input type="text" class="form-control" id="homePhoneNumber">
                            </div>
                            <div class="col">
                                <label for="officePhoneNumber">Telefono Fisso (Aziendale)</label>
                                <input type="text" class="form-control" id="officePhoneNumber">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="privateMobilePhoneNumber">Cellulare (Personale)</label>
                                <input type="text" class="form-control" id="privateMobilePhoneNumber">
                            </div>
                            <div class="col">
                                <label for="companyMobilePhoneNumber">Cellulare (Aziendale)</label>
                                <input type="text" class="form-control" id="companyMobilePhoneNumber">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="privateEMail">E-Mail (Personale)</label>
                                <input type="email" class="form-control" id="privateEMail">
                            </div>
                            <div class="col">
                                <label for="companyEMail">E-Mail (Aziendale)</label>
                                <input type="email" class="form-control" id="companyEMail">
                            </div>
                        </div>
                        <br>
                        <div class="mb-3">
                            <label for="fiscalCode" class="form-label">Codice Fiscale</label>
                            <input type="text" class="form-control" id="fiscalCode">
                        </div>
                        <div class="mb-3">
                            <label for="vatNumber" class="form-label">Partita IVA</label>
                            <input type="text" class="form-control" id="vatNumber">
                        </div>
                        <div class="mb-3">
                            <label for="footNote" class="form-label">Annotazioni</label>
                            <textarea class="form-control" id="footNote" rows="3"></textarea>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-success" onclick="createCustomerAJAX()">
                    <span data-feather="folder-plus"></span>
                    Crea
                </button>
            </div>
        </div>
    </div>
</div>

<!-- EDIT CUSTOMER MODAL -->
<div class="modal fade" id="editCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifica della scheda cliente n&ordm; <u><span id="ecm.title"></span></u></h5>
                <div class="spinner-modal-container" id="ecm.spinner">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="nscB2">
                    <form>
                        <div class="mb-3">
                            <label for="businessName" class="form-label">Ragione sociale</label>
                            <input type="text" class="form-control" id="ecm.businessName">
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="registeredOfficeAddress">Indirizzo</label>
                                <input type="text" class="form-control" id="ecm.registeredOfficeAddress">
                            </div>
                            <div class="col">
                                <label for="registeredOfficeCity">Città</label>
                                <input type="text" class="form-control" id="ecm.registeredOfficeCity">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="headquartersAddress">Indirizzo di fatturazione</label>
                                <input type="text" class="form-control" id="ecm.headquartersAddress">
                            </div>
                            <div class="col">
                                <label for="headquartersCity">Città (fatturazione)</label>
                                <input type="text" class="form-control" id="ecm.headquartersCity">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="homePhoneNumber">Telefono Fisso (Personale)</label>
                                <input type="text" class="form-control" id="ecm.homePhoneNumber">
                            </div>
                            <div class="col">
                                <label for="officePhoneNumber">Telefono Fisso (Aziendale)</label>
                                <input type="text" class="form-control" id="ecm.officePhoneNumber">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="privateMobilePhoneNumber">Cellulare (Personale)</label>
                                <input type="text" class="form-control" id="ecm.privateMobilePhoneNumber">
                            </div>
                            <div class="col">
                                <label for="companyMobilePhoneNumber">Cellulare (Aziendale)</label>
                                <input type="text" class="form-control" id="ecm.companyMobilePhoneNumber">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="privateEMail">E-Mail (Personale)</label>
                                <input type="email" class="form-control" id="ecm.privateEMail">
                            </div>
                            <div class="col">
                                <label for="companyEMail">E-Mail (Aziendale)</label>
                                <input type="email" class="form-control" id="ecm.companyEMail">
                            </div>
                        </div>
                        <br>
                        <div class="mb-3">
                            <label for="fiscalCode" class="form-label">Codice Fiscale</label>
                            <input type="text" class="form-control" id="ecm.fiscalCode">
                        </div>
                        <div class="mb-3">
                            <label for="vatNumber" class="form-label">Partita IVA</label>
                            <input type="text" class="form-control" id="ecm.vatNumber">
                        </div>
                        <div class="mb-3">
                            <label for="footNote" class="form-label">Annotazioni</label>
                            <textarea class="form-control" id="ecm.footNote" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <a class="btn btn-sm btn-outline-dark" onclick="amsLaunch('customer'+document.getElementById('ecm.title').innerText)">
                                <span data-feather="database"></span>
                                Visualizza allegati in AMS
                            </a>
                        </div>
                        <p>Creazione: <strong id="ecm.createdAt">...</strong>  -  
                        Ultima modifica: <strong id="ecm.updatedAt">...</strong> da <strong id="ecm.lastEditedBy">...</strong>  -  
                        Versione: <strong id="ecm.version">...</strong></p>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-success" onclick="editCustomerAjax(document.getElementById('ecm.title').innerText, document.getElementById('ecm.version').innerText)">
                    <span data-feather="save"></span>
                    Salva
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CREATE USER FOR CUSTOMER MODAL -->
<div class="modal fade" id="userCreateCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuovo utente associato al cliente n&ordm; <u><span id="uccm.customerId"></span></u></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <form>
                        <div class="mb-3">
                            <label for="userName" class="form-label">Nome utente</label>
                            <input type="text" class="form-control" id="uccm.userName" required>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="legalName">Nome</label>
                                <input type="text" class="form-control" id="uccm.legalName">
                            </div>
                            <div class="col">
                                <label for="legalSurname">Cognome</label>
                                <input type="text" class="form-control" id="uccm.legalSurname">
                            </div>
                        </div>
                        <br>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Nuova password:</label>
                            <input type="password" class="form-control" id="uccm.newPassword" placeholder="Minimo 8 caratteri">
                            <div class="mt-2">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="uccm.passMeter"
                                            role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Conferma nuova password:</label>
                            <input type="password" class="form-control" id="uccm.confirmPassword">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-success" onclick="userCreateCustomerAJAX(document.getElementById('uccm.customerId').innerText)">
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
            <th class="col-md-1">ID Cliente</th>
            <th class="col-md-3">Nome</th>
            <th class="col-md-3">Indirizzo</th>
            <th class="col-md-2">Città</th>
            <th class="col-md-1">Operazioni</th>
            <th class="col-md-1">Utente Collegato</th>
        </tr>
    </thead>
    <tbody>
        <?php

        if (isset($_GET['query']) && $_GET['query'] != "") {
            $additionalQuery = "";

            $additionalQuery = "WHERE LOWER(
                                    CONCAT(
                                        IFNULL(customers.idCustomer, ''),
                                        '',
                                        IFNULL(customers.businessName, ''),
                                        '',
                                        IFNULL(customers.registeredOfficeAddress, ''),
                                        '',
                                        IFNULL(customers.registeredOfficeCity, '')
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

        $result_count = $con->query("SELECT COUNT(*) As total_records FROM customers $additionalQuery");
        $total_records = $result_count->fetch_array();
        $total_records = $total_records['total_records'];
        $total_no_of_pages = ceil($total_records / $total_records_per_page);
        $second_last = $total_no_of_pages - 1; // total page minus 1

        $query = 
        "SELECT 
            customers.idCustomer, 
            customers.businessName, 
            customers.registeredOfficeAddress, 
            customers.registeredOfficeCity,
            users.userName 
        FROM 
            customers 
        LEFT JOIN users ON(
            customers.idCustomer = users.idCustomer
        )
        $additionalQuery 
        LIMIT $offset, $total_records_per_page
        ";

        $result = $con->query($query);
        while ($row = $result->fetch_array()) {
        ?>
            <tr>
                <td> <?php echo $row['idCustomer']; ?> </td>
                <td> <?php echo $row['businessName']; ?> </td>
                <td> <?php echo $row['registeredOfficeAddress']; ?> </td>
                <td> <?php echo $row['registeredOfficeCity']; ?> </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-outline-dark dropdown-toggle" type="button" id="actionsMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <span data-feather="menu"></span>
                            Operazioni
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="actionsMenu">
                            <li><a class="dropdown-item" href="../installations/?idCustomer=<?php echo $row['idCustomer']; ?>">
                                    <span data-feather="box"></span>
                                    Visualizza Installazioni
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editCustomerModal" data-bs-ecmCid="<?php echo $row['idCustomer']; ?>">
                                    <span data-feather="edit"></span>
                                    Visualizza o Modifica
                                </a></li>
                            <li><a class="dropdown-item" onclick="amsLaunch('customer<?php echo $row['idCustomer']; ?>')">
                                    <span data-feather="database"></span>
                                    Visualizza in AMS
                                </a></li>
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteCustomerModal" data-bs-dcmCid="<?php echo $row['idCustomer']; ?>">
                                <span data-feather="delete"></span>
                                    Elimina
                                </a></li>
                        </ul>
                    </div>
                </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-outline-dark dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <span data-feather="menu"></span>
                            Utente Collegato
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="userMenu">
                            <?php
                            if ($row['userName'] != null) {
                            ?>
                                <li>
                                    <span class="dropdown-item">
                                        <span data-feather="user"></span>
                                        <strong><?php echo $row['userName']; ?></strong>
                                    </span>
                                </li>
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
                            <?php
                            } else {
                            ?>
                                <li>
                                    <span class="dropdown-item">
                                        <span data-feather="minus-circle"></span>
                                        L'utente non esiste
                                    </span>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#userCreateCustomerModal" data-bs-cid="<?php echo $row['idCustomer'] ?>">
                                        <span data-feather="user-plus"></span>
                                        Crea Utente
                                    </a></li>
                            <?php
                            }
                            ?>
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
                paginationButton(true, $second_last, $second_last, $_GET["query"], "Vai a pagina $second_last");
                paginationButton(true, $total_no_of_pages, $total_no_of_pages, $_GET["query"], "Vai a pagina $total_no_of_pages");
            } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                paginationButton(true, "1", "1", $_GET["query"], "Vai a pagina 1");
                paginationButton(true, "1", "2", $_GET["query"], "Vai a pagina 2");
                paginationButton(false, "", "...", $_GET["query"], "Altre pagine");
                for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                    paginationButton(($counter != $page_no), $counter, $counter, $_GET["query"], "Vai a pagina $counter");
                }
                paginationButton(false, "", "...", $_GET["query"], "Altre pagine");
                paginationButton(true, $second_last, $second_last, $_GET["query"], "Vai a pagina $second_last");
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
closePage($level, $jsdeps, "customer.index.js");
