document.addEventListener('DOMContentLoaded', () => {

    // --- 1. LIKES ---
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.classList.contains('is-loading')) return;

            const beerId = this.getAttribute('data-id');
            const countSpan = this.querySelector('.count');
            const currentBtn = this;

            this.classList.add('is-loading');
            this.style.opacity = "0.5";
            this.style.pointerEvents = "none";

            const params = new URLSearchParams();
            params.append('beer_id', beerId);

            fetch('/Beers-App/Avis-Public/like_process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    countSpan.innerText = data.new_count;
                    data.status === 'added' ? currentBtn.classList.add('active') : currentBtn.classList.remove('active');
                }
            })
            .catch(error => console.error('Erreur Like:', error))
            .finally(() => {
                this.classList.remove('is-loading');
                this.style.opacity = "1";
                this.style.pointerEvents = "auto";
            });
        });
    });

    // --- 2. OUVRIR / FERMER LES COMMENTAIRES (📜) ---
    document.querySelectorAll('.comment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const beerId = this.getAttribute('data-id');
            const container = document.getElementById('comments-' + beerId);
            
            if (container) {
                // On vérifie le style actuel pour switcher
                const isHidden = window.getComputedStyle(container).display === 'none';
                container.style.display = isHidden ? 'block' : 'none';
            }
        });
    });

    // --- 3. ENVOYER UN COMMENTAIRE ---
    document.querySelectorAll('.send-comment').forEach(btn => {
        btn.addEventListener('click', async function() {
            const beerId = this.dataset.beerId;
            const input = document.querySelector(`.comment-input[data-beer-id="${beerId}"]`);
            const content = input.value;
            const list = document.querySelector(`#comments-${beerId} .comments-list`);

            if (!content.trim()) return;

            try {
                const response = await fetch('/Beers-App/Avis-Public/comment_process.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `beer_id=${beerId}&content=${encodeURIComponent(content)}`
                });
                const data = await response.json();
                if (data.success) {
                    const newComment = document.createElement('div');
                    newComment.className = 'comment-item';
                    newComment.innerHTML = `<strong>${data.pseudo} :</strong> <p>${data.content}</p>`;
                    if (list.querySelector('p.is-size-7')) list.innerHTML = '';
                    list.appendChild(newComment);
                    input.value = '';
                }
            } catch (error) { alert("Erreur envoi."); }
        });
    });

    // --- 4. BARRE DE RECHERCHE ---
    document.querySelector('.input-custom-search').addEventListener('input', function(e) {

    const searchTerms = e.target.value.toLowerCase();
    const beerCards = document.querySelectorAll('.beer-list-item:not(.bar-adventure-card)');
    const barCards = document.querySelectorAll('.bar-adventure-card');

    beerCards.forEach(card => {
        const text = card.innerText.toLowerCase();
        card.style.display = text.includes(searchTerms) ? 'flex' : 'none';
    });

    barCards.forEach(card => {
        const text = card.innerText.toLowerCase();
        card.style.display = text.includes(searchTerms) ? '' : 'none';
    });
});

}); // FIN DU DOMContentLoaded

// --- 5. SUPPRESSION / EDITION (Délégation d'événements car éléments dynamiques) ---
document.addEventListener('click', async (e) => {
    // SUPPRIMER
    if (e.target.classList.contains('delete-comment-btn')) {
        const commentId = e.target.dataset.id;
        if (!confirm("Effacer ce parchemin ?")) return;
        try {
            const response = await fetch('/Beers-App/Avis-Public/delete_comment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `comment_id=${commentId}`
            });
            const data = await response.json();
            if (data.success) document.getElementById(`comment-block-${commentId}`).remove();
        } catch (err) { alert("Erreur suppression."); }
    }

    // EDITER (Apparition de l'input)
    if(e.target.classList.contains('edit-comment-btn')) {
        const commentId = e.target.dataset.id;
        const block = document.getElementById(`comment-block-${commentId}`);
        const textElement = block.querySelector('p');
        const currentText = textElement.innerText;

        textElement.innerHTML = `
            <div class="field has-addons mt-2">
                <div class="control is-expanded"><input class="input is-small edit-input" type="text" value="${currentText}"></div>
                <div class="control"><button class="button is-small is-success is-light save-edit" data-id="${commentId}">✔️</button></div>
                <div class="control"><button class="button is-small is-danger is-light cancel-edit" data-id="${commentId}" data-old="${currentText}">❌</button></div>
            </div>`;
        block.querySelector('.comment-actions').style.display = 'none';
    }

    // SAUVEGARDER L'EDIT
    if (e.target.classList.contains('save-edit')) {
        const commentId = e.target.dataset.id;
        const block = document.getElementById(`comment-block-${commentId}`);
        const newContent = block.querySelector('.edit-input').value;
        try {
            const response = await fetch('update_comment.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `comment_id=${commentId}&content=${encodeURIComponent(newContent)}`
            });
            const data = await response.json();
            if (data.success) restaurerCommentaire(commentId, newContent);
        } catch (err) { alert("Erreur mise à jour."); }
    }

    if (e.target.classList.contains('cancel-edit')) {
        restaurerCommentaire(e.target.dataset.id, e.target.dataset.old);
    }
});

function restaurerCommentaire(id, texte) {
    const block = document.getElementById(`comment-block-${id}`);
    if(block) {
        block.querySelector('p').innerText = texte;
        block.querySelector('.comment-actions').style.display = 'block';
    }
}

function switchGrimoire(type) {
    // 1. Cacher les deux sections
    document.getElementById('grimoire-bieres').style.display = 'none';
    document.getElementById('grimoire-bars').style.display = 'none';
    
    // 2. Enlever la classe active des boutons
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    // 3. Afficher la bonne section et activer le bouton
    if (type === 'bieres') {
        document.getElementById('grimoire-bieres').style.display = 'block';
        event.currentTarget.classList.add('active');
    } else {
        document.getElementById('grimoire-bars').style.display = 'block';
        event.currentTarget.classList.add('active');
    }
}

function toggleText(btn) {
    const textZone = btn.previousElementSibling;
    
    if (textZone.classList.contains('clamped')) {
        textZone.classList.remove('clamped');
        btn.innerText = "▲ Réduire";
    } else {
        textZone.classList.add('clamped');
        btn.innerText = "▼ Voir le récit";
    }
}