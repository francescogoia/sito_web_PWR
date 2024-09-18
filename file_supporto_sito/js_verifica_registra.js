

document.addEventListener('DOMContentLoaded', (event) => {              // aspetto che la pagina si sia caricata e poi attendo che venga inviato il form
    document.getElementById('form_registra').addEventListener('submit', function(event) {
        verifica(event);                // quando il form viene inviato chiamo la funzione verifica() che controlla il formato dei campi inseriti dall'utente
    });
});

function verifica(event){            // verifica delle regex lato client    
    let nome = document.getElementById("nome").value
    let cognome = document.getElementById("cognome").value
    let data_nascita = document.getElementById("data_nascita").value
    let indirizzo = document.getElementById("indirizzo").value
    let username = document.getElementById("username").value
    let password = document.getElementById("password").value
    if (nome == "" || cognome == "" || data_nascita == "" || indirizzo == "" || username == "" || password == "") {
        window.alert("Compilare tutti i campi")
        return
    }
    let re_nome = new RegExp("^[A-Z][a-zA-Z ]{1,11}$")
    let re_cognome = new RegExp("^[A-Z][a-zA-Z ]{1,15}$")
    let re_data_nascita = new RegExp("^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$")
    let re_indirizzo = new RegExp("^(Via|Corso|Largo|Piazza|Vicolo) ([a-zA-Z ]{1,}) ([0-9]{1,4})$")
    let re_username = new RegExp("^[a-zA-Z][\\w-]{3,9}$")
    let re_password = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d.*\\d)(?=.*[#!?@%^&*+=].*[#!?@%^&*+=])[A-Za-z\\d#!?@%^&*+=]{8,16}$")
    // almeno 1 maiuscola, almeno 1 minuscola, almeno 2 numeri, almeno 2 caratteri tra [#!?@%^&*+=], lunghezza tot tra 8 e 16 caratteri
    let giorno = parseFloat(data_nascita.split("-")[2])
    let mese = parseFloat(data_nascita.split("-")[1])
    let anno = parseFloat(data_nascita.split("-")[0])
    // verifico che anno, mese e giorno siano valori sensati
    let isValid = true;
    document.getElementById("result_check_regex").innerHTML = ""
    if (re_nome.test(nome) != true){
        window.alert("Verifica formato 'nome' non superata.")
        document.getElementById("result_check_regex").innerHTML += "Verifica formato 'nome' non superata. "
        isValid = false
    }
    if (re_cognome.test(cognome) != true){
        window.alert("Verifica formato 'cognome' non superata.")
        document.getElementById("result_check_regex").innerHTML += "Verifica formato 'cognome' non superata. "
        isValid = false
    }
    if (re_data_nascita.test(data_nascita) != true || test_mese_giorno(mese, giorno, anno) != true){
        window.alert("Verifica formato 'data di nascita' non superata.")
        document.getElementById("result_check_regex").innerHTML += "Verifica formato 'data di nascita' non superata. "
        isValid = false
    }
    if (re_indirizzo.test(indirizzo) != true){
        window.alert("Verifica formato 'indirizzo' non superata.")
        document.getElementById("result_check_regex").innerHTML += "Verifica formato indirizzo' non superata. "
        isValid = false
    }
    if (re_username.test(username) != true){
        window.alert("Verifica formato 'username' non superata.")
        document.getElementById("result_check_regex").innerHTML += "Verifica formato 'username' non superata. "
        isValid = false
    }
    if (re_password.test(password) != true){
        window.alert("Verifica formato 'password' non superata.")
        document.getElementById("result_check_regex").innerHTML += "Verifica formato 'password' non superata. "
        isValid = false
    }
    if (isValid != true) {
        event.preventDefault();          // impedisco che il form venga inviato
    } else{         
        document.getElementById("result_check_regex").innerHTML = "Verifiche formato compi superate. "
        return true
    }
}



function test_mese_giorno(mese, giorno, anno){        // verifica compatibilitò anno, mese e giorno
    const d = new Date();
    let anno_attuale = d.getFullYear();
    if (anno < 1900 || anno >= anno_attuale){
        window.alert(`${anno}: anno non valido`)
        return false
    }
    if (giorno < 1 || giorno > 31) {
        window.alert(`${giorno}: giorno non valido`)
        return false
    }
    if (mese < 1 || mese > 12) {
        window.alert(`${mese}: mese non valido`)
        return false
    }
    else {
        if (mese == 2 && giorno > 29){
            document.getElementById("result_check_regex").innerHTML += `Giorno ${giorno} e mese ${mese} non validi. `
            return false
        }
        else if (mese == 4 && giorno > 30){
            document.getElementById("result_check_regex").innerHTML += `Giorno ${giorno} e mese ${mese} non validi.`
            return false
        }
        else if (mese == 9 && giorno > 30){
            document.getElementById("result_check_regex").innerHTML += `Giorno ${giorno} e mese ${mese} non validi. `
            return false
    }
        else if (mese == 11 && giorno > 30){
            document.getElementById("result_check_regex").innerHTML += `Giorno ${giorno} e mese ${mese} non validi. `
            return false
        }
    }
    return true
}

// quando viene chiamata una di queste funzioni (quando l'utente clicca sul campo username o password oppure clicca in un altro luogo della pagina)
// compare o scompare l'indicazione sul formato della stringa da utilizzare
function info_regex_user(){
    document.getElementById("info_user").innerHTML = "Una stringa lunga da 4 a 10 caratteri, con solo lettere, numeri e '-' o '_' come valori ammessi e deve cominciare con un carattere alfabetico."
    document.getElementById("info_user").style.paddingBottom = "0.5rem"
}
function info_regex_pwd(){
    document.getElementById("info_password").innerHTML = "Una stringa lunga da 8 a 16 caratteri, che puo’ contenere lettere, numeri e caratteri speciali, e deve contenere almeno 1 lettera maiuscola, 1 lettera minuscola, 2 numeri e 2 caratteri speciali tra i seguenti (#!?@%^&*+=)."
    document.getElementById("info_password").style.paddingBottom = "0.5rem"
}
function info_regex_user_svuota(){
    document.getElementById("info_user").innerHTML = ""
}
function info_regex_psw_svuota(){
    document.getElementById("info_password").innerHTML = ""
}