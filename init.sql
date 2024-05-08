CREATE TABLE ACTIVITES(
   id_activite INT AUTO_INCREMENT,
   nom_activite VARCHAR(100),
   PRIMARY KEY(id_activite)
);


CREATE TABLE TABINDEX(
   id_index INT AUTO_INCREMENT,
   index_nom VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_index)
);

CREATE TABLE ROLES(
   id_role INT AUTO_INCREMENT,
   role VARCHAR(50),
   PRIMARY KEY(id_role)
);

CREATE TABLE CATEGORIES(
   id_categorie INT AUTO_INCREMENT,
   categorie VARCHAR(50),
   PRIMARY KEY(id_categorie)
);

CREATE TABLE TRAJETS(
   id_trajets INT AUTO_INCREMENT,
   PRIMARY KEY(id_trajets)
);

CREATE TABLE INDEXPLANNING(
   id_index_planning INT AUTO_INCREMENT,
   index_nom_planning VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_index_planning)
);

CREATE TABLE PLANNINGS(
   id_planning INT AUTO_INCREMENT,
   description TEXT,
   date_activite DATE,
   id_trajets INT,
   id_index_planning INT NOT NULL,
   id_activite INT NOT NULL,
   PRIMARY KEY(id_planning),
   FOREIGN KEY(id_trajets) REFERENCES TRAJETS(id_trajets),
   FOREIGN KEY(id_index_planning) REFERENCES INDEXPLANNING(id_index_planning),
   FOREIGN KEY(id_activite) REFERENCES ACTIVITES(id_activite)
);


CREATE TABLE FORMATIONS(
   id_formation INT AUTO_INCREMENT,
   nom_formation VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_formation)
);

CREATE TABLE SEMAINE(
   id_dispo INT AUTO_INCREMENT,
   dispo VARCHAR(50),
   PRIMARY KEY(id_dispo)
);

CREATE TABLE ETAPES(
   id_etape INT AUTO_INCREMENT,
   nom_etape VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_etape)
);

CREATE TABLE TYPE(
   id_type INT AUTO_INCREMENT,
   type VARCHAR(50) NOT NULL,
   unit_mesure VARCHAR(50),
   PRIMARY KEY(id_type)
);


CREATE TABLE ADRESSE(
   id_adresse INT AUTO_INCREMENT,
   adresse VARCHAR(255) NOT NULL,
   PRIMARY KEY(id_adresse)
);


CREATE TABLE ENTREPOTS(
   id_entrepot INT AUTO_INCREMENT,
   nom_entrepot VARCHAR(100),
   parking INT,
   id_adresse INT NOT NULL,
   PRIMARY KEY(id_entrepot),
   FOREIGN KEY(id_adresse) REFERENCES ADRESSE(id_adresse)   
);


CREATE TABLE VEHICULES(
   id_vehicule INT AUTO_INCREMENT,
   capacite INT,
   nom_du_vehicules VARCHAR(255),
   nombre_de_place INT,
   id_entrepot INT,
   PRIMARY KEY(id_vehicule),
   FOREIGN KEY(id_entrepot) REFERENCES ENTREPOTS(id_entrepot)
);

CREATE TABLE CONDUIT(
   id_trajets INT,
   id_vehicule INT,
   PRIMARY KEY(id_trajets, id_vehicule),
   FOREIGN KEY(id_trajets) REFERENCES TRAJETS(id_trajets),
   FOREIGN KEY(id_vehicule) REFERENCES VEHICULES(id_vehicule)
);

CREATE TABLE UTILISATEUR(
   id_user INT AUTO_INCREMENT,
   nom VARCHAR(50) NOT NULL,
   prenom VARCHAR(50) NOT NULL,
   email VARCHAR(80),
   telephone VARCHAR(20),
   date_inscription DATE NOT NULL,
   apikey VARCHAR(255),
   mdp VARCHAR(255) NOT NULL,
   id_adresse INT NOT NULL,
   id_entrepot INT,
   id_index INT NOT NULL,
   id_role INT NOT NULL,
   PRIMARY KEY(id_user),
   FOREIGN KEY(id_adresse) REFERENCES ADRESSE(id_adresse),
   FOREIGN KEY(id_entrepot) REFERENCES ENTREPOTS(id_entrepot),
   FOREIGN KEY(id_index) REFERENCES TABINDEX(id_index),
   FOREIGN KEY(id_role) REFERENCES ROLES(id_role)
);

CREATE TABLE TICKETS(
   id_ticket INT AUTO_INCREMENT,
   description TEXT,
   date_creation DATETIME NOT NULL,
   date_cloture DATETIME,
   id_user_admin INT,
   id_etape INT NOT NULL,
   id_categorie INT NOT NULL,
   id_user_owner INT NOT NULL,
   PRIMARY KEY(id_ticket),
   FOREIGN KEY(id_user_admin) REFERENCES UTILISATEUR(id_user),
   FOREIGN KEY(id_etape) REFERENCES ETAPES(id_etape),
   FOREIGN KEY(id_categorie) REFERENCES CATEGORIES(id_categorie),
   FOREIGN KEY(id_user_owner) REFERENCES UTILISATEUR(id_user)
);

CREATE TABLE PRODUIT(
   id_produit INT AUTO_INCREMENT,
   nom_produit VARCHAR(100),
   id_type INT NOT NULL,
   PRIMARY KEY(id_produit),
   FOREIGN KEY(id_type) REFERENCES TYPE(id_type)
);

CREATE TABLE DISCUSSION(
   id_message INT AUTO_INCREMENT,
   text TEXT,
   date_message DATETIME NOT NULL,
   id_user INT NOT NULL,
   id_ticket INT NOT NULL,
   PRIMARY KEY(id_message),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user),
   FOREIGN KEY(id_ticket) REFERENCES TICKETS(id_ticket)
);

CREATE TABLE DEMANDE(
   id_demande INT AUTO_INCREMENT,
   desc_demande VARCHAR(255),
   id_planning INT,
   id_user INT NOT NULL,
   PRIMARY KEY(id_demande),
   FOREIGN KEY(id_planning) REFERENCES PLANNINGS(id_planning),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user)
);


CREATE TABLE ETAGERES(
   id_etagere INT AUTO_INCREMENT,
   nombre_de_place INT NOT NULL,
   id_entrepot INT NOT NULL,
   PRIMARY KEY(id_etagere),
   FOREIGN KEY(id_entrepot) REFERENCES ENTREPOTS(id_entrepot)
);



CREATE TABLE DON(
   id_don INT AUTO_INCREMENT,
   prix INT NOT NULL,
   date_don DATE NOT NULL,
   id_user INT NOT NULL,
   PRIMARY KEY(id_don),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user)
);

CREATE TABLE STOCKS(
   id_stock INT AUTO_INCREMENT,
   quantite_produit INT NOT NULL,
   date_entree DATE,
   date_sortie DATE,
   date_peremption DATE,
   desc_produit TEXT,
   id_produit INT NOT NULL,
   id_etagere INT NOT NULL,
   PRIMARY KEY(id_stock),
   FOREIGN KEY(id_produit) REFERENCES PRODUIT(id_produit),
   FOREIGN KEY(id_etagere) REFERENCES ETAGERES(id_etagere)
);

CREATE TABLE COLLECTE(
   id_collecte INT AUTO_INCREMENT,
   quantite VARCHAR(50),
   id_produit INT NOT NULL,
   PRIMARY KEY(id_collecte),
   FOREIGN KEY(id_produit) REFERENCES PRODUIT(id_produit)
);

CREATE TABLE LISTE(
   id_user INT,
   id_trajets INT,
   PRIMARY KEY(id_user, id_trajets),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user),
   FOREIGN KEY(id_trajets) REFERENCES TRAJETS(id_trajets)
);


CREATE TABLE RECOLTE(
   id_trajets INT,
   id_collecte INT,
   PRIMARY KEY(id_trajets, id_collecte),
   FOREIGN KEY(id_trajets) REFERENCES TRAJETS(id_trajets),
   FOREIGN KEY(id_collecte) REFERENCES COLLECTE(id_collecte)
);

CREATE TABLE UTILISER(
   id_trajets INT,
   id_adresse INT,
   PRIMARY KEY(id_trajets, id_adresse),
   FOREIGN KEY(id_trajets) REFERENCES TRAJETS(id_trajets),
   FOREIGN KEY(id_adresse) REFERENCES ADRESSE(id_adresse)
);


CREATE TABLE PARTICIPE(
   id_user INT,
   id_planning INT,
   confirme VARCHAR(50),
   PRIMARY KEY(id_user, id_planning),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user),
   FOREIGN KEY(id_planning) REFERENCES PLANNINGS(id_planning)
);

CREATE TABLE FORMER(
   id_user INT,
   id_formation INT,
   PRIMARY KEY(id_user, id_formation),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user),
   FOREIGN KEY(id_formation) REFERENCES FORMATIONS(id_formation)
);

CREATE TABLE DISPONIBILITE(
   id_user INT,
   id_dispo INT,
   PRIMARY KEY(id_user, id_dispo),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user),
   FOREIGN KEY(id_dispo) REFERENCES SEMAINE(id_dispo)
);

CREATE TABLE RECU(
   id_collecte INT,
   id_demande INT,
   recu INT NOT NULL,
   PRIMARY KEY(id_collecte, id_demande),
   FOREIGN KEY(id_collecte) REFERENCES COLLECTE(id_collecte),
   FOREIGN KEY(id_demande) REFERENCES DEMANDE(id_demande)
);

INSERT INTO ADRESSE (adresse) VALUES
('10 Avenue des Champs-Élysées, 75008 Paris'),
('15 Boulevard Saint-Michel, 75005 Paris'),
('48 Rue de Rivoli, 75004 Paris'),
('7 Rue de la Paix, 75002 Paris'),
('20 Rue de la Convention, 75015 Paris'),
("3 Place de l'Opéra, 75009 Paris"),
('9 Rue du Faubourg Saint-Honoré, 75008 Paris'),
('2 Boulevard de la Madeleine, 75008 Paris'),
('33 Rue du Bac, 75007 Paris'),
('12 Rue de la République, 69002 Lyon');


INSERT INTO TYPE (type, unit_mesure) VALUES
('Alimentaire', 'kg'),
('Vestimentaire', 'unité'),
('Scolaire', 'unité'),
('Médical', 'unité'),
('Loisirs', 'unité');

INSERT INTO PRODUIT (nom_produit, id_type) VALUES
('Riz', 1),
('Pâtes', 1),
('Conserves de légumes', 1),
('Manteau d''hiver', 2),
('Chaussures', 2),
('Cahiers', 3),
('Stylos', 3),
('Médicaments', 4),
('Jouets', 5);

INSERT INTO INDEXPLANNING (index_nom_planning) VALUES
('terminé'), 
('organiser'),
('en attente');


INSERT INTO ACTIVITES (nom_activite) VALUES
('Collecte de vêtements'),
('Distribution de repas chauds'),
('Atelier d''informatique'),
('Soutien scolaire'),
('Visite de personnes âgées');

INSERT INTO PLANNINGS (description, date_activite,id_trajets, id_index_planning, id_activite) VALUES
('Collecte de vêtements d''hiver', '2024-05-01',NULL, 2, 1),
('Distribution de repas chauds aux SDF', '2024-05-15',NULL, 2, 2),
('Atelier d''initiation à l''informatique', '2024-06-01',NULL, 2, 3),
('Soutien scolaire pour les élèves en difficulté', '2024-06-15',NULL, 2, 4),
('Visite et animation pour les personnes âgées', '2024-07-01',NULL, 2, 5);

INSERT INTO ENTREPOTS (nom_entrepot, parking, id_adresse) VALUES
('Entrepot Paris', 6, 1),
('Entrepot Laon', 4, 2),
('Entrepot Marseille', 8, 3);

INSERT INTO ETAGERES (nombre_de_place , id_entrepot) VALUES
(50,1),
(100,1),
(600,1),
(250,1),
(70,1),
(50,2),
(100,2),
(600,2),
(250,2),
(70,2),
(50,3),
(100,3),
(600,3),
(250,3),
(70,3);

INSERT INTO STOCKS (quantite_produit, date_entree, date_sortie, date_peremption, desc_produit, id_produit, id_etagere) VALUES
(500, '2024-04-01', NULL, '2025-04-01', 'Riz basmati', 1, 1),
(300, '2024-04-05', NULL, '2025-06-01', 'Pâtes de blé', 2, 1),
(200, '2024-04-10', NULL, '2025-08-01', 'Conserves de tomates', 3, 1),
(100, '2024-04-15', NULL, '2025-10-01', 'Manteaux d''hiver taille M', 4, 2),
(50, '2024-04-20', NULL, '2025-12-01', 'Chaussures de sport taille 42', 5, 2),
(75, '2024-04-25', NULL, '2025-02-01', 'Cahiers à spirales', 6, 1),
(125, '2024-04-30', NULL, '2025-04-01', 'Stylos à bille noirs', 7, 1),
(30, '2024-05-01', NULL, '2026-01-01', 'Paracétamol 500mg', 8, 2),
(80, '2024-05-05', NULL, '2025-06-01', 'Jouets en bois', 9, 2);

INSERT INTO FORMATIONS (nom_formation) VALUES
('Formation en gestion de projet'),
('Formation en communication'),
('Formation en comptabilité'),
('Formation en informatique'),
('Formation en animation');

INSERT INTO SEMAINE (dispo) VALUES
('Lundi'),
('Mardi'),
('Mercredi'),
('Jeudi'),
('Vendredi'),
('Samedi'),
('Dimanche'),
('Vacances');

INSERT INTO ETAPES (nom_etape) VALUES
('En attente'),
('En cours'),
('Terminé'),
('Annulé');

INSERT INTO ROLES (role) VALUES
('Dirigeant'),
('Administration'), 
('Bénévole'),
('Bénéficiaire'),
('Prestataires');

INSERT INTO CATEGORIES (categorie) VALUES
('urgent'),
('connexion'), 
('perte'),
('autre'),
('je sais pas');

INSERT INTO TABINDEX (index_nom) VALUES
('inactif / déréférencé'),
('actif'),
('attente de validation');

INSERT INTO TRAJETS VALUES
(1),
(2),
(3),
(4),
(5);

INSERT INTO UTILISER (id_trajets, id_adresse) VALUES 
(1,2), 
(1,3), 
(1,4), 
(2,5), 
(2,6), 
(2,7), 
(2,8), 
(3,9), 
(3,10);

INSERT INTO UTILISATEUR (nom, prenom, email, telephone, date_inscription, apikey, mdp, id_adresse, id_entrepot, id_index, id_role) VALUES
(
'Doe', 'John', 'john.doe@gmail.com', '0635742201', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 1
),
(
'Smith', 'Alice', 'alice.smith@gmail.com', '0635752202', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 2
),
(
'Johnson', 'Michael', 'michael.johnson@gmail.com', '0635752203', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 3
),
(
'Brown', 'Emma', 'emma.brown@gmail.com', '0635752204', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 4
),
(
'Miller', 'Sophia', 'sophia.miller@gmail.com', '0635752205', '2022-04-01', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 5
),
(
'Davis', 'William', 'william.davis@gmail.com', '0635752206', '2021-01-02', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 3
),
(
'Wilson', 'Olivia', 'olivia.wilson@gmail.com', '0635752207', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 1
),
(
'Moore', 'Daniel', 'daniel.moore@gmail.com', '0635752208', '2024-01-18', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 2
),
(
'Taylor', 'Isabella', 'isabella.taylor@gmail.com', '0637752209', '2024-01-13', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 3
),
(
'Anderson', 'Mason', 'mason.anderson@gmail.com', '0635752210', '2024-01-12', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 4
),
(
'White', 'Emily', 'emily.white@gmail.com', '0635752211', '2021-02-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 5
),
(
'Martin', 'James', 'james.martin@gmail.com', '0635252212', '2021-02-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 3
),
(
'Johnson', 'Ella', 'ella.johnson@gmail.com', '0635752213', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 1
),
(
'Brown', 'Benjamin', 'benjamin.brown@gmail.com', '0638652214', '2021-04-24', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 2
),
(
'Miller', 'Ava', 'ava.miller@gmail.com', '0635752215', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 3
),
(
'Davis', 'William', 'william.davis@gmail.com', '0632332216', '2021-04-14', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 4
),
(
'Wilson', 'Charlotte', 'charlotte.wilson@gmail.com', '0635752217', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 5
),
(
'Moore', 'Jack', 'jack.moore@gmail.com', '0635752218', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 3
),
(
'Taylor', 'Harper', 'harper.taylor@gmail.com', '0635752219', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 1, 1
),
(
'Anderson', 'Evelyn', 'evelyn.anderson@gmail.com', '0635752220', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 2
),
(
'White', 'Andrew', 'andrew.white@gmail.com', '0635752221', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 1, 3
),
(
'Martin', 'Grace', 'grace.martin@gmail.com', '0635841222', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 4
),
(
'Johnson', 'Joseph', 'joseph.johnson@gmail.com', '063575186', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 1, 5
),
(
'Brown', 'Scarlett', 'scarlett.brown@gmail.com', '0635752224', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 3
),
(
'Miller', 'Logan', 'logan.miller@gmail.com', '0635752225', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 1, 1
),
(
'Davis', 'Elizabeth', 'elizabeth.davis@gmail.com', '0635752226', '2023-09-14', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 2
),
(
'Wilson', 'Aiden', 'aiden.wilson@gmail.com', '0635752227', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 1, 3
),
(
'Moore', 'Grace', 'grace.moore@gmail.com', '0635752228', '2024-04-12', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 4
),
(
'Taylor', 'Elijah', 'elijah.taylor@gmail.com', '0635752229', '2023-12-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 1, 5
),
(
'Anderson', 'Aria', 'aria.anderson@gmail.com', '0635752230', '2024-01-01', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, NULL, 2, 3
);

UPDATE UTILISATEUR SET apikey = SHA2(CONCAT(id_user, nom, prenom, mdp, email), 256) WHERE id_user IS NOT NULL;

INSERT INTO TICKETS (description, date_creation, date_cloture, id_user_admin, id_etape, id_categorie, id_user_owner) VALUES
('Besoin de vêtements d''hiver pour les sans-abri', '2024-04-01 10:00:00', '2024-04-15 18:00:00', 1, 1, 2, 3),
('Manque de bénévoles pour la distribution de repas', '2024-04-05 14:30:00', '2024-04-20 20:00:00', 2, 2, 1, 7),
('Problème avec l''équipement informatique de l''atelier', '2024-04-10 09:00:00', '2024-04-25 17:00:00', 2, 3, 3, 13),
('Difficulté à trouver des tuteurs pour le soutien scolaire', '2024-04-15 11:00:00', '2024-04-30 19:00:00', 2, 4, 4, 19),
('Besoin de bénévoles pour animer les visites aux personnes âgées', '2024-04-20 15:00:00', '2024-05-05 21:00:00', 1, 4, 5, 25);

