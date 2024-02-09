let nature_logement = document.getElementById("nature");
let type_logement = document.getElementById("type");

nature_logement.addEventListener("mouseleave" , () => {
    let valeur_select = nature_logement.options[nature_logement.selectedIndex].value
    //alert("test")
    if (valeur_select == "chateau"){
        type_logement.selectedIndex = 9;
        type_logement.disabled="true";
    } else {
        alert("aie");
        type_logement.disabled = "false";
    }
})