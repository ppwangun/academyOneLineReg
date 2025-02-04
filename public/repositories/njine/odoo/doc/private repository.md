# Guide pour l'ajout de libraires privées 

## Prérequis

Ajouter à l'aide des lignes ci-dessous, un dépot local dans le fichier "composer.json" situé à la racine 
de votre projet. Le chemin spécifié par la clé "url" doit aboutir au dossier ou se situe le fichier "composer.json"
de votre librairie.

```<dossier_projet>/composer.json
    "repositories": [
        {
            "type": "path",
            "url": "c:/wamp/www/w-academy/repositories/njine/odoo"
        }
    ],
    "require": {
            "njine/odoo": "*" 
    },
```

Vous pouvez aussi spécifier une archive au format zip

```<dossier_projet>/composer.json
    "repositories": [
        {
            "type": "artifact",
            "url": "c:/wamp/www/w-academy/repositories/"
        }
    ],
    "require": {
            "njine/odoo": "1.0.0" 
    },
```

## Installation

Il suffit alors d'exécuter la commande ci-dessous

```bash
composer update
```

## Procédure alternative

On peut également spécifier uniquement le dépot privé et utiliser la commande "composer require ..." 
pour obtenir lemême résultat

```<dossier_projet>/composer.json
    "repositories": [
        {
            "type": "path",
            "url": "c:/wamp/www/w-academy/repositories/njine/odoo"
        }
    ]
```

```bash
composer require njine/odoo:*
```

## Exemple

```<dossier_projet>/composer.json
    "repositories": [
        {
            "type": "path",
            "url": "C:/Users/Njine/Développement/Agenla/Agenla v5.0/Modules/njine/odoo"
        }
    ]
```
```bash
php composer.phar require njine/odoo:1.0.0 --ignore-platform-reqs
```

## Contacts
+ Email: [chuanguen@gmail.com](mailto:chuanguen@gmail.com)
