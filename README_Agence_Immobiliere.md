# README - Projet Symfony "Agence Immobilière"

Bienvenue dans le projet **Agence Immobilière**, une application web dynamique d'achat, vente et location de biens immobiliers, développée avec Symfony 6 et Docker.

## Objectifs du projet

Ce projet répond à l'ensemble des **8 Unités Compétences (UC)** requises, notamment :
- Maquetage et accessibilité
- Développement full stack (front & back)
- Sécurité et RGPD
- Déploiement via Docker

## Prérequis

- **Docker** installé (version 20+)
- **Git** (version 2.3+)
- **Composer** (version 2+)
- Navigateur moderne (Chrome, Firefox, Edge)

## Installation locale avec Docker

1. Cloner le dépôt Git :
```bash
git clone https://github.com/utilisateur/agence-immobiliere.git
cd agence-immobiliere
```

2. Lancer Docker :
```bash
docker-compose up -d
```
Accédez à l'application sur : `http://localhost:8080`

3. Initialiser la base de données :
```bash
docker exec -it agence_php bash
php bin/console doctrine:migrations:migrate
exit
```

## Structure du projet

- `/src/Controller/` : Logique métier et routes
- `/templates/` : Vues Twig (frontend et backend)
- `/public/` : Fichiers publics (CSS, JS)
- `/migrations/` : Scripts de base de données
- `/docker-compose.yml` : Configuration Docker

## Fonctionnalités principales

- Authentification sécurisée (client, propriétaire, admin)
- CRUD complet des biens immobiliers
- Gestion des favoris et des visites
- Formulaire de contact avec sécurité CSRF
- Interface responsive avec support mobile

## Outils et technologies

- Symfony 6.x
- Doctrine ORM / MySQL 8
- Twig / HTML / CSS / JS
- Docker / GitHub / Trello

## Déploiement futur

- Intégration GitHub Actions (CI/CD)
- Ajout MongoDB pour favoris (optionnel)
- Amélioration dark mode et accessibilité

## Auteur et contact

Projet réalisé par **[Manuel Pulido dan le cadre de validation d'un project de transition profetionelle]** https://github.com/manuelpborda/projet_immobilier/tree/master

Contact : [manuelpborda@gmail.com]

Licence : Usage pédagogique uniquement. Tous droits réservés.
