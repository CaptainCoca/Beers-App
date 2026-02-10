// On attend que la page soit prête
document.addEventListener('DOMContentLoaded', () => {

    // On appelle notre fichier PHP
    fetch('/Beers-App/check_session.php')
        .then(response => response.json())
        .then(data => {
            if (data.connected) {
                // On sélectionne la zone des boutons
                const authZone = document.getElementById('auth-buttons');
                
                // On vide les boutons et on injecte le pseudo + déconnexion
                authZone.innerHTML = `
                    <div class="user-display" style="display: flex; align-items: center; gap: 15px;">
                        <span style="color: #000000; font-weight: bold;">
                             <i class="fas fa-user"></i> Bienvenue ! ${data.pseudo}
                        </span>
                        <a href="/Beers-App/logout.php" class="inner-btn">Déconnexion</a>
                    </div>
                `;
            }
        })
        .catch(error => console.error('Erreur de session:', error));
});