# WikiBiographie

Cette extension offre la gestion de biographies dont le contenu peut être synchronisé avec celui de Wikipédia en français.

## Guide de l'utilisateur

[Consulter le guide de l'utilisateur (pdf)](https://productionsrhizome.org/wp-content/uploads/Rhizome_WikiBiographie_guidedutilisation_V2.pdf)  

## Installation de l'extension

Le dossier de l'extension doit être déposé dans le dossier `public/wp-content/plugins` du site WordPress.

## Fichiers et dossiers importants

### Paramètres de l'extension (admin/class-wikibiographie-settings.php)

La classe `WikiBiographie_Settings_Page` détermine les options et actions à afficher dans le panneau de configuration de l'extension (accessible via le menu "Réglages" > "WikiBiographie" du panneau d'administration) et leur fonctionnement.

### Metaboxes (admin/partials/)

Les fichiers PHP situés dans le dossier `admin/partials/` définissent le HTML à générer des différentes metaboxes affichées sur la page d'édition d'une biographie.

### JavaScript (admin/js/wikibiographie-admin.js)

Le code JavaScript à exécuter sur la page d'édition d'une biographie et sur la page des paramètres de l'extension est placé dans le fichier `wikibiographie-admin.js`.

### Activation de l'extension (includes/class-wikibiographie-activator.php)

La méthode `activate` de la classe `Wikibiographie_Activator` renferme la configuration initiale créée lors de l'activation de l'extension.

### Fonctionnement du CPT Biographie (includes/class-wikibiographie.php)

La classe `Wikibiographie` contient la déclaration du CPT Biographie et détermine son fonctionnement, notamment en ce qui a trait à la gestion des données en provenance de Wikipédia et de celles entrées manuellement.

### Intégration avec WikiData et Wikipédia (service/WikiDataService.php)

L'ensemble des appels aux APIs externes de WikiData et de Wikipédia sont faits via la classe `WikiDataService`.

### Affichage des biographies (templates/)

Deux gabarits (templates) de base sont mis à disposition: un pour l'affichage d'une biographie (single-biographie.php) et l'autre pour la liste des biographies (archive-biographie.php).
Il est possible d'adapter l'affichage des biographies à un thème en particulier. Pour cela, il suffit de créer un dossier nommé `wikibiographie` dans le dossier du thème et d'y ajouter deux fichiers : `single-biographie.php` et `archive-biographie.php` configurés selon les besoins. Se baser sur les fichiers originaux situés dans le dossier `templates` de l'extension si nécessaire.

## Licence

Ce projet est publié sous licence GNU GPL v3.0.
