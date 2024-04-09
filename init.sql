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

CREATE TABLE PRODUIT(
   id_produit INT,
   nom_produit VARCHAR(100),
   PRIMARY KEY(id_produit)
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
   quantite INT,
   date_entree DATE,
   date_sortie DATE,
   date_peremption_ DATE,
   lot INT,
   id_produit INT NOT NULL,
   id_entrepot INT NOT NULL,
   PRIMARY KEY(id_stock),
   FOREIGN KEY(id_produit) REFERENCES PRODUIT(id_produit),
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
   groupe_collecte INT NOT NULL,
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

INSERT INTO COLLECTE (id_collecte, localisation_passage, date_collecte)
VALUES
(1, 'Localisation A', '2024-04-08'),
(2, 'Localisation B', '2024-04-08'),
(3, 'Localisation C', '2024-04-08'),
(4, 'Localisation D', '2024-04-08'),
(5, 'Localisation E', '2024-04-08'),
(6, 'Localisation F', '2024-04-08'),
(7, 'Localisation G', '2024-04-08'),
(8, 'Localisation H', '2024-04-08'),
(9, 'Localisation I', '2024-04-08'),
(10, 'Localisation J', '2024-04-10'),
(11, 'Localisation K', '2024-04-10'),
(12, 'Localisation L', '2024-04-10'),
(13, 'Localisation M', '2024-04-10'),
(14, 'Localisation N', '2024-04-10'),
(15, 'Localisation O', '2024-04-10'),
(16, 'Localisation P', '2024-04-10'),
(17, 'Localisation Q', '2024-04-10'),
(18, 'Localisation R', '2024-04-15'),
(19, 'Localisation S', '2024-04-15'),
(20, 'Localisation A', '2024-04-15'),
(21, 'Localisation U', '2024-04-15'),
(22, 'Localisation V', '2024-04-15'),
(23, 'Localisation W', '2024-04-15'),
(24, 'Localisation X', '2024-04-15'),
(25, 'Localisation Y', '2024-04-15'),
(26, 'Localisation Z', '2024-05-05'),
(27, 'Localisation AA', '2024-05-05'),
(28, 'Localisation B', '2024-05-05'),
(29, 'Localisation A', '2024-05-05'),
(30, 'Localisation AD', '2024-05-05'),
(31, 'Localisation AE', '2024-05-05'),
(32, 'Localisation AF', '2024-05-05'),
(33, 'Localisation AG', '2024-05-05'),
(34, 'Localisation AH', '2024-05-05'),
(35, 'Localisation I', '2024-05-05'),
(36, 'Localisation J', '2024-05-10'),
(37, 'Localisation K', '2024-05-10'),
(38, 'Localisation L', '2024-05-10'),
(39, 'Localisation M', '2024-05-10'),
(40, 'Localisation A', '2024-05-10');

INSERT INTO PRODUIT (id_produit, nom_produit)
VALUES
(1, 'Produit A'),
(2, 'Produit B'),
(3, 'Produit C'),
(4, 'Produit D'),
(5, 'Produit E'),
(6, 'Produit F'),
(7, 'Produit G'),
(8, 'Produit H'),
(9, 'Produit I'),
(10, 'Produit J'),
(11, 'Produit K'),
(12, 'Produit L'),
(13, 'Produit M'),
(14, 'Produit N'),
(15, 'Produit O'),
(16, 'Produit P');

INSERT INTO STOCKS (id_stock, quantite, date_entree, date_sortie, date_peremption_, id_entrepot, id_produit)
VALUES
(1, 100, '2024-04-08', NULL, '2024-05-01', 1,1),
(2, 150, '2024-04-08', NULL, '2024-05-02', 2,2),
(3, 200, '2024-04-08', NULL, '2024-05-03', 1,3),
(4, 120, '2024-04-08', NULL, '2024-05-04', 2,4),
(5, 80, '2024-04-08', NULL, '2024-05-05', 1,5),
(6, 90, '2024-04-08', NULL, '2024-05-06', 1,6),
(7, 110, '2024-04-08', NULL, '2024-05-07', 2,7),
(8, 130, '2024-04-08', NULL, '2024-05-08', 2,8),
(9, 70, '2024-04-08', NULL, '2024-05-09', 1,9),
(10, 180, NULL, '2024-04-21', '2024-05-10', 2,2),
(11, 150, '2024-04-08', NULL, '2024-05-11', 2,1),
(12, 100, '2024-04-08', NULL, '2024-05-12', 1,1),
(13, 190, NULL, '2024-05-01', '2024-05-13', 1,2),
(14, 220, NULL, '2024-05-11', '2024-05-14', 2,3),
(15, 80, '2024-04-10', NULL, '2024-05-15', 1,1),
(16, 130, '2024-04-10', '2024-05-11', '2024-05-16', 1,2),
(17, 170, '2024-04-10', NULL, '2024-05-17', 2,7),
(18, 200, '2024-04-10', '2024-04-28', '2024-05-18', 1,8),
(19, 90, '2024-04-10', NULL, '2024-05-19', 1,9),
(20, 120, '2024-04-10', NULL, '2024-05-20', 2,10),
(21, 80, '2024-04-10', NULL, '2024-05-21', 1,11),
(22, 160, '2024-04-15', NULL, '2024-05-22', 1,12),
(23, 140, '2024-04-15', NULL, '2024-05-23', 2,13),
(24, 110, NULL, '2024-05-10', '2024-05-24', 1,14),
(25, 200, '2024-04-15', NULL, '2024-05-25', 2,12),
(26, 170, '2024-04-15', NULL, '2024-05-26', 2,11),
(27, 90, '2024-04-15', NULL, '2024-05-27', 1,2),
(28, 120, '2024-04-15', NULL, '2024-05-28', 2,2),
(29, 140, '2024-04-15', NULL, '2024-05-29', 2,3),
(30, 100, '2024-04-15', NULL, '2024-05-30', 1,3),
(31, 180, '2024-05-05', NULL, '2024-05-31', 2,5),
(32, 150, '2024-05-05', NULL, '2024-06-01', 2,9),
(33, 110, '2024-05-05', NULL, '2024-06-02', 1,7),
(34, 130, '2024-05-05', NULL, '2024-06-03', 2,4),
(35, 160, '2024-05-05', NULL, '2024-06-04', 2,6),
(36, 70, '2024-05-05', NULL, '2024-06-05', 1,11),
(37, 120, '2024-05-05', NULL, '2024-06-06', 1,1),
(38, 180, '2024-05-05', NULL, '2024-06-07', 2,2),
(39, 200, '2024-05-10', NULL, '2024-06-08', 1,2),
(40, 90, '2024-05-10', NULL, '2024-06-09', 1,3);

INSERT INTO ORGANISE (id_user, id_collecte, groupe_collecte)
VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 7, 1),
(8, 8, 1),
(9, 9, 1),
(10, 10, 2),
(11, 11, 2),
(12, 12, 1),
(13, 13, 2),
(14, 14, 2),
(15, 15, 2),
(16, 16, 2);

INSERT INTO RECUPERE (id_stock, id_collecte)
VALUES
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
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15),
(16, 16),
(17, 17),
(18, 18),
(19, 19),
(20, 20),
(21, 21),
(22, 22),
(23, 23),
(24, 24),
(25, 25),
(26, 26),
(27, 27),
(28, 28),
(29, 29),
(30, 30),
(31, 31),
(32, 32),
(33, 33),
(34, 34),
(35, 35),
(36, 36),
(37, 37),
(38, 38),
(39, 39),
(40, 40);

UPDATE UTILISATEUR SET apikey = SHA2(CONCAT(id_user, nom, prenom, mdp, email), 256) WHERE id_user IS NOT NULL;
