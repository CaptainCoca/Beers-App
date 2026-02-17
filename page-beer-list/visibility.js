document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. GESTION DES TOGGLES (VISIBILITÉ) ---
    const toggles = document.querySelectorAll('.public-toggle');
    toggles.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const beerId = this.getAttribute('data-beer-id');
            const status = this.checked ? 1 : 0;
            fetch('/Beers-App/page-beer-list/toggle-visibility.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${beerId}&status=${status}`
            })
            .then(response => { if (response.ok) console.log(`Bière ${beerId} : Visibilité OK`); })
            .catch(error => console.error('Erreur Toggle:', error));
        });
    });

    //BARRE DE RECHERCHE 
    const searchInput = document.querySelector('.wireframe-input') || document.querySelector('.input-custom-search');
    const searchBtn = document.querySelector('.button.is-dark') || document.querySelector('.inner-btn-search');

    function filtrerLesBieres() {
        const word = searchInput.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.column.is-3');

        cards.forEach(card => {
            const text = card.innerText.toLowerCase();
            
            if (text.includes(word)) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filtrerLesBieres);
    }

    if (searchBtn) {
        searchBtn.addEventListener('click', (e) => {
            e.preventDefault();
            filtrerLesBieres();
        });
    }
});

document.querySelectorAll('.expand-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const comment = this.previousElementSibling; // Cible la div .beer-comment
        
        if (comment.classList.contains('text-collapsed')) {
            comment.classList.remove('text-collapsed');
            this.innerText = '▲'; // Flèche vers le haut
        } else {
            comment.classList.add('text-collapsed');
            this.innerText = '▼'; // Flèche vers le bas
        }
    });
});