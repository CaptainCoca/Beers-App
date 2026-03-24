<div class="form-carnet">
    <h2 class="carnet-title">
        Nouvelle Escale 🖋️
    </h2>
    
    <p class="is-italic mb-4" style="color: var(--noir-houblon); font-size: 0.9rem; font-family: 'Special Elite', cursive; margin-top: 5px">
        Remplissez les pages de votre carnet pour ne jamais oublier cette taverne...
    </p>

    <?php
    // Récupération des bars de référence
    $stmtBars = $pdo->query("SELECT id, name, address FROM niort_bars_reference ORDER BY name ASC");
    $barsReference = $stmtBars->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <form action="bar-process.php" method="POST" enctype="multipart/form-data">
        
        <div class="field">
            <label class="label-carnet">Nom de l'établissement</label>
            <div class="control">
                <select name="bar_ref_id" class="input-manuscrit" required onchange="fillAddress(this)">
                    <option value="">-- Choisissez une taverne --</option>
                    <?php foreach ($barsReference as $ref): ?>
                        <option 
                            value="<?= $ref['id'] ?>" 
                            data-address="<?= htmlspecialchars($ref['address']) ?>">
                            <?= htmlspecialchars($ref['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="field">
            <label class="label-carnet">📍 Localisation (Adresse)</label>
            <div class="control">
                <!-- Rempli automatiquement selon le bar choisi, non modifiable -->
                <input class="input-manuscrit" type="text" name="address" id="address-field" 
                       placeholder="Sélectionnez un bar ci-dessus..." readonly
                       style="opacity: 0.7; cursor: not-allowed;">
            </div>
        </div>

        <div class="field">
            <label class="label-carnet">Note du Maître</label>
            <div class="control">
                <select name="rating" class="input-manuscrit">
                    <option value="5">★★★★★ - Légendaire</option>
                    <option value="4">★★★★☆ - Très recommandé</option>
                    <option value="3" selected>★★★☆☆ - Sympathique</option>
                    <option value="2">★★☆☆☆ - Moyen</option>
                    <option value="1">★☆☆☆☆ - À éviter</option>
                </select>
            </div>
        </div>

        <div class="field">
            <label class="label-carnet">Mémoires (Ambiance, bières...)</label>
            <div class="control">
                <textarea class="input-manuscrit" name="description" rows="5" placeholder="Écrivez votre récit ici..."></textarea>
            </div>
        </div>

        <div class="field">
            <label class="label-carnet">Illustration du lieu (Photo)</label>
            <div class="control">
                <input type="file" name="bar_image" class="input-manuscrit" accept="image/*">
            </div>
        </div>

        <div class="from-actions">
            <button type="submit" class="btn-ajouter">
                Sceller dans le Grimoire
            </button>
            <a href="bar-list.php" class="button is-text is-fullwidth">
                Abandonner la rédaction
            </a>
        </div>
    </form>
</div>

<script>
// Remplit automatiquement l'adresse quand on choisit un bar
function fillAddress(select) {
    const selected = select.options[select.selectedIndex];
    const addressField = document.getElementById('address-field');
    addressField.value = selected.dataset.address || '';
}
</script>