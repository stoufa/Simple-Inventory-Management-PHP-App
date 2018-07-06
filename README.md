# simple-gestion-stock-php
Simple application web de gestion de stock

Application Web développée avec PHP5, MySQL, HTML5, jQuery
qui permet l'ajout, la suppression, la modification et la recherche des clients, des gammes, des articles, et des gadgets
ainsi que le suivi des mouvements ( réception / livraison des gadgets aux clients avec les quantités respectives )
l'application permet aussi l'impression des données affichés et de télécharger les données sous format XML.

Cette application est faite au cours d'un stage d'été qui a eu lieu pendant le mois de juillet 2014 au sein de la société Adwya.

Le fichier adwya.sql crée les différentes tables de la base de données nommée `adwya` utilisées par l'application
ainsi que les différentes contraintes ( clés primaires et étrangéres ) et ajoute deux utilisateurs pour des raisons de test :
  l'utilisateur `a` ( administrateur ) et l'utilisateur `b` ( utilisateur régulier : Il n'a pas le droit de modifier ou supprimer les données )
  
Le dossier `application/` contient l'application alors que le dossier `screenshots/` contient des captures d'écran de l'application


<p align="center">
  <img src="https://raw.githubusercontent.com/stoufa/simple-gestion-stock-php/master/screenshots/1.png" alt="screenshot"/>
</p>

