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


