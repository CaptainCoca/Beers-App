function updateVisibility(checkbox) {
    const barId = checkbox.getAttribute('data-bar-id');
    const isPublic = checkbox.checked ? 1 : 0;
    const statusText = document.getElementById('status-text');

    // Baisse l'opacité pendant l'envoi
    checkbox.style.opacity = "0.5";
    statusText.innerText = "⏳ Mise à jour...";

    // Envoie les données à bar-visibility.php
    fetch('bar-visibility.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `bar_id=${barId}&is_public=${isPublic}&ajax=1` // On ajoute ajax=1
    })
    .then(response => {
        if (response.ok) {
            checkbox.style.opacity = "1";
            statusText.innerText = isPublic ? "✅ Partagé !" : "📜 Partager dans le Grimoire Public";
            
            // Remet le texte normal après 2 secondes
            setTimeout(() => {
                statusText.innerText = isPublic ? "📜 Partagé (Public)" : "📜 Partager dans le Grimoire Public";
            }, 2000);
        }
    })
    .catch(error => {
        alert("Erreur lors de la mise à jour");
        checkbox.checked = !checkbox.checked; // Remet la case comme avant en cas d'erreur
    });
}