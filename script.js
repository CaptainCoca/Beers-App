// On attend que la page soit prête
document.addEventListener('DOMContentLoaded', () => {

    // On appelle notre fichier PHP
    fetch('check_session.php')
        .then(response => response.json())
        .then(data => {
            if (data.connected) {
                // On sélectionne la zone des boutons
                const authZone = document.getElementById('auth-buttons');
                
                // On vide les boutons et on injecte le pseudo + déconnexion
                authZone.innerHTML = `
                    <div class="user-display" style="display: flex; align-items: center; gap: 15px;">
                        <span style="color: #ffca3a; font-weight: bold;">
                             <i class="fas fa-user"></i> ${data.pseudo}
                        </span>
                        <a href="logout.php" class="inner-btn" style="background-color: #ff3860; color: white;">Déconnexion</a>
                    </div>
                `;
            }
        })
        .catch(error => console.error('Erreur de session:', error));
});