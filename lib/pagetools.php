<?php
include 'miscfun.php';

function openPage($pageid, $title, $level, $customcss = "")
{
    global $config;
    if ($config["INST_NAME"] != "") {
        $installationName = $config["INST_NAME"];
    } else {
        $installationName = "Gestionale";
    }
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("Location: " . npRelativeToRoot($level) . "login/");
        exit;
    }
    if($_SESSION["permissionType"] < 3 && $pageid != 0){
        header("Location: " . npRelativeToRoot($level) . "login/");
        exit;
    }
?>
    <!DOCTYPE html>
    <html lang="it">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>GIPCAT - <?php echo $title ?></title>

        <?php require 'css.deps.php' ?>

        <link rel="apple-touch-icon" sizes="57x57" href="<?php relativeToRoot($level); ?>static/img/branding/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php relativeToRoot($level); ?>static/img/branding/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php relativeToRoot($level); ?>static/img/branding/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php relativeToRoot($level); ?>static/img/branding/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php relativeToRoot($level); ?>static/img/branding/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php relativeToRoot($level); ?>static/img/branding/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php relativeToRoot($level); ?>static/img/branding/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php relativeToRoot($level); ?>static/img/branding/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php relativeToRoot($level); ?>static/img/branding/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192" href="<?php relativeToRoot($level); ?>static/img/branding/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php relativeToRoot($level); ?>static/img/branding/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="<?php relativeToRoot($level); ?>static/img/branding/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo relativeToRoot($level); ?>static/img/branding/favicon-16x16.png">
        <link rel="manifest" href="<?php echo relativeToRoot($level); ?>manifest.json">
        <meta name="msapplication-TileColor" content="#1F255E">
        <meta name="msapplication-TileImage" content="<?php echo relativeToRoot($level); ?>static/img/branding/ms-icon-144x144.png">
        <meta name="theme-color" content="#1F255E">

        <link href="<?php echo relativeToRoot($level); ?>static/css/gipcat.css" rel="stylesheet">

        <?php
        if ($customcss != "") {
            echo '<link href="' . relativeToRoot($level) . 'static/css/' . $customcss . '" rel="stylesheet">';
        }
        ?>

        <script>
            var relativeToRoot = "<?php echo relativeToRoot($level); ?>";
            var sessionUserName = "<?php echo $_SESSION['userName']; ?>";
        </script>
    </head>

    <body>

        <?php if($_SESSION["permissionType"] < 3){ ?>
            
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <img src="<?php echo relativeToRoot($level); ?>static/img/branding/favicon-32x32.png" alt="logo" style="margin-left: 10px;">
            <span style="margin-right: 10px;"></span>
            <a class="nav-mobi link-light text-end" href="<?php relativeToRoot($level); ?>login/logout.php">
                <span data-feather="user"></span>
                <?php echo $_SESSION["legalName"] . " " . $_SESSION["legalSurname"] ?> -
                Esci
            </a>   
        </header>
             
        <?php }else{ ?>

        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="<?php relativeToRoot($level); ?>">
                <img src="<?php echo relativeToRoot($level); ?>static/img/branding/favicon-32x32.png" alt="logo">
                <span style="margin-right: 10px;"></span>
                <?php echo $installationName ?>
            </a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-nav">
                <div class="nav-item text-nowrap">
                    <a class="nav-link px-3" href="<?php relativeToRoot($level); ?>login/logout.php">
                        <span data-feather="user"></span>
                        <?php echo $_SESSION["legalName"] . " " . $_SESSION["legalSurname"] ?> -
                        Esci
                    </a>
                </div>
            </div>
        </header>

        <div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3">
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Generale</span>
                        </h6>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a <?php checkAriaCurr(0, $pageid) ?> href="<?php relativeToRoot($level); ?>">
                                    <span data-feather="home"></span>
                                    Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a <?php checkAriaCurr(1, $pageid) ?> href="<?php relativeToRoot($level); ?>customers/">
                                    <span data-feather="users"></span>
                                    Anagrafiche Clienti
                                </a>
                            </li>
                            <li class="nav-item">
                                <a <?php checkAriaCurr(2, $pageid) ?> href="<?php relativeToRoot($level); ?>installations/">
                                    <span data-feather="box"></span>
                                    Gestore Installazioni
                                </a>
                            </li>
                            <li class="nav-item">
                                <a <?php checkAriaCurr(4, $pageid) ?> href="<?php relativeToRoot($level); ?>interventions/">
                                    <span data-feather="calendar"></span>
                                    Gestore Interventi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a <?php checkAriaCurr(5, $pageid) ?> href="<?php relativeToRoot($level); ?>eclist/">
                                    <span data-feather="phone-forwarded"></span>
                                    Esporta Elenco Chiamate
                                </a>
                            </li>
                        </ul>
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Utenti</span>
                        </h6>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a <?php checkAriaCurr(6, $pageid) ?> href="#" data-bs-toggle="modal" data-bs-target="#userPassChangeModal" 
                                data-bs-username="<?php echo $_SESSION["userName"] ?>">
                                    <span data-feather="key"></span>
                                    Cambio Propria Password
                                </a>
                            </li>
                        </ul>
                        <?php if ($_SESSION["permissionType"] == 4) { ?>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a <?php checkAriaCurr(7, $pageid) ?> href="<?php relativeToRoot($level); ?>users/">
                                        <span data-feather="user-check"></span>
                                        Gestione Utenze
                                    </a>
                                </li>
                            </ul>
                        <?php } ?>
                    </div>
                </nav>
                <!-- PASSWORD CHANGE MODAL CODE -->
                <div class="modal fade" id="userPassChangeModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Cambiamento password per l'utente <u><span id="upcm.title"></span></u></h5>
                                <div class="spinner-modal-container" id="upcm.spinner">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3 visually-hidden" id="upcm.oldPasswordContainer">
                                    <label for="spcm.oldPassword" class="form-label">Vecchia password:</label>
                                    <input type="password" class="form-control" id="upcm.oldPassword">
                                </div>
                                <div class="mb-3">
                                    <label for="spcm.newPassword" class="form-label">Nuova password:</label>
                                    <input type="password" class="form-control" id="upcm.newPassword" placeholder="Minimo 8 caratteri">
                                    <div class="mt-2">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" id="upcm.passMeter"
                                                    role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="spcm.confirmPassword" class="form-label">Conferma nuova password:</label>
                                    <input type="password" class="form-control" id="upcm.confirmPassword">
                                </div>
                                <input type="hidden" id="upcm.isSelf" value="false">
                                <p>Creazione: <strong id="upcm.createdAt">...</strong>  -  
                            Ultima modifica: <strong id="upcm.updatedAt">...</strong> Ultima modifica da: <strong id="upcm.lastEditedBy">...</strong>  -  
                            Versione: <strong id="upcm.version">...</strong></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <span data-feather="x-octagon"></span>
                                    Annulla
                                </button>
                                <button type="button" class="btn btn-success" onclick="userChangePasswordAJAX(document.getElementById('upcm.isSelf').value)">
                                    <span data-feather="save"></span>
                                    Salva
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
               
                    <!-- DELETE USER MODAL -->
                    <div class="modal fade" id="userDeleteModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Eliminazione utente piattaforma</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Sei sicuro di voler completamente eliminare l'utente <strong id="dum.title"></strong> dal gestionale?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <span data-feather="x-octagon"></span>
                                        Annulla
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="deleteUserAJAX()">
                                        <span data-feather="trash"></span>
                                        Elimina
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>
                
                <!-- VIEW INSTALLATION MODAL -->
                <div class="modal fade" id="viewInstallationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Installazione n&ordm; <u><span id="vim.title"></span></u> 
                                del cliente n&ordm; <u><span id="vim.idCustomer"></span></u></h5>
                                <div class="spinner-modal-container" id="vim.spinner">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="nscB3">
                                    <form>
                                        <div class="row">
                                            <div class="col">
                                                <label for="vim.installationAddress">Indirizzo</label>
                                                <input type="text" class="form-control" id="vim.installationAddress" disabled>
                                            </div>
                                            <div class="col">
                                                <label for="vim.installationCity">Città</label>
                                                <input type="text" class="form-control" id="vim.installationCity" disabled>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row mb-3">
                                            <div class="col col-md-4">
                                                <label for="vim.heaterBrand" class="form-label">Marca</label>
                                                <input type="text" class="form-control" id="vim.heaterBrand" disabled>
                                            </div>
                                            <div class="col col-md-4">
                                                <label for="vim.heater" class="form-label">Modello</label>
                                                <input type="text" class="form-control" id="vim.heater" disabled>
                                            </div>
                                            <div class="col col-md-4">
                                                <label for="vim.heaterSerialNumber" class="form-label">Matricola</label>
                                                <input type="text" class="form-control" id="vim.heaterSerialNumber" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col col-md-8">
                                                <label for="vim.installationType" class="form-label">Tipo installazione</label>
                                                <select class="form-select" id="vim.installationType" disabled>
                                                    <option value="Caldaia" selected>Caldaia</option>
                                                    <option value="Pompa di calore">Pompa di calore</option>
                                                    <option value="Ibrido">Ibrido</option>
                                                    <option value="Climatizzatore">Climatizzatore</option>
                                                    <option value="Altro">Altro (Vedi Note)</option>
                                                </select>
                                            </div>
                                            <div class="col col-md-4">
                                                <label for="vim.monthlyCallInterval" class="form-label">Intervallo mensile chiamate</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-text">
                                                        <input class="form-check-input mt-0" type="checkbox" required id="vim.toCall" disabled>
                                                        <?php if($_SESSION["permissionType"] > 2){ ?>
                                                            <span style="margin-left: 10px;">Da chiamare?</span>
                                                        <?php } ?>
                                                    </div>
                                                    <input type="text" class="form-control" id="vim.monthlyCallInterval" disabled>
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col col-md-8">
                                                <label for="vim.manteinanceContractName" class="form-label">Contr. Manut.</label>
                                                <input type="text" class="form-control" id="vim.manteinanceContractName" disabled>
                                            </div>
                                            <div class="col col-md-4">
                                                <label for="vim.contractExpiryDate" class="form-label">Data di scadenza</label>
                                                <input type="date" class="form-control" id="vim.contractExpiryDate" disabled>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="vim.footNote" class="form-label">Annotazioni</label>
                                            <textarea class="form-control" id="vim.footNote" rows="3" disabled></textarea>
                                        </div>
                                        <?php if($_SESSION["permissionType"] > 2){ ?>
                                            <!-- <div class="mb-3">
                                                <a class="btn btn-sm btn-outline-dark" onclick="amsLaunch('installation'+document.getElementById('vim.title').innerText)">
                                                    <span data-feather="database"></span>
                                                    Visualizza allegati in AMS
                                                </a>
                                            </div> -->
                                        <?php } ?>
                                        <p>Creazione: <strong id="vim.createdAt">...</strong>  -  
                                        Ultima modifica: <strong id="vim.updatedAt">...</strong> da <strong id="vim.lastEditedBy">...</strong>  -  
                                        Versione: <strong id="vim.version">...</strong></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 

                <!-- VIEW CUSTOMER MODAL -->
                <div class="modal fade" id="viewCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Scheda cliente n&ordm; <u><span id="vcm.title"></span></u></h5>
                                <div class="spinner-modal-container" id="vcm.spinner">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="vcm">
                                    <form>
                                        <div class="mb-3">
                                            <label for="businessName" class="form-label">Ragione sociale</label>
                                            <input type="text" class="form-control" id="vcm.businessName" disabled>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <label for="registeredOfficeAddress">Indirizzo</label>
                                                <input type="text" class="form-control" id="vcm.registeredOfficeAddress" disabled>
                                            </div>
                                            <div class="col">
                                                <label for="registeredOfficeCity">Città</label>
                                                <input type="text" class="form-control" id="vcm.registeredOfficeCity" disabled>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col">
                                                <label for="headquartersAddress">Indirizzo di fatturazione</label>
                                                <input type="text" class="form-control" id="vcm.headquartersAddress" disabled>
                                            </div>
                                            <div class="col">
                                                <label for="headquartersCity">Città (fatturazione)</label>
                                                <input type="text" class="form-control" id="vcm.headquartersCity" disabled>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col">
                                                <label for="homePhoneNumber">Telefono Fisso (Personale)</label>
                                                <input type="text" class="form-control" id="vcm.homePhoneNumber" disabled>
                                            </div>
                                            <div class="col">
                                                <label for="officePhoneNumber">Telefono Fisso (Aziendale)</label>
                                                <input type="text" class="form-control" id="vcm.officePhoneNumber" disabled>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col">
                                                <label for="privateMobilePhoneNumber">Cellulare (Personale)</label>
                                                <input type="text" class="form-control" id="vcm.privateMobilePhoneNumber" disabled>
                                            </div>
                                            <div class="col">
                                                <label for="companyMobilePhoneNumber">Cellulare (Aziendale)</label>
                                                <input type="text" class="form-control" id="vcm.companyMobilePhoneNumber" disabled>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col">
                                                <label for="privateEMail">E-Mail (Personale)</label>
                                                <input type="email" class="form-control" id="vcm.privateEMail" disabled>
                                            </div>
                                            <div class="col">
                                                <label for="companyEMail">E-Mail (Aziendale)</label>
                                                <input type="email" class="form-control" id="vcm.companyEMail" disabled>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="mb-3">
                                            <label for="fiscalCode" class="form-label">Codice Fiscale</label>
                                            <input type="text" class="form-control" id="vcm.fiscalCode" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="vatNumber" class="form-label">Partita IVA</label>
                                            <input type="text" class="form-control" id="vcm.vatNumber" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="footNote" class="form-label">Annotazioni</label>
                                            <textarea class="form-control" id="vcm.footNote" rows="3" disabled></textarea>
                                        </div>
                                        <?php if($_SESSION["permissionType"] > 2){ ?>
                                            <!-- <div class="mb-3">
                                                <a class="btn btn-sm btn-outline-dark" onclick="amsLaunch('customer'+document.getElementById('vcm.title').innerText)">
                                                    <span data-feather="database"></span>
                                                    Visualizza allegati in AMS
                                                </a>
                                            </div> -->
                                        <?php } ?>
                                        <p>Creazione: <strong id="vcm.createdAt">...</strong>  -  
                                        Ultima modifica: <strong id="vcm.updatedAt">...</strong> da <strong id="vcm.lastEditedBy">...</strong>  -  
                                        Versione: <strong id="vcm.version">...</strong></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if($_SESSION["permissionType"] < 3){ ?>
                    <main style="margin: 10px;">
                <?php }else{ ?>
                    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?php }
                }

function closePage($level, $js_d, $customjs = "")
{
                    global $jsdeps;
                    global $con;
                    ?>
                </main>
            </div>
        </div>
        <?php 
            require 'js.deps.php';
            foreach($js_d as $dep){
                echo $jsdeps[$dep];
            }
        ?>
        <script src="<?php echo relativeToRoot($level); ?>static/js/gipcat.js"></script>
        <?php if ($customjs != "") { ?>
            <script src="<?php echo relativeToRoot($level); ?>static/js/<?php echo $customjs ?>"></script>
        <?php } ?>
    </body>
    </html>
    <?php
    $con->close();
    die();
}

function loginPage($username_err, $password_err)
{
    ?>
    <!DOCTYPE html>
    <html lang="it">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>GIPCAT</title>

        <?php require 'css.deps.php' ?>

        <link rel="apple-touch-icon" sizes="57x57" href="../static/img/branding/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="../static/img/branding/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="../static/img/branding/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="../static/img/branding/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="../static/img/branding/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="../static/img/branding/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="../static/img/branding/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="../static/img/branding/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="../static/img/branding/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192" href="../static/img/branding/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../static/img/branding/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="../static/img/branding/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../static/img/branding/favicon-16x16.png">
        <link rel="manifest" href="../manifest.json">
        <meta name="msapplication-TileColor" content="#1F255E">
        <meta name="msapplication-TileImage" content="../static/img/branding/ms-icon-144x144.png">
        <meta name="theme-color" content="#1F255E">

        <link href="../static/css/login.css" rel="stylesheet">
    </head>

    <body class="text-center">
    
        <main class="form-signin">
            <form action="" method="POST">
                <img src="../static/img/branding/apple-icon-180x180.png" alt="gipcatlogo" width="180" height="180">

                <h1 class="h3 mb-3 fw-normal">Effettua l'accesso</h1>

                <div class="form-floating">
                    <input type="text" class="form-control" id="username" name="username" placeholder="name@example.com">
                    <label for="username">
                        <span data-feather="user"></span>
                        Username
                    </label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    <label for="password">
                        <span data-feather="key"></span>
                        Password
                    </label>
                </div>
                <button class="w-100 btn btn-lg btn-outline-dark" type="submit">Accedi</button>
                <p class="mt-5 mb-3 text-muted ">
                    &copy; 2021 Vittorio Lo Mele / Cusenza Service <br>
                    Software protetto da licenza 
                    <a class="link-dark" href="https://www.gnu.org/licenses/agpl-3.0.html">AGPLV3</a>
                    Codice sorgente su 
                    <a class="link-dark" href="https://github.com/vittodevit/gipcat-fe-php">GitHub</a>
                </p>
            </form>
        </main>
        <?php
        require 'js.deps.php';
        echo $jsdeps["bootstrap-bundle"];
        echo $jsdeps["jquery"];
        echo $jsdeps["feathericons"];
        echo $jsdeps["toastr"];
        ?>
        <script>
            feather.replace({ 'aria-hidden': 'true' })
            <?php 
            if(!empty($username_err)){
                echo "toastr.error(\"$username_err\");";
            }
            if(!empty($password_err)){
                echo "toastr.error(\"$password_err\");";
            }
            ?>
        </script>
    </body>
    </html>
<?php
}

function paginationButton($enabled, $link, $text, $query, $label = ""){
    if($enabled){
    ?>
    <li class="page-item">
        <a class="page-link" href="?page_no=<?php 
            echo $link;
            if(!empty($query)){
                echo '&query='.htmlspecialchars($query);
            }
        ?>" aria-label="<?php echo $label ?>">
            <?php echo $text ?>
        </a>
    </li>
    <?php
    }else{
        ?>
        <li class="page-item disabled">
            <a class="page-link" href="#" aria-label="<?php echo $label ?>">
                <?php echo $text ?>
            </a>
        </li>
        <?php 
    }
}

function printInterventionsCard($data){
    $IS = array(
        array("Programmato", "orange"),
        array("Eseguito", "green"),
        array("Annullato", "red")
    );
    if($data['userName'] == null){
        $at = "Nessuno";
    }else{
        $at = "[".$data['userName']."] ".$data['legalName']." ".$data['legalSurname'];
        if($data['color'] != null){
            $color = $data['color'];
            $at .= " <span style='color: $color;'>&#9632;</span>";
        }
    }
?>
<div class="card mb-3 scrollbar-w">
    <div class="card-header"
    <?php 
        if($data['userName'] != null){
            $color = $data['color'];
            if($color != null){
                echo "style=\"background-color: $color;\"";
            }
        }
    ?>
    >
        <div class="row">
            <div class="col col-md-10">
                <span data-feather="clock"></span>
                <b><?php echo $data['interventionTimeStart'] ?> &#8594; <?php echo $data['interventionTimeEnd'] ?> - <?php echo $data['interventionType'] ?></b>
            </div>
            <div class="col col-md-2 text-end">
                <div class="dropdown">
                    <a class="link-dark" role="button" id="drpd1" data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-feather="menu"></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="drpd1">
                        <li><a class="dropdown-item" 
                            data-bs-toggle="modal" data-bs-target="#viewCustomerModal" data-bs-vcmCid="<?php echo $data['idCustomer']; ?>">
                            <span data-feather="user"></span>
                            Visualizza Scheda Cliente</a></li>
                            
                        <li><a class="dropdown-item"
                        data-bs-toggle="modal" data-bs-target="#viewInstallationModal" data-bs-vimIid="<?php echo $data['idInstallation']; ?>">
                            <span data-feather="box"></span>
                            Visualizza Scheda Installazione</a></li>

                        <?php if($_SESSION["permissionType"] > 2){ ?>
                            <li><a class="dropdown-item" href="./interventions/?idInstallation=<?php echo $data['idInstallation']; ?>">
                            <span data-feather="calendar"></span>
                            Visualizza Storico Interventi</a></li>
                        <?php } ?>

                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editInterventionModal" data-bs-eimIid="<?php echo $data['idIntervention']; ?>">
                            <span data-feather="edit"></span>
                            Visualizza o Modifica Intervento </a></li>

                        <?php if($_SESSION["permissionType"] > 2){ ?>
                            <!-- <li><a class="dropdown-item" onclick="amsLaunch('intervention<?php //echo $data['idIntervention']; ?>')">
                            <span data-feather="database"></span>
                            Visualizza Intervento in AMS </a></li> -->
                        <?php } ?>

                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteInterventionModal" data-bs-dimIid="<?php echo $data['idIntervention']; ?>">
                            <span data-feather="delete"></span>
                            Elimina Intervento </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <span data-feather="user"></span>
        <b>Cliente:</b> <?php echo $data['businessName'] ?>
        <br>
        <span data-feather="compass"></span>
        <b>Indirizzo Installazione:</b> <?php echo $data['installationAddress']." - ".$data['installationCity'] ?>
        <br>
        <span data-feather="home"></span>
        <b>Tipo Installazione:</b> <?php echo $data['installationType'] ?>
        <br>
        <span data-feather="box"></span>
        <b>Marca e Modello:</b> <?php echo $data['heaterBrand']." ".$data['heater'] ?>
        <hr style="margin-top: 8px; margin-bottom: 8px;">
        <span data-feather="check"></span>
        <b>Stato Intervento: <span style="color:<?php echo $IS[$data['interventionState']][1] ?> ;"><?php echo $IS[$data['interventionState']][0] ?></span></b> 
        <br>
        <span data-feather="tool"></span>
        <b>Assegnato a:</b> <?php echo $at ?>
    </div>
</div>
<?php
}

function printInterventionsNBCard($data){
    $IS = array(
        array("Programmato", "orange"),
        array("Eseguito", "green"),
        array("Annullato", "red")
    );
    if($data['userName'] == null){
        $at = "Nessuno";
    }else{
        $at = "[".$data['userName']."] ".$data['legalName']." ".$data['legalSurname'];
        if($data['color'] != null){
            $color = $data['color'];
            $at .= " <span style='color: $color;'>&#9632;</span>";
        }
    }
?>
<div class="card mb-3 scrollbar-w">
    <div class="card-header"
    <?php 
        if($data['userName'] != null){
            $color = $data['color'];
            if($color != null){
                echo "style=\"background-color: $color;\"";
            }
        }
    ?>
    >
        <div class="row">
            <div class="col col-md-10">
                <span data-feather="clock"></span>
                <b><?php echo $data['interventionTimeStart'] ?> &#8594; <?php echo $data['interventionTimeEnd'] ?></b>
            </div>
            <div class="col col-md-2 text-end">
                <div class="dropdown">
                    <a class="link-dark" role="button" id="drpd1" data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-feather="menu"></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="drpd1">
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editInterventionNBModal" data-bs-einbmIid="<?php echo $data['idIntervention']; ?>">
                            <span data-feather="edit"></span>
                            Visualizza o Modifica Intervento </a></li>

                        <?php if($_SESSION["permissionType"] > 2){ ?>
                            <!-- <li><a class="dropdown-item" onclick="amsLaunch('intervention<?php //echo $data['idIntervention']; ?>')">
                            <span data-feather="database"></span>
                            Visualizza Intervento in AMS </a></li> -->
                        <?php } ?>

                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteInterventionNBModal" data-bs-dinbmIid="<?php echo $data['idIntervention']; ?>">
                            <span data-feather="delete"></span>
                            Elimina Intervento </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <p>
            <?php echo $data['footNote'] ?>
        </p>
        <hr style="margin-top: 8px; margin-bottom: 8px;">
        <span data-feather="check"></span>
        <b>Stato Intervento: <span style="color:<?php echo $IS[$data['interventionState']][1] ?> ;"><?php echo $IS[$data['interventionState']][0] ?></span></b> 
        <br>
        <span data-feather="tool"></span>
        <b>Assegnato a:</b> <?php echo $at ?>
    </div>
</div>
<?php
}

function printCallCard($data){ ?>
<div class="card mb-3 scrollbar-w">
    <div class="card-header">
        <div class="row">
            <div class="col col-md-10">
                <span data-feather="phone-call"></span>
                <b>Chiamata per </b> <?php echo $data['businessName'] ?>
            </div>
            <div class="col col-md-2 text-end">
                <div class="dropdown">
                    <a class="link-dark" role="button" id="drpd1" data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-feather="menu"></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="drpd1">
                        <li><a class="dropdown-item" 
                            data-bs-toggle="modal" data-bs-target="#createInterventionModal" data-bs-cimIid="<?php echo $data['idInstallation'] ?>">
                                <span data-feather="calendar"></span>
                                Programma Intervento
                            </a></li>

                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" 
                            data-bs-toggle="modal" data-bs-target="#doNotCallModal" data-bs-dncIid="<?php echo $data['idInstallation']; ?>">
                            <span data-feather="slash"></span>
                            Non Chiamare Più</a></li>

                        <li><a class="dropdown-item" 
                            data-bs-toggle="modal" data-bs-target="#postponeCallModal" data-bs-pcmIid="<?php echo $data['idIntervention']; ?>">
                            <span data-feather="skip-forward"></span>
                            Postponi Chiamata</a></li>

                        <li><a class="dropdown-item" 
                            data-bs-toggle="modal" data-bs-target="#annotationsModal" data-bs-amIid="<?php echo $data['idIntervention']; ?>">
                            <span data-feather="paperclip"></span>
                            Aggiungi Annotazione</a></li>

                            <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" 
                            data-bs-toggle="modal" data-bs-target="#viewCustomerModal" data-bs-vcmCid="<?php echo $data['idCustomer']; ?>">
                            <span data-feather="user"></span>
                            Visualizza Scheda Cliente</a></li>
                            
                        <li><a class="dropdown-item"
                            data-bs-toggle="modal" data-bs-target="#viewInstallationModal" data-bs-vimIid="<?php echo $data['idInstallation']; ?>">
                            <span data-feather="box"></span>
                            Visualizza Scheda Installazione</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col col-md-11">
                <span data-feather="file-text"></span>
                <b>Contratto di Manutenzione:</b> <?php echo $data['manteinanceContractName'] ?>
                <br>
                <span data-feather="compass"></span>
                <b>Indirizzo Installazione:</b> <?php echo $data['installationAddress']." - ".$data['installationCity'] ?>
                <br>
                <span data-feather="home"></span>
                <b>Tipo Installazione:</b> <?php echo $data['installationType'] ?>
                <br>
                <span data-feather="box"></span>
                <b>Marca e Modello:</b> <?php echo $data['heaterBrand']." ".$data['heater'] ?>
            </div>
            <div class="col col-md-1 text-end">
                <?php if(!empty($data['associatedCallNote'])){ ?>
                    <span data-feather="paperclip" data-bs-toggle="tooltip" data-bs-placement="right" 
                    title="Annotazioni presenti per questa chiamata.">
                        Annotazioni presenti per questa chiamata.
                    </span>  
                <?php } ?>
                <br>
                <?php if(!empty($data['associatedCallPosticipationDate'])){ ?>
                    <span data-feather="skip-forward" data-bs-toggle="tooltip" data-bs-placement="right" 
                    title="Rimandata al <?php echo $data['associatedCallPosticipationDate'] ?>">
                        Rimandata al <?php echo $data['associatedCallPosticipationDate'] ?>
                    </span>
                <?php } ?>
            </div>
        </div>
        <hr style="margin-top: 8px; margin-bottom: 8px;">
        <span data-feather="calendar"></span>
        <b>Ultimo Intervento: </b> <?php echo convertDate($data['interventionDate']) ?>
        <br>
        <span data-feather="tool"></span>
        <b>Tipo Ultimo Intervento</b> <?php echo $data['interventionType'] ?>
    </div>
</div>
<?php
}

function printInterventionsModals(){ 
    global $con;
    ?>
<!-- DELETE INTERVENTION MODAL -->
<div class="modal fade" id="deleteInterventionModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminazione intervento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Sei sicuro di voler eliminare l'intervento n&ordm; <strong id="dim.title"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x-octagon"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-danger" onclick="deleteInterventionAJAX(document.getElementById('dim.title').textContent)">
                    <span data-feather="trash"></span>
                    Elimina
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CREATE INTERVENTION MODAL -->
<div class="modal fade" id="createInterventionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuovo intervento per l'installazione n&ordm; <u><span id="cim.title"></span></u></h5>
                <div class="spinner-modal-container" id="spinner">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="nscB1">
                    <form>
                        <div class="row">
                            <div class="col">
                                <label for="interventionType">Tipo intervento:</label>
                                <select class="form-select" id="interventionType" required>
                                    <option value="Manutenzione ordinaria" selected>Manutenzione ordinaria</option>
                                    <option value="Manutenzione + Analisi Fumi">Manutenzione + Analisi Fumi</option>
                                    <option value="Intervento Generico">Intervento Generico</option>
                                    <option value="Prima Accensione">Prima Accensione</option>
                                    <option value="Installazione">Installazione</option>
                                    <option value="Altro">Altro (Vedi Note)</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="interventionState">Stato Intervento:</label>
                                <select class="form-select" id="interventionState" required>
                                    <option value="0" selected>Programmato</option>
                                    <option value="1">Eseguito</option>
                                    <option value="2">Annullato</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row mb-3">
                            <div class="col col-md-3">
                                <label for="interventionDate" class="form-label">Data intervento:</label>
                                <input type="date" class="form-control" id="interventionDate" value="<?php echo date("Y-m-d") ?>">
                            </div>
                            <div class="col col-md-2">
                                <label for="interventionTime" class="form-label">Ora:</label>
                                <select class="form-select" id="interventionTime" required>
                                    <?php
                                    for($h = 8; $h < 22; $h++){
                                        for($m = 0; $m < 4; $m++){
                                            $mr = "";
                                            switch ($m) {
                                                case 0:
                                                    $mr = "00";
                                                    break;
                                                case 1:
                                                    $mr = "15";
                                                    break;
                                                case 2:
                                                    $mr = "30";
                                                    break;
                                                case 3:
                                                    $mr = "45";
                                                    break;
                                            }
                                            if($h < 10){
                                                $hr = "0".$h;
                                            }else{
                                                $hr = $h;
                                            }
                                            echo "<option value=\"". $hr . ":" . $mr . ":00\">". $hr . ":" . $mr . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col col-md-3">
                                <label for="interventionDuration" class="form-label">Durata:</label>
                                <select class="form-select" id="interventionDuration" required>
                                    <option value="30" selected>Mezz' ora</option>
                                    <option value="60">Un ora</option>
                                    <option value="120">Due ore</option>
                                </select>
                            </div>
                            <div class="col col-md-4">
                                <label for="countInCallCycle" class="form-label">Ciclo chiamate:</label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="checkbox" required checked id="countInCallCycle">
                                        <span style="margin-left: 10px;">Conta nel ciclo chiamate?</span>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="mt-3">
                            <h6>Per la data ed ora selezionata lo stato dei tecnici è:</h6>
                            <table class="table table-bordered" >
                                <thead>
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            Nome Utente
                                        </th>
                                        <th>
                                            Nome
                                        </th>
                                        <th>
                                            Cognome
                                        </th>
                                        <th>
                                            Impegnato
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="overlapTable">

                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" id="assignedTo" value="">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="shipmentDate" class="form-label">Data di spedizione:</label>
                                <input type="date" class="form-control" id="shipmentDate">
                            </div>
                            <div class="col">
                                <label for="protocolNumber" class="form-label">Numero di protocollo:</label>
                                <input type="number" class="form-control" id="protocolNumber">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="billingDate" class="form-label">Data di fatturazione:</label>
                                <input type="date" class="form-control" id="billingDate">
                            </div>
                            <div class="col">
                                <label for="billingNumber" class="form-label">Numero di fattura:</label>
                                <input type="number" class="form-control" id="billingNumber">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="paymentDate" class="form-label">Data di pagamento:</label>
                            <input type="date" class="form-control" id="paymentDate">
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
                <button type="button" class="btn btn-success" onclick="createInterventionAJAX()">
                    <span data-feather="save"></span>
                    Salva
                </button>
            </div>
        </div>
    </div>
</div>

<!-- EDIT INTERVENTION MODAL -->
<div class="modal fade" id="editInterventionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifica dell'intervento n&ordm; <u><span id="eim.title"></span></u> 
                dell'installazione n&ordm; <u><span id="eim.idInstallation"></span></u></h5>
                <div class="spinner-modal-container" id="eim.spinner">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="nscB2">
                    <form>
                        <div class="row">
                            <div class="col">
                                <label for="eim.interventionType">Tipo intervento:</label>
                                <select class="form-select" id="eim.interventionType" required>
                                    <option value="Manutenzione ordinaria" selected>Manutenzione ordinaria</option>
                                    <option value="Manutenzione ordinaria + fumi">Manutenzione + Analisi Fumi</option>
                                    <option value="Intervento Generico">Intervento Generico</option>
                                    <option value="Prima Accensione">Prima Accensione</option>
                                    <option value="Installazione">Installazione</option>
                                    <option value="Altro">Altro (Vedi Note)</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="eim.interventionState">Stato Intervento:</label>
                                <select class="form-select" id="eim.interventionState" required>
                                    <option value="0" selected>Programmato</option>
                                    <option value="1">Eseguito</option>
                                    <option value="2">Annullato</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row mb-3">
                            <div class="col col-md-3">
                                <label for="eim.interventionDate" class="form-label">Data intervento:</label>
                                <input type="date" class="form-control" id="eim.interventionDate">
                            </div>
                            <div class="col col-md-2">
                                <label for="eim.interventionTime" class="form-label">Ora:</label>
                                <select class="form-select" id="eim.interventionTime" required>
                                    <option value="00:00:00">Non specificato</option>
                                    <?php
                                    for($h = 8; $h < 22; $h++){
                                        for($m = 0; $m < 4; $m++){
                                            $mr = "";
                                            switch ($m) {
                                                case 0:
                                                    $mr = "00";
                                                    break;
                                                case 1:
                                                    $mr = "15";
                                                    break;
                                                case 2:
                                                    $mr = "30";
                                                    break;
                                                case 3:
                                                    $mr = "45";
                                                    break;
                                            }
                                            if($h < 10){
                                                $hr = "0".$h;
                                            }else{
                                                $hr = $h;
                                            }
                                            echo "<option value=\"". $hr . ":" . $mr . ":00\">". $hr . ":" . $mr . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col col-md-3">
                                <label for="eim.interventionDuration" class="form-label">Durata:</label>
                                <select class="form-select" id="eim.interventionDuration" required>
                                    <option value="30" selected>Mezz' ora</option>
                                    <option value="60">Un ora</option>
                                    <option value="120">Due ore</option>
                                </select>
                            </div>
                            <div class="col col-md-4">
                                <label for="eim.countInCallCycle" class="form-label">Ciclo chiamate:</label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="checkbox" required checked id="eim.countInCallCycle">
                                        <span style="margin-left: 10px;">Conta nel ciclo chiamate?</span>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6>Stato dei tecnici per data ed ora selezionati:</h6>
                            <table class="table table-bordered" >
                                <thead>
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            Nome Utente
                                        </th>
                                        <th>
                                            Nome
                                        </th>
                                        <th>
                                            Cognome
                                        </th>
                                        <th>
                                            Impegnato
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="eim.overlapTable">
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" id="eim.assignedTo" value="">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="eim.shipmentDate" class="form-label">Data di spedizione:</label>
                                <input type="date" class="form-control" id="eim.shipmentDate">
                            </div>
                            <div class="col">
                                <label for="eim.protocolNumber" class="form-label">Numero di protocollo:</label>
                                <input type="number" class="form-control" id="eim.protocolNumber">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="eim.billingDate" class="form-label">Data di fatturazione:</label>
                                <input type="date" class="form-control" id="eim.billingDate">
                            </div>
                            <div class="col">
                                <label for="eim.billingNumber" class="form-label">Numero di fattura:</label>
                                <input type="number" class="form-control" id="eim.billingNumber">
                            </div>
                        </div>
                        <div class="mb-3">
                                <label for="eim.paymentDate" class="form-label">Data di pagamento:</label>
                                <input type="date" class="form-control" id="eim.paymentDate">
                            </div>
                        <div class="mb-3">
                            <label for="eim.footNote" class="form-label">Annotazioni</label>
                            <textarea class="form-control" id="eim.footNote" rows="3"></textarea>
                        </div>
                        <p>Creazione: <strong id="eim.createdAt">...</strong>  -  
                        Ultima modifica: <strong id="eim.updatedAt">...</strong> da <strong id="eim.lastEditedBy">...</strong>  -  
                        Versione: <strong id="eim.version">...</strong></p>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-success" onclick="editInterventionAjax(document.getElementById('eim.title').innerText, document.getElementById('eim.version').innerText)">
                    <span data-feather="save"></span>
                    Salva
                </button>
            </div>
        </div>
    </div>
</div>

<?php
}

function printInterventionsNBModals(){ 
    global $con;
    ?>
<!-- DELETE INTERVENTION NB MODAL -->
<div class="modal fade" id="deleteInterventionNBModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminazione intervento generico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Sei sicuro di voler eliminare l'intervento generico n&ordm; <strong id="dinbm.title"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x-octagon"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-danger" onclick="deleteInterventionNBAJAX(document.getElementById('dinbm.title').textContent)">
                    <span data-feather="trash"></span>
                    Elimina
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CREATE INTERVENTION NB MODAL -->
<div class="modal fade" id="createInterventionNBModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuovo intervento generico</span></u></h5>
                <div class="spinner-modal-container" id="cinbm.spinner">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="nscB3">
                    <form>
                        <div class="row mb-3">
                            <div class="col col-md-3">
                                <label for="cinbm.interventionDate" class="form-label">Data intervento:</label>
                                <input type="date" class="form-control" id="cinbm.interventionDate" value="<?php echo date("Y-m-d") ?>">
                            </div>
                            <div class="col col-md-2">
                                <label for="cinbm.interventionTime" class="form-label">Ora:</label>
                                <select class="form-select" id="cinbm.interventionTime" required>
                                    <?php
                                    for($h = 8; $h < 22; $h++){
                                        for($m = 0; $m < 4; $m++){
                                            $mr = "";
                                            switch ($m) {
                                                case 0:
                                                    $mr = "00";
                                                    break;
                                                case 1:
                                                    $mr = "15";
                                                    break;
                                                case 2:
                                                    $mr = "30";
                                                    break;
                                                case 3:
                                                    $mr = "45";
                                                    break;
                                            }
                                            if($h < 10){
                                                $hr = "0".$h;
                                            }else{
                                                $hr = $h;
                                            }
                                            echo "<option value=\"". $hr . ":" . $mr . ":00\">". $hr . ":" . $mr . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col col-md-3">
                                <label for="cinbm.interventionDuration" class="form-label">Durata:</label>
                                <select class="form-select" id="cinbm.interventionDuration" required>
                                    <option value="30" selected>Mezz' ora</option>
                                    <option value="60">Un ora</option>
                                    <option value="120">Due ore</option>
                                </select>
                            </div>
                            <div class="col col-md-4">
                                <label for="cinbm.interventionState" class="form-label">Stato Intervento:</label>
                                <select class="form-select" id="cinbm.interventionState" required>
                                    <option value="0" selected>Programmato</option>
                                    <option value="1">Eseguito</option>
                                    <option value="2">Annullato</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h6>Per la data ed ora selezionata lo stato dei tecnici è:</h6>
                            <table class="table table-bordered" >
                                <thead>
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            Nome Utente
                                        </th>
                                        <th>
                                            Nome
                                        </th>
                                        <th>
                                            Cognome
                                        </th>
                                        <th>
                                            Impegnato
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="cinbm.overlapTable">

                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" id="cinbm.assignedTo" value="">
                        <div class="mb-3">
                            <label for="footNote" class="form-label">Annotazioni</label>
                            <textarea class="form-control" id="cinbm.footNote" rows="3"></textarea>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-success" onclick="createInterventionNBAJAX()">
                    <span data-feather="save"></span>
                    Salva
                </button>
            </div>
        </div>
    </div>
</div>

<!-- EDIT INTERVENTION NB MODAL -->
<div class="modal fade" id="editInterventionNBModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifica dell'intervento generico n&ordm; <u><span id="einbm.title"></span></u> </h5>
                <div class="spinner-modal-container" id="einbm.spinner">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="nscB4">
                    <form>
                        <br>
                        <div class="row mb-3">
                            <div class="col col-md-3">
                                <label for="einbm.interventionDate" class="form-label">Data intervento:</label>
                                <input type="date" class="form-control" id="einbm.interventionDate">
                            </div>
                            <div class="col col-md-2">
                                <label for="einbm.interventionTime" class="form-label">Ora:</label>
                                <select class="form-select" id="einbm.interventionTime" required>
                                    <option value="00:00:00">Non specificato</option>
                                    <?php
                                    for($h = 8; $h < 22; $h++){
                                        for($m = 0; $m < 4; $m++){
                                            $mr = "";
                                            switch ($m) {
                                                case 0:
                                                    $mr = "00";
                                                    break;
                                                case 1:
                                                    $mr = "15";
                                                    break;
                                                case 2:
                                                    $mr = "30";
                                                    break;
                                                case 3:
                                                    $mr = "45";
                                                    break;
                                            }
                                            if($h < 10){
                                                $hr = "0".$h;
                                            }else{
                                                $hr = $h;
                                            }
                                            echo "<option value=\"". $hr . ":" . $mr . ":00\">". $hr . ":" . $mr . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col col-md-3">
                                <label for="einbm.interventionDuration" class="form-label">Durata:</label>
                                <select class="form-select" id="einbm.interventionDuration" required>
                                    <option value="30" selected>Mezz' ora</option>
                                    <option value="60">Un ora</option>
                                    <option value="120">Due ore</option>
                                </select>
                            </div>
                            <div class="col col-md-4">
                                <label for="einbm.interventionState" class="form-label">Stato Intervento:</label>
                                <select class="form-select" id="einbm.interventionState" required>
                                    <option value="0" selected>Programmato</option>
                                    <option value="1">Eseguito</option>
                                    <option value="2">Annullato</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6>Stato dei tecnici per data ed ora selezionati:</h6>
                            <table class="table table-bordered" >
                                <thead>
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            Nome Utente
                                        </th>
                                        <th>
                                            Nome
                                        </th>
                                        <th>
                                            Cognome
                                        </th>
                                        <th>
                                            Impegnato
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="einbm.overlapTable">
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" id="einbm.assignedTo" value="">
                        <div class="mb-3">
                            <label for="einbm.footNote" class="form-label">Annotazioni</label>
                            <textarea class="form-control" id="einbm.footNote" rows="3"></textarea>
                        </div>
                        <p>Creazione: <strong id="einbm.createdAt">...</strong>  -  
                        Ultima modifica: <strong id="einbm.updatedAt">...</strong> da <strong id="einbm.lastEditedBy">...</strong>  -  
                        Versione: <strong id="einbm.version">...</strong></p>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <span data-feather="x"></span>
                    Annulla
                </button>
                <button type="button" class="btn btn-success" onclick="editInterventionNBAjax(document.getElementById('einbm.title').innerText, document.getElementById('einbm.version').innerText)">
                    <span data-feather="save"></span>
                    Salva
                </button>
            </div>
        </div>
    </div>
</div>

<?php
}