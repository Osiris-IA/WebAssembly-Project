# WasmLightroom 

**WasmLightroom** est une application web de retouche photo collaborative, rapide côté navigateur grâce à WebAssembly et pensée pour une édition non-destructive côté serveur.

## Objectif du Projet

Développer un MVP web-first de retouche photo qui répond à deux enjeux :

1. **Performance client** : traiter des images HD sans bloquer l'interface (WebAssembly/C++).
2. **Optimisation serveur** : conserver l'image originale + la recette des retouches pour limiter le stockage.

---

## Démarrage Rapide

### Prérequis

- Docker & Docker Compose

### Installation & Lancement

```bash
# Clone le projet
git clone <repo-url>
cd Wasm-project

# Lance l'infrastructure complète (PHP, MySQL, conteneurs Wasm)
docker compose up -d

# Accès aux services
- Frontend : http://localhost:8080
- Login : http://localhost:8080/views/login.php
- Inscription : http://localhost:8080/views/register.php
```

**Arrêter les services :**

```bash
docker compose down
```

---

## 📋 Architecture

### Structure du Projet

```
Wasm-project/
├── backend/                      # Code PHP/Frontend
│   ├── index.html               # Application principale Lightroom
│   ├── style.css                # Styles Lightroom (design moderne)
│   ├── upload.php               # Upload sécurisé d'images
│   ├── feed.php                 # Flux d'actualité avec pagination
│   ├── save_recette.php         # Sauvegarde de l'historique des filtres
│   ├── auth.php                 # Gestion de l'authentification
│   ├── views/
│   │   ├── login.php            # Page de connexion
│   │   └── register.php         # Page d'inscription
│   ├── controllers/
│   │   └── AuthController.php   # Logique d'authentification
│   ├── models/
│   │   └── users.php            # Modèle utilisateur
│   ├── config/
│   │   └── database.php         # Configuration PDO
│   └── uploads/                 # Répertoire de stockage des images
├── demos/
│   └── filters.cpp              # Implémentation C++ des filtres
├── build/
│   └── filters.wasm             # Module WebAssembly compilé
├── compose.yml                  # Configuration Docker Compose
├── Dockerfile.as                # Image Docker avec stack web
├── dump.sql                     # Schéma & données de test
└── README.md                    # Documentation (ce fichier)
```

---

## Design & UX

- **Interfaçage** : Inspiré de Lightroom/Adobe (panneaux latéraux, editeurs)
- **Thème Sombre** : `style.css` avec gradients et blur effect moderne
- **Responsive** : Layout grid adaptatif (desktop → mobile)
- **Feed Horizontal** : Scroll infini pour explorer les photos du flux

---

## Dépendances & Versions Importées

| Composant  | Version      | Rôle                          |
| ---------- | ------------ | ----------------------------- |
| PHP        | 8.x (Alpine) | Framework backend             |
| MySQL      | Alpine       | Base de données               |
| Emscripten | 3.1.51       | Compilateur C++ → WebAssembly |
| Docker     | Latest       | Orchestration conteneurs      |

---

## Support & Questions

Consultez le fichier `compose.yml` pour les configurations réseau, ou lancez :

```bash
docker compose logs php
docker compose logs database
```

pour déboguer les erreurs d'exécution.

---

**Date de création** : Avril 2026  
**État** : MVP Fonctionnel
**Équipe** : Iris HADJ MAHFOUD
