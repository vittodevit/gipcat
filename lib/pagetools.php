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
        header("Location: " . relativeToRoot($level) . "login/");
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
    </head>

    <body>

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
                                <a <?php checkAriaCurr(3, $pageid) ?> href="<?php relativeToRoot($level); ?>technicians/">
                                    <span data-feather="tool"></span>
                                    Anagrafiche Tecnici
                                </a>
                            </li>
                            <li class="nav-item">
                                <a <?php checkAriaCurr(4, $pageid) ?> href="<?php relativeToRoot($level); ?>calendar/">
                                    <span data-feather="calendar"></span>
                                    Calendario Interventi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a <?php checkAriaCurr(5, $pageid) ?> href="<?php relativeToRoot($level); ?>heaterdb/">
                                    <span data-feather="list"></span>
                                    Lista Caldaie
                                </a>
                            </li>
                        </ul>
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Utenti</span>
                        </h6>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a <?php checkAriaCurr(6, $pageid) ?> href="<?php relativeToRoot($level); ?>passwordchange">
                                    <span data-feather="key"></span>
                                    Cambio Propria Password
                                </a>
                            </li>
                        </ul>
                        <?php if ($_SESSION["permissionType"] == 4) { ?>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a <?php checkAriaCurr(7, $pageid) ?> href="<?php relativeToRoot($level); ?>users">
                                        <span data-feather="user-check"></span>
                                        Gestione Utenze
                                    </a>
                                </li>
                            </ul>
                        <?php } ?>
                    </div>
                </nav>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <?php
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

    <body>

        <div class="login-page">
            <div class="form">
                <img src="../static/img/branding/apple-icon-180x180.png" alt="gipcatlogo" width="180" height="180">
                <br>
                <br>
                <form class="login-form" action="" method="post">
                    <input type="text" placeholder="Username" name="username" />
                    <span class="help-block"><?php echo $username_err; ?></span>
                    <input type="password" placeholder="Password" name="password" />
                    <span class="help-block"><?php echo $password_err; ?></span>
                    <button type="submit">login</button>
                </form>
            </div>
        </div>

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
