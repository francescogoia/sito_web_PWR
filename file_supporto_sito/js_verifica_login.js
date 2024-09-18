document.addEventListener('DOMContentLoaded', (event) => {              // aspetto che la pagina si sia caricata e poi attendo che venga inviato il form
    document.getElementById('form_login').addEventListener('submit', function(event) {
        verifica(event);            // quando il form viene inviato chiamo la funzione verifica() che controlla il formato dei campi inseriti dall'utente
    });
});

function verifica(event){  
    let username = document.getElementById("username").value
    let password = document.getElementById("password").value
    let re_username = new RegExp("^[a-zA-Z][\\w-]{3,9}$")
    let re_password = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d.*\\d)(?=.*[#!?@%^&*+=].*[#!?@%^&*+=])[A-Za-z\\d#!?@%^&*+=]{8,16}$")
    let isValid = true;
    if (username == "" || password == ""){          // se l'utente preme invia senza inserire username e password gli viene suggerito di procedere senza autenticazione
        document.getElementById("login_result").innerHTML = "Inserire username e password, oppure procedere senza autenticazione"
        isValid = false
        return
    }
    if (re_username.test(username) != true){
        window.alert("Verifica formato 'username' non superata.")
        document.getElementById("login_result").innerHTML = "Ritenta il login, username o password errati."
        isValid = false
    }
    if (re_password.test(password) != true){
        window.alert("Verifica formato 'password' non superata.")
        document.getElementById("login_result").innerHTML = "Ritenta il login, username o password errati."
        isValid = false
    }
    if (isValid != true) {
        event.preventDefault();          // impedisco che il form venga inviato
    }
}