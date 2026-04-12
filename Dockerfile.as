# 1. Utiliser une image Node.js légère comme base
FROM node:20-slim

# 2. Définir le dossier de travail dans le conteneur
WORKDIR /app

# 3. Installer le compilateur AssemblyScript et le serveur web globalement
# 'asc' sera disponible pour compiler, 'http-server' pour servir les fichiers
RUN npm install -g wat-wasm assemblyscript http-server

# 4. Documenter le port que le conteneur écoute
EXPOSE 8080

# 5. Commande de démarrage automatique
# Lance http-server dans le dossier courant (.)
# -p 8080 : sur le port 8080
# --cors : autorise le chargement du Wasm (Cross-Origin Resource Sharing)
# -c-1 : désactive le cache (essentiel pour le développement pour voir vos changements immédiatement)
CMD ["http-server", ".", "-p", "8080", "--cors", "-c-1"]
