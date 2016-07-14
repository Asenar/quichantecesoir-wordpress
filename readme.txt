=== Qui Chante Ce Soir ===
Contributors: Asenar
Tags: concerts, chanson française, agenda, tour date
Requires at least: 2.7
Tested up to: 3.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Version: 0.1

QuiChanteCeSoir permet d'afficher les dates issues du site d'annonce de
concert quichantecesoir.com

[Work In Progress]

== Description ==

Le plugin Qui Chante Ce Soir intègre dans une page ou un widget l'affichage
des prochaines dates d'un artiste avec les infos saisies.

== Installation ==

Placer le dossier *quichantecesoir* dans wp-content/plugins et activez le
depuis l'interface d'administration.

== Utilisation ==

* Sur une page ou un article, saisissez le tag avec l'url que vous souhaitez
afficher et le nom de l'artiste (ou un autre terme si vous le souhaitez).

    [quichantecesoir url=http://quichantecesoir.com/a/114-Nicolas-Bacchus]


== Configuration ==

* url (requis) : l'url de la page de l'artiste.

* custom_order (champ séparé par des virgules) : défini l'ordre d'affichage par défaut des informations.

* images (0 ou 1) : permet d'afficher ou non les images

* table (0 ou 1) :
** si "1", utilise les balises html (table, tr, td)
** si "0", utilise des balises div


* custom_css : permet de rajouter des règles css spécifiques pour le widget.
Attention, ces règles s'appliqueront à tout votre site.

== Options disponible dans les "template_tag" ==


== Screenshots ==

[On verra plus tard]

== Changelog ==

= 0.1 =
* start
