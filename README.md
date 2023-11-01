# Biblio Esprit

![Logo Symfony](https://symfony.com/images/logos/header-logo.svg)

## Description

Le but de cet atelier est la manipulation de la Base de données.
ORM Doctrine et CRUD
- Atelier 2.1 : La mise en place d'un projet symfony
- Atelier 2.2 : La manipulation des controllers + routing
- Atelier 3 : Twig
- Atelier 4 (Partie 1 et 2 ) ORM-Doctrine
- Atelier 5 : Dql-QB
  
## Table des matières

- [Installation](#installation)
- [Utilisation](#utilisation)

## Installation

Décrivez comment installer et configurer votre projet Symfony. Incluez les prérequis et les instructions étape par étape pour mettre en place le projet sur un environnement de développement local.

```bash
git clone https://github.com/esprit-upweb/my_symfony_project_correction.git
cd my_symfony_project_correction
composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

## Utilisation
```bash
php bin/console server:start
Ou 
symfony serve
# Ouvrez votre navigateur et accédez à http://localhost:8000
```
