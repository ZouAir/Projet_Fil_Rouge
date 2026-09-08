
# GEST CLUB — Gestion Réservations Club ACA

## 📋 Description

Application web de réservation d'événements sportifs et de loisir pour le MNS Football Club.

## 🛠️ Stack

- Figma
- HTML5 / CSS3
- JavaScript
- PHP 8.2 (procédural)
- MySQL 8.0
- Docker (développement local)
- Git

## 📁 Architecture

/pfr/

├── public/           → Accessible au navigateur (index.php, CSS, JS)

├── includes/         → PHP serveur (bdd.php, header, footer)

├── assets/           → Ressources (css/, js/)

├── .env.local        → Variables Docker (local)

├── .env.production   → Référence credentials serveur

├── docker-compose.yml

└── Dockerfile

## 🚀 Démarrage Local

### Avec Docker

```bash
cd pfr
docker-compose down -v
docker-compose up
```

Accès : http://localhost:8000/

### PhpMyAdmin

http://localhost:8080

## 📊 Base de Données

Credentials configurés dans `.env.local` (Docker)

## 📅 Deadline

- Livraison dossier : 11 septembre 2026
- Soutenance : 1er octobre 2026
