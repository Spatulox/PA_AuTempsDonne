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
   id_trajets INT ,
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
   date_activite DATETIME,
   id_trajets INT,
   id_index_planning INT NOT NULL,
   id_activite INT NOT NULL,
   PRIMARY KEY(id_planning),
   FOREIGN KEY(id_trajets) REFERENCES TRAJETS(id_trajets),
   FOREIGN KEY(id_index_planning) REFERENCES INDEXPLANNING(id_index_planning),
   FOREIGN KEY(id_activite) REFERENCES ACTIVITES(id_activite)
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
   activite VARCHAR(50) NOT NULL,
   etat INT NOT NULL,
   date_act DATETIME,
   id_activite INT NOT NULL,
   id_planning INT,
   id_user INT NOT NULL,
   PRIMARY KEY(id_demande),
   FOREIGN KEY(id_activite) REFERENCES ACTIVITES(id_activite),
   FOREIGN KEY(id_planning) REFERENCES PLANNINGS(id_planning),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user)
);


CREATE TABLE ETAGERES(
   id_etagere INT AUTO_INCREMENT,
   nombre_de_place INT NOT NULL,
   code VARCHAR(255) NOT NULL,
   id_entrepot INT NOT NULL,
   PRIMARY KEY(id_etagere),
   UNIQUE(code),
   FOREIGN KEY(id_entrepot) REFERENCES ENTREPOTS(id_entrepot)
);



CREATE TABLE DON(
   id_don INT AUTO_INCREMENT,
   prix INT NOT NULL,
   date_don DATE NOT NULL,
   id_user INT,
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

CREATE TABLE FORUM(
   id_forum INT AUTO_INCREMENT,
   forum_text VARCHAR(255),
   id_user INT NOT NULL,
   PRIMARY KEY(id_forum),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user)
);

CREATE TABLE FICHIER(
   id_fichier INT AUTO_INCREMENT,
   nom_fichier VARCHAR(50) NOT NULL,
   chemin_fichier VARCHAR(255) NOT NULL,
   id_user INT NOT NULL,
   PRIMARY KEY(id_fichier),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user)
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
('SAME ADDRESS AS FIRST'),
('33 Rue Marcelin Berthelot, 02001 Laon'),
('1 Rue Devisme, 02000 Laon'),
('10 Rue des Vignes, 02000 Laon'),
('5 Place Aubry, 02000 Laon'),
('8 Rue du Bourg, 02000 Laon'),
('14 Rue Maubant, 02000 Laon'),
('3 Rue des Tournelles, 02000 Laon'),
('14 Rue du Bourg, 02000 Laon'),
('22 Avenue Carnot, 02000 Laon'),
('8 Rue Maubant, 02000 Laon'),
('31 Rue Geruzez, 02000 Laon'),
('6 Rue edouard Branly, 02000 Laon'),
('18 Rue du Beau-Regard, 02000 Laon'),
('25 Rue de Semilly, 02000 Laon'),
('4 Rue des Anglais, 02000 Laon'),
('16 Rue du Bourg, 02000 Laon'),
('9 Rue des Chesneaux, 02000 Laon'),
('12 Rue de la Tannerie, 02000 Laon'),
('3 Rue des Accacias, 02000 Laon'),
('15 Rue des Vignes, 02000 Laon'),
('33 Rue Marcelin Berthelot, 02000 Laon'),
('10 Rue des Vignes, 02000 Laon'),
('7 Rue du Cloitre, 02000 Laon'),
('29 Rue edouard Gand, 02000 Laon'),
('23 Rue Marcelin Berthelot, 02000 Laon'),
('35 Rue du Bois de Buires, 02000 Laon'),
('20 Rue du Cloitre, 02000 Laon'),
('28 Rue edouard Gand, 02000 Laon'),
('24 Avenue Carnot, 02000 Laon'),
('5 Rue des Potiers, 02000 Laon'),
('11 Rue des Anglais, 02000 Laon'),
('17 Rue du Bourg, 02000 Laon'),
('2 Rue Geruzez, 02000 Laon'),
('26 Rue de Semilly, 02000 Laon'),
('8 Rue des Chesneaux, 02000 Laon'),
('13 Rue Maubant, 02000 Laon'),
('30 Rue du Beau-Regard, 02000 Laon');


INSERT INTO TYPE (type, unit_mesure) VALUES
('Alimentaire', 'kg'),
('Vestimentaire', 'unite'),
('Scolaire', 'unite'),
('Medical', 'unite'),
('Loisirs', 'unite');

INSERT INTO PRODUIT (nom_produit, id_type) VALUES
('Riz', 1),
('Pates', 1),
('Conserves de legumes', 1),
('Manteau d''hiver', 2),
('Chaussures', 2),
('Cahiers', 3),
('Stylos', 3),
('Medicaments', 4),
('Jouets', 5);

INSERT INTO INDEXPLANNING (index_nom_planning) VALUES
('termine'), 
('organiser'),
('en attente');


INSERT INTO ACTIVITES (nom_activite) VALUES
('Collecte de vetements'),
('Distribution de repas chauds'),
('Atelier d''informatique'),
('Soutien scolaire'),
('Visite de personnes agees'),
('Collecte'),
('Maraude');

INSERT INTO ENTREPOTS (nom_entrepot, parking, id_adresse) VALUES
('Entrepot Laon', 6, 2),
('Entrepot Saint_Martin', 4, 3);


INSERT INTO ETAGERES (nombre_de_place ,code, id_entrepot) VALUES
(500,"255ba2e00eee912a0e83d46f5b6c90f0816ec9b3ab1c51850ed43b4ad50f8f63",1),
(1000,"2c2c414882fd59e38e878abc6a96d2a86013b85951d2ec6eed9e8c22b5e6027d",1),
(1000,"958666110ec960ed062a5371bf71ae9bc95d8008d1e6ec340ee0709cf316a94e",1),
(250,"19dab5212dba6c1a2103055b3e4e53c9a31ede04dcc786a4560bcc855824a01a",1),
(250,"017aa1c54869634a7cfd97bd809d7f98698eac20681e90dd24cd7c326024ac20",1),
(250,"aa2e10b383dbbc3f04988611a9a2335f323c7ecc28022a45f06273076d0faa4f",1),
(250,"55cd77a79bd0b0f2f7ebe59c02d7debe9b3dda04f173011b84e3cfe4b4cc974f",1),
(500,"62c95e03036f7855b4cb919cb36a8a09e3a46e7b83af5bda9569466b96fd09d6",2),
(1000,"a7d5f3e90df49695dd56c4452d17989b3b7a7c8117012367041c52a6c4e31a67",2),
(1000,"a9f65c3f1b74cb6115184185bdc5866725eda42518aef6454e43cda4c3e0f0fd",2),
(250,"46a2f57345c7a5d3f470a1367ffa10b76fb29c77b66a37a1387900064802a762",2),
(250,"f5692dd51ffa5e11a9312ffad663367f3ecda15286e630cd81835977636ddee9",2),
(250,"2389bdffe5dfdeac05c5f44265408b1f838753ff75b85e173e7dc3652408f04d",2),
(250,"390e1680f9b5d41547db78396651189add88e346851b61d1b3956498bd9a4fca",2);



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
('Termine'),
('Annule');

INSERT INTO ROLES (role) VALUES
('Dirigeant'),
('Administration'), 
('Benevole'),
('Beneficiaire'),
('Prestataires');

INSERT INTO CATEGORIES (categorie) VALUES
('urgent'),
('connexion'), 
('perte'),
('autre'),
('je sais pas');

INSERT INTO TABINDEX (index_nom) VALUES
('inactif / dereference'),
('actif'),
('attente de validation');



INSERT INTO UTILISATEUR (nom, prenom, email, telephone, date_inscription, apikey, mdp, id_adresse, id_entrepot, id_index, id_role) VALUES
(
'Doe', 'John', 'john.doe@gmail.com', '0635742201', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 3, NULL, 2, 1
),
(
'Smith', 'Alice', 'alice.smith@gmail.com', '0635752202', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 4, NULL, 2, 2
),
(
'Johnson', 'Michael', 'michael.johnson@gmail.com', '0635752203', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 5, 1, 2, 3
),
(
'Brown', 'Emma', 'emma.brown@gmail.com', '0635752204', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 6, NULL, 2, 4
),
(
'Miller', 'Sophia', 'sophia.miller@gmail.com', '0635752205', '2022-04-01', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 7, NULL, 2, 5
),
(
'Davis', 'William', 'william.davis@gmail.com', '0635752206', '2021-01-02', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 8, 1, 2, 3
),
(
'Wilson', 'Olivia', 'olivia.wilson@gmail.com', '0635752207', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 9, NULL, 2, 1
),
(
'Moore', 'Daniel', 'daniel.moore@gmail.com', '0635752208', '2024-01-18', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 10, NULL, 2, 2
),
(
'Taylor', 'Isabella', 'isabella.taylor@gmail.com', '0637752209', '2024-01-13', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 11, 1, 2, 3
),
(
'Anderson', 'Mason', 'mason.anderson@gmail.com', '0635752210', '2024-01-12', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 12, NULL, 2, 4
),
(
'White', 'Emily', 'emily.white@gmail.com', '0635752211', '2021-02-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 13, NULL, 2, 5
),
(
'Martin', 'James', 'james.martin@gmail.com', '0635252212', '2021-02-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 13, 2, 2, 3
),
(
'Johnson', 'Ella', 'ella.johnson@gmail.com', '0635752213', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 14, NULL, 2, 1
),
(
'Brown', 'Benjamin', 'benjamin.brown@gmail.com', '0638652214', '2021-04-24', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 15, NULL, 2, 2
),
(
'Miller', 'Ava', 'ava.miller@gmail.com', '0635752215', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 16, 2, 2, 3
),
(
'Davis', 'William', 'william.davis@gmail.com', '0632332216', '2021-04-14', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 17, NULL, 2, 4
),
(
'Wilson', 'Charlotte', 'charlotte.wilson@gmail.com', '0635752217', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 18, NULL, 2, 5
),
(
'Moore', 'Jack', 'jack.moore@gmail.com', '0635752218', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 19, 2, 2, 3
),
(
'Taylor', 'Harper', 'harper.taylor@gmail.com', '0635752219', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 20, NULL, 2, 1
),
(
'Anderson', 'Evelyn', 'evelyn.anderson@gmail.com', '0635752220', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 21, NULL, 2, 2
),
(
'White', 'Andrew', 'andrew.white@gmail.com', '0635752221', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 22, 1, 2, 3
),
(
'Martin', 'Grace', 'grace.martin@gmail.com', '0635841222', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 23, NULL, 2, 4
),
(
'Johnson', 'Joseph', 'joseph.johnson@gmail.com', '063575186', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 24, NULL, 2, 5
),
(
'Brown', 'Scarlett', 'scarlett.brown@gmail.com', '0635752224', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 25, 2, 2, 3
),
(
'Miller', 'Logan', 'logan.miller@gmail.com', '0635752225', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 26, NULL, 2, 3
),
(
'Davis', 'Elizabeth', 'elizabeth.davis@gmail.com', '0635752226', '2023-09-14', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 27, NULL, 2, 2
),
(
'Wilson', 'Aiden', 'aiden.wilson@gmail.com', '0635752227', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 28, 1, 3, 3
),
(
'Moore', 'Grace', 'grace.moore@gmail.com', '0635752228', '2024-04-12', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 29, NULL, 2, 4
),
(
'Taylor', 'Elijah', 'elijah.taylor@gmail.com', '0635752229', '2023-12-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 30, NULL, 1, 5
),
(
'Anderson', 'Aria', 'aria.anderson@gmail.com', '0635752230', '2024-01-01', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 31, 2, 1, 3
);

UPDATE UTILISATEUR SET apikey = SHA2(CONCAT(id_user, nom, prenom, mdp, email), 256) WHERE id_user IS NOT NULL;


INSERT INTO DISPONIBILITE (id_user, id_dispo)
VALUES
  (3, 1), (3, 5), (3, 6), (3, 7),
  (6, 1), (6, 2), (6, 6), (6, 7),
  (15, 1), (15, 2), (15, 3),
  (18, 1), (18, 2), (18, 3), (18, 4), (18, 5),
  (9, 1), (9, 2), (9, 6), (9, 7),
  (24, 1), (24, 2), (24, 3), (24, 7),
  (12, 4), (12, 5), (12, 6), (12, 7);

  INSERT INTO VEHICULES (capacite, nom_du_vehicules, nombre_de_place, id_entrepot)
VALUES
  (3, 'lexus lfa', 2, 1),
  (100, 'Mercedes Sprinter', 2, 1),
  (250, 'Peugeot Boxer', 2, 2),
  (400, 'Iveco Daily', 2, 2),
  (400, 'Nissan NV400', 2, 1);

INSERT INTO PLANNINGS (description, date_activite, id_index_planning, id_activite)
VALUES (
  'patoche help',
  '2024-05-20 08:00:00',
  3,
  4
);

INSERT INTO TRAJETS (id_trajets)
VALUES 
(1),
(2);

INSERT INTO UTILISER (id_trajets, id_adresse)
VALUES (2, 17);

  INSERT INTO DEMANDE (desc_demande, activite, etat, date_act, id_activite, id_planning, id_user)
VALUES
  ('collecte intermarcher', 'groupe', 1, NULL, 6, NULL, 17),
  ('collecte divers', 'groupe', 1, NULL, 6, NULL, 17),
  ('collecte divers', 'groupe', 1, NULL, 6, NULL, 23),
  ('ecole a besoin daide', 'seul', 1, '2024-05-15 09:00:00', 3, NULL, 4),
  ('Mamie gertrude doit aller faire cest course', 'seul', 1, '2024-05-18 14:00:00', 5, NULL, 10),
  ('patoche help', 'seul', 0, '2024-05-20 08:00:00', 4, 1, 16),
  ('patoche cour du soir help', 'seul', 1, '2024-05-25 18:00:00', 4, NULL, 16);

INSERT INTO COLLECTE (quantite, id_produit)
VALUES
  ('60', 1),
  ('50', 5),
  ('200', 4),
  ('98', 5),
  ('47', 2),
  ('12', 5),
  ('150', 7),
  ('50', 9);

INSERT INTO RECU (id_collecte, id_demande, recu)
VALUES
  (1, 1, 1),
  (2, 1, 1),
  (3, 2, 1),
  (4, 2, 1),
  (5, 2, 1),
  (6, 3, 1),
  (7, 3, 1),
  (8, 3, 1);



  INSERT INTO STOCKS (quantite_produit, date_entree, date_sortie, date_peremption, desc_produit, id_produit, id_etagere)
VALUES
  (75, '2024-05-06', NULL, '2025-04-20', 'clair', 1, 8),
  (12, '2024-05-06', NULL, '2025-04-20', 'pansement', 8, 8),
  (30, '2024-05-06', NULL, NULL, 'pansement', 9, 8),
  (30, '2024-05-06', NULL, NULL, 'pansement', 9, 1),
  (400, '2024-05-06', NULL, '2024-05-06', 'brun', 1, 1),
  (70, '2024-05-06', NULL, '2024-05-06', '', 2, 1),
  (130, '2024-05-06', NULL, '2024-05-06', '', 2, 2);