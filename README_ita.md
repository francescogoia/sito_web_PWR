Progettazione di Servizi Web e Reti di Calcolatori 
Politecnico di Torino – Prof. Andrea Atzeni, Ing. Lorenzo Ferro 
AA 2023-2024 homework di programmazione 
Sviluppare in tecnologia PHP un sito web per un social network in stile Twitter (X) organizzato come 
segue. 
La pagina iniziale (HOME, corrispondente al file home.php) fornisce una presentazione generale del 
sito e delle possibili funzionalità. 
La pagina di registrazione (REGISTRA, corrispondente a registrazione.php) permette ad un nuovo 
utente di registrarsi al social network. La pagina permette all’utente di inserire i dati generali quali 
nome (campo “name”), cognome (campo “surname”), data di nascita (campo “birthdate”), indirizzo 
(campo “address”), username (campo “nick”) e password (campo “password”). Nome deve essere 
una stringa di minimo 2 e massimo 12 caratteri, con solo lettere ed il carattere spazio come caratteri 
accettabili e deve necessariamente iniziare con una lettera maiuscola. Cognome deve essere una 
stringa di minimo 2 e massimo 16 caratteri, con solo lettere ed il carattere spazio come caratteri 
accettabili e deve necessariamente iniziare con una lettera maiuscola. Data di nascita deve essere 
nella forma “aaaa-mm-gg” (dove il valore 0 in posizione più significativa nel mese e nel giorno può 
eventualmente essere omesso). Domicilio deve essere nella forma “Via/Corso/Largo/Piazza/Vicolo 
nome numeroCivico”, dove nome può contenere caratteri alfabetici e spazi mentre numeroCivico 
`e un numero naturale composto da 1 a 4 cifre decimali. Username deve essere una stringa lunga 
da 4 a 10 caratteri, con solo lettere, numeri e ‘-’ o ‘_’ come valori ammessi e deve cominciare con un 
carattere alfabetico. Password deve essere una stringa lunga da 8 a 16 caratteri, che puo’ contenere 
lettere, numeri e caratteri speciali, e deve contenere almeno 1 lettera maiuscola, 1 lettera minuscola, 
2 numeri e 2 caratteri speciali tra i seguenti (#!?@%^&*+=). 
La pagina di identificazione (LOGIN, corrispondente al file login.php) permette all’utente di 
introdurre il suo username e la sua password per autenticarsi. La pagina contiene due campi testuali – aventi ID user e pwd – per inserire i dati e due bottoni che rispettivamente cancellano il contenuto 
di tutti i campi (pulsante CANCELLA) o li inviano al server per il controllo d’accesso (pulsante INVIA). 
Se l’autenticazione fallisce, si rimane nella stessa pagina segnalando l’errore ed invitando l’utente 
a ritentare il login. Inoltre, la pagina LOGIN ha un bottone "continua senza autenticarsi" che 
consente all'utente di saltare il processo di autenticazione. Qualora un utente si sia autenticato con 
successo, nella parte in alto a sinistra di ogni pagina, fino al logout, dovrà essere presente 
l’indicazione dello username dell’utente ed i primi 30 caratteri dell’ultimo tweet inserito dall’utente 
(o nulla se l’utente non ha ancora inserito alcun tweet). 
Facendo accessi successivi alla pagina LOGIN dal medesimo browser (anche in giorni diversi ma 
nelle 16 ore successive all’ultimo login), il campo utente deve essere precompilato con l’ultimo 
valore usato con successo da quel browser nella pagina LOGIN. 
L'utente viene indirizzato direttamente alla pagina BACHECA (bacheca.php) dopo 
l'autenticazione. La pagina bacheca contiene una lista di tutti i tweet che l'utente ha fatto. La lista 
di tweet è strutturata in modo da vedere nome autore, data e ora (del server) e contenuto del 
tweet. La lista è ordinata per data di invio del tweet. In caso non siano presenti tweet da parte di 
quell’utente viene mostrato un messaggio che invoglia la scrittura di un nuovo tweet. Nella pagina 
deve essere presente un “filtro” che permette di visualizzare di selezionare un intervallo temporale, 
e di visualizzare solo i tweet creati in quell’intervallo. 
La pagina SCRIVI contiene la possibilità di inserire un testo (“tweet”) ed un bottone INVIA che 
permette di salvare il tweet. Un tweet può essere lungo al massimo 140 caratteri. Dopo aver 
spedito un tweet si verrà reinderizzati verso la pagina BACHECA. 
Un utente che ha saltato la procedura di login verrà indirizzato alla pagina SCOPRI che contiene 
tutti i tweet scritti da qualsiasi utente. 
Nel caso un utente salti la procedura di login e cerchi di navigare sulle pagine BACHECA o INVIA 
cambiando l’URL del browser deve essere mostrato il messaggio di errore “identità non verificata” 
e spiegato che non essendo authenticato non si ha permesso di usare questa funzionalità. 
Tutte le pagine devono contenere nella medesima posizione un menu comune per andare alle 
pagine HOME, REGISTRA, SCRIVI, BACHECA, LOGIN e SCOPRI. Le voci BACHECA e SCRIVI 
devono essere sempre presenti, ma attive solo per utenti autenticati. Inoltre il menù contiene una 
voce che permette in qualunque momento di uscire dal sistema (LOGOUT) ossia di tornare 
anonimi, come prima della procedura di autenticazione. Questa voce non e attiva se l’utente non 
si è ancora autenticato. Viceversa, la voce LOGIN deve essere presente ma non attiva per un 
utente autenticato. 
Tutto il sito fa riferimento ad un DB in formato MySQL contenente le seguenti tabelle.  
La tabella “utenti” contiene i record degli utenti registrati ed e organizzata su otto campi: 
• il campo “nome” e il nome dell’utente; ` 
• il campo “cognome” e il cognome dell’utente;  
• il campo “data” e la data di nascita dell’utente, nella forma “aaaa-mm-gg”; 
• il campo “indirizzo” e il domicilio dell’utente registrato, e pu ` o cominciare solo con una 
delle cinque ` parole “Via”, “Corso”, “Largo”, “Piazza”, “Vicolo” seguito dal nome della via 
e dal suo numero civico (es. “Corso Duca degli Abruzzi 24”); 
• il campo “username” e l’identificativo dell’utente ed ` e la chiave primaria;  
• il campo “pwd” e la password dell’utente;  
La tabella tweets contiene tutti i tweet caricati dagli utenti: 
• il campo “username” che indica lo username dell’utente che ha scritto il tweet; 
• il campo “data” che indica la data a cui e’ stato pubblicato il tweet; 
• il campo “testo” che contaiene il testo del tweet; 
Il sito deve essere impaginato e formattato in modo coerente in tutte le pagine che devono avere 
lo stesso stile grafico facilmente controllabile mediante un foglio di stile. Ogni pagina deve 
contenere – come dati comuni e facilmente modificabili – un header (col nome del sito) ed un 
footer (che indica il nome del file e l’autore della pagina). Ove possibile e sensato, queste 
informazioni devono essere calcolate automaticamente. Il sito deve essere installabile in una 
qualunque cartella di un server web; le pagine non devono quindi avere dipendenze da path o 
indirizzi di rete specifici. Tutti i contenuti devono essere aderenti agli standard W3C e nello 
sviluppo del sito si devono mettere in pratica le norme di buona programmazione web apprese a 
lezione. Nell’accesso al DB si richiede di minimizzare i privilegi assegnati a ciascuna pagina, 
usando in modo opportuno i due utenti predefiniti nel DB (“normale” e “privilegiato”); si noti inoltre 
che non è permesso usare l’utente “admin” o altri utenti non definiti nel DB fornito. 
