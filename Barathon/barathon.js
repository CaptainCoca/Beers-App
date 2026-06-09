// BARATHON.JS — Carte interactive des bars de Niort
// Utilise la bibliothèque Leaflet.js pour afficher une carte

// Crée la carte et la centre sur Niort (latitude, longitude)
const map = L.map('map').setView([46.3239, -0.4588], 14);

// Ajoute le fond de carte OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap' // Mention légale obligatoire d'OpenStreetMap
}).addTo(map); // Attache ce fond de carte à notre carte créée au-dessus


function renderStars(rating) {
    if (!rating || rating == 0) return '☆☆☆☆☆ <em>Aucun avis</em>';
    const full  = Math.round(rating);
    const empty = 5 - full;
    return '★'.repeat(full) + '☆'.repeat(empty) + ` ${parseFloat(rating).toFixed(1)}/5`;
}

// FONCTION : Récupère les avis d'un bar spécifique
// Filtre la liste globale `avisData` selon l'ID du bar
function getAvisPourBar(barRefId) {
    return avisData.filter(a => a.bar_ref_id == barRefId);
}

// FONCTION : Met à jour le panneau latéral
// avec les infos du bar sur lequel on a cliqué
function updateSidebar(bar) {
    // innerText = injecte du texte brut (sans HTML), plus sécurisé pour les données utilisateurs
    document.getElementById('detail-name').innerText = bar.name;

    // innerHTML = injecte du HTML (ici nécessaire pour afficher les étoiles ★ générées)
    document.getElementById('detail-rating').innerHTML  = renderStars(bar.rating);

    // Affiche le nombre d'avis ou un message par défaut si aucun avis
    document.getElementById('detail-nb-avis').innerText = 
        bar.nb_avis > 0 ? `${bar.nb_avis} avis public(s)` : 'Aucun avis pour le moment';

    // || '' = si la valeur est null ou undefined, on affiche une chaîne vide plutôt qu'un bug
    document.getElementById('detail-status').innerText  = bar.status  || '';
    document.getElementById('detail-phone').innerText   = bar.phone   || '';
    document.getElementById('detail-address').innerText = bar.address || '';

    // Récupère les avis correspondant à ce bar via la fonction définie plus haut
    const avis = getAvisPourBar(bar.id);
    const container = document.getElementById('avis-container');

    if (avis.length === 0) {
        container.innerHTML = '<p><em>Aucun avis partagé pour ce bar.</em></p>';
    } else {
        // .map() transforme chaque avis en bloc HTML, .join('') les colle ensemble en une seule chaîne
        container.innerHTML = avis.map(a => `
            <div class="avis-item" style="margin-bottom:10px; border-bottom:1px solid #ccc; padding-bottom:8px;">
                <strong>${a.pseudo}</strong> — ${renderStars(a.rating)}<br>
                <em style="font-size:0.85rem;">"${a.description || '...'}"</em>
            </div>
        `).join('');
    }
}

// AJOUT DES MARQUEURS sur la carte
// barsData est un tableau de bars injecté par PHP
barsData.forEach(bar => {
    // Crée un marqueur (épingle) aux coordonnées GPS du bar et l'ajoute à la carte
    const marker = L.marker([bar.latitude, bar.longitude]).addTo(map);

    // Bulle d'info qui s'affiche quand on clique sur le marqueur
    marker.bindPopup(`<strong>${bar.name}</strong><br>${renderStars(bar.rating)}`);

    // Quand on clique sur le marqueur : met à jour la sidebar ET ouvre la popup
    marker.on('click', function () {
        updateSidebar(bar);
        marker.openPopup();
    });
});

// RECHERCHE PAR NOM de bar
// Appelée quand on clique sur le bouton "Rechercher"
function searchBar() {
    // Récupère le texte tapé, le met en minuscules et supprime les espaces en début/fin
    const query = document.getElementById('bar-search').value.toLowerCase().trim();

    // .find() s'arrête au premier bar dont le nom contient la recherche
    const found = barsData.find(b => b.name.toLowerCase().includes(query));

    if (found) {
        // Déplace la carte sur le bar trouvé avec un zoom plus rapproché (16)
        map.setView([found.latitude, found.longitude], 16);
        updateSidebar(found); // Met à jour la sidebar avec les infos du bar trouvé
    } else {
        alert('Aucune escale trouvée pour "' + query + '"');
    }
}

// Permet de déclencher la recherche en appuyant sur Entrée (sans cliquer le bouton)
document.getElementById('bar-search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') searchBar();
});