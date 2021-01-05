#Configuration et installation de projet web sous Wordpress (en mode classique)

##Droits et propriété intellectuelle

Document confidentiel, toute reproduction et utilisation en dehors d'un cadre contractuelle est interdite.

## Préambule

Ce document a pour vocation de renseigner sur la procédure de mise en oeuvre, d'installation et de mise à jour du projet web.

En cas de problème, veuillez contacter le support [support@tigreblanc.fr](mailto:support@tigreblanc.fr)

##Installation du projet

###Installation depuis le livrable 

Décompresser le livrable fourni par votre interlocuteur.

###Récupération depuis le dépôt de la version stable hors sources (avancé)

@see https://svn.tigreblanc.fr/project/{project}/tags/ pour identifier la version courante.

```bash
svn export https://svn.tigreblanc.fr/project/{project}/tags/{x.x.x}
```

###Récupération depuis le dépôt de la version de développement sources incluses (déconseillé)

@see https://svn.tigreblanc.fr/project/{project}/branches/ pour identifier la version courante.

```bash
svn co https://svn.tigreblanc.fr/project/{project}/branches/{x.x}
```

##Mise en oeuvre du projet

###Mise en place de l'arborescence du projet

* db : Fichiers de base de données.
* httpdocs : Fichiers sources du projet web.
* log : Fichiers de journalisation.
* repository : Fichiers de copie local des dépôts associés au projet (framework, plugins, ...).
* tmp : Fichiers temporaires (session PHP, ...).

```bash
mkdir db httpdocs log repository tmp 
```

###Installation depuis le dépôt (recommandée)

```bash
composer create-project --no-secure-http --repository-url http://composer.tigreblanc.fr/presstify presstify/wordpress-classic httpdocs 
```

**Récupération des dépendances JS**

```bash
npm install
```
**Renseigner le fichier de configuration d'environnement**

```bash
vi .env
```

**Renseigner le fichier de configuration de compilation**

```bash
vi webpack.config.js
```

###Installation décomposée (déconseillé, à titre informatif uniquement)

####Installation de Wordpress

```bash
wget https://fr.wordpress.org/latest-fr_FR.zip && unzip latest-fr_FR.zip && mv wordpress/* ./ && rm -rf latest-fr_FR.zip wordpress
```

####Installation de composer

> Curl doit être installé au préalable sur votre poste.
@see [Installation de curl](sudo apt-get install php-curl).

```bash
curl -sS https://getcomposer.org/installer | php
```

####Création du fichier de projet (optionnel)


```bash
vi composer.json
```

**Adapter le contenu du composer.json au projet.**

```bash
{
  "name": "presstify/wordpress-classic",
  "description": "Projet web Wordpress + PresstiFy, en mode classique - Organisation des ressources du projet basée sur l'arborescence native de Wordpress.",
  "keywords": [
    "framework",
    "presstify",
    "project",
    "wordpress",
    "classic"
  ],
  "homepage": "https://svn.tigreblanc.fr/presstify-wp-classic",
  "time": "YYYY-MM-DD",
  "authors": [
    {
      "name": "Jordy Manner",
      "email": "jordy.manner@milkcreation.fr",
      "role": "Developper"
    }
  ],
  "repositories": [
    {
      "type": "composer",
      "url": "http://composer.tigreblanc.fr/presstify/"
    },
    {
      "type":"composer",
      "url":"https://wpackagist.org"
    }
  ],
  "require": {
    "php": ">=7.1.3",
    "composer/installers": "~1.0",
    "presstify/presstify-mu": "^1.0",
    "presstify-themes/pollen": "^1.0",
    "rbdwllr/wordpress-salts-generator": "^0.1",
    "vlucas/phpdotenv": "^2.4"
  },
  "minimum-stability": "dev",
  "prefer-stable": true,
  "config": {
    "secure-http": false
  },
  "extra": {
    "installer-paths": {
      "wp-content/plugins/{$name}": [
        "type:wordpress-plugin"
      ],
      "wp-content/mu-plugins/{$name}": [
        "type:wordpress-muplugin"
      ],
      "wp-content/themes/{$name}": [
        "type:wordpress-theme"
      ]
    }
  },
  "scripts": {
    "post-root-package-install": [
      "@php -r \"file_exists('.env') || copy('.env.sample', '.env');\""
    ],
    "post-create-project-cmd": [
      "vendor/bin/wpsalts dotenv --clean >> .env"
    ]
  }
}
```

####Installation de Webpack (optionel)

#####Installer nodeJS

```bash
curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
sudo apt install -y nodejs
```

#####Vérification de version

```bash
nodejs -v
> v8.11.1
```

```bash
npm -v
> 5.8.0
```

#####Mise à jour de npm (optionnel)

```bash
sudo npm install npm@latest -g
```

###Création du dépôt de site

####Commandes SVN utiles

#####Affichage du status

######Global

```bash
svn stat
```

######Elements modifiés uniquement

```bash
svn stat |grep ^M
```

######Elements ajoutés uniquement

```bash
svn stat |grep ^A
```

######Elements à supprimer

```bash
svn stat |grep ^!
```

#####Ajout des fichiers à la racine d'un dossier (recursif)

```bash
svn add --force .
```

#####Suppression des éléments manquants

```bash
svn st | grep ^! | awk '{print " --force "$2}' | xargs svn rm 
```

#####Exclusion de dossier (recursif)

```bash
svn rm --keep-local ./wp-content/plugins/*
svn propset svn:ignore "*" ./wp-content/plugins
svn rm --keep-local ./wp-content/themes/{theme}/dist/*
svn propset svn:ignore "*" ./wp-content/themes/{theme}/dist
 ```
 
#####Exclusion de fichiers 

```bash
vi .svnignore
--------------------------------------
.buildpath
.idea
.project
.settings
composer.lock
node_modules
package-lock.json
vendor
```

```bash
svn propset svn:ignore -F .svnignore .
```

####Création du répertoire de dépôt

```bash
mkdir -p repository/httpdocs/branches repository/httpdocs/tags repository/httpdocs/trunk 
cd repository/trunk
```

```bash
svn mkdir -m "{project} : Création du répertoire de dépôt" https://svn.tigreblanc.fr/project/{project}
```

```bash
svn co https://svn.tigreblanc.fr/project/{project} ./
svn add --force .
svn ci -m "{project} : Création de la structure du répertoire de dépôt"
rm -rf .svn svn add --force .
svn ci -m "{example.com} : Création de la structure du répertoire de dépôt"
rm -rf .svn 

```

##Crédits

[https://tigreblanc.fr](https://tigreblanc.fr])

###Gestionnaires de dépôts

[https://getcomposer.org/](https://getcomposer.org/)

[https://webpack.js.org/](https://webpack.js.org/)

[http://composer.tigreblanc.fr/](http://composer.tigreblanc.fr/) (privé)

[https://svn.tigreblanc.fr](https://svn.tigreblanc.fr) (privé)

###Gestionnaires de dépendances

[https://packagist.org/](https://packagist.org/)

[https://wpackagist.org/](https://wpackagist.org/)

[https://www.npmjs.com/](https://www.npmjs.com/)

###Framework

####PHP

[https://fr.wordpress.com/](https://fr.wordpress.com/)

[https://prmy-account/my-account/esstify.com](https://presstify.com)

[https://symfony.com/](https://symfony.com/)

[https://laravel.com/](https://laravel.com/)

###CSS

[https://getbootstrap.com/](https://getbootstrap.com/)