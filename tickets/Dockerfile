# Utiliser l'image de base Go
FROM golang:1.22

# Définir le répertoire de travail
WORKDIR /app

# Copier le code source
COPY . .

# Compiler le programme Go
RUN go build -o main .

RUN chown -R www-data:www-data /app
RUN chmod -R 755 /app

EXPOSE 8085

# Définir la commande de démarrage
CMD ["./main"]
