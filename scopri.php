<?php
session_start();
function allTweets(){
    $address = $_SERVER['SERVER_ADDR'];
    $conn = mysqli_connect($address, "normale", "posso_leggere?", "social_network");
    $html_result = "";
    if (mysqli_connect_errno()){        // segnalo gli errori di collegamento al database
        $html_result = "<p>errore - collegamento al DB impossibile: ". mysqli_connect_errno() ."</p>";
        return $html_result;
    } else {
        mysqli_set_charset($conn,"utf8mb4");        // charset che consente il salvataggio di emoticon
        $query = "SELECT * FROM `tweets` ORDER BY data DESC";
        $result = mysqli_query($conn, $query);
        if (! $result){
            $html_result = "<p>errore - query fallita: ". mysqli_error($conn) . "</p>";
        }
        else{
            $contatore = 0;
            foreach ($result as $row){
                $contatore++;
                $tweets[$contatore] = ["username" => $row["username"],"data" =>$row["data"],"testo"=>$row["testo"]];
                // creo un arrayMap con tutti i tweet estratti
            }
            mysqli_free_result($result);
            mysqli_close($conn);
            // creo la tabella
            $html_result .=  "<table>";
            $html_result .= "<caption>Tutti i tweet pubblicati su FEEDIT</caption>";
            $html_result .= "<colgroup><col><col><col></colgroup>";
            $html_result .= "<thead>";
            $html_result .=  "<tr>";
            $html_result .= "<th>username</th>";
            $html_result .= "<th>data e ora</th>";
            $html_result .= "<th>tweet</th>";
            $html_result .= "</tr>";
            $html_result .= "</thead>";
            $html_result .= "<tbody>";
            foreach ($tweets as $id => $tweet) {        // id in realtà è il contatore creato sopra
                $html_result .=  "<tr>";
                $html_result .= "<td>".$tweet["username"]."</td>";
                $html_result .= "<td>".$tweet["data"]."</td>";
                $html_result .= "<td>".$tweet["testo"]."</td>";
                $html_result .=  "</tr>";
                }
            $html_result .= "</tbody>";
            $html_result .= "</table>";
            return $html_result;
        }
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
    <title>FEEDIT-SCOPRI</title>
</head>
<body>

<?php echo carica_header_pagina() ?>

    <?php echo carica_menu_file() ?>
    <div class="contenitore">
        <div id="ultimo_tweet"><?php echo ultimo_tweet_utente() ?></div>
        <div>
            <?php echo allTweets();?>
        </div>
        
    </div>

    <?php echo carica_footer_pagina() ?>
</body>
</html>