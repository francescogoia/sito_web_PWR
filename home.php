<?php
session_start();


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
    <title>FEEDIT-HOME</title>
</head>
<body>
    <?php echo carica_header_pagina() ?>

    <?php echo carica_menu_file() ?>

    <div class="contenitore">
    <div id="ultimo_tweet"><?php echo ultimo_tweet_utente() ?></div>
    <div>
        <p>Funzionalità di FEEDIT</p>
        <p>FEEDIT è un social network che permette agli utenti di pubblicare brevi messaggi o tweet, rendendoli pubblici.
            L'utente, dopo essersi registrato può pubblicare i suoi messaggi, altrimenti può solamente vedere i tweet pubblicati.
        </p>
        <p>La pagina <a href="registra.php">registra</a> permette ad un nuovo utente di registrarsi e iniziare a usare il social network.</p>
        <p>La pagina <a href="login.php">login</a> permette ad un utente di inserire username e password per autenticarsi.</p>
        <p>Una volta autenticato, l'utente può accedere alla pagina <a href="bacheca.php">bacheca</a> per visualizzare i tweet da lui pubblicati.</p>
        <p>La pagina <a href="scrivi.php">scrivi</a> permette all'utente di pubblicare un nuovo tweet.</p>
        <p>La pagina scopri <a href="scopri.php">scopri</a> permette di visualizzare tutti i tweet pubblicati su Feedit.</p>
        <p>Un utente autenticato può effettuare il logout accedendo alla <a href="logout.php">pagina apposita.</a></p>
    </div>

</div>

    <?php echo carica_footer_pagina() ?>


</body>
</html>
