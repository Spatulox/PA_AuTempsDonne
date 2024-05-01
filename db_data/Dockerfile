FROM mysql

# Ajoute le fichier init.sql au container
COPY init.sql /docker-entrypoint-initdb.d/

# Créer les variables d'environnement pour la BDD
ENV MYSQL_ROOT_PASSWORD=password
ENV MYSQL_DATABASE=apiDev_db
ENV MYSQL_USER=apiDev
ENV MYSQL_PASSWORD=password