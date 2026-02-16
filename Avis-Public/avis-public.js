document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Si le bouton est déjà en cours de traitement, on ne fait rien
        if (this.classList.contains('is-loading')) return;

        const beerId = this.getAttribute('data-id');
        const countSpan = this.querySelector('.count');
        const currentBtn = this;

        // Bloque le bouton visuellement et techniquement
        this.classList.add('is-loading');
        this.style.opacity = "0.5";
        this.style.pointerEvents = "none";

        const params = new URLSearchParams();
        params.append('beer_id', beerId);

        // Utilise le chemin relatif
        fetch('/Beers-App/Avis-Public/like_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: params
        })
        .then(response => {
            if (!response.ok) throw new Error('Erreur réseau');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Mise à jour du chiffre
                countSpan.innerText = data.new_count;
                
                // Toggle de la classe active selon si on a ajouté ou retiré le like
                // Si status est 'added', on ajoute. Sinon on enlève.
                if (data.status === 'added') {
                    currentBtn.classList.add('active');
                } else {
                    currentBtn.classList.remove('active');
                }
            } else {
                alert("Erreur : " + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert("Impossible de contacter le serveur.");
        })
        .finally(() => {
            // Redonne la main à l'utilisateur
            this.classList.remove('is-loading');
            this.style.opacity = "1";
            this.style.pointerEvents = "auto";
        });
    });

    // PARTIE Commentaires
    const commentButtons = document.querySelectorAll('.comment-btn');

    commentButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const beerId = this.getAttribute('data-id');
            const container = document.getElementById('comments-' + beerId);
            
            // Toggle : Affiche ou cache
            if (container.style.display === 'none' || container.style.display === '') {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        });
    });

});

// ENVOI D'UN COMMENTAIRE
document.querySelectorAll('.send-comment').forEach(btn => {
    btn.addEventListener('click', async function() {
        const beerId = this.dataset.beerId;
        const input = document.querySelector(`.comment-input[data-beer-id="${beerId}"]`);
        const content = input.value;
        const list = document.querySelector(`#comments-${beerId} .comments-list`);

        if (!content.trim()) return; // Ne rien faire si c'est vide

        try {
            const response = await fetch('/Beers-App/Avis-Public/comment_process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `beer_id=${beerId}&content=${encodeURIComponent(content)}`
            });

            const data = await response.json();

            if (data.success) {
                // Créer le nouveau message graphiquement
                const newComment = document.createElement('div');
                newComment.className = 'comment-item';
                newComment.innerHTML = `<strong>${data.pseudo} :</strong> <p>${data.content}</p>`;
                
                // Si c'était le premier com, on vide le message "aucun parchemin"
                if (list.querySelector('p.is-size-7')) {
                    list.innerHTML = '';
                }

                list.appendChild(newComment);
                input.value = ''; // Vider le champ
            } else {
                alert(data.message);
            }
        } catch (error) {
            alert("Erreur lors de l'envoi du parchemin.");
        }
    });
});

//SUPPRESSION
document.addEventListener('click', async (e) => {
    if (e.target.classList.contains('delete-comment-btn')) {
        const commentId = e.target.dataset.id;
        if (!confirm("Voulez-vous vraiment effacer ce parchemin ?")) return;

        try {
            const response = await fetch('/Beers-App/Avis-Public/delete_comment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `comment_id=${commentId}`
            });
            const data = await response.json();
            if (data.success) {
                document.getElementById(`comment-block-${commentId}`).remove();
            }
        } catch (err) { alert("Erreur lors de la suppression."); }
    }
});

