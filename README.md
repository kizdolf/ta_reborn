# Toulouse Acoustics WebSite

> Que faut-il savoir pour reprendre le site? Je vais faire la liste des technologies utilisés, des bugs connus,
des fonctionnalités présentes, de ce qui manque, etc...

Le site se découpe en trois parties:
* Le front-end (le site visible par les utilisateurs)
* Le back-end (l'admin du site, pour publier de nouvelles vidéos)
* L'API, qui fait le lien entre le front-end et la base de donées.
-------
### Technologies utilisés
#### 1) __Front-end__
> Le front-end se base sur du Javascript via le framework [AngularJS](http://angularjs.org) en version 1.2. 
Il y a également du [jQuery](https://jquery.com/) pour palier certaines difficutés renconrtés avec Angular.

__Fichiers :__ 
* index.php : main file, contient le layout du site ainsi que les inclusions de script.
* js/ :
    * app.js: config de base d'Angular.
    * controllers.js: logique complète du site
    * jque.js: jQuery en place sur le site
    * routes.js: routing Angular
    * services.js: services internes: appels à l'API, création des frames.
* views/ : Toutes les vues du sites, chacune dans son fichier html.
* css/ : Style du site dans style.css.
* img/ : 
    * badges/ : badges/icones du site.
    * headers/ : icones sociales utilisés
    * uniques. : Photos uploadés par la team. dans chaque sous dossier un dossier est créer portant l'id de
            l'artiste/quartier/... . un dossier min est également créer. Il contient les miniatures de chaque photo.
 * components/ : dépendances du site. (source d'Angular, Jquery, ...)
 
 
 #### 2) __Back-end__
 > Le back-end est en pur PHP avec un soupçon de Javascript. pas de framework ni de librairies.
 
 __Fichiers :__
  * admin/ : ensemble des fichiers utilisé par le back-end. Plus de détails plus bas.
  * classes/ : classes PHP utilisés. 
    * SimpleImage_class.php : Gère le cropping/resizing des images. C'est pas moi qui l'ai faite.
    * log_class.php : Gère la connection à l'admin (is logued?, is user?, set session, unset session)
    * tables_class.php : permet de créer des div simplement selon l'action utilisateur.
    * tapdo_class : Classe principale, fait le lien entre le php et la base de données, défini toutes les méthodes pour
        créer/modifier/supprimmer/récupérer vidéos/artistes/lieu/catégories. est appelé dans à peu près tout les fichiers côté
        admin, et dans l'API.
    
    
##### Le dossier admin.
> L'admin se base sur de l'inclusion de fichiers php selon la page demandé. Chaque fichier contient généralement sa propre 
logique de fonctionnement, et se base sur les classes ainsi que sur un fichier clé de l'admin `admin_functions.php`.

__admin_functions.php__ permet aussi bien de créer de l'html dynamiquement que de gérer la sauvegarde des photos, les fonctions y sont plutôt silmples
mais malheureusement pas documentés.. désolé, je vais essayer de faire ça quand même...

#### 3) API

>L'API faisant le lien entre le front-end et la base de données elle est plutôt courte et simple. Elle se base uniquement 
sur des requètes GET (nope, c'est pas du REST) et ce n'est finalement qu'un gros switch case avec quelques helpers pour alléger le code.

__Fichiers :__
 * api.php : Point d'entrée de l'API, c'est là que doivent arriver les requètes.
 * contact.php : Vérifie en temps réel l'état d'envoi de message via l'onglet 'contact'. Envoie le mail quand c'est bon.
 
-------
### Base de donées

La base de données est sous MySQL, l'ensemble des tables nécéssaire étant présente dans le fichier `ta.sql` présent sur le repos. Y sont inclus par défaut des utilisateurs, (login: test, mdp: test) et un post. Il suffit d'envoyer ce fichier au serveur mysql pour préparer l'ensemble de la base de donées. La configuration Mysql se fait dans le fichier `admin/conf/bdd_conf.json` , la partie __"init"__ contenant les champs à remplir.


> Le fichier __t_q.php__ à la racine du projet permet normalement de récupérer directement depuis viméo toutes les vidéos publiés, d'en extraire les infos importantes et de créer les posts relatif. En gros il est supposé tout faire tout seul. Mais après plusieurs modifications cela ne marche pas réelement comme prévu. __Se serait donc à refaire__. Viméo fourni maintenant une API pour récupérer directement toute une chaine : [Lien API viméo](https://developer.vimeo.com/api/start).


