document.addEventListener('DOMContentLoaded', () => {
    const toggles = document.querySelectorAll('.public-toggle');

    toggles.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const beerId = this.getAttribute('data-beer-id');
            const status = this.checked ? 1 : 0;

            // Envoi de la donnée sans recharger la page
            fetch('/Beers-App/page-beer-list/toggle-visibility.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded' 
                },
                body: `id=${beerId}&status=${status}`
            })
            .then(response => {
                if (response.ok) {
                    console.log(`Bière ${beerId} : Visibilité mise à jour (${status})`);
                    // Optionnel : on peut changer la couleur de la carte ici pour feedback visuel
                }
            })
            .catch(error => console.error('Erreur:', error));
        });
    });
});