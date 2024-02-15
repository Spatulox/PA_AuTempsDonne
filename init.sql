CREATE TABLE BENEVOLES(
   id_benevoles INT,
   nom VARCHAR(50),
   prenom VARCHAR(50),
   email VARCHAR(80),
   telephone VARCHAR(20),
   index_benevole INT NOT NULL,
   date_inscription DATE,
   role INT,
   PRIMARY KEY(id_benevoles)
);

CREATE TABLE ACTIVITES(
   id_activite INT,
   nom_activite VARCHAR(100),
   date_activite DATE,
   type_activite VARCHAR(50),
   PRIMARY KEY(id_activite)
);

CREATE TABLE ENTREPOTS(
   id_entrepot INT,
   nom_entrepot VARCHAR(100),
   localisation VARCHAR(255),
   PRIMARY KEY(id_entrepot)
);

CREATE TABLE FORMATIONS(
   id_formation_ INT,
   nom_formation VARCHAR(50),
   PRIMARY KEY(id_formation_)
);

CREATE TABLE BENEFICIARES(
   id_beneficiaire INT,
   nom_beneficiaire VARCHAR(50),
   prenom_beneficiaire VARCHAR(50),
   type INT NOT NULL,
   index_beneficiare INT NOT NULL,
   PRIMARY KEY(id_beneficiaire)
);

CREATE TABLE COLLECTE(
   id_collecte INT,
   localisation_passage VARCHAR(255),
   date_collecte DATE,
   groupe_collecte INT NOT NULL,
   PRIMARY KEY(id_collecte)
);

CREATE TABLE STOCKS(
   id_stock INT,
   nom_produit VARCHAR(100),
   quantite INT,
   date_entree DATE,
   date_sortie DATE,
   id_entrepot INT NOT NULL,
   PRIMARY KEY(id_stock),
   FOREIGN KEY(id_entrepot) REFERENCES ENTREPOTS(id_entrepot)
);

CREATE TABLE suivie(
   id_benevoles INT,
   id_formation_ INT,
   PRIMARY KEY(id_benevoles, id_formation_),
   FOREIGN KEY(id_benevoles) REFERENCES BENEVOLES(id_benevoles),
   FOREIGN KEY(id_formation_) REFERENCES FORMATIONS(id_formation_)
);

CREATE TABLE PLANNINGS(
   id_benevoles INT,
   id_activite INT,
   PRIMARY KEY(id_benevoles, id_activite),
   FOREIGN KEY(id_benevoles) REFERENCES BENEVOLES(id_benevoles),
   FOREIGN KEY(id_activite) REFERENCES ACTIVITES(id_activite)
);

CREATE TABLE AIDE(
   id_activite INT,
   id_beneficiaire INT,
   PRIMARY KEY(id_activite, id_beneficiaire),
   FOREIGN KEY(id_activite) REFERENCES ACTIVITES(id_activite),
   FOREIGN KEY(id_beneficiaire) REFERENCES BENEFICIARES(id_beneficiaire)
);

CREATE TABLE ORGANISE(
   id_benevoles INT,
   id_collecte INT,
   PRIMARY KEY(id_benevoles, id_collecte),
   FOREIGN KEY(id_benevoles) REFERENCES BENEVOLES(id_benevoles),
   FOREIGN KEY(id_collecte) REFERENCES COLLECTE(id_collecte)
);

CREATE TABLE RECUPERE(
   id_stock INT,
   id_collecte INT,
   PRIMARY KEY(id_stock, id_collecte),
   FOREIGN KEY(id_stock) REFERENCES STOCKS(id_stock),
   FOREIGN KEY(id_collecte) REFERENCES COLLECTE(id_collecte)
);

CREATE TABLE GERER(
   id_benevoles INT,
   id_beneficiaire INT,
   PRIMARY KEY(id_benevoles, id_beneficiaire),
   FOREIGN KEY(id_benevoles) REFERENCES BENEVOLES(id_benevoles),
   FOREIGN KEY(id_beneficiaire) REFERENCES BENEFICIARES(id_beneficiaire)
);




INSERT INTO BENEVOLES VALUES
(1, 'Dupont', 'Jean', 'jean.dupont@email.com', '0635752201', 1, '2024-02-14', 1),
(2, 'Smith', 'Emma', 'emma.smith@email.com', '0635752201', 1, '2024-02-15', 2),
(3, 'Johnson', 'David', 'david.johnson@email.com', '0635752201', 1, '2023-02-16', 3),
(4, 'Brown', 'Sophie', 'sophie.brown@email.com', '0635752201', 1, '2023-02-17', 1),
(5, 'Miller', 'Michael', 'michael.miller@email.com', '0635752201', 5, '2021-02-18', 2),
(6, 'Davis', 'Olivia', 'olivia.davis@email.com', '0635752201', 1, '2021-06-19', 3),
(7, 'Wilson', 'Daniel', 'daniel.wilson@email.com', '0635752201', 1, '2022-12-20', 1),
(8, 'Moore', 'Emily', 'emily.moore@email.com', '0635752201', 1, '2023-02-21', 2),
(9, 'Taylor', 'Matthew', 'matthew.taylor@email.com', '0635752201', 1, '2024-01-22', 3),
(10, 'Anderson', 'Sophia', 'sophia.anderson@email.com', '0635752201', 1, '2024-02-23', 1),
(11, 'White', 'Jackson', 'jackson.white@email.com', '0635752201', 1, '2021-08-24', 2);

INSERT INTO ACTIVITES VALUES
(1, 'Collecte de vêtements', '2024-02-15', 'Collecte'),
(2, 'Distribution de repas', '2024-02-20', 'Distribution'),
(3, 'Atelier de recyclage', '2024-02-25', 'Atelier'),
(4, 'Collecte alimentaire', '2024-01-01', 'Collecte'),
(5, 'Visite aux personnes âgées', '2024-01-05', 'Visite'),
(6, 'Cours dinformatique', '2024-03-10', 'Cours'),
(7, 'Journée de sensibilisation', '2024-03-15', 'Sensibilisation'),
(8, 'Collecte de jouets', '2024-03-20', 'Collecte'),
(9, 'Distribution de couvertures', '2024-03-25', 'Distribution'),
(10, 'Atelier de jardinage', '2024-03-30', 'Atelier'),
(11, 'Collecte de fournitures scolaires', '2024-04-05', 'Collecte');

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

INSERT INTO BENEFICIARES VALUES 
(1, 'Martin', 'Sophie', 1, 1),
(2, 'Johnson', 'David', 1, 1),
(3, 'Brown', 'Sophie', 2, 1),
(4, 'Miller', 'Michael', 3, 1),
(5, 'Davis', 'Olivia', 1, 1),
(6, 'Wilson', 'Daniel', 1, 1),
(7, 'Moore', 'Emily', 2, 1),
(8, 'Taylor', 'Matthew', 2, 1),
(9, 'Anderson', 'Sophia', 3, 1),
(10, 'White', 'Jackson', 3, 1),
(11, 'Smith', 'Emma', 3, 1),
(12, 'Jones', 'Christopher', 1, 1),
(13, 'Garcia', 'Ava', 2, 1),
(14, 'Martinez', 'Daniel', 1, 1),
(15, 'Clark', 'Olivia', 3, 1),
(16, 'Lewis', 'Liam', 2, 1),
(17, 'Walker', 'Sophie', 2, 1),
(18, 'Hill', 'Andrew', 2, 1),
(19, 'Carter', 'Abigail', 1, 1),
(20, 'Turner', 'Ella', 3, 1),
(21, 'Adams', 'Noah', 3, 1);