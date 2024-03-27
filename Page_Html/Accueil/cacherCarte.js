var btn = document.getElementById('carte');
var map = document.getElementById('map');

function toggleCarte() {
    if (map.style.display === "none") {
        map.style.display = "block";
        var mesLogs = document.getElementsByClassName("acc-with-map")[0];
        mesLogs.style.width = "30%";
    } else {
        map.style.display = "none";
        var mesLogs = document.getElementsByClassName("acc-with-map")[0];
        mesLogs.style.width = "80%";

    }
}

btn.addEventListener("click", function () {
    toggleCarte();
});