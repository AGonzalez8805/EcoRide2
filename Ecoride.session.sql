-- USE Ecoride;

CREATE TABLE utilisateurs(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name varchar(255) NOT NULL,
    firstName varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    pseudo VARCHAR (50) NOT NULL UNIQUE,
    photo VARCHAR (255),
    isSuspended BOOLEAN DEFAULT FALSE,
    credit INT DEFAULT 20
);

CREATE TABLE role(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR (255) NOT NULL UNIQUE
);

CREATE TABLE possede(
    id INT NOT NULL,
    id_utilisateurs INT NOT NULL,
    PRIMARY KEY (id, id_utilisateurs),
    FOREIGN KEY (id) REFERENCES role(id),
    FOREIGN KEY (id_utilisateurs) REFERENCES utilisateurs(id)
);

DROP TABLE IF EXISTS possede;

CREATE TABLE admin(
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR (255) NOT NULL,
    pseudo VARCHAR (50) NOT NULL UNIQUE,
    password VARCHAR (255) NOT NULL
);

INSERT IGNORE INTO role (name, description)
VALUES (
    'admin', 
    'Administrateur avec tous les droits');

INSERT INTO admin (email, pseudo, password)
VALUES (
    'admin@ecoride.com', 
    'Jose', 
    '$2y$12$Tvy8qLaLftRTYIYYq45s1ux/0whsHzp.VL.EQnQRWACu4mieYsNye');

CREATE TABLE employe(
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR (255) NOT NULL,
    pseudo VARCHAR (50) NOT NULL UNIQUE,
    password VARCHAR (255) NOT NULL,
    isSuspended BOOLEAN DEFAULT FALSE,
    id_admin INT NOT NULL,
    FOREIGN KEY (id_admin) REFERENCES admin(id)
);

CREATE TABLE voiture(
    id INT AUTO_INCREMENT PRIMARY KEY,
    modele VARCHAR(50) NOT NULL,
    immatriculation VARCHAR(50) NOT NULL UNIQUE,
    energie VARCHAR(50) NOT NULL,
    couleur VARCHAR(50) NOT NULL,
    marque VARCHAR(50) NOT NULL,
    datePremierImmatriculation DATE NOT NULL,
    nbPlaces INT NOT NULL,
    fumeur BOOLEAN DEFAULT FALSE,
    animaux BOOLEAN DEFAULT FALSE,
    preferencesSupplementaires VARCHAR(255),
    id_utilisateurs INT NOT NULL,
    FOREIGN KEY (id_utilisateurs) REFERENCES utilisateurs(id)
);

RENAME TABLE voiture TO vehicule;

CREATE TABLE covoiturage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dateDepart DATE NOT NULL,
    heureDepart TIME NOT NULL,
    lieuDepart VARCHAR(100) NOT NULL,
    dateArrivee DATE NOT NULL,
    heureArrivee TIME NOT NULL,
    lieuArrivee VARCHAR(100) NOT NULL,
    statut VARCHAR(50) NOT NULL,
    nbPlace INT NOT NULL,
    prixPersonne FLOAT NOT NULL,
    id_utilisateurs INT NOT NULL, -- chauffeur
    id_voiture INT NOT NULL,
    FOREIGN KEY (id_utilisateurs) REFERENCES utilisateurs(id),
    FOREIGN KEY (id_voiture) REFERENCES voiture(id)
);

-- Supprimer la contrainte existante sur id_voiture
ALTER TABLE covoiturage
    DROP FOREIGN KEY covoiturage_ibfk_2;

-- Renommer la colonne id_voiture en id_vehicule
ALTER TABLE covoiturage
    CHANGE id_voiture id_vehicule INT NOT NULL;

-- Ajouter la nouvelle contrainte de clé étrangère vers vehicule(id)
ALTER TABLE covoiturage
    ADD CONSTRAINT fk_covoiturage_vehicule
    FOREIGN KEY (id_vehicule) REFERENCES vehicule(id);


CREATE TABLE participation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateurs INT NOT NULL,
    id_covoiturage INT NOT NULL,
    dateInscription DATE DEFAULT (CURRENT_DATE),
    statut VARCHAR(50) DEFAULT 'en_attente',
    FOREIGN KEY (id_utilisateurs) REFERENCES utilisateurs(id),
    FOREIGN KEY (id_covoiturage) REFERENCES covoiturage(id)
);

CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commentaire VARCHAR(255) NOT NULL,
    note TINYINT NOT NULL CHECK (note BETWEEN 1 AND 5),
    statut VARCHAR(50) NOT NULL,
    datePublication DATE NOT NULL,
    id_utilisateurs INT NOT NULL, -- auteur de l'avis
    id_employe INT NOT NULL,
    FOREIGN KEY (id_utilisateurs) REFERENCES utilisateurs(id),
    FOREIGN KEY (id_employe) REFERENCES employe(id)
);

CREATE TABLE transaction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateurs INT,
    typeOperation VARCHAR(50), -- paiement, participation, crédit offert, etc.
    montant INT NOT NULL,
    dateOperation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateurs) REFERENCES utilisateurs(id)
);

    
ALTER TABLE admin ADD id_role INT;

ALTER TABLE employe ADD id_role INT;

ALTER TABLE utilisateurs 
ADD typeUtilisateur ENUM('chauffeur', 'passager', 'chauffeur-passager', 'non-defini') NOT NULL DEFAULT 'non-defini' ;

ALTER TABLE admin 
ADD CONSTRAINT fk_admin_role 
FOREIGN KEY (id_role) REFERENCES role(id);

ALTER TABLE employe 
ADD CONSTRAINT fk_employe_role 
FOREIGN KEY (id_role) REFERENCES role(id);

INSERT INTO role (name) VALUES 
('admin'),
('employe'),
('utilisateur'),
('chauffeur'),
('passager'),
('chauffeur-passager');

INSERT INTO utilisateurs (id, name, firstName, email, password, credit)
VALUES (
    NULL,
    'Dupont',
    'Julie',
    'julie.dupont@mail.com',
    '$2y$10$MSeHhNCSyFfYeIs5xuzz.uRF1UX3aeNDAYdEB7QXr/OjEWgk6y356',
    20
);

INSERT INTO vehicule (modele, immatriculation, energie, couleur, marque, 
                    datePremierImmatriculation, nbPlaces, fumeur, animaux, 
                    preferencesSupplementaires, id_utilisateurs)
VALUES (
    'Clio', 
    'AB-123-CD', 
    'Essence', 
    'Rouge', 
    'Renault', 
    '2020-01-01', 
    5, 
    FALSE, 
    FALSE, 
    'Aucune', 
    24
);

INSERT INTO covoiturage(
    dateDepart,heureDepart, lieuDepart,
    dateArrivee, heureArrivee, lieuArrivee,
    statut, nbPlace, prixPersonne,
    id_utilisateurs, id_vehicule
) VALUES (
    '2023-09-15', '14:00:00', 'Paris',
    '2023-09-15', '15:00:00', 'Marseille',
    'en_cours', 2, 100.0,
    24, 12
);