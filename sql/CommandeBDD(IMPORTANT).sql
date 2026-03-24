/* Nouvelle table pour eviter que l'utilisateur like plusieur fois la même biere */
CREATE TABLE likes_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,  
    beer_id INT NOT NULL,    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_beer (user_id, beer_id)
);

/* Nouvelle table pour les commentaire */
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    beer_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    pseudo VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS beers_table (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,          -- Lien vers ton utilisateur
    beer_name VARCHAR(100) NOT NULL,
    rating INT(1) DEFAULT 0,           -- Ta note sur 5
    description TEXT,
    image_path VARCHAR(255),           -- Le nom du fichier image
    is_public TINYINT(1) DEFAULT 0,    -- 0 = privé, 1 = partagé
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    -- On ajoute la clé étrangère pour la cohérence des données
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE beers_table ADD COLUMN likes INT DEFAULT 0;

/* Nouvelle table pour les bars */
CREATE TABLE IF NOT EXISTS bars_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    bar_name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    rating INT NOT NULL,
    description TEXT,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

ALTER TABLE bars_table ADD COLUMN is_public TINYINT(1) DEFAULT 0;

CREATE TABLE IF NOT EXISTS niort_bars_reference (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100)   NOT NULL,
  address     VARCHAR(255),
  latitude    DECIMAL(10, 8) NOT NULL,
  longitude   DECIMAL(11, 8) NOT NULL,
  rating      DECIMAL(3, 2)  DEFAULT 0,   -- moyenne calculée automatiquement
  phone       VARCHAR(20),
  status      VARCHAR(50),
  description TEXT,
  image_url   VARCHAR(255)   DEFAULT 'bar-default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- AJOUT DE LA FK DANS bars_table (Pour le trigger)
ALTER TABLE bars_table
  ADD COLUMN bar_ref_id INT DEFAULT NULL,
  ADD CONSTRAINT fk_bars_table_ref
    FOREIGN KEY (bar_ref_id)
    REFERENCES niort_bars_reference(id)
    ON DELETE SET NULL;

-- TRIGGERS
DELIMITER $$

CREATE TRIGGER trg_rating_after_insert
AFTER INSERT ON bars_table
FOR EACH ROW
BEGIN
  IF NEW.bar_ref_id IS NOT NULL THEN
    UPDATE niort_bars_reference
    SET rating = (
      SELECT ROUND(AVG(rating), 2)
      FROM bars_table
      WHERE bar_ref_id = NEW.bar_ref_id
    )
    WHERE id = NEW.bar_ref_id;
  END IF;
END$$

CREATE TRIGGER trg_rating_after_update
AFTER UPDATE ON bars_table
FOR EACH ROW
BEGIN
  IF NEW.bar_ref_id IS NOT NULL THEN
    UPDATE niort_bars_reference
    SET rating = (
      SELECT ROUND(AVG(rating), 2)
      FROM bars_table
      WHERE bar_ref_id = NEW.bar_ref_id
    )
    WHERE id = NEW.bar_ref_id;
  END IF;
END$$

CREATE TRIGGER trg_rating_after_delete
AFTER DELETE ON bars_table
FOR EACH ROW
BEGIN
  IF OLD.bar_ref_id IS NOT NULL THEN
    UPDATE niort_bars_reference
    SET rating = (
      SELECT COALESCE(ROUND(AVG(rating), 2), 0)
      FROM bars_table
      WHERE bar_ref_id = OLD.bar_ref_id
    )
    WHERE id = OLD.bar_ref_id;
  END IF;
END$$

DELIMITER ;

-- 10 bars de niort pour commencé
INSERT INTO niort_bars_reference 
    (name, address, latitude, longitude, rating, phone, status, description) 
VALUES
(
    'Le B-Pub',
    '5 Esplanade de la République, 79000 Niort',
    46.32412160, -0.45933248,
    0, NULL, 'Ouvert',
    'Bar-brasserie irlandais ouvert depuis 1991. Bières pression, whiskies, cocktails et restauration toute la journée. Écran géant pour les matchs.'
),
(
    'Au Bureau',
    '7 Esplanade de la République, 79000 Niort',
    46.32409675, -0.45941666,
    0, NULL, 'Ouvert',
    'Pub-brasserie à l\'ambiance anglaise. Large choix de bières, burgers et plats à partager. Happy hours réguliers.'
),
(
    'Le Temple Bar',
    'Place du Temple, 79000 Niort',
    46.32418100, -0.45919662,
    0, NULL, 'Ouvert',
    'Le bar irlandais de Niort, situé sur l\'esplanade de la place du Temple. Ambiance pub authentique.'
),
(
    'Le 11 Bis Troquet Lounge',
    '11 Bis Rue Victor Hugo, 79000 Niort',
    46.32650106, -0.46443875,
    0, NULL, 'Ouvert',
    'Bar-lounge tendance du centre-ville, préféré des jeunes Niortais. Cocktails et ambiance moderne.'
),
(
    'Le Hangar',
    'Port Boineau, 79000 Niort',
    46.33711784, -0.40030042,
    0, NULL, 'Ouvert',
    'Bar, restaurant et salle de concert avec dancefloor. Cuisine maison à base de produits frais. Lieu festif et convivial.'
),
(
    'La Cervoiserie',
    'Rue de la Gare, 79000 Niort',
    46.31599087, -0.49372763,
    0, NULL, 'Ouvert',
    'Bar à thème dédié aux bières artisanales. Lieu convivial et animé avec une sélection unique de bières.'
),
(
    'Le Disque Bleu',
    'Avenue Saint Jean d\'Angély, 79000 Niort',
    46.31725721, -0.46557912,
    0, NULL, 'Ouvert',
    'Bar chaleureux et décontracté. Bières pression, cocktails, vins. Soirées à thème et événements sportifs réguliers.'
),
(
    'Magic Flonflon',
    'Port Boinot, 3 Rue de la Chamoiserie, 79000 Niort',
    46.32464553, -0.46952515,
    0, NULL, 'Ouvert',
    'Bar à vins et cocktails parmi les plus fréquentés de Niort. Lieu de rencontre et de détente.'
),
(
    'La ØX Taverne',
    'Esplanade du Jardin de la Brèche, 79000 Niort',
    46.33461289, -0.42802630,
    0, NULL, 'Ouvert',
    'Taverne avec repas sur place. Ambiance unique et authentique au cœur de Niort.'
),
(
    'Le Café Star',
    'Route de Coulonges, 79000 Niort',
    46.34357836, -0.47510833,
    0, NULL, 'Ouvert',
    'Café convivial et chaleureux. Boissons chaudes et froides, snacks. Idéal pour une pause détente.'
);




