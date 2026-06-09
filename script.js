// DOMContentLoaded : on attend que tout le HTML soit chargé avant d'exécuter le code
// (sinon document.getElementById pourrait chercher un élément qui n'existe pas encore)
document.addEventListener('DOMContentLoaded', () => {

    // Appelle le fichier PHP qui lit la session et retourne un JSON
    fetch('/Beers-App/check_session.php')
        .then(response => response.json()) // Convertit la réponse texte en objet JavaScript
        .then(data => {
            if (data.connected) {
                // Cible la zone du header réservée aux boutons connexion/inscription
                const authZone = document.getElementById('auth-buttons');
                
                // Remplace les boutons "Connexion" / "Inscription" par un message de bienvenue
                // et un bouton "Déconnexion"
                // Template literal (backticks) : permet d'injecter des variables avec ${}
                authZone.innerHTML = `
                    <div class="user-display" style="display: flex; align-items: center; gap: 15px;">
                        <span style="color: #000000; font-weight: bold;">
                             <i class="fas fa-user"></i> Bienvenue ! ${data.pseudo}
                        </span>
                        <a href="/Beers-App/logout.php" class="inner-btn">Déconnexion</a>
                    </div>
                `;
            }
            // Si data.connected est false, on ne fait rien :
            // les boutons Connexion/Inscription restent affichés tels quels dans le HTML
        })
        .catch(error => console.error('Erreur de session:', error));
        // .catch() = gère les erreurs réseau (ex: PHP inaccessible, JSON invalide)
        // console.error() = affiche l'erreur dans la console du navigateur (F12) sans crasher la page
});