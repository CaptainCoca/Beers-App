<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <title>Nouvelle Pépite - Le Maître Houblon</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <link rel="stylesheet" href="/Beers-App/Ajouter-Biere/ajouter-biere.css">
    <link href="https://fonts.googleapis.com/css2?family=Special+Elite&display=swap" rel="stylesheet">
</head>
<body>
    <a href="/Beers-App/page-beer-list/beer-list.php" class="back-home">
        <span>← Ma Bibliothèque</span> 
    </a>

    <div class="app-container is-flex is-align-items-center is-justify-content-center">
        <div class="container">
            <div class="columns is-centered"> <div class="column is-6"> <div class="wireframe-box p-6">
                        <h1 class="title has-text-centered">Ajouter une découverte</h1>
                    
                        <form action="/Beers-App/Ajouter-Biere\add_beer_process.php" method="POST" enctype="multipart/form-data">

                            <div class="field">
                                <label class="label">Nom de la bière</label> <div class="control">
                                    <input class="input wireframe-input" type="text" name="beer_name" placeholder="Ex: Pelican, 1664.." required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Photo de la pépite (ou pas)</label>
                                <div class="control">
                                    <div class="file has-name is-fullwidth">
                                        <label class="file-label">
                                            <input class="file-input" type="file" name="beer_photo" accept="image/*">
                                            <span class="file-cta">
                                                <span class="file-icon">📤</span>
                                                <span class="file-label">Choisir une image...</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Ma note</label>
                                <div class="control">
                                    <div class="select is-fullwidth"> <select name="rating" class="wireframe-input">
                                            <option value="5">⭐⭐⭐⭐⭐ - Divine</option>
                                            <option value="4">⭐⭐⭐⭐☆ - Très Bonne</option>
                                            <option value="3">⭐⭐⭐☆☆ - Sympathique</option>
                                            <option value="2">⭐⭐☆☆☆ - Bof Bof</option>
                                            <option value="1">⭐☆☆☆☆ - Dla Merde</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="field"> <label class="label">Mon avis (pour la postérité)</label> <div class="control">
                                    <textarea class="textarea wireframe-input" name="description" placeholder="Note de dégustation, amertume, ou anecdote rigolote..." rows="4"></textarea>
                                </div>
                            </div>

                            <div class="control mt-6">
                                <button type="submit" class="button is-dark is-fullwidth is-large inner-btn">Enregistrer dans mon grimoire</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
