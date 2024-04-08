CREATE TABLE ACTIVITES(
   id_activite INT,
   nom_activite VARCHAR(100),
   PRIMARY KEY(id_activite)
);

CREATE TABLE ENTREPOTS(
   id_entrepot INT,
   nom_entrepot VARCHAR(100),
   localisation VARCHAR(255),
   PRIMARY KEY(id_entrepot)
);

CREATE TABLE COLLECTE(
   id_collecte INT,
   localisation_passage VARCHAR(255),
   date_collecte DATE,
   groupe_collecte INT NOT NULL,
   PRIMARY KEY(id_collecte)
);

CREATE TABLE TABINDEX(
   id_index INT,
   index_nom VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_index)
);

CREATE TABLE ROLES(
   id_role INT,
   role VARCHAR(50),
   PRIMARY KEY(id_role)
);

CREATE TABLE CATEGORIES(
   id_categorie INT,
   categorie VARCHAR(50),
   PRIMARY KEY(id_categorie)
);

CREATE TABLE PLANNINGS(
   id_planning INT,
   description TEXT,
   lieux VARCHAR(50),
   date_activite DATE,
   id_index INT NOT NULL,
   id_activite INT NOT NULL,
   PRIMARY KEY(id_planning),
   FOREIGN KEY(id_index) REFERENCES TABINDEX(id_index),
   FOREIGN KEY(id_activite) REFERENCES ACTIVITES(id_activite)
);

CREATE TABLE UTILISATEUR(
   id_user INT,
   nom VARCHAR(50) NOT NULL,
   prenom VARCHAR(50) NOT NULL,
   email VARCHAR(80),
   telephone VARCHAR(20),
   date_inscription DATE NOT NULL,
   apikey VARCHAR(255),
   mdp VARCHAR(255) NOT NULL,
   id_entrepot INT NOT NULL,
   id_index INT NOT NULL,
   id_role INT NOT NULL,
   PRIMARY KEY(id_user),
   FOREIGN KEY(id_entrepot) REFERENCES ENTREPOTS(id_entrepot),
   FOREIGN KEY(id_index) REFERENCES TABINDEX(id_index),
   FOREIGN KEY(id_role) REFERENCES ROLES(id_role)
);

CREATE TABLE STOCKS(
   id_stock INT,
   nom_produit VARCHAR(100),
   quantite INT,
   date_entree DATE,
   date_sortie DATE,
   date_peremption_ DATE,
   id_entrepot INT NOT NULL,
   PRIMARY KEY(id_stock),
   FOREIGN KEY(id_entrepot) REFERENCES ENTREPOTS(id_entrepot)
);

CREATE TABLE TICKETS(
   id_ticket INT,
   description VARCHAR(50),
   categorie INT,
   id_user INT NOT NULL,
   PRIMARY KEY(id_ticket),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user)
);

CREATE TABLE PARTICIPE(
   id_user INT,
   id_planning INT,
   PRIMARY KEY(id_user, id_planning),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user),
   FOREIGN KEY(id_planning) REFERENCES PLANNINGS(id_planning)
);

CREATE TABLE ORGANISE(
   id_user INT,
   id_collecte INT,
   PRIMARY KEY(id_user, id_collecte),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user),
   FOREIGN KEY(id_collecte) REFERENCES COLLECTE(id_collecte)
);

CREATE TABLE RECUPERE(
   id_stock INT,
   id_collecte INT,
   PRIMARY KEY(id_stock, id_collecte),
   FOREIGN KEY(id_stock) REFERENCES STOCKS(id_stock),
   FOREIGN KEY(id_collecte) REFERENCES COLLECTE(id_collecte)
);

CREATE TABLE LIE(
   id_ticket INT,
   id_categorie INT,
   PRIMARY KEY(id_ticket, id_categorie),
   FOREIGN KEY(id_ticket) REFERENCES TICKETS(id_ticket),
   FOREIGN KEY(id_categorie) REFERENCES CATEGORIES(id_categorie)
);

INSERT INTO ENTREPOTS VALUES    
(1, 'Entrepôt de Saint Quentin', '2 Rue André Missenard, 02100'),
(2, 'Entrepôt de Laon', '34 Rue Roger Salengro, 02000 ');

INSERT INTO ACTIVITES (id_activite, nom_activite) VALUES
(1, 'Maraude'),
(2, 'Collecte'),
(3, 'aide scolaire'),
(4, 'aide personne age'),
(5, 'nettoiage rue');

INSERT INTO ROLES (id_role, role)
VALUES
(1, 'Dirigeant'),
(2, 'Administration'),
(3, 'Bénévole'),
(4, 'Bénéficiaire'),
(5, 'Prestataires');

INSERT INTO TABINDEX (id_index, index_nom)
VALUES 
    (0, 'inactif / déréférencé'),
    (1, 'actif'),
    (2, 'attente de validation'),
    (4, 'terminé'),
    (5, 'organiser'),
    (6, 'en attente');

INSERT INTO UTILISATEUR VALUES 
(1, 'Doe', 'John', 'john.doe@gmail.com', '0635742201', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 1),
(2, 'Smith', 'Alice', 'alice.smith@gmail.com', '0635752202', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 2),
(3, 'Johnson', 'Michael', 'michael.johnson@gmail.com', '0635752203', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 3),
(4, 'Brown', 'Emma', 'emma.brown@gmail.com', '0635752204', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 4),
(5, 'Miller', 'Sophia', 'sophia.miller@gmail.com', '0635752205', '2022-04-01', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 5),
(6, 'Davis', 'William', 'william.davis@gmail.com', '0635752206', '2021-01-02', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 3),
(7, 'Wilson', 'Olivia', 'olivia.wilson@gmail.com', '0635752207', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 1),
(8, 'Moore', 'Daniel', 'daniel.moore@gmail.com', '0635752208', '2024-01-18', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 2),
(9, 'Taylor', 'Isabella', 'isabella.taylor@gmail.com', '0637752209', '2024-01-13', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 3),
(10, 'Anderson', 'Mason', 'mason.anderson@gmail.com', '0635752210', '2024-01-12', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 4),
(11, 'White', 'Emily', 'emily.white@gmail.com', '0635752211', '2021-02-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 5),
(12, 'Martin', 'James', 'james.martin@gmail.com', '0635252212', '2021-02-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 3),
(13, 'Johnson', 'Ella', 'ella.johnson@gmail.com', '0635752213', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 1),
(14, 'Brown', 'Benjamin', 'benjamin.brown@gmail.com', '0638652214', '2021-04-24', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 2),
(15, 'Miller', 'Ava', 'ava.miller@gmail.com', '0635752215', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 3),
(16, 'Davis', 'William', 'william.davis@gmail.com', '0632332216', '2021-04-14', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 4),
(17, 'Wilson', 'Charlotte', 'charlotte.wilson@gmail.com', '0635752217', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 5),
(18, 'Moore', 'Jack', 'jack.moore@gmail.com', '0635752218', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 3),
(19, 'Taylor', 'Harper', 'harper.taylor@gmail.com', '0635752219', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 1),
(20, 'Anderson', 'Evelyn', 'evelyn.anderson@gmail.com', '0635752220', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 2),
(21, 'White', 'Andrew', 'andrew.white@gmail.com', '0635752221', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 3),
(22, 'Martin', 'Grace', 'grace.martin@gmail.com', '0635841222', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 4),
(23, 'Johnson', 'Joseph', 'joseph.johnson@gmail.com', '063575186', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 5),
(24, 'Brown', 'Scarlett', 'scarlett.brown@gmail.com', '0635752224', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 3),
(25, 'Miller', 'Logan', 'logan.miller@gmail.com', '0635752225', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 1),
(26, 'Davis', 'Elizabeth', 'elizabeth.davis@gmail.com', '0635752226', '2023-09-14', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 2),
(27, 'Wilson', 'Aiden', 'aiden.wilson@gmail.com', '0635752227', '2024-04-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 3),
(28, 'Moore', 'Grace', 'grace.moore@gmail.com', '0635752228', '2024-04-12', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 4),
(29, 'Taylor', 'Elijah', 'elijah.taylor@gmail.com', '0635752229', '2023-12-04', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 1, 1, 5),
(30, 'Anderson', 'Aria', 'aria.anderson@gmail.com', '0635752230', '2024-01-01', NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4', 2, 1, 3);

INSERT INTO PLANNINGS (id_planning, description, lieux, date_activite, id_index, id_activite)
VALUES 
    (1, 'Description de l''événement 1', 'Lieu de l''événement 1', '2024-04-10', 4, 1),
    (2, 'Description de l''événement 2', 'Lieu de l''événement 2', '2024-04-15', 4, 2),
    (3, 'Description de l''événement 3', 'Lieu de l''événement 3', '2024-04-20', 5, 3),
    (4, 'Description de l''événement 4', 'Lieu de l''événement 4', '2024-04-25', 5, 4),
    (5, 'Description de l''événement 5', 'Lieu de l''événement 5', '2024-05-01', 5, 5),
    (6, 'Description de l''événement 6', 'Lieu de l''événement 6', '2024-05-05', 5, 1),
    (7, 'Description de l''événement 7', 'Lieu de l''événement 7', '2024-05-10', 5, 1),
    (8, 'Description de l''événement 8', 'Lieu de l''événement 8', '2024-05-15', 5, 2),
    (9, 'Description de l''événement 9', 'Lieu de l''événement 9', '2024-05-20', 6, 3),
    (10, 'Description de l''événement 10', 'Lieu de l''événement 10', '2024-05-25', 6, 4);
    
INSERT INTO PARTICIPE (id_user, id_planning) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 1),
(12, 2),
(13, 1),
(14, 2),
(15, 1),
(16, 3),
(17, 3),
(18, 4),
(19, 1),
(20, 2),
(21, 3),
(22, 5),
(23, 4),
(24, 4),
(25, 5),
(26, 6),
(27, 2),
(28, 2),
(29, 1),
(30, 3);


UPDATE UTILISATEUR SET apikey = SHA2(CONCAT(id_user, nom, prenom, mdp, email), 256) WHERE id_user IS NOT NULL;