function applyBoutonChanger() {
    document.querySelector(".logrowb > input").addEventListener("hover", (e) => {
        e.value = "ananas"
    })
}

document.addEventListener("DOMContentLoaded", () => {
    applyBoutonChanger()
})