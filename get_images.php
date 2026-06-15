<?php
/*
 Template Name: missing images
 Template Post Type: page
*/
get_header(); ?>
<?php 
$list = ["https://actetre.de/heft-a6-lin-herzen/",
"https://actetre.de/heft-a6-lin-herzen-2/",
"https://actetre.de/heft-a6-lin-blumen/",
"https://actetre.de/heft-a6-lin-blumen-2/",
"https://actetre.de/roses-for-you/",
"https://actetre.de/roses-for-you-2/",
"https://actetre.de/dog-and-cat-with-christmas-tree/",
"https://actetre.de/dog-and-cat-with-christmas-tree-2/",
"https://actetre.de/merry-christmas-75/",
"https://actetre.de/merry-christmas-76/",
"https://actetre.de/merry-christmas-77/",
"https://actetre.de/merry-christmas-78/",
"https://actetre.de/heft-a6-lin-blumen-3/",
"https://actetre.de/heft-a6-lin-blumen-4/",
"https://actetre.de/heft-a6-lin-kreise/",
"https://actetre.de/heft-a6-lin-kreise-2/",
"https://actetre.de/ackermann-m-diptychon-blau/",
"https://actetre.de/ackermann-m-diptychon-blau-2/",
"https://actetre.de/baumeister-w-bew-vertik-auf-blau/",
"https://actetre.de/baumeister-w-bew-vertik-auf-blau-2/",
"https://actetre.de/bei-dir-sein/",
"https://actetre.de/bei-dir-sein-2/",
"https://actetre.de/stille-kraft/",
"https://actetre.de/stille-kraft-2/",
"https://actetre.de/fusi-w-yellow-2001/",
"https://actetre.de/fusi-w-yellow-2001-2/",
"https://actetre.de/matisse-h-verve/",
"https://actetre.de/matisse-h-verve-2/",
"https://actetre.de/matisse-h-la-vague/",
"https://actetre.de/matisse-h-la-vague-2/",
"https://actetre.de/dali-s-a-lively-still-life-zg/",
"https://actetre.de/dali-s-a-lively-still-life-zg-2/",
"https://actetre.de/staedte-postkarte-rothenburg-quer/",
"https://actetre.de/staedte-postkarte-rothenburg-quer-2/",
"https://actetre.de/alles-liebe-quer/",
"https://actetre.de/alles-liebe-quer-2/",
"https://actetre.de/staedte-postkarte-donauschifffahrt-quer/",
"https://actetre.de/staedte-postkarte-donauschifffahrt-quer-2/",
"https://actetre.de/happy-hanukkah/",
"https://actetre.de/happy-hanukkah-2/",
"https://actetre.de/spiralblock-a6-antarctica/",
"https://actetre.de/spiralblock-a6-antarctica-2/",
"https://actetre.de/spiralblock-a6-love-story/",
"https://actetre.de/spiralblock-a6-love-story-2/",
"https://actetre.de/spiralblock-a6-confidential/",
"https://actetre.de/spiralblock-a6-confidential-2/",
"https://actetre.de/spiralblock-a5-antarctica/",
"https://actetre.de/spiralblock-a5-antarctica-2/",
"https://actetre.de/spiralblock-a5-love-story/",
"https://actetre.de/spiralblock-a5-love-story-2/",
"https://actetre.de/spiralblock-a5-confidential/",
"https://actetre.de/spiralblock-a5-confidential-2/",
"https://actetre.de/spiralblock-a5-retro/",
"https://actetre.de/spiralblock-a5-retro-2/",
"https://actetre.de/spiralblock-a5-bunte-katzen/",
"https://actetre.de/spiralblock-a5-bunte-katzen-2/",
"https://actetre.de/magnet-believe-in-yourself-o-56-mm/",
"https://actetre.de/magnet-believe-in-yourself-o-56-mm-2/",
"https://actetre.de/spiralblock-a5-herzen/",
"https://actetre.de/spiralblock-a5-herzen-2/",
"https://actetre.de/spiralblock-a5-be-happy/",
"https://actetre.de/spiralblock-a5-be-happy-2/",
"https://actetre.de/spiralblock-a5-farbtupfer/",
"https://actetre.de/spiralblock-a5-farbtupfer-2/",
"https://actetre.de/spiralblock-a5-moskau/",
"https://actetre.de/spiralblock-a5-moskau-2/",
"https://actetre.de/heft-liniert-17x22-cm/",
"https://actetre.de/heft-liniert-17x22-cm-2/",
"https://actetre.de/staedte-postkarte-berlin-kadewe-quer/",
"https://actetre.de/staedte-postkarte-berlin-kadewe-quer-2/",
"https://actetre.de/staedte-postkarte-berlin-panorama-quer/",
"https://actetre.de/staedte-postkarte-berlin-panorama-quer-2/",
"https://actetre.de/delaunay-robert-relief-disques-1936/",
"https://actetre.de/delaunay-robert-relief-disques-1936-2/",
"https://actetre.de/delaunay-r-rythme-n-1-decoration-murale-pour-le-salon-des-tuileries-1938/",
"https://actetre.de/delaunay-r-rythme-n-1-decoration-murale-pour-le-salon-des-tuileries-1938-2/",
"https://actetre.de/van-gogh-the-siesta-after-millet/",
"https://actetre.de/van-gogh-the-siesta-after-millet-2/",
"https://actetre.de/monet-palazzo-contarini/",
"https://actetre.de/monet-palazzo-contarini-2/",
"https://actetre.de/klee-p-le-petit-chat-1940/",
"https://actetre.de/klee-p-le-petit-chat-1940-2/",
"https://actetre.de/klee-p-oiseau-superieur-1940/",
"https://actetre.de/klee-p-oiseau-superieur-1940-2/",
"https://actetre.de/klee-p-chameau-1939/",
"https://actetre.de/klee-p-chameau-1939-2/",
"https://actetre.de/cat-rowe-textil-jolly-snowman/",
"https://actetre.de/cat-rowe-textil-jolly-snowman-2/",
"https://actetre.de/cat-rowe-textil-reindeer-in-red-scarf/",
"https://actetre.de/cat-rowe-textil-reindeer-in-red-scarf-2/",
"https://actetre.de/cat-rowe-textil-dove/",
"https://actetre.de/cat-rowe-textil-dove-2/",
"https://actetre.de/cat-rowe-textil-santa-and-reindeer/",
"https://actetre.de/cat-rowe-textil-santa-and-reindeer-2/",
"https://actetre.de/cat-rowe-textil-three-wise-snowmen/",
"https://actetre.de/cat-rowe-textil-three-wise-snowmen-2/",
"https://actetre.de/cat-rowe-textil-rudolf-and-friends/",
"https://actetre.de/cat-rowe-textil-rudolf-and-friends-2/",
"https://actetre.de/cat-rowe-textil-twas-the-night-before/",
"https://actetre.de/cat-rowe-textil-twas-the-night-before-2/",
"https://actetre.de/cat-rowe-textil-leave-one-for-santa/",
"https://actetre.de/cat-rowe-textil-leave-one-for-santa-2/",
"https://actetre.de/cat-rowe-textil-a-stocking-full/",
"https://actetre.de/cat-rowe-textil-a-stocking-full-2/",
"https://actetre.de/cat-rowe-textil-on-a-snowy-night/",
"https://actetre.de/cat-rowe-textil-on-a-snowy-night-2/",
"https://actetre.de/collageorama-zeke-the-cool-camel/",
"https://actetre.de/collageorama-zeke-the-cool-camel-2/",
"https://actetre.de/collageorama-pug-dmc/",
"https://actetre.de/collageorama-pug-dmc-2/",
"https://actetre.de/julia-trigg-vivid-vintage-peacock-butterfly/",
"https://actetre.de/julia-trigg-vivid-vintage-peacock-butterfly-2/",
"https://actetre.de/julia-trigg-vivid-vintage-colourful-camellia/",
"https://actetre.de/julia-trigg-vivid-vintage-colourful-camellia-2/",
"https://actetre.de/julia-trigg-vivid-vintage-funky-finch/",
"https://actetre.de/julia-trigg-vivid-vintage-funky-finch-2/",
"https://actetre.de/vintage-matchbox-cloud-nine/",
"https://actetre.de/vintage-matchbox-cloud-nine-2/",
"https://actetre.de/vintage-matchbox-shine-on/",
"https://actetre.de/vintage-matchbox-shine-on-2/",
"https://actetre.de/vintage-matchbox-bathtime-bubbles-mum/",
"https://actetre.de/vintage-matchbox-bathtime-bubbles-mum-2/",
"https://actetre.de/friedliche-festtage-gutes-neues-jahr/",
"https://actetre.de/friedliche-festtage-gutes-neues-jahr-2/",
"https://actetre.de/frohe-festtage-gutes-neues-jahr-13/",
"https://actetre.de/frohe-festtage-gutes-neues-jahr-14/",
"https://actetre.de/besinnliche-festtage-gutes-neues-jahr/",
"https://actetre.de/besinnliche-festtage-gutes-neues-jahr-2/",
"https://actetre.de/schoene-weihnachtszeit-gutes-neues-jahr/",
"https://actetre.de/schoene-weihnachtszeit-gutes-neues-jahr-2/",
"https://actetre.de/herzliche-weihnachtsgruesse-glueckliches-neues-jahr/",
"https://actetre.de/herzliche-weihnachtsgruesse-glueckliches-neues-jahr-2/",
"https://actetre.de/frohe-festtage-gutes-neues-jahr-15/",
"https://actetre.de/frohe-festtage-gutes-neues-jahr-16/",
"https://actetre.de/frohes-fest-139/",
"https://actetre.de/frohes-fest-140/",
"https://actetre.de/schoene-weihnachtszeit-und-ein-gutes-neues-jahr/",
"https://actetre.de/schoene-weihnachtszeit-und-ein-gutes-neues-jahr-2/",
"https://actetre.de/frohes-fest-141/",
"https://actetre.de/frohes-fest-142/",
"https://actetre.de/frohe-weihnachten-und-ein-gutes-neues-jahr-35/",
"https://actetre.de/frohe-weihnachten-und-ein-gutes-neues-jahr-36/",
"https://actetre.de/frohe-weihnachten-gutes-neues-jahr-67/",
"https://actetre.de/frohe-weihnachten-gutes-neues-jahr-68/",
"https://actetre.de/froehliche-weihnachten-von-uns-allen/",
"https://actetre.de/froehliche-weihnachten-von-uns-allen-2/",
"https://actetre.de/frohe-weihnachten-und-ein-gutes-neues-jahr-37/",
"https://actetre.de/frohe-weihnachten-und-ein-gutes-neues-jahr-38/",
"https://actetre.de/merry-christmas-and-a-happy-new-year-21/",
"https://actetre.de/merry-christmas-and-a-happy-new-year-22/",
"https://actetre.de/merry-christmas-and-a-happy-new-year-23/",
"https://actetre.de/merry-christmas-and-a-happy-new-year-24/",
"https://actetre.de/froehliche-festtage-glueck-im-neuen-jahr/",
"https://actetre.de/froehliche-festtage-glueck-im-neuen-jahr-2/",
"https://actetre.de/ho-ho-ho-froehliche-weihnachten-und-ein/",
"https://actetre.de/ho-ho-ho-froehliche-weihnachten-und-ein-2/",
"https://actetre.de/ein-frohes-fest-und-ein-gutes-neues-jahr/",
"https://actetre.de/ein-frohes-fest-und-ein-gutes-neues-jahr-2/",
"https://actetre.de/frohes-fest-glueckliches-neues-jahr/",
"https://actetre.de/frohes-fest-glueckliches-neues-jahr-2/",
"https://actetre.de/frohe-weihnachten-gutes-neues-jahr-69/",
"https://actetre.de/frohe-weihnachten-gutes-neues-jahr-70/",
"https://actetre.de/frohe-festtage-gutes-neues-jahr-17/",
"https://actetre.de/frohe-festtage-gutes-neues-jahr-18/",
"https://actetre.de/auf-ein-gutes-neues-jahr/",
"https://actetre.de/auf-ein-gutes-neues-jahr-2/",
"https://actetre.de/plein-de-bonheur-bonne-annee/",
"https://actetre.de/plein-de-bonheur-bonne-annee-2/",
"https://actetre.de/meilleurs-voeux-et-bonne-annee-21/",
"https://actetre.de/meilleurs-voeux-et-bonne-annee-22/",
"https://actetre.de/meilleurs-voeux-et-bonne-annee-23/",
"https://actetre.de/meilleurs-voeux-et-bonne-annee-24/",
"https://actetre.de/joyeuses-fetes-bonne-annee-3/",
"https://actetre.de/joyeuses-fetes-bonne-annee-4/",
"https://actetre.de/joyeuses-fetes-35/",
"https://actetre.de/joyeuses-fetes-36/",
"https://actetre.de/joyeuses-fetes-de-fin-dannee-31/",
"https://actetre.de/joyeuses-fetes-de-fin-dannee-32/",
"https://actetre.de/joyeuses-fetes-bonne-annee-5/",
"https://actetre.de/joyeuses-fetes-bonne-annee-6/",
"https://actetre.de/douces-fetes-de-fin-dannee/",
"https://actetre.de/douces-fetes-de-fin-dannee-2/",
"https://actetre.de/joyeux-noel-et-bonne-annee-41/",
"https://actetre.de/joyeux-noel-et-bonne-annee-42/",
"https://actetre.de/belles-fetes-bonne-annee/",
"https://actetre.de/belles-fetes-bonne-annee-2/",
"https://actetre.de/woman-at-restaurant-looking-at-winter-scene/",
"https://actetre.de/woman-at-restaurant-looking-at-winter-scene-2/",
"https://actetre.de/pas-de-deux-ice-skating/",
"https://actetre.de/pas-de-deux-ice-skating-2/",
"https://actetre.de/colourful-fireworks/",
"https://actetre.de/colourful-fireworks-2/",
"https://actetre.de/christmas-33/",
"https://actetre.de/christmas-34/",
"https://actetre.de/christmas-35/",
"https://actetre.de/christmas-36/",
"https://actetre.de/merry-christmas-%e2%9c%b6-happy-new-year-3/",
"https://actetre.de/merry-christmas-%e2%9c%b6-happy-new-year-4/",
"https://actetre.de/christmas-37/",
"https://actetre.de/christmas-38/",
"https://actetre.de/christmas-39/",
"https://actetre.de/christmas-40/",
"https://actetre.de/christmas-41/",
"https://actetre.de/christmas-42/",
"https://actetre.de/christmas-43/",
"https://actetre.de/christmas-44/",
"https://actetre.de/ho-ho-ho-christmas/",
"https://actetre.de/ho-ho-ho-christmas-2/",
"https://actetre.de/christmas-45/",
"https://actetre.de/christmas-46/",
"https://actetre.de/christmas-47/",
"https://actetre.de/christmas-48/",
"https://actetre.de/merry-christmas-%e2%9c%b6-happy-new-year-5/",
"https://actetre.de/merry-christmas-%e2%9c%b6-happy-new-year-6/",
"https://actetre.de/merry-christmas-%f0%9f%8e%84%f0%9f%8e%84%f0%9f%8e%84%f0%9f%8e%84-happy-new-year/",
"https://actetre.de/merry-christmas-%f0%9f%8e%84%f0%9f%8e%84%f0%9f%8e%84%f0%9f%8e%84-happy-new-year-2/",
"https://actetre.de/5-geschenkanhaenger-christmas-41/",
"https://actetre.de/5-geschenkanhaenger-christmas-42/",
"https://actetre.de/5-geschenkanhaenger-christmas-43/",
"https://actetre.de/5-geschenkanhaenger-christmas-44/",
"https://actetre.de/5-geschenkanhaenger-christmas-45/",
"https://actetre.de/5-geschenkanhaenger-christmas-46/",
"https://actetre.de/5-geschenkanhaenger-christmas-47/",
"https://actetre.de/5-geschenkanhaenger-christmas-48/",
"https://actetre.de/5-geschenkanhaenger-christmas-49/",
"https://actetre.de/5-geschenkanhaenger-christmas-50/",
"https://actetre.de/5-geschenkanhaenger-christmas-51/",
"https://actetre.de/5-geschenkanhaenger-christmas-52/",
"https://actetre.de/5-geschenkanhaenger-christmas-53/",
"https://actetre.de/5-geschenkanhaenger-christmas-54/",
"https://actetre.de/5-geschenkanhaenger-christmas-55/",
"https://actetre.de/5-geschenkanhaenger-christmas-56/",
"https://actetre.de/5-geschenkanhaenger-christmas-57/",
"https://actetre.de/5-geschenkanhaenger-christmas-58/",
"https://actetre.de/5-geschenkanhaenger-christmas-59/",
"https://actetre.de/5-geschenkanhaenger-christmas-60/",
"https://actetre.de/ak-weihnachtsmann-und-rentier-o-t/",
"https://actetre.de/ak-weihnachtsmann-und-rentier-o-t-2/",
"https://actetre.de/ak-weihnachtsbaum-auf-rotem-hintergrund-o-t/",
"https://actetre.de/ak-weihnachtsbaum-auf-rotem-hintergrund-o-t-2/",
"https://actetre.de/ak-weihnachtsbaum-auf-goldenem-hintergrund-o-t/",
"https://actetre.de/ak-weihnachtsbaum-auf-goldenem-hintergrund-o-t-2/",
"https://actetre.de/ak-weihnachtsmann-mit-seinen-tierischen-freunden-o-t/",
"https://actetre.de/ak-weihnachtsmann-mit-seinen-tierischen-freunden-o-t-2/",
"https://actetre.de/ak-weihnachtliche-erdmaennchen-o-t/",
"https://actetre.de/ak-weihnachtliche-erdmaennchen-o-t-2/",
"https://actetre.de/geschenkpapier-weihnachten-50-x-70-cm-39/",
"https://actetre.de/geschenkpapier-weihnachten-50-x-70-cm-40/",
"https://actetre.de/geschenkpapier-weihnachten-50-x-70-cm-41/",
"https://actetre.de/geschenkpapier-weihnachten-50-x-70-cm-42/",
"https://actetre.de/geschenkpapier-weihnachten-50-x-70-cm-43/",
"https://actetre.de/geschenkpapier-weihnachten-50-x-70-cm-44/",
"https://actetre.de/geschenkpapier-weihnachten-50-x-70-cm-45/",
"https://actetre.de/geschenkpapier-weihnachten-50-x-70-cm-46/",
"https://actetre.de/ak-children-in-front-of-christmas-tree/",
"https://actetre.de/ak-children-in-front-of-christmas-tree-2/",
"https://actetre.de/ak-flying-reindeer/",
"https://actetre.de/ak-flying-reindeer-2/",
"https://actetre.de/ak-forest-animals-in-front-of-christmas-tree/",
"https://actetre.de/ak-forest-animals-in-front-of-christmas-tree-2/",
"https://actetre.de/froehliche-weihnachten-engel-um-weihnachtsbaum/",
"https://actetre.de/froehliche-weihnachten-engel-um-weihnachtsbaum-2/",
"https://actetre.de/froehliche-weihnachten-joyeux-noel-merry-christmas/",
"https://actetre.de/froehliche-weihnachten-joyeux-noel-merry-christmas-2/",
"https://actetre.de/froehliche-weihnachten-weihnachtsmann-auf-esel/",
"https://actetre.de/froehliche-weihnachten-weihnachtsmann-auf-esel-2/",
"https://actetre.de/froehliche-weihnachten-fahrrad-mit-geschenken/",
"https://actetre.de/froehliche-weihnachten-fahrrad-mit-geschenken-2/",
"https://actetre.de/froehliche-weihnachten-musizierende-kinder/",
"https://actetre.de/froehliche-weihnachten-musizierende-kinder-2/",
"https://actetre.de/froehliche-weihnachten-nikolaus-auf-weissem-pferd/",
"https://actetre.de/froehliche-weihnachten-nikolaus-auf-weissem-pferd-2/",
"https://actetre.de/froehliche-weihnachten-waldtiere/",
"https://actetre.de/froehliche-weihnachten-waldtiere-2/",
"https://actetre.de/froehliche-weihnachten-weihnachtsbaum-im-schnee/",
"https://actetre.de/froehliche-weihnachten-weihnachtsbaum-im-schnee-2/",
"https://actetre.de/modernes-buntes-florales-muster-hoch/",
"https://actetre.de/modernes-buntes-florales-muster-hoch-2/",
"https://actetre.de/abstraktes-buntes-florales-muster-quer/",
"https://actetre.de/abstraktes-buntes-florales-muster-quer-2/",
"https://actetre.de/koi-fische-hoch/",
"https://actetre.de/koi-fische-hoch-2/",
"https://actetre.de/musiknoten-quer/",
"https://actetre.de/musiknoten-quer-2/",
"https://actetre.de/geburtstagskuchen-hoch/",
"https://actetre.de/geburtstagskuchen-hoch-2/",
"https://actetre.de/voegel-auf-ast-vor-gelbem-hintergrund-hoch/",
"https://actetre.de/voegel-auf-ast-vor-gelbem-hintergrund-hoch-2/",
"https://actetre.de/schmetterling-mit-blumen-modern-hoch/",
"https://actetre.de/schmetterling-mit-blumen-modern-hoch-2/",
"https://actetre.de/zwei-tukans-auf-ast-hoch/",
"https://actetre.de/zwei-tukans-auf-ast-hoch-2/",
"https://actetre.de/katze-im-buecherregal-hoch/",
"https://actetre.de/katze-im-buecherregal-hoch-2/",
"https://actetre.de/frau-im-palmenhaus-hoch/",
"https://actetre.de/frau-im-palmenhaus-hoch-2/",
"https://actetre.de/good-luck-moderne-symbole-quer/",
"https://actetre.de/good-luck-moderne-symbole-quer-2/",
"https://actetre.de/fuer-dich-mit-allerliebsten-gruessen-hoch/",
"https://actetre.de/fuer-dich-mit-allerliebsten-gruessen-hoch-2/",
"https://actetre.de/bee-happy-hoch/",
"https://actetre.de/bee-happy-hoch-2/",
"https://actetre.de/ich-wuensche-dir-einen-baerigen-geburtstag-hoch/",
"https://actetre.de/ich-wuensche-dir-einen-baerigen-geburtstag-hoch-2/",
"https://actetre.de/alles-liebe-hoch-3/",
"https://actetre.de/alles-liebe-hoch-4/",
"https://actetre.de/hipp-hipp-hurra-glueckwunsch-hoch/",
"https://actetre.de/hipp-hipp-hurra-glueckwunsch-hoch-2/",
"https://actetre.de/hund-mit-geburtstagskerzen-o-t-hoch/",
"https://actetre.de/hund-mit-geburtstagskerzen-o-t-hoch-2/",
"https://actetre.de/feier-schoen-hoch/",
"https://actetre.de/feier-schoen-hoch-2/",
"https://actetre.de/das-leben-ist-so-schoen-hoch/",
"https://actetre.de/das-leben-ist-so-schoen-hoch-2/",
"https://actetre.de/danke-hoch/",
"https://actetre.de/danke-hoch-2/",
"https://actetre.de/blauwal-mit-moewe-o-t-hoch/",
"https://actetre.de/blauwal-mit-moewe-o-t-hoch-2/",
"https://actetre.de/nashorn-mit-luftballon-o-t-hoch/",
"https://actetre.de/nashorn-mit-luftballon-o-t-hoch-2/",
"https://actetre.de/glueckwunsch-von-uns-allen-hoch/",
"https://actetre.de/glueckwunsch-von-uns-allen-hoch-2/",
"https://actetre.de/glueckwunsch-dino-mit-maus-hoch/",
"https://actetre.de/glueckwunsch-dino-mit-maus-hoch-2/",
"https://actetre.de/glueckwunsch-leopard-mit-krone-hoch/",
"https://actetre.de/glueckwunsch-leopard-mit-krone-hoch-2/",
"https://actetre.de/blumenstrauss-o-t-hoch-3/",
"https://actetre.de/blumenstrauss-o-t-hoch-4/",
"https://actetre.de/ich-denk-an-dich-hoch/",
"https://actetre.de/ich-denk-an-dich-hoch-2/",
"https://actetre.de/endlich-zeit-fuer-dich-hoch/",
"https://actetre.de/endlich-zeit-fuer-dich-hoch-2/",
"https://actetre.de/gratulation-hoch/",
"https://actetre.de/gratulation-hoch-2/",
"https://actetre.de/alles-liebe-zur-verlobung/",
"https://actetre.de/alles-liebe-zur-verlobung-2/",
"https://actetre.de/glueckwunsch-zum-hochzeitstag-5/",
"https://actetre.de/glueckwunsch-zum-hochzeitstag-6/",
"https://actetre.de/du-erwartest-ein-kind-glueckwunsch/",
"https://actetre.de/du-erwartest-ein-kind-glueckwunsch-2/",
"https://actetre.de/la-france-rennes-le-parlement-de-bretagne-quer/",
"https://actetre.de/la-france-rennes-le-parlement-de-bretagne-quer-2/",
"https://actetre.de/la-france-rennes-la-place-du-champ-jacquet-quer/",
"https://actetre.de/la-france-rennes-la-place-du-champ-jacquet-quer-2/",
"https://actetre.de/la-france-rennes-la-place-de-la-republique-quer/",
"https://actetre.de/la-france-rennes-la-place-de-la-republique-quer-2/",
"https://actetre.de/la-france-rennes-la-place-saint-anne-quer/",
"https://actetre.de/la-france-rennes-la-place-saint-anne-quer-2/",
"https://actetre.de/la-france-fort-boyard-quer/",
"https://actetre.de/la-france-fort-boyard-quer-2/",
"https://actetre.de/la-france-la-rochelle-lhotel-de-ville-de-la-rochelle-quer/",
"https://actetre.de/la-france-la-rochelle-lhotel-de-ville-de-la-rochelle-quer-2/",
"https://actetre.de/la-france-la-rochelle-la-grosse-horloge-quer/",
"https://actetre.de/la-france-la-rochelle-la-grosse-horloge-quer-2/",
"https://actetre.de/la-france-la-rochelle-la-maison-du-chat-quer/",
"https://actetre.de/la-france-la-rochelle-la-maison-du-chat-quer-2/",
"https://actetre.de/la-france-la-rochelle-le-vieux-port-quer/",
"https://actetre.de/la-france-la-rochelle-le-vieux-port-quer-2/",
"https://actetre.de/la-france-marseille-la-basilique-notre-dame-de-la-garde-quer/",
"https://actetre.de/la-france-marseille-la-basilique-notre-dame-de-la-garde-quer-2/",
"https://actetre.de/la-france-marseille-la-cathedrale-la-major-quer/",
"https://actetre.de/la-france-marseille-la-cathedrale-la-major-quer-2/",
"https://actetre.de/la-france-marseille-le-vieux-port-quer/",
"https://actetre.de/la-france-marseille-le-vieux-port-quer-2/",
"https://actetre.de/la-france-avignon-le-pont-davignon-quer/",
"https://actetre.de/la-france-avignon-le-pont-davignon-quer-2/",
"https://actetre.de/la-france-la-provence-le-mont-ventoux-quer/",
"https://actetre.de/la-france-la-provence-le-mont-ventoux-quer-2/",
"https://actetre.de/la-france-aix-en-provence-la-fontaine-de-la-rotonde-quer/",
"https://actetre.de/la-france-aix-en-provence-la-fontaine-de-la-rotonde-quer-2/",
"https://actetre.de/la-france-aix-en-provence-le-cours-mirabeau-quer/",
"https://actetre.de/la-france-aix-en-provence-le-cours-mirabeau-quer-2/",
"https://actetre.de/la-france-la-provence-le-marche-provencal-quer/",
"https://actetre.de/la-france-la-provence-le-marche-provencal-quer-2/",
"https://actetre.de/la-france-la-partie-de-petanque-quer/",
"https://actetre.de/la-france-la-partie-de-petanque-quer-2/",
"https://actetre.de/floribunda-looking-up/",
"https://actetre.de/floribunda-looking-up-2/",
"https://actetre.de/floribunda-vintage-bike-with-flowers/",
"https://actetre.de/floribunda-vintage-bike-with-flowers-2/",
"https://actetre.de/norman-parkinson-the-art-of-travel/",
"https://actetre.de/norman-parkinson-the-art-of-travel-2/",
"https://actetre.de/joyeux-noel-117/",
"https://actetre.de/joyeux-noel-118/",
"https://actetre.de/joyeux-noel-119/",
"https://actetre.de/joyeux-noel-120/",
"https://actetre.de/joyeuses-fetes-37/",
"https://actetre.de/joyeuses-fetes-38/",
"https://actetre.de/joyeuses-fetes-39/",
"https://actetre.de/joyeuses-fetes-40/",
"https://actetre.de/un-doux-et-joyeux-noel-3/",
"https://actetre.de/un-doux-et-joyeux-noel-4/",
"https://actetre.de/meilleurs-voeux-53/",
"https://actetre.de/meilleurs-voeux-54/",
"https://actetre.de/vintage-matchbox-parrot/",
"https://actetre.de/vintage-matchbox-parrot-2/",
"https://actetre.de/chocolate-o-t-hoch/",
"https://actetre.de/chocolate-o-t-hoch-2/",
"https://actetre.de/moka-pot-o-t-hoch/",
"https://actetre.de/moka-pot-o-t-hoch-2/",
"https://actetre.de/girl-eating-doughnuts-o-t-hoch/",
"https://actetre.de/girl-eating-doughnuts-o-t-hoch-2/",
"https://actetre.de/whiskey-glass-o-t-hoch/",
"https://actetre.de/whiskey-glass-o-t-hoch-2/",
"https://actetre.de/fries-with-friends-o-t-hoch/",
"https://actetre.de/fries-with-friends-o-t-hoch-2/",
"https://actetre.de/ice-cream-with-sprinkles-o-t-hoch/",
"https://actetre.de/ice-cream-with-sprinkles-o-t-hoch-2/",
"https://actetre.de/pencils-o-t-hoch/",
"https://actetre.de/pencils-o-t-hoch-2/",
"https://actetre.de/hand-with-ring-o-t-hoch/",
"https://actetre.de/hand-with-ring-o-t-hoch-2/",
"https://actetre.de/girl-on-a-skateboard-o-t-hoch/",
"https://actetre.de/girl-on-a-skateboard-o-t-hoch-2/",
"https://actetre.de/tschuessi-danke-fuer-die-tolle-zeit/",
"https://actetre.de/tschuessi-danke-fuer-die-tolle-zeit-2/",
"https://actetre.de/zum-jubilaeum-herzlichen-glueckwunsch/",
"https://actetre.de/zum-jubilaeum-herzlichen-glueckwunsch-2/",
"https://actetre.de/hip-hip-hurra-herzlichen-glueckwunsch-zum-jubilaeum/",
"https://actetre.de/hip-hip-hurra-herzlichen-glueckwunsch-zum-jubilaeum-2/",
"https://actetre.de/zum-jubilaeum-solls-konfetti-regnen/",
"https://actetre.de/zum-jubilaeum-solls-konfetti-regnen-2/",
"https://actetre.de/auf-ins-naechste-abenteuer/",
"https://actetre.de/auf-ins-naechste-abenteuer-2/",
"https://actetre.de/feierabend-alles-gute-fuer-den-ruhestand/",
"https://actetre.de/feierabend-alles-gute-fuer-den-ruhestand-2/",
"https://actetre.de/neue-wege-entstehen-alles-gute-zum-ruhestand/",
"https://actetre.de/neue-wege-entstehen-alles-gute-zum-ruhestand-2/",
"https://actetre.de/tschuessikowski-du-wirst-uns-fehlen/",
"https://actetre.de/tschuessikowski-du-wirst-uns-fehlen-2/",
"https://actetre.de/rollengeschenkpapier-ditsy-floral-4m/",
"https://actetre.de/rollengeschenkpapier-ditsy-floral-4m-2/",
"https://actetre.de/rollengeschenkpapier-big-floral-4m/",
"https://actetre.de/rollengeschenkpapier-big-floral-4m-2/",
"https://actetre.de/rollengeschenkpapier-spotty-blue-4m/",
"https://actetre.de/rollengeschenkpapier-spotty-blue-4m-2/",
"https://actetre.de/rollengeschenkpapier-spotty-pink-4m/",
"https://actetre.de/rollengeschenkpapier-spotty-pink-4m-2/",
"https://actetre.de/rollengeschenkpapier-birthday-stripes-blue-4m/",
"https://actetre.de/rollengeschenkpapier-birthday-stripes-blue-4m-2/",
"https://actetre.de/rollengeschenkpapier-birthday-stripes-pink-4m/",
"https://actetre.de/rollengeschenkpapier-birthday-stripes-pink-4m-2/",
"https://actetre.de/rollengeschenkpapier-balloons-blue-4m/",
"https://actetre.de/rollengeschenkpapier-balloons-blue-4m-2/",
"https://actetre.de/rollengeschenkpapier-balloons-pink-4m/",
"https://actetre.de/rollengeschenkpapier-balloons-pink-4m-2/",
"https://actetre.de/rollengeschenkpapier-stars-4m/",
"https://actetre.de/rollengeschenkpapier-stars-4m-2/",
"https://actetre.de/rollengeschenkpapier-besotted-hearts-4m/",
"https://actetre.de/rollengeschenkpapier-besotted-hearts-4m-2/",
"https://actetre.de/rollengeschenkpapier-stars-blue-4m/",
"https://actetre.de/rollengeschenkpapier-stars-blue-4m-2/",
"https://actetre.de/rollengeschenkpapier-stars-pink-4m/",
"https://actetre.de/rollengeschenkpapier-stars-pink-4m-2/",
"https://actetre.de/rollengeschenkpapier-dogs-grey-4m/",
"https://actetre.de/rollengeschenkpapier-dogs-grey-4m-2/",
"https://actetre.de/rollengeschenkpapier-cats-pink-4m/",
"https://actetre.de/rollengeschenkpapier-cats-pink-4m-2/",
"https://actetre.de/rollengeschenkpapier-new-baby-4m/",
"https://actetre.de/rollengeschenkpapier-new-baby-4m-2/",
"https://actetre.de/rollengeschenkpapier-large-colour-checks-4m/",
"https://actetre.de/rollengeschenkpapier-large-colour-checks-4m-2/",
"https://actetre.de/rollengeschenkpapier-colour-ric-rac-4m/",
"https://actetre.de/rollengeschenkpapier-colour-ric-rac-4m-2/",
"https://actetre.de/rollengeschenkpapier-bee-meadow-blue-4m/",
"https://actetre.de/rollengeschenkpapier-bee-meadow-blue-4m-2/",
"https://actetre.de/rollengeschenkpapier-bee-meadow-pink-4m/",
"https://actetre.de/rollengeschenkpapier-bee-meadow-pink-4m-2/",
"https://actetre.de/rollengeschenkpapier-stephanies-garden-4m/",
"https://actetre.de/rollengeschenkpapier-stephanies-garden-4m-2/",
"https://actetre.de/rollengeschenkpapier-butterflies-pink-4m/",
"https://actetre.de/rollengeschenkpapier-butterflies-pink-4m-2/",
"https://actetre.de/rollengeschenkpapier-cats-4m/",
"https://actetre.de/rollengeschenkpapier-cats-4m-2/",
"https://actetre.de/rollengeschenkpapier-dogs-4m/",
"https://actetre.de/rollengeschenkpapier-dogs-4m-2/",
"https://actetre.de/rollengeschenkpapier-wedding-cake-4m/",
"https://actetre.de/rollengeschenkpapier-wedding-cake-4m-2/",
"https://actetre.de/rollengeschenkpapier-strawberries-pink-4m/",
"https://actetre.de/rollengeschenkpapier-strawberries-pink-4m-2/",
"https://actetre.de/rollengeschenkpapier-cool-ice-cream-pink-4m/",
"https://actetre.de/rollengeschenkpapier-cool-ice-cream-pink-4m-2/",
"https://actetre.de/rollengeschenkpapier-unicorns-pink-2m/",
"https://actetre.de/rollengeschenkpapier-unicorns-pink-2m-2/",
"https://actetre.de/rollengeschenkpapier-party-jungle-animals-2m/",
"https://actetre.de/rollengeschenkpapier-party-jungle-animals-2m-2/",
"https://actetre.de/rollengeschenkpapier-dinoroar-2m/",
"https://actetre.de/rollengeschenkpapier-dinoroar-2m-2/",
"https://actetre.de/rollengeschenkpapier-superheroes-2m/",
"https://actetre.de/rollengeschenkpapier-superheroes-2m-2/",
"https://actetre.de/rollengeschenkpapier-soccer-2m/",
"https://actetre.de/rollengeschenkpapier-soccer-2m-2/",
"https://actetre.de/rollengeschenkpapier-mermaids-2m/",
"https://actetre.de/rollengeschenkpapier-mermaids-2m-2/",
"https://actetre.de/rollengeschenkpapier-cool-sunshine-and-rainbow-2m/",
"https://actetre.de/rollengeschenkpapier-cool-sunshine-and-rainbow-2m-2/",
"https://actetre.de/vintage-matchbox-beautiful-soul/",
"https://actetre.de/vintage-matchbox-beautiful-soul-2/",
"https://actetre.de/vintage-matchbox-hearts-delight/",
"https://actetre.de/vintage-matchbox-hearts-delight-2/",
"https://actetre.de/norman-parkinson-fresh-colour/",
"https://actetre.de/norman-parkinson-fresh-colour-2/",
"https://actetre.de/norman-parkinson-little-hope-hatch/",
"https://actetre.de/norman-parkinson-little-hope-hatch-2/",
"https://actetre.de/norman-parkinson-model-with-parasol/",
"https://actetre.de/norman-parkinson-model-with-parasol-2/",
"https://actetre.de/norman-parkinson-blackpool-beach-1960/",
"https://actetre.de/norman-parkinson-blackpool-beach-1960-2/",
"https://actetre.de/collage-orama-lion-steampunk/",
"https://actetre.de/collage-orama-lion-steampunk-2/",
"https://actetre.de/tief-im-herzen-bleibt-die-erinnerung-blaetter/",
"https://actetre.de/tief-im-herzen-bleibt-die-erinnerung-blaetter-2/",
"https://actetre.de/auf-den-fluegeln-der-zeit-vogelschwarm/",
"https://actetre.de/auf-den-fluegeln-der-zeit-vogelschwarm-2/",
"https://actetre.de/mein-tiefes-mitgefuehl-abstrakt-aquarell/",
"https://actetre.de/mein-tiefes-mitgefuehl-abstrakt-aquarell-2/",
"https://actetre.de/aufrichtige-anteilnahme-zweig-aquarell/",
"https://actetre.de/aufrichtige-anteilnahme-zweig-aquarell-2/",
"https://actetre.de/tiefes-mitgefuehl-in-dieser-blaetter-und-zweige/",
"https://actetre.de/tiefes-mitgefuehl-in-dieser-blaetter-und-zweige-2/",
"https://actetre.de/ein-stiller-gruss-blaetter-mit-vogel/",
"https://actetre.de/ein-stiller-gruss-blaetter-mit-vogel-2/",
"https://actetre.de/today-is-your-day-kl-gb/",
"https://actetre.de/today-is-your-day-kl-gb-2/",
"https://actetre.de/zum-geburtstag-alles-liebe-kl-gb/",
"https://actetre.de/zum-geburtstag-alles-liebe-kl-gb-2/",
"https://actetre.de/happy-happy-birthday-kl-gb/",
"https://actetre.de/happy-happy-birthday-kl-gb-2/",
"https://actetre.de/machs-gut-kl-gb/",
"https://actetre.de/machs-gut-kl-gb-2/",
"https://actetre.de/alles-gute-zum-geburtstag-kl-gb-3/",
"https://actetre.de/alles-gute-zum-geburtstag-kl-gb-4/",
"https://actetre.de/froehliche-weihnachten-hund-mit-geschenk-wf-mini/",
"https://actetre.de/froehliche-weihnachten-hund-mit-geschenk-wf-mini-2/",
"https://actetre.de/froehliche-weihnachten-baerchen-wf-mini/",
"https://actetre.de/froehliche-weihnachten-baerchen-wf-mini-2/",
"https://actetre.de/froehliche-weihnachten-weisser-fuchs-wf-mini/",
"https://actetre.de/froehliche-weihnachten-weisser-fuchs-wf-mini-2/",
"https://actetre.de/froehliche-weihnachten-bambi-und-seine-freunde-wf-mini/",
"https://actetre.de/froehliche-weihnachten-bambi-und-seine-freunde-wf-mini-2/",
"https://actetre.de/froehliche-weihnachten-katzen-im-oldtimer-wf-mini/",
"https://actetre.de/froehliche-weihnachten-katzen-im-oldtimer-wf-mini-2/",
"https://actetre.de/froehliche-weihnachten-katzen-um-tannenbaum-wf-mini/",
"https://actetre.de/froehliche-weihnachten-katzen-um-tannenbaum-wf-mini-2/",
"https://actetre.de/froehliche-weihnachten-giraffen-wf-mini/",
"https://actetre.de/froehliche-weihnachten-giraffen-wf-mini-2/",
"https://actetre.de/froehliche-weihnachten-tannenbaum-und-spielzeug-wf-mini/",
"https://actetre.de/froehliche-weihnachten-tannenbaum-und-spielzeug-wf-mini-2/",
"https://actetre.de/richard-spare-scent-of-the-lily/",
"https://actetre.de/richard-spare-scent-of-the-lily-2/"];
?>

<?php
$img_link_list = [];
$i = 0;
foreach($list as $singleUrl){
    //if($i > 15)break;
    $domPart = new DOMDocument;
  $domPart->preserveWhiteSpace = false;

  /* loading the file 
  https://actetre-2.enpr.de/wp-content/themes/ACTEtre-WP/categories.xml
  categories-6C80D992-5BF0-896D-938E-E2D4DFF37726.xml (klappkarten Christmas)
  categories-0B2CA26C-57AA-7AC8-3C25-10EFF502C73D.xml (Klappkarten Everyday)
  categories-level0.xml
  */
  $domPart->loadHTMLFile($singleUrl);
  $img = $domPart->getElementsByTagName("img");
    $htmlX = $domPart->saveHTML();




  /* preparing the xpath for the dom doc */
  $xpathPart = new DOMXPath($domPart);

  $queryPart = "//div[@class='productimage']";
  $itemsPart = $xpathPart->query($queryPart);
  ///html/body/main/div/div[1]/div[1]/div

  /* echo "<pre style='color:deeppink;'>".var_export($domPart->getElementsByTagName('img'),true)."</pre>";
  echo "<pre style='color:tomato;'>".var_export($itemsPart,true)."</pre>";
  echo "<pre style='color:tomato;'>".var_export($itemsPart->count(),true)."</pre>";
  for ($i = 0; $i < $img->length; $i++) {
    $nodes = $img->childNodes;
    echo "<pre style='color:tomato;'>".var_export($img,true)."</pre>";

    foreach ($nodes as $node) {
  
            echo $node->nodeType. ":: ";
            echo $node->nodeName. ":: ";
        
            echo $node->nodeValue. "<br>";
        
        
        
    }
  echo "<pre style='color:tomato;'>".var_export($img,true)."</pre>";
    echo $img->item($i)->nodeValue . "\n";
} */
  foreach ($img as $itemX) {  
    
        if(substr($itemX->getAttribute('src'),-3) == "jpg"){
            echo "<pre style='color:blue;'>".var_export($itemX->getAttribute('src'),true)."</pre>";
            $img_link = "https://www.actetre.de/".$itemX->getAttribute('src');
        }
        /* echo "test55555";   
        //echo "<pre style='color:tomato;'>".var_export($itemX->item(),true)."</pre>";   
        $nodes = $itemX->childNodes;
        echo $itemX->nodeType. ": ";
        echo $itemX->nodeName. ": ";

        echo $itemX->nodeValue. "<br>";
        foreach ($nodes as $node) {
                
            
                echo "<pre style='color:blue;'>".var_export($node,true)."</pre>";
                echo $node->nodeType. ":: ";
                echo $node->nodeName. ":: ";
            
                echo $node->nodeValue. "<br>";
            
            
                
            } */
    }
    //file_put_contents(__DIR__.'/'."missing_images.txt",$img_link."\r\n",FILE_APPEND);
    $img_link_list[] = $img_link;


    

$url = $img_link;
// parsed path
    $path = parse_url($url, PHP_URL_PATH);

    // extracted basename
    $filename = basename($path);

    
    $img = __DIR__.'/tmp_images/'.$filename;
    echo $img."<br>";
    file_put_contents($img, file_get_contents($url));
    //$i++;
}

echo "<pre style='color:blue;'>".var_export($img_link_list,true)."</pre>";
    /* foreach ($itemsPart as $item) {  
        echo "test5";      
        $nodes = $item->childNodes;
        echo $item->nodeType. ": ";
        echo $item->nodeName. ": ";
    
        echo $item->nodeValue. "<br>";
        echo "<pre style='color:orange;'>".var_export($item->getElementsByTagName('img'),true)."</pre>";

        /* $items = $item->getElementsByTagName('img');
        for ($i = 0; $i < $items->length; $i++) {
            echo "adsfasdf".$items->item($i)->nodeValue . "\n";
        } /
        foreach ($nodes as $node) {
            
            $nodes2 = $node->childNodes;
            foreach ($nodes2 as $node2) {
                echo "<pre style='color:blue;'>".var_export($node2,true)."</pre>";
                echo $node2->nodeType. ":: ";
                echo $node2->nodeName. ":: ";
            
                echo $node2->nodeValue. "<br>";
            
            }
            
        }

    } */
?>
<script>

/* jQuery( document ).ready(function() {
    var tmp = "https://www.actetre.de"+jQuery(".productimage img").attr('src');
    console.dir(tmp);
}); */
</script>


<?php
get_footer();
?>