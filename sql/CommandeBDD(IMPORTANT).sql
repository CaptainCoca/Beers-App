/* Il faudra l'ajouter dans la table beers_table*/
ALTER TABLE beers_table ADD COLUMN is_public TINYINT(1) DEFAULT 0;

/* Il faudra l'ajouter dans la table beers_table*/
ALTER TABLE public_beers ADD COLUMN likes INT DEFAULT 0;

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

/* Bref ta compris */
ALTER TABLE bars_table ADD COLUMN is_public TINYINT(1) DEFAULT 0;
