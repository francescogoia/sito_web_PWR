<?php
if (session_status() !== PHP_SESSION_ACTIVE)
    session_start();

if (!isset($_SESSION['login_data'])) {
    $_SESSION['login_data'] = ["username" => ""];               // creo la sessione con l'username dell'utente
}

$html_result = "";
function carica_cookie($username){
    setcookie("username", $username, time() + 16* 3600);        // setto il cookie con durata 16 ore
}

function set_session($username){
    $_SESSION["login_data"]["username"] = $username;
}
function verifica_php($username, $password){                // verifico lato server che le Regex siano corrette
    $re_username = "/^[a-zA-Z][\\w-]{3,9}$/";
    $re_password = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d.*\\d)(?=.*[#!?@%^&*+=].*[#!?@%^&*+=])[A-Za-z\\d#!?@%^&*+=]{8,16}$/";
    if (preg_match($re_username, $username) !== 1) {
        return false;
    }
    if (preg_match($re_password, $password) !== 1) {
        return false;
    }
    return true;
}

function login(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
        global $html_result;
        $address = $_SERVER['SERVER_ADDR'];
        $conn = mysqli_connect($address, "normale", "posso_leggere?", "social_network");
        if (mysqli_connect_errno()){
            $html_result = "errore - collegamento al DB impossibile: ". mysqli_connect_errno() ;
        } else {
            $user = $_POST["username"];
            $pwd = $_POST["password"];
            if ($user == ""|| $pwd == "") {         // controllo lato server che username e password siano stati inseriti
                $html_result = "Inserire username e password, oppure procedere senza autenticazione";
                return;
            }
            if (verifica_php($user, $pwd) == true){             // verifico che username e password rispettino le Regex
                $query = "SELECT * FROM `utenti` WHERE username = ? and pwd = ?";
                $stmt = mysqli_prepare($conn, $query);
                
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ss", $user, $pwd);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $nome, $cognome, $data_n, $indirizzo, $usr, $pwd);
                    
                    $html_result = "";
                    if (mysqli_stmt_fetch($stmt)) {             // se nel database è presente un utente con username a password inseriti si viene reindirizzati alla pagina bachca
                        carica_cookie($usr);    
                        set_session($usr);
                        header("Location: bacheca.php");
                        exit();
                    }
                    // in caso non esista alcun utente con username e password inseriti.
                    $html_result .= "Ritenta il login, username o password errati.";

                    mysqli_stmt_close($stmt);
                } else {
                    $html_result = "errore - preparazione statement fallita: " . mysqli_error($conn);
                }       // gli errori di collegamento al database vengono segnalati
            } else{
                return;
            }
            mysqli_close($conn);
        }
    }
}

// con questa funzione carico il form di login solo se l'utente non è autenticato, altrimenti lo invito al logout prima di procedere ad un nuovo login
function carica_form_login(){
    $user = $_SESSION["login_data"]["username"];
    $user_cookie = "";
    if(isset($_COOKIE['username']) && ($_COOKIE['username']) != ''){
        $user_cookie = $_COOKIE['username'];
    }
    if ($user == "") {
        if ($user_cookie != ""){
        echo "
            <label for='username'>USERNAME</label>
            <input type='text' name='username' id='username' autofocus value=".$user_cookie.">
            <label for='password'>PASSWORD</label>
            <input type='password' name='password' id='password'>
            <button type='submit' id='button_invia'>INVIA</button>
            <button type='reset' id='button_reset'>CANCELLA</button>
            </form>";
        } else {
            echo "
            <label for='username'>USERNAME</label>
            <input type='text' name='username' id='username' autofocus >
            <label for='password'>PASSWORD</label>
            <input type='password' name='password' id='password'>
            <button type='submit' id='button_invia'>INVIA</button>
            <button type='reset' id='button_reset'>CANCELLA</button>
            </form>";
        }
    }
    else {
        echo "ERRORE: Utente già autenticato, effettuare il <a href='logout.php'>logout</a></form>";
    }
}

login();
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
    <script id="verifica_js" src="file_supporto_sito/js_verifica_login.js" defer></script>
    <script id="menu_js" src="file_supporto_sito/js_menu.js"></script>
    <title>FEEDIT-LOGIN</title>
    <style>
        p{
            padding: 0;
        }
    </style>
</head>
<body>
    <?php echo carica_header_pagina() ?>
    <?php echo carica_menu_file() ?>

    <div class="contenitore">
        <div id="ultimo_tweet"><?php echo ultimo_tweet_utente() ?></div>
        
        <div id='div_form_login'>
            <form name='form_login' id='form_login' action='login.php' method='POST'>

                <?
                // chiamo la funzione di carica form_login dopo aver "aperto" il tag <form> perché altrimenti javaScript lato client 
                // (che è in ascolto per quanto riguarda gli eventi di form_login segnala un errore)
                carica_form_login()?>        

            <form name='form_no_autenticazione' id='form_no_autenticazione' action='scopri.php'>
                <button id='button_no_registrazione' type='submit'>CONTINUA SENZA AUTENTICAZIONE</button>
            </form>

            <p id='login_result'><?php echo $html_result ?></p>

        </div>



    </div>    

    <?php echo carica_footer_pagina() ?>

</body>
</html>