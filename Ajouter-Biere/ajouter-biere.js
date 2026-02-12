document.addEventListener('DOMContentLoaded', () => {
    const fileInput = document.querySelector('#beer_photo');
    const fileName = document.querySelector('#file-name-display');

    if (fileInput && fileName) {
        fileInput.onchange = () => {
            if (fileInput.files.length > 0) {
                // On affiche le nom du premier fichier sélectionné
                fileName.textContent = fileInput.files[0].name;
            } else {
                // Si l'utilisateur annule, on remet le texte par défaut
                fileName.textContent = "Aucun fichier...";
            }
        };
    }
});