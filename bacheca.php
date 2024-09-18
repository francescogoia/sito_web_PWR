<?php
session_start();




function crea_filtro_temporale(){
        $html_filtro_temporale = "<form name='filtro_temporale' id='filtro_temporale' action='bacheca.php' method='POST'>
        <label for='data_0'>DATA INIZIO</label>    
        <input type='date' id='data_0' name='data_0'>
        <label for='data_1'>DATA FINE</label>    
        <input type='date' id='data_1' name='data_1'>
        <button id='button_seleziona_date' type='submit'>SELEZIONA DATE</button>
        </form>";
        return $html_filtro_temporale;
}

function bacheca_utente_filtro(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data_0']) && isset($_POST['data_1'])) {
        $data_0 = $_POST['data_0'] . " 00:00:00";
        $data_1 = $_POST['data_1'] . " 23:59:59";        // aggiungo l'ora alle date in ingresso altrimenti il confronto con la data del server non è corretto
        $data_0_output = $_POST["data_0"];
        $data_1_output = $_POST["data_1"];              // in output non restituisco anche le ore aggiunte sopra
        $html_result = "";
        if ($data_0_output != "" && $data_1_output != "") {
            $user = $_SESSION["login_data"]["username"];
                if ($user != "") {
                    $address = $_SERVER['SERVER_ADDR'];
                    $conn = mysqli_connect($address, "normale", "posso_leggere?", "social_network");
                    if (mysqli_connect_errno()){
                        $html_result = "errore - collegamento al DB impossibile: ". mysqli_connect_errno() ;
                        return $html_result;
                    } else {
                        mysqli_set_charset($conn, "utf8mb4");
                        $query = "SELECT t.username, t.data, t.testo
                                    from tweets t
                                    WHERE t.username = ? and t.data <= ? and t.data >= ?
                                    ORDER BY t.`data` DESC";
                        $stmt = mysqli_prepare($conn, $query);
                        
                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, "sss", $user, $data_1, $data_0);       // data tweet minore di data_1 e maggiore di data_0 
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $tweets = mysqli_fetch_all($result, MYSQLI_ASSOC);
                            
                            if (count($tweets) > 0) {
                                echo "<script type='text/JavaScript'>document.getElementById('tabella_tweet').innerHTML = '';
                                    </script>";
                                $html_result .= "<table id='tabella_tweet'>";
                                $html_result .= "<caption>Tweet pubblicati da '". $user ."' tra il ". $data_0_output ." e il ". $data_1_output ."</caption>";
                                $html_result .= "<colgroup><col><col><col></colgroup>";
                                $html_result .= "<thead>";
                                $html_result .= "<tr>";
                                $html_result .= "<th>username</th>";
                                $html_result .= "<th>data e ora tweet</th>";
                                $html_result .= "<th>testo</th>";
                                $html_result .= "</tr>";
                                $html_result .= "</thead>";
                                $html_result .= "<tbody>";
                                foreach ($tweets as $tweet) {
                                    $html_result .= "<tr>";
                                    $html_result .= "<td>" . htmlspecialchars($tweet['username']) . "</td>";
                                    $html_result .= "<td>" . htmlspecialchars($tweet['data']) . "</td>";
                                    
                                    $html_result .= "<td>" . htmlspecialchars($tweet['testo']) . "</td>";
                                    $html_result .= "</tr>";
                                }
                            } else {
                                $html_result .= "<p>Nessun tweet trovato tra il ". $data_0_output ." e il ". $data_1_output .".</p>";
                                return $html_result;
                            }
                            $html_result .= "</tbody>";
                            $html_result .= "</table>";
                            mysqli_stmt_close($stmt);
                            return $html_result;
                        } else {
                            $html_result = "errore - preparazione statement fallita: " . mysqli_error($conn);
                        }
                        mysqli_close($conn);
                        return $html_result;
                    }
                } else {
                $html_result .= "username o password non inseriti";
                return $html_result;
                }
            }
        }
    return $html_result;
}


function bacheca_utente(){
    $user = $_SESSION["login_data"]["username"];
    $html_result = "";
    if ($user != "") {
        $address = $_SERVER['SERVER_ADDR'];
        $conn = mysqli_connect($address, "normale", "posso_leggere?", "social_network");
        if (mysqli_connect_errno()){
            $html_result = "errore - collegamento al DB impossibile: ". mysqli_connect_errno() ;
            return $html_result;
        } else {
            mysqli_set_charset($conn, "utf8mb4");
            $query = "SELECT t.username, t.data, t.testo from tweets t WHERE t.username = ? ORDER BY t.`data` DESC";
            $stmt = mysqli_prepare($conn, $query);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $user);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $tweets = mysqli_fetch_all($result, MYSQLI_ASSOC);
                if (count($tweets) > 0) {
                    $html_result .= crea_filtro_temporale();
                    $html_result .= "<table id='tabella_tweet'>";
                    $html_result .= "<caption>Tutti i tweet pubblicati da '". $user ."'</caption>";
                    $html_result .= "<colgroup><col><col><col></colgroup>";
                    $html_result .= "<thead>";
                    $html_result .= "<tr>";
                    $html_result .= "<th>username</th>";
                    $html_result .= "<th>data e ora tweet</th>";
                    $html_result .= "<th>testo</th>";
                    $html_result .= "</tr>";
                    $html_result .= "</thead>";
                    $html_result .= "<tbody>";
                    foreach ($tweets as $tweet) {
                        $html_result .= "<tr>";
                        $html_result .= "<td>" . htmlspecialchars($tweet['username']) . "</td>";
                        $html_result .= "<td>" . htmlspecialchars($tweet['data']) . "</td>";
                        $html_result .= "<td>" . htmlspecialchars($tweet['testo']) . "</td>";
                        $html_result .= "</tr>";
                    }
                } else {
                    $html_result .= "<p>Nessun tweet trovato, <a href='scrivi.php'>scrivi un nuovo tweet</a>.</p>";
                    return $html_result;
                }
                $html_result .= "</tbody>";
                $html_result .= "</table>";
                mysqli_stmt_close($stmt);
                return $html_result;
            } else {
                $html_result = "errore - preparazione statement fallita: " . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    } else {            // in caso un utente non autenticato acceda direttamente alla pagina
    $html_result .= "ERRORE: Identità non verificata, per accedere alla bacheca occorre effettuare il <a href='login.php'>login</a>.";
    return $html_result;
    }
    return $html_result; 
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
    <title>FEEDIT-BACHECA</title>
</head>
<body>
    <?php echo carica_header_pagina() ?>

    <?php echo carica_menu_file() ?>
    <div class="contenitore">
        <div id="ultimo_tweet"><?php echo ultimo_tweet_utente() ?></div>

        <div>
            <?php 
                echo bacheca_utente();
                echo bacheca_utente_filtro()
            ?>
        </div>

    
</div>
    



    <?php echo carica_footer_pagina() ?>
</body>
</html>