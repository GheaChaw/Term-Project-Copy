const map = L.map('map').setView([42.728104, -73.687576], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var full = document.getElementsByClassName("address");
text = full[0].innerText;
for (let i = 0; i < text.length; i++) {
    text = text.replace(" ", '+');
}
console.log(text);
link = "https://nominatim.openstreetmap.org/search?q="+text+"&format=json&polygon=1&addressdetails=1";
console.log(link);

fetch(link)
    .then(response => response.json())
    .then(data => {
        lat = data[0].lat;
        lon = data[0].lon;
        console.log(lat);
        const marker = L.marker([lat, lon]).addTo(map);
    });
