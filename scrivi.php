<?php
session_start();
$html_result = "";


function scrivi_tweet(){
    global $html_result;
    $user = $_SESSION["login_data"]["username"];
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if ($user != "") {
        $address = $_SERVER['SERVER_ADDR'];
        $conn = mysqli_connect($address, "privilegiato", "SuperPippo!!!", "social_network");            // utente privilegiato per insert
        if (mysqli_connect_errno()){
            $html_result = "errore - collegamento al DB impossibile: ". mysqli_connect_errno() ;    // segnalo gli errori di collegamento al database
        } else {
            mysqli_set_charset($conn, "utf8mb4");           // charset che permette l'uso di emoticon
            if (isset($_POST["testo_tweet"])) {
                $testo = $_POST["testo_tweet"];
                $data = date("Y-m-d") ."-". date("H:i:s");      // funzione date() per avere la data e l'ora lato server
                if ($testo != "") {
                    $query_1 = "SELECT * FROM `utenti` WHERE username = ?";         // controllo che lo username esista
                    $stmt_1 = mysqli_prepare($conn, $query_1);
                    if ($stmt_1) {
                        mysqli_stmt_bind_param($stmt_1, "s", $user);
                        mysqli_stmt_execute($stmt_1);
                        mysqli_stmt_bind_result($stmt_1, $nome, $cognome, $data_n, $indirizzo, $usr, $pwd);
                        if (mysqli_stmt_fetch($stmt_1)) {         // ho verificato che lo username, procedo a inserire il tweet
                            mysqli_stmt_close($stmt_1);
                            $query_2 = "INSERT INTO tweets VALUES (?, ?, ?)";
                            $stmt_2 = mysqli_prepare($conn, $query_2);
                            if ($stmt_2){
                                mysqli_stmt_bind_param($stmt_2, "sss", $user, $data, $testo);
                                if (!mysqli_stmt_execute($stmt_2)) {
                                    $html_result = "Errore - " . mysqli_error($conn);
                                } else {
                                    $html_result = "Inserito nuovo tweet";
                                    mysqli_stmt_close($stmt_2);
                                    header("Location: bacheca.php");        // tweet inserito e utente reindirizzato alla pagina bacheca
                                    exit();
                                }
                            } else {
                                $html_result = "Errore nella preparazione della query di inserimento.";
                            }
                            } else {
                                $html_result = "Errore username.";
                            }
                        } else {
                            $html_result = "Errore nella preparazione della query di selezione.";
                        }
                    } 
                }
                mysqli_close($conn);
            }
        } else {            // in caso un utente non autenticato acceda direttamente alla pagina
            $html_result .= "ERRORE: Identità non verificata, per accedere a `scrivi` occorre effettuare il <a href='login.php'>login</a>"; 
        }
    }
}
scrivi_tweet();
function carica_form(){
    $user = $_SESSION["login_data"]["username"];
    if ($user != "") {      // permetto solo agli utenti autenticati di scrivere un tweet
        echo "
        <label for='form_scrivi_tweet'>Scrivi il tuo tweet</label>
        <form id='form_scrivi_tweet' name='form_scrivi_tweet' action='scrivi.php' method='post'>
            <textarea id='testo_tweet' name='testo_tweet' maxlength='140' placeholder='Scrivi un tuo pensiero (max 140 caratteri)'></textarea>
            <button id= 'form_scrivi_invia' type='submit' onclick='data_ora()'>INVIA</button>
            <button id= 'form_scrivi_reset'type='reset'>CANCELLA</button>
        </form>";
    }
    else {
        echo "ERRORE: Identità non verificata, per accedere a `scrivi` occorre effettuare il <a href='login.php'>login</a>";
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
    
    <title>FEEDIT-SCRIVI</title>
</head>
<body>
<?php echo carica_header_pagina() ?>

    <?php echo carica_menu_file() ?>
    <div class="contenitore">
        <div id="ultimo_tweet"><?php echo ultimo_tweet_utente() ?></div>
        <div id="div_form_scrivi">
            <?php carica_form();?>
        </div>
        <div id="div_result_scrivi"><?php echo $html_result; ?></div>
    </div>

    <?php echo carica_footer_pagina() ?>

</body>
</html>