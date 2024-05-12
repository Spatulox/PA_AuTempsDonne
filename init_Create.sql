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