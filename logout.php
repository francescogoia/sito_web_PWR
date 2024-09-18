<?php
session_start();

$html_result = "";



function clear_session(){
    session_unset();
    session_destroy();    
}

function logout(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['button_logout'])) {
        if (!isset($_SESSION["login_data"]["username"])) {
            header("Location: login.php");
            exit();
        } else {
        clear_session();
        // non elimino il cookie, ma aspetto che scada entro le 16 ore previste
        header("Location: home.php");
        exit();
        }
    }
}
logout();

function carica_form(){
    $user = $_SESSION["login_data"]["username"];
    if ($user != "") {
        echo "<p>Effettuare il logout?</p>
        <form id='form_logout' name='form_logout' action='logout.php' method='post'>
            <button id='button_logout' name='button_logout' type='submit'>LOGOUT</button>
        </form>";
    }
    else {          // in caso un utente non autenticato acceda direttamente alla pagina
        echo "Identità non verificata, per accedere a `logout` occorre effettuare il <a href='login.php'>login</a>";
    }
}

include "file_supporto_sito/file_funzioni_ripetute.php";

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Francesco Goia">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="file_supporto_sito/feedit_fav_icon_nuova.png">
    <link rel="stylesheet" href="file_supporto_sito/style_feedit.css">
    <script id="menu_js" src="file_supporto_sito/js_menu.js"></script>
    <title>FEEDIT-LOGOUT</title>
</head>
<body>
<?php echo carica_header_pagina() ?>

    <?php echo carica_menu_file() ?>
    <div class="contenitore">
    <div id="ultimo_tweet"><?php echo ultimo_tweet_utente() ?></div>
    <div id="div_form_logout">
        <?php carica_form(); ?>
    </div>
    <p><?php echo $html_result ?></p>

</div>


    <?php echo carica_footer_pagina() ?>
</body>
</html>