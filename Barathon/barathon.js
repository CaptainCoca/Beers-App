// Tes données restent les mêmes
const barsData = [
    {
        name: "Le Hangar",
        rating: "⭐⭐⭐⭐ 4.2",
        status: "Ouvert • Ferme à 01:00",
        phone: "05 49 79 23 10",
        quote: "Un lieu industriel parfait pour déguster une pinte.",
        lat: 46.3370, lng: -0.4001
    },
    {
        name: "La Cervoiserie",
        rating: "⭐⭐⭐⭐⭐ 4.6",
        status: "Ouvert • Ferme à 22:30",
        phone: "05 49 04 28 42",
        quote: "Le paradis des Maîtres Houblons à Niort.",
        lat: 46.3158, lng: -0.4937
    },
    {
        name: "Bar des Halles",
        rating: "⭐⭐⭐ 3.8",
        status: "Fermé • Ouvre à 07:00",
        phone: "05 49 24 12 34",
        quote: "L'escale authentique du centre-ville.",
        lat: 46.3253, lng: -0.4630
    }
];

// Initialisation de la carte
const map = L.map('map').setView([46.3239, -0.4588], 14); // Centré sur Niort

// On charge les tuiles (le dessin de la carte)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

// Ajout des marqueurs
barsData.forEach(bar => {
    const marker = L.marker([bar.lat, bar.lng]).addTo(map);
    
    // Au clic sur le marqueur
    marker.on('click', function() {
        updateSidebar(bar);
    });
});

function updateSidebar(bar) {
    document.getElementById("detail-name").innerText = bar.name;
    document.getElementById("detail-rating").innerText = bar.rating;
    document.getElementById("detail-status").innerHTML = `<span class="open">●</span> ${bar.status}`;
    document.getElementById("detail-phone").innerText = bar.phone;
    document.querySelector(".quote-box p").innerText = `"${bar.quote}"`;
}