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

-- TRIGGERS (a faire)

