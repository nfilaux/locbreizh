function init() {
    const monAdresse = {
        lat: 47.480167388916016,
        lng: -0.5234440565109253
    }

    const zoomLevel = 13;

    const map = L.map('map').setView([monAdresse.lat, monAdresse.lng], zoomLevel);

    const mainLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    });

    let marker = L.marker([monAdresse.lat, monAdresse.lng]);

    mainLayer.addTo(map);
    marker.addTo(map);
    marker.bindPopup("<b> 3 rue des Mimosas </b> <br> 49000 Angers").openPopup();
}
