// Initialisation de la carte centrée sur Niort
const map = L.map('map').setView([46.3239, -0.4588], 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

// Génération des étoiles selon la note
function renderStars(rating) {
    if (!rating || rating == 0) return '☆☆☆☆☆ <em>Aucun avis</em>';
    const full  = Math.round(rating);
    const empty = 5 - full;
    return '★'.repeat(full) + '☆'.repeat(empty) + ` ${parseFloat(rating).toFixed(1)}/5`;
}

// Récupère les avis publics pour un bar donné
function getAvisPourBar(barRefId) {
    return avisData.filter(a => a.bar_ref_id == barRefId);
}

// Mise à jour de la sidebar
function updateSidebar(bar) {
    document.getElementById('detail-name').innerText    = bar.name;
    document.getElementById('detail-rating').innerHTML  = renderStars(bar.rating);
    document.getElementById('detail-nb-avis').innerText = 
        bar.nb_avis > 0 ? `${bar.nb_avis} avis public(s)` : 'Aucun avis pour le moment';
    document.getElementById('detail-status').innerText  = bar.status  || '';
    document.getElementById('detail-phone').innerText   = bar.phone   || '';
    document.getElementById('detail-address').innerText = bar.address || '';

    // Affichage des avis utilisateurs
    const avis = getAvisPourBar(bar.id);
    const container = document.getElementById('avis-container');

    if (avis.length === 0) {
        container.innerHTML = '<p><em>Aucun avis partagé pour ce bar.</em></p>';
    } else {
        container.innerHTML = avis.map(a => `
            <div class="avis-item" style="margin-bottom:10px; border-bottom:1px solid #ccc; padding-bottom:8px;">
                <strong>${a.pseudo}</strong> — ${renderStars(a.rating)}<br>
                <em style="font-size:0.85rem;">"${a.description || '...'}"</em>
            </div>
        `).join('');
    }
}

// Ajout des marqueurs sur la carte
barsData.forEach(bar => {
    const marker = L.marker([bar.latitude, bar.longitude]).addTo(map);

    // Popup au survol
    marker.bindPopup(`<strong>${bar.name}</strong><br>${renderStars(bar.rating)}`);

    // Sidebar au clic
    marker.on('click', function () {
        updateSidebar(bar);
        marker.openPopup();
    });
});

// Recherche par nom
function searchBar() {
    const query = document.getElementById('bar-search').value.toLowerCase().trim();
    const found = barsData.find(b => b.name.toLowerCase().includes(query));

    if (found) {
        map.setView([found.latitude, found.longitude], 16);
        updateSidebar(found);
    } else {
        alert('Aucune escale trouvée pour "' + query + '"');
    }
}

// Recherche via touche Entrée
document.getElementById('bar-search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') searchBar();
});