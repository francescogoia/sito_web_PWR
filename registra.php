<?php
if (session_status() !== PHP_SESSION_ACTIVE)
    session_start();


function carica_cookie($username){
    setcookie("username", $username, time() + 16 * 3600);        // // setto il cookie con durata 16 ore
}

function set_session($username){
    $_SESSION["login_data"]["username"] = $username;        // creo la sessione con l'username dell'utente
}

// verifica delle regex anche lato server
function test_mese_giorno($mese, $giorno) {         // verifico che il giorno e il mese siano compatibili
    if ($giorno < 1 || $giorno > 31) {
        return false;
    }
    if ($mese < 1 || $mese > 12) {
        return false;
    } else {
        if ($mese == 2 && $giorno > 29) {
            return false;
        } elseif ($mese == 4 && $giorno > 30) {
            return false;
        } elseif ($mese == 9 && $giorno > 30) {
            return false;
        } elseif ($mese == 11 && $giorno > 30) {
            return false;
        }
    }
    return true;
}
$errors = "";
function verifica_php($nome, $cognome, $data_nascita, $indirizzo, $user, $password){
    $errors = "";
    $re_nome = "/^[A-Z][a-zA-Z ]{1,11}$/";
    $re_cognome = "/^[A-Z][a-zA-Z ]{1,15}$/";
    $re_data_nascita = "/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/";
    $re_indirizzo = "/^(Via|Corso|Largo|Piazza|Vicolo) ([a-zA-Z ]{1,}) ([0-9]{1,4})$/";
    $re_username = "/^[a-zA-Z][\\w-]{3,9}$/";
    $re_password = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d.*\\d)(?=.*[#!?@%^&*+=].*[#!?@%^&*+=])[A-Za-z\\d#!?@%^&*+=]{8,16}$/";
    $giorno = explode("-", $data_nascita)[2];
    $mese = explode("-", $data_nascita)[1];
    if (empty($nome) || empty($cognome) || empty($data_nascita) || empty($indirizzo) || empty($username) || empty($password)) {
        $errors .= "Compilare tutti i campi.";
    }
    if (preg_match($re_nome, $nome) !== 1) {
        $errors .= "Verifica formato 'nome' non superata. ";
    }
    if (preg_match($re_cognome, $cognome) !== 1) {
        $errors .= "Verifica formato 'cognome' non superata. ";
    }
    if (preg_match($re_data_nascita, $data_nascita) !== 1) {
        $errors .= "Verifica formato 'data di nascita' non superata. ";
    }
    if (preg_match($re_indirizzo, $indirizzo) !== 1) {
        $errors .= "Verifica formato 'indirizzo' non superata. ";
    }
    if (preg_match($re_username, $user) !== 1) {
        $errors .= "Verifica formato 'username' non superata. ";
    }
    if (preg_match($re_password, $password) !== 1) {
        $errors .= "Verifica formato 'password' non superata. ";
    }
    if (preg_match($re_nome, $nome) && 
            preg_match($re_cognome, $cognome) && 
            preg_match($re_data_nascita, $data_nascita) && 
            preg_match($re_indirizzo, $indirizzo) && 
            preg_match($re_username, $user) && 
            preg_match($re_password, $password) && test_mese_giorno($mese, $giorno) == true
            ){
                return true;
            }
}
$html_result = "";
function registra(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['data_nascita']) && isset($_POST['indirizzo']) 
            && isset($_POST['username']) && isset($_POST['password'])) {
        global $html_result;
        global $errors;
        $address = $_SERVER['SERVER_ADDR'];
        $conn = mysqli_connect($address, "privilegiato", "SuperPippo!!!", "social_network");    // creo la connessione al database
        if (mysqli_connect_errno()){                                                            // utente privilegiato per poter effettuare una insert
            $html_result = "errore - collegamento al DB impossibile: ". mysqli_connect_errno() ;
        } else {
            $nome = $_POST["nome"];
            $cognome = $_POST["cognome"];
            $data_nascita = $_POST["data_nascita"];
            $indirizzo = $_POST["indirizzo"];
            $user = $_POST["username"];
            $pwd = $_POST["password"];
            if (verifica_php($nome, $cognome, $data_nascita, $indirizzo, $user, $pwd) == true) {        // verifica lato server delle regular expression
                $query = "INSERT INTO utenti VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ssssss", $nome, $cognome, $data_nascita, $indirizzo, $user, $pwd);
                    if (!mysqli_stmt_execute($stmt)) {
                        // errori username duplicato
                        if (mysqli_errno($conn) == 1062) { // codice di errore chiave primaria duplicata
                            $html_result = "Errore - Username già esistente. Scegliere un altro username.";
                        } else {    // altro errore
                            $html_result = "Errore - " . mysqli_error($conn);
                        }
                    } else {
                        $html_result = "Registrazione effettuata con successo.";
                        carica_cookie($user);           // se l'utente ha effettuato una registrazione nonostante fosse già autenticato, il suo username viene sostituito 
                        set_session($user);             // dallo username proveniente dalla pagina registra.php (sostanzialmente è come se effettuasse il logout)
                        header("Location: bacheca.php");        // una volta effettuata la registrazione si viene reindirizzati alla pagina bacheca
                        exit();
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $html_result = "Errore - preparazione statement fallita: " . mysqli_error($conn);
                }
                mysqli_close($conn);
            } else{
                $html_result = "Verificare formato dati inseriti. " .$errors;

             }
        }
    }
}
registra();

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
    <script id="verifica_js" src="file_supporto_sito/js_verifica_registra.js" defer></script>
    <script id="menu_js" src="file_supporto_sito/js_menu.js"></script>
    <title>FEEDIT-REGISTRA</title>
</head>
<body>
    <?php echo carica_header_pagina() ?>
    <?php echo carica_menu_file() ?>

    <div class="contenitore">
        <div id="ultimo_tweet"><?php echo ultimo_tweet_utente() ?></div>

        <div id="div_form_registra">
            <form name="form_registra" id="form_registra" method="POST" action="registra.php">
                <label for="nome">NAME</label>
                <input type="text" name="nome" id="nome" autofocus>
                <label for="cognome">SURNAME</label>
                <input type="text" name="cognome" id="cognome">
                <label for="data_nascita">BIRTHDATE</label>
                <input type="text" name="data_nascita" id="data_nascita" placeholder="aaaa-mm-gg">
                <label for="indirizzo">ADDRESS</label>
                <input type="text" name="indirizzo" id="indirizzo" placeholder="Via/Corso/Largo/Piazza/Vicolo nome numeroCivico">
                <label for="username">NICK</label>
                <input type="text" name="username" id="username" onfocus="info_regex_user()" onblur="info_regex_user_svuota()">
                <p class="info_regex" id="info_user"></p>
                <label for="password">PASSWORD</label>
                <input type="password" name="password" id="password" onfocus="info_regex_pwd()" onblur="info_regex_psw_svuota()">
                <p class="info_regex" id="info_password"></p>
                <button type="submit" id="button_invia">INVIA</button>
                <button type="reset" id ="button_cancella">CANCELLA</button>
            </form>
            <p id="result_check_regex"><?php echo $errors;?></p>
        </div>
        <p id="html_result"><?php echo $html_result; ?></p>
    </div>
    <?php echo carica_footer_pagina() ?>
</body>
</html>