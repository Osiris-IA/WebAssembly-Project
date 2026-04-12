-- Users : id, pseudo, email, password (haché !).

-- Images : id, user_id, chemin_fichier (l'originale), date_upload.

-- Retouches : id, image_id, type_filtre (ex: "sepia"), valeur (ex: 0.5), ordre_execution.

-- Albums & Partages : Pour gérer les permissions privées/publiques.

-- Conseil de prof : Pour le "Feed" avec requête complexe, tu devras utiliser des JOIN entre Images et Albums pour vérifier si l'utilisateur connecté a le droit de voir l'image.


-- Gestion des Utilisateurs & Permissions (5 pts) : Inscription, connexion, et système d'albums. Un album peut être Privé, Public, ou Partagé avec des utilisateurs spécifiques (nécessite une table de jointure).


-- Stockage Non-Destructif (5 pts) : Upload sécurisé de l'image (vérification MIME, renommage). 
-- Le serveur stocke l'image originale intacte ET enregistre en base de données l'historique exact des retouches (la "recette") pour pouvoir rejouer l'édition.
-- Le Flux d'Actualité (Feed) (3 pts) : Une page d'accueil avec une requête SQL complexe qui remonte les images publiques et celles partagées avec l'utilisateur connecté, triées par date.

-- Pagination / Lazy Loading (2 pts) : Le backend doit exposer les données du Feed par lots (ex: LIMIT 10 OFFSET 0) via des paramètres d'URL pour ne pas saturer le navigateur.


CREATE TABLE Users (
    id int PRIMARY KEY AUTO_INCREMENT,
    pseudo varchar(255) NOT NULL UNIQUE,
    email varchar(255) NOT NULL UNIQUE,
    password_hash varchar(255) NOT NULL, 
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP -- automatique

);

CREATE TABLE Albums (
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL, -- propriétaire de l'album - lien vers Users
    nom varchar(255) NOT NULL,
    is_public BOOLEAN NOT NULL DEFAULT FALSE, -- visibilité - privé/public
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id) -- clé étrangère : lien vers Users(id)
);

CREATE TABLE Images (
    id int AUTO_INCREMENT PRIMARY KEY,
    album_id int NOT NULL, -- lien vers Albums(id) pour gérer les permissions
    user_id int NOT NULL, -- lien vers Users(id) pour savoir qui a uploadé l'image
    chemin_fichier varchar(255) NOT NULL, 
    date_upload DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (album_id) REFERENCES Albums(id),
    FOREIGN KEY (user_id) REFERENCES Users(id)
);

-- table de jointure
CREATE TABLE Album_shares (
    album_id int NOT NULL, -- id de l'album partagé - lien vers Albums
    user_id int NOT NULL, -- utilisateur avec qui on partage - lien vers Users
    can_edit BOOLEAN NOT NULL DEFAULT FALSE, -- permission d'édition pour les albums partagés
    shared_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,-- date de partage
    PRIMARY KEY (album_id, user_id), -- clé primaire composite pour éviter les doublons de partage
    FOREIGN KEY (album_id) REFERENCES Albums(id), -- clé étrangère : lien vers Albums(id)
    FOREIGN KEY (user_id) REFERENCES Users(id)
);


CREATE TABLE historique_Retouches (
    id int AUTO_INCREMENT PRIMARY KEY,
    image_id int NOT NULL, -- lien vers Images(id) pour savoir à quelle image la retouche s'applique
    user_id int NOT NULL, -- lien vers Users(id) pour savoir qui a fait la retouche
    type_filtre varchar(255) NOT NULL, -- ex: "sepia", "brightness", etc.
    valeur DECIMAL(10, 2) NOT NULL, -- valeur du filtre, ex: 0.5 pour 50% de sepia
    ordre_execution int NOT NULL, -- pour savoir dans quel ordre appliquer les retouches
    FOREIGN KEY (image_id) REFERENCES Images(id),
    FOREIGN KEY (user_id) REFERENCES Users(id)
);



SELECT a.*
FROM Albums a
LEFT JOIN Album_shares s
  ON s.album_id = a.id AND s.user_id = :current_user_id
WHERE a.id = :album_id
  AND (
    a.user_id = :current_user_id
    OR a.is_public = TRUE
    OR s.user_id IS NOT NULL
  );
-- Cette requête SQL vérifie si l'utilisateur connecté a le droit de voir un album spécifique en fonction de sa propriété, de sa visibilité et des partages. Remplacez :current_user_id et :album_id par les valeurs appropriées lors de l'exécution de la requête.

SELECT i.*
FROM Images i
JOIN Albums a ON i.album_id = a.id -- pour savoir si l’image est publique 
LEFT JOIN Album_shares s -- pour savoir si elle est partagée
    ON s.album_id = a.id AND s.user_id = :user_id

WHERE -- filtre ce que tu peux voir
    i.user_id = :user_id
    OR a.is_public = TRUE
    OR s.user_id IS NOT NULL

ORDER BY i.date_upload DESC;

LIMIT 10 OFFSET 0;