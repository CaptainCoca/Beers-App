document.addEventListener('DOMContentLoaded', () => {
    // SYSTÈME DE LIKES
    // Chaque bouton "like" a un attribut data-id avec l'ID de la bière
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function() {

            // Si le bouton est déjà en cours de traitement, on ignore le clic (anti double-clic)
            if (this.classList.contains('is-loading')) return;

            const beerId = this.getAttribute('data-id'); // ID de la bière likée
            const countSpan = this.querySelector('.count'); // L'élément HTML qui affiche le nombre de likes
            const currentBtn = this; // Sauvegarde la référence au bouton

            // On désactive visuellement le bouton pendant l'envoi
            this.classList.add('is-loading');
            this.style.opacity = "0.5";
            this.style.pointerEvents = "none"; // Empêche tout clic pendant la requête

            // Prépare les données à envoyer au serveur (format clé=valeur)
            const params = new URLSearchParams();
            params.append('beer_id', beerId);

            // Envoie une requête POST au serveur PHP sans recharger la page (AJAX)
            fetch('/Beers-App/Avis-Public/like_process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params
            })
            .then(response => response.json()) // Convertit la réponse texte en objet JavaScript
            .then(data => {
                if (data.success) {
                    countSpan.innerText = data.new_count; // Met à jour le compteur affiché
                    // Si le like a été ajouté → classe 'active' (bouton coloré), sinon on l'enlève
                    data.status === 'added' ? currentBtn.classList.add('active') : currentBtn.classList.remove('active');
                }
            })
            .catch(error => console.error('Erreur Like:', error)) // En cas d'erreur réseau
            .finally(() => {
                // Quoi qu'il arrive (succès ou erreur), on réactive le bouton
                this.classList.remove('is-loading');
                this.style.opacity = "1";
                this.style.pointerEvents = "auto";
            });
        });
    });

    // 2. OUVRIR / FERMER LA SECTION COMMENTAIRES
    // Le bouton 📜 fait apparaître ou disparaître les commentaires d'une bière
    document.querySelectorAll('.comment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const beerId = this.getAttribute('data-id');

            // Cible le conteneur de commentaires lié à cette bière via son id HTML
            const container = document.getElementById('comments-' + beerId);
            
            if (container) {
                // getComputedStyle lit le CSS réellement appliqué (même via feuille de style externe)
                const isHidden = window.getComputedStyle(container).display === 'none';

                // Toggle : si caché → afficher, si visible → cacher
                container.style.display = isHidden ? 'block' : 'none';
            }
        });
    });

    // 3. ENVOYER UN NOUVEAU COMMENTAIRE
    // Le bouton "Envoyer" poste le commentaire sans recharger la page
    document.querySelectorAll('.send-comment').forEach(btn => {
        btn.addEventListener('click', async function() {
            const beerId = this.dataset.beerId; // Lecture de l'attribut data-beer-id

            // Sélectionne le champ texte correspondant à cette bière (attribut data-beer-id identique)
            const input = document.querySelector(`.comment-input[data-beer-id="${beerId}"]`);
            const content = input.value;

            // Cible la liste où on va injecter le nouveau commentaire
            const list = document.querySelector(`#comments-${beerId} .comments-list`);

            // Sécurité : .trim() supprime les espaces, on n'envoie pas un commentaire vide
            if (!content.trim()) return;

            try {
                const response = await fetch('/Beers-App/Avis-Public/comment_process.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    // encodeURIComponent protège les caractères spéciaux (accents, &, etc.)
                    body: `beer_id=${beerId}&content=${encodeURIComponent(content)}`
                });
                const data = await response.json();
                if (data.success) {
                    // Crée un nouvel élément HTML pour le commentaire et l'ajoute à la liste
                    const newComment = document.createElement('div');
                    newComment.className = 'comment-item';
                    newComment.innerHTML = `<strong>${data.pseudo} :</strong> <p>${data.content}</p>`;

                    // Si un message "aucun commentaire" existait, on le supprime d'abord
                    if (list.querySelector('p.is-size-7')) list.innerHTML = '';

                    list.appendChild(newComment); // Ajoute le commentaire à la fin de la liste
                    input.value = ''; // Vide le champ de saisie après envoi
                }
            } catch (error) { alert("Erreur envoi."); }
        });
    });

    // 4. BARRE DE RECHERCHE (filtre les cartes en temps réel)
    // Masque les cartes bières/bars qui ne correspondent pas à la saisie
    document.querySelector('.input-custom-search').addEventListener('input', function(e) {

        const searchTerms = e.target.value.toLowerCase(); // Texte tapé, tout en minuscules

        // Sélectionne toutes les cartes bières (en excluant les cartes bars grâce à :not)
        const beerCards = document.querySelectorAll('.beer-list-item:not(.bar-adventure-card)');
        const barCards = document.querySelectorAll('.bar-adventure-card');

        // Pour chaque carte bière, on affiche (flex) ou masque (none) selon la correspondance
        beerCards.forEach(card => {
            const text = card.innerText.toLowerCase();
            card.style.display = text.includes(searchTerms) ? 'flex' : 'none';
        });

        // Idem pour les cartes bars (display vide = valeur CSS par défaut, pas 'flex')
        barCards.forEach(card => {
            const text = card.innerText.toLowerCase();
            card.style.display = text.includes(searchTerms) ? '' : 'none';
        });
    });

}); // FIN DU DOMContentLoaded

// 5. SUPPRESSION ET ÉDITION DE COMMENTAIRES
// Utilise la "délégation d'événements" car les commentaires sont
// ajoutés dynamiquement (ils n'existent pas au chargement initial)
// → On écoute les clics sur document entier, puis on vérifie la cible
document.addEventListener('click', async (e) => {

    // ----- SUPPRIMER UN COMMENTAIRE -----
    if (e.target.classList.contains('delete-comment-btn')) {
        const commentId = e.target.dataset.id;

        // Demande confirmation avant suppression (confirm = boîte de dialogue native)
        if (!confirm("Effacer ce parchemin ?")) return;

        try {
            const response = await fetch('/Beers-App/Avis-Public/delete_comment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `comment_id=${commentId}`
            });
            const data = await response.json();

            // Si la suppression a réussi côté serveur, on retire l'élément du DOM (sans recharger)
            if (data.success) document.getElementById(`comment-block-${commentId}`).remove();
        } catch (err) { alert("Erreur suppression."); }
    }

    // ----- PASSER EN MODE ÉDITION -----
    // Remplace le texte du commentaire par un champ input + boutons valider/annuler
    if(e.target.classList.contains('edit-comment-btn')) {
        const commentId = e.target.dataset.id;
        const block = document.getElementById(`comment-block-${commentId}`);
        const textElement = block.querySelector('p');
        const currentText = textElement.innerText; // On sauvegarde le texte original

        // Remplace le paragraphe par un formulaire d'édition inline
        textElement.innerHTML = `
            <div class="field has-addons mt-2">
                <div class="control is-expanded"><input class="input is-small edit-input" type="text" value="${currentText}"></div>
                <div class="control"><button class="button is-small is-success is-light save-edit" data-id="${commentId}">✔️</button></div>
                <div class="control"><button class="button is-small is-danger is-light cancel-edit" data-id="${commentId}" data-old="${currentText}">❌</button></div>
            </div>`;

        // Cache les boutons d'action (éditer/supprimer) pendant qu'on est en mode édition
        block.querySelector('.comment-actions').style.display = 'none';
    }

    // ----- SAUVEGARDER L'ÉDITION -----
    if (e.target.classList.contains('save-edit')) {
        const commentId = e.target.dataset.id;
        const block = document.getElementById(`comment-block-${commentId}`);
        const newContent = block.querySelector('.edit-input').value; // Récupère le nouveau texte

        try {
            const response = await fetch('update_comment.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `comment_id=${commentId}&content=${encodeURIComponent(newContent)}`
            });
            const data = await response.json();
            // Si le serveur confirme la mise à jour, on remet l'affichage normal avec le nouveau texte
            if (data.success) restaurerCommentaire(commentId, newContent);
        } catch (err) { alert("Erreur mise à jour."); }
    }

    // ----- ANNULER L'ÉDITION -----
    // data-old contient l'ancien texte stocké en HTML, on le remet tel quel
    if (e.target.classList.contains('cancel-edit')) {
        restaurerCommentaire(e.target.dataset.id, e.target.dataset.old);
    }
});

// FONCTION UTILITAIRE : Remet un commentaire en mode "lecture"
// Réaffiche le texte et les boutons d'action normaux
function restaurerCommentaire(id, texte) {
    const block = document.getElementById(`comment-block-${id}`);
    if(block) {
        block.querySelector('p').innerText = texte; // Remet le texte (ancien ou nouveau)
        block.querySelector('.comment-actions').style.display = 'block'; // Réaffiche les boutons
    }
}

// FONCTION : Bascule entre les onglets "Bières" et "Bars"
// du Grimoire Communautaire
function switchGrimoire(type) {
    // Cache les deux sections
    document.getElementById('grimoire-bieres').style.display = 'none';
    document.getElementById('grimoire-bars').style.display = 'none';
    
    // Retire la classe "active" (surbrillance) de tous les boutons d'onglets
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    // Affiche la bonne section et met le bouton cliqué en surbrillance
    if (type === 'bieres') {
        document.getElementById('grimoire-bieres').style.display = 'block';
        event.currentTarget.classList.add('active'); // event.currentTarget = le bouton qui a déclenché l'event
    } else {
        document.getElementById('grimoire-bars').style.display = 'block';
        event.currentTarget.classList.add('active');
    }
}

// FONCTION : Afficher / masquer la description complète
// Le bouton "Voir le récit" étend ou réduit le texte coupé
function toggleText(btn) {
    // previousElementSibling = l'élément HTML qui se trouve juste avant le bouton dans le DOM
    const textZone = btn.previousElementSibling;
    
    if (textZone.classList.contains('clamped')) {
        // 'clamped' = classe CSS qui coupe le texte (ex: max 3 lignes avec overflow hidden)
        textZone.classList.remove('clamped'); // Affiche tout le texte
        btn.innerText = "▲ Réduire";
    } else {
        textZone.classList.add('clamped'); // Recoupe le texte
        btn.innerText = "▼ Voir le récit";
    }
}