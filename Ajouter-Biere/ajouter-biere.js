document.addEventListener('DOMContentLoaded', () => {
    // Cible le champ de sélection de fichier (input type="file" avec id="beer_photo")
    const fileInput = document.querySelector('#beer_photo');

    // Cible l'élément HTML où l'on va afficher le nom du fichier choisi
    const fileName = document.querySelector('#file-name-display');

    // Vérifie que les deux éléments existent bien dans la page avant d'agir
    // (évite un crash si le script est chargé sur une page sans ce formulaire)
    if (fileInput && fileName) {

        // Se déclenche dès que l'utilisateur sélectionne un fichier
        fileInput.onchange = () => {
            if (fileInput.files.length > 0) {
                // .files est une liste des fichiers sélectionnés — on prend le premier [0]
                // .name retourne uniquement le nom du fichier (ex: "ma_biere.jpg")
                fileName.textContent = fileInput.files[0].name;
            } else {
                // Si l'utilisateur ouvre la fenêtre puis annule sans choisir de fichier
                fileName.textContent = "Aucun fichier...";
            }
        };
    }
});