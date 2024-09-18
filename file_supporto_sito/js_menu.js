

function apri_menu(){           // quando la funzione viene chiamata, se il menu è già visualizzato lo fa scomparire, altrimenti lo fa apparrire a video (solo media < 700px)
    let home = document.getElementById("a_home")
    let registra = document.getElementById("a_registra")
    let scrivi = document.getElementById("a_scrivi")
    let bacheca = document.getElementById("a_bacheca")
    let login = document.getElementById("a_login")
    let scopri = document.getElementById("a_scopri")
    let logout = document.getElementById("a_logout")

    if (home.className == "invisibile"){
        home.classList.replace("invisibile", "visibile")
        registra.classList.replace("invisibile", "visibile")
        scrivi.classList.replace("invisibile", "visibile")
        bacheca.classList.replace("invisibile", "visibile")
        login.classList.replace("invisibile", "visibile")
        scopri.classList.replace("invisibile", "visibile")
        logout.classList.replace("invisibile", "visibile")
        return
    }
    if (home.className == "visibile"){
        home.classList.replace("visibile", "invisibile")
        registra.classList.replace("visibile", "invisibile")
        scrivi.classList.replace("visibile", "invisibile")
        bacheca.classList.replace("visibile", "invisibile")
        login.classList.replace("visibile", "invisibile")
        scopri.classList.replace("visibile", "invisibile")
        logout.classList.replace("visibile", "invisibile")
        return
    }
}