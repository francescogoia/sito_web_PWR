<?php
// le funzioni in questo file vengono chiamate in tutte le pagine del sito
function carica_header_pagina(){            // funzione che carica header di una pagina (nome social e nome pagina)
    $nome_file = basename($_SERVER['PHP_SELF'], ".php");        // escludo il suffisso .php
            $html_result = "<header><figure>
                    <img src='file_supporto_sito/feedit_fav_icon_nuova.png' alt='Logo FEEDIT'>        
                    </figure>
                    <h1>FEEDIT</h1>
                    <h2>". $nome_file ."</h2>                    
                </header>";         // includo nell'header il logo
    return $html_result;

}
function carica_menu_file(){                            // funzione che carica il menu del sito, quando l'utente ha effettuato l'accesso non vengono abilitati (tramite css)
    $user = $_SESSION["login_data"]["username"];        // i link per login.php, registra.php e logout.php (utente già loggato e registrato)
    $html_menu_result = "<nav>";
    if ($user == "") {              // apri_menu() è contenuta nel file js_menu.js e cambia la classe dei componenti del menu in caso
                                    // l'utente clicchi su Menu (facendoli comparire) (solo per viewport larga meno di 700px, ma si rimanda al file style_feedit.css)
        $html_menu_result .= "<ul id='menu'>
                <li id='comandoMenu' onclick='apri_menu()'>Menù</li>
                <li id='a_home' class='invisibile'><a href='home.php'>HOME</a></li>
                <li id='a_registra' class='invisibile'><a href='registra.php'>REGISTRA</a></li>
                <li id='a_scrivi' class='invisibile'><a href='scrivi.php' class='disabilita_link'>SCRIVI</a></li>
                <li id='a_bacheca' class='invisibile'><a href='bacheca.php' class='disabilita_link'>BACHECA</a></li>
                <li id='a_login' class='invisibile'><a href='login.php'>LOGIN</a></li>
                <li id='a_scopri' class='invisibile'><a href='scopri.php'>SCOPRI</a></li>
                <li id='a_logout' class='invisibile'><a href='logout.php' class='disabilita_link'>LOGOUT</a></li>
            </ul></nav>";           // apri_menu(), presente nel file js_menu.js cambia la classe degli elementi del menu 
    return $html_menu_result;
    } else {
        $html_menu_result .= "<ul id='menu'>
        <li id='comandoMenu' onclick='apri_menu()'>Menù</li>
            <li id='a_home' class='invisibile'><a href='home.php'>HOME</a></li>
            <li id='a_registra' class='invisibile'><a href='registra.php' class='disabilita_link'>REGISTRA</a></li>
            <li id='a_scrivi' class='invisibile'><a href='scrivi.php'>SCRIVI</a></li>
            <li id='a_bacheca' class='invisibile'><a href='bacheca.php'>BACHECA</a></li>
            <li id='a_login' class='invisibile'><a href='login.php' class='disabilita_link'>LOGIN</a></li>
            <li id='a_scopri' class='invisibile'><a href='scopri.php'>SCOPRI</a></li>
            <li id='a_logout' class='invisibile'><a href='logout.php'>LOGOUT</a></li>
            </ul></nav>";
    return $html_menu_result;
    }
}
function carica_footer_pagina(){        // funzione che carica il footer della pagina 
    $nome_file = basename($_SERVER['PHP_SELF']);
    $meta_tags = get_meta_tags($nome_file);
    $autore = $meta_tags["author"];
    $html_result = "<footer><p>&copy; 2024 FEEDIT"." ". $nome_file ." ".  $autore ."</p></footer>";
    return $html_result;
}



function ultimo_tweet_utente(){     // funzione che recupera l'ultimo tweet pubblicato da un utente (se questo ha effettuato l'accesso)
    $user = $_SESSION["login_data"]["username"];
    $html_ultimo_tweet = "";
    if ($user != "") {
        $address = $_SERVER['SERVER_ADDR'];
        $conn = mysqli_connect($address, "normale", "posso_leggere?", "social_network");
        if (mysqli_connect_errno()){
            $html_ultimo_tweet = "errore - collegamento al DB impossibile: ". mysqli_connect_errno() ;
            return $html_ultimo_tweet;
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
                    $ultimo_tweet = array_shift($tweets);
                    $html_ultimo_tweet .= "<span id='span_ultimo_tweet'><span id='username_ultimo_tweet'>". $user ."</span>";
                    if (strlen(htmlspecialchars($ultimo_tweet['testo'])) > 30){         // se il tweet è èiù lungo di 30 caratteri aggiungo ...
                        $html_ultimo_tweet .= "<span id='testo_ultimo_tweet'>".substr(htmlspecialchars($ultimo_tweet['testo']), 0, 30) . "...</span></span>";
                    } else {
                    $html_ultimo_tweet .= "<span id='testo_ultimo_tweet'>". substr(htmlspecialchars($ultimo_tweet['testo']), 0, 30) . "</span></span>";
                    }

                } else {
                    $html_ultimo_tweet .= "<span id='span_ultimo_tweet'>". $user ."</span>";  // nessun tweet pubblicato dall'utente --> mostro solo lo username
                    return $html_ultimo_tweet;
                }
                mysqli_stmt_close($stmt);
                return $html_ultimo_tweet;
            } else {
                $html_ultimo_tweet = "errore - preparazione statement fallita: " . mysqli_error($conn);                
            }
            mysqli_close($conn);
        }
    } else {
        $html_ultimo_tweet = "";   // l'utente non ha effettuato l'accesso --> lascio il campo vuoto
    return $html_ultimo_tweet;
    }
    return $html_ultimo_tweet; 
}