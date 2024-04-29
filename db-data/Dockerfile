FROM mysql
COPY init.sql /docker-entrypoint-initdb.d/
ENV MYSQL_ROOT_PASSWORD=password
ENV MYSQL_DATABASE=apiDev_db
ENV MYSQL_USER=apiDev
ENV MYSQL_PASSWORD=password