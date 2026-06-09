// Cette fonction est appelée directement depuis le HTML via onchange="updateVisibility(this)"
function updateVisibility(checkbox) {
    // Récupère l'ID du bar stocké dans l'attribut data-bar-id de la case à cocher
    const barId = checkbox.getAttribute('data-bar-id');

    // checkbox.checked = true si la case est cochée, false sinon
    // On convertit en 0 ou 1 car la BDD stocke la visibilité comme un entier
    const isPublic = checkbox.checked ? 1 : 0;

    // Cible le texte de statut affiché à côté de la case (ex: "⏳ Mise à jour...")
    const statusText = document.getElementById('status-text');

    // Feedback visuel immédiat : la case devient semi-transparente pendant l'envoi
    checkbox.style.opacity = "0.5";
    statusText.innerText = "⏳ Mise à jour...";

    // Envoie la mise à jour au serveur PHP de façon asynchrone (sans recharger la page)
    fetch('bar-visibility.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        // ajax=1 est un paramètre qu'on ajoute pour signaler au PHP que c'est une requête AJAX
        // → le PHP renverra juste "success"/"error" au lieu de faire une redirection
        body: `bar_id=${barId}&is_public=${isPublic}&ajax=1`
    })
    .then(response => {
        if (response.ok) { // response.ok = true si le code HTTP est entre 200 et 299 (succès)
            checkbox.style.opacity = "1"; // Réaffiche la case normalement
            // Met à jour le texte selon l'état choisi
            statusText.innerText = isPublic ? "✅ Partagé !" : "📜 Partager dans le Grimoire Public";
            
            // Après 2 secondes, remet un texte d'état permanent (plus neutre que "✅ Partagé !")
            setTimeout(() => {
                statusText.innerText = isPublic ? "📜 Partagé (Public)" : "📜 Partager dans le Grimoire Public";
            }, 2000); // 2000 millisecondes = 2 secondes
        }
    })
    .catch(error => {
        alert("Erreur lors de la mise à jour");
        // En cas d'erreur réseau, on remet la case dans son état précédent (inverse du nouvel état)
        // pour que l'affichage reste cohérent avec ce qui est vraiment enregistré en BDD
        checkbox.checked = !checkbox.checked;
    });
}