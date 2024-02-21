CREATE TABLE UTILISATEUR(
   id_user INT AUTO_INCREMENT,
   nom VARCHAR(50) NOT NULL,
   prenom VARCHAR(50) NOT NULL,
   email VARCHAR(80) UNIQUE NOT NULL,
   telephone VARCHAR(20),
   index_user INT NOT NULL,
   date_inscription DATE NOT NULL,
   type INT,
   role INT,
   apikey VARCHAR(255),
   mdp VARCHAR(255) NOT NULL,
   PRIMARY KEY(id_user)
);

CREATE TABLE ACTIVITES(
   id_activite INT AUTO_INCREMENT,
   nom_activite VARCHAR(100),
   date_activite DATE,
   type_activite VARCHAR(50),
   index_activite INT NOT NULL,
   PRIMARY KEY(id_activite)
);

CREATE TABLE ENTREPOTS(
   id_entrepot INT AUTO_INCREMENT,
   nom_entrepot VARCHAR(100),
   localisation VARCHAR(255),
   PRIMARY KEY(id_entrepot)
);

CREATE TABLE FORMATIONS(
   id_formation INT AUTO_INCREMENT,
   nom_formation VARCHAR(50),
   PRIMARY KEY(id_formation)
);

CREATE TABLE COLLECTE(
   id_collecte INT AUTO_INCREMENT,
   localisation_passage VARCHAR(255),
   date_collecte DATE,
   groupe_collecte INT NOT NULL,
   PRIMARY KEY(id_collecte)
);

CREATE TABLE STOCKS(
   id_stock INT AUTO_INCREMENT,
   nom_produit VARCHAR(100),
   quantite INT,
   date_entree DATE,
   date_sortie DATE,
   id_entrepot INT NOT NULL,
   PRIMARY KEY(id_stock),
   FOREIGN KEY(id_entrepot) REFERENCES ENTREPOTS(id_entrepot)
);

CREATE TABLE SUIVI(
   id_user INT,
   id_formation INT,
   PRIMARY KEY(id_user, id_formation),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user),
   FOREIGN KEY(id_formation) REFERENCES FORMATIONS(id_formation)
);

CREATE TABLE PLANNINGS(
   id_user INT,
   id_activite INT,
   PRIMARY KEY(id_user, id_activite),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user),
   FOREIGN KEY(id_activite) REFERENCES ACTIVITES(id_activite)
);

CREATE TABLE AIDE(
   id_user INT,
   id_activite INT,
   PRIMARY KEY(id_user, id_activite),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user),
   FOREIGN KEY(id_activite) REFERENCES ACTIVITES(id_activite)
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







INSERT INTO UTILISATEUR VALUES
(1, 'Dupont', 'Jean', 'jean.dupont@gmail.com', '0635752201', 1, '2024-02-14', NULL, 1, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(2, 'Smith', 'Emma', 'emma.smith@gmail.com', '0635752201', 1, '2024-02-15', NULL, 2, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(3, 'Johnson', 'David', 'david.johnson@gmail.com', '0635752201', 1, '2023-02-16', NULL, 3, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(4, 'Brown', 'Sophie', 'sophie.brown@gmail.com', '0635752201', 1, '2023-02-17', NULL, 1, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(5, 'Miller', 'Michael', 'michael.miller@gmail.com', '0635752201', 5, '2021-02-18', NULL, 2, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(6, 'Davis', 'Olivia', 'olivia.davis@gmail.com', '0635752201', 1, '2021-06-19', NULL, 3, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(7, 'Wilson', 'Daniel', 'daniel.wilson@gmail.com', '0635752201', 1, '2022-12-20', NULL, 1, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(8, 'Moore', 'Emily', 'emily.moore@gmail.com', '0635752201', 1, '2023-02-21', NULL, 2, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(9, 'Taylor', 'Matthew', 'matthew.taylor@gmail.com', '0635752201', 1, '2024-01-22', NULL, 3, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(10, 'Anderson', 'Sophia', 'sophia.anderson@gmail.com', '0635752201', 1, '2024-02-23', NULL, 1, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(11, 'White', 'Jackson', 'jackson.white@gmail.com', '0635752201', 1, '2021-08-24', NULL, 2, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(12, 'Martin', 'Sophie', "no_emaila", NULL, 1, '2024-02-14', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(13, 'Johnson', 'David', "no_emailz", NULL, 1, '2024-02-15', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(14, 'Brown', 'Sophie', "no_emaile", NULL, 1, '2024-02-16', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(15, 'Miller', 'Michael', "no_emasil", NULL, 1, '2024-02-17', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(16, 'Davis', 'Olivia', "no_emadil", NULL, 1, '2024-02-18', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(17, 'Wilson', 'Daniel', "no_emcail", NULL, 1, '2024-02-19', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(18, 'Moore', 'Emily', "no_emaivl", NULL, 1, '2024-02-20', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(19, 'Taylor', 'Matthew', "no_emfail", NULL, 1, '2024-02-21', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(20, 'Anderson', 'Sophia', "no_xemail", NULL, 1, '2024-02-22', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(21, 'White', 'Jackson', "no_eamail", NULL, 1, '2024-02-23', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(22, 'Smith', 'Emma', "no_e<mail", NULL, 1, '2024-02-24', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(23, 'Jones', 'Christopher', "ndo_email", NULL, 1, '2024-02-25', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(24, 'Garcia', 'Ava', "no_ebmail", NULL, 1, '2024-02-26', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(25, 'Martinez', 'Daniel', "nco_email", NULL, 1, '2024-02-27', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(26, 'Clark', 'Olivia', "xno_email", NULL, 1, '2024-02-28', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(27, 'Lewis', 'Liam', "no_emavbnil", NULL, 1, '2024-02-29', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(28, 'Walker', 'Sophie', "no_vvemail", NULL, 1, '2024-03-01', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(29, 'Hill', 'Andrew', "no_evbmail", NULL, 1, '2024-03-02', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(30, 'Carter', 'Abigail', "n,o_email", NULL, 1, '2024-03-03', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(31, 'Turner', 'Ella', "no_emkkil", NULL, 1, '2024-03-04', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4'),
(32, 'Adams', 'Noah', "no_emhfail", NULL, 1, '2024-03-05', NULL, 4, NULL, '03AC674216F3E15C761EE1A5E255F067953623C8B388B4459E13F978D7C846F4');



INSERT INTO ACTIVITES VALUES
(1, 'Collecte de vêtements', '2024-02-15', 'Collecte',0),
(2, 'Distribution de repas', '2024-02-20', 'Distribution',0),
(3, 'Atelier de recyclage', '2024-02-25', 'Atelier',1),
(4, 'Collecte alimentaire', '2024-01-01', 'Collecte',0),
(5, 'Visite aux personnes âgées', '2024-01-05', 'Visite',0),
(6, 'Cours dinformatique', '2024-03-10', 'Cours',1),
(7, 'Journée de sensibilisation', '2024-03-15', 'Sensibilisation',1),
(8, 'Collecte de jouets', '2024-03-20', 'Collecte',1),
(9, 'Distribution de couvertures', '2024-03-25', 'Distribution',1),
(10, 'Atelier de jardinage', '2024-03-30', 'Atelier',1),
(11, 'Collecte de fournitures scolaires', '2024-04-05', 'Collecte',1);

INSERT INTO FORMATIONS VALUES
(1, 'Formation Secourisme'),
(2, 'Formation en Secourisme Avancé'),
(3, 'Formation en Premiers Soins'),
(4, 'Formation en conduite '),
(5, 'Formation en Communication Interpersonnelle'),
(6, 'Formation en activité personne âgé ');

INSERT INTO ENTREPOTS VALUES    
(1, 'Entrepôt de Saint Quentin', '2 Rue André Missenard, 02100');
INSERT INTO ENTREPOTS VALUES
(2, 'Entrepôt de Laon', '34 Rue Roger Salengro, 02000 ');


INSERT INTO COLLECTE VALUES
(1, ' 10 Av. Carnot, 02000 ', '2024-02-16',1),
(2, '81 Bd Pierre Brossolette, 02000 ', '2024-02-16', 1),
(3, '13 Bd de Lyon, 02000 ', '2024-02-16', 1),
(4, '50 Bd de Lyon, 02000 ', '2024-02-16', 1),
(5, '25 Av. Gambetta, 02000 ', '2024-02-16', 1),
(6, '10 Rue de lÉperon, 02000 ', '2024-02-16', 1),
(7, '41 Rue Jean Baptiste Lebas, 02000 ', '2024-02-16', 1),
(8, '43 Rue dEnfer, 02000 ', '2024-02-16', 1),
(9, '8 Rue du Missouri, 02000 ', '2024-02-16', 1),
(10, '37 Rue du Québec, 02000 ', '2024-02-16', 1),
(11, '30 Rue Mongin, 02000 ', '2024-04-05', 2),
(12, '10 Rue de lÉperon, 02000 ', '2024-04-05', 2),
(13, '10 Av. Carnot, 02000 ', '2024-04-05', 2),
(14, '69 Rue Nestor Gréhant, 02000 ', '2024-04-05', 2),
(15, '35 Rte de la Fère, 02000 ', '2024-04-05', 2),
(16, '90 Rue de lAbreuvoir, 02000 ', '2024-04-05', 2),
(17, ' 8 Rue du Missouri, 02000 ', '2024-04-05', 2),
(18, '13 Bd de Lyon, 02000 ', '2024-04-05', 2),
(19, '37 Rue du Québec, 02000 ', '2024-04-05', 2),
(20, '15 Rue de Sault Sainte-Marie, 02000', '2024-04-05', 2),
(21, '14 Rue Jacques Lescot, 02100 ', '2024-03-01', 3),
(22, '3 Rue de la 3e Dim, 02100 ', '2024-03-01', 3),
(23, '27 Rue de la 3e Dim, 02100 ', '2024-03-01', 3),
(24, '9 Rue du Labeur, 02100 ', '2024-03-01', 3),
(25, '13 Rue de Tunis, 02100', '2024-03-01', 3),
(26, '25 All. des Tisserands, 02100', '2024-03-01', 3),
(27, '207 Rue dEpargnemailles, 02100' , '2024-03-01', 3),
(28, '9 Rue Mulot, 02100 ', '2024-03-01', 3),
(29, '4 Rue Paringault, 02100 ', '2024-03-01', 3),
(30, '14 Rue dAlsace, 02100 ', '2024-03-01', 3);

INSERT INTO STOCKS VALUES 
(1, 'vetement', 100, '2024-02-14', NULL, 1),
(2, 'pate', 150, '2024-02-18', NULL, 2),
(3, 'poulet', 20, '2024-02-22', '2024-03-02', 1),
(4, 'paracétamol ', 80, '2024-03-05', NULL, 1),
(5, 'pomme', 4, '2024-03-10', NULL, 2);





UPDATE UTILISATEUR SET apikey = SHA2(CONCAT(id_user, nom, prenom, mdp, email), 256) WHERE id_user IS NOT NULL;