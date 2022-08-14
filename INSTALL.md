# Sylius_Starter_Pack
### (UserGroup, LandingPage, WebPage, CmsComponent, CmsStyle, CmsTemplate)

## Clonage et installation :
* git clone MonProjet.git
* cd MonProjet

Si besoin :
* git checkout develop

* composer install

Répondre Yes à toutes les recettes.
Si problème avec payum à l’installation (erreur dans la console d’install) :
```rm config/packages/payum.yaml```

```php bin/console cache:clear```

## Fichier .env.local
Créer un .env.local à la racine du projet avec la config MySQL :
```DATABASE_URL=mysql://user:password@host:port/your_project_%kernel.environment%```

## Installation de Sylius
```php bin/console sylius:install```

* répondre N (non) pour les données d’exemples à importer
* currency : EUR
* locale : fr
* email : votre email
* connexion : avec l’email
* password : votre mot de passe
(developpeurs@delit-dinfluence.fr / DDinfluence18000)

## Installation des tables app_
```symfony console d:m:diff```

```symfony console d:m:m```

## Assets avec Webpack Encore
**Attention : utiliser NodeJS Version 14 maxi**

Passer en version NodeJS 14 avec nvm
Installer la version 14* :
```nvm install 14```

Utiliser la version 14*:
```nvm use 14```

```yarn install```

```yarn encore production```

## Serveur Symfony
Si en local, on démarre le serveur Symfony:
```symfony serve -d```

Page admin : http://127.0.0.1:8000/admin
Connexion email + password créés à l'étape "Installation de Sylius"