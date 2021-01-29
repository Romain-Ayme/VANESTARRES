# VANESTARRES
## Membre
- Ayme Romain
- Charrat Romain
- Drets Lilian
## [Lien du site](http://romain-ayme.alwaysdata.net/)
## Intro
On devait faire un réseau social pour Vanéstarre.
50 caractères maximum par message.
Pouvoir taguer des messages avec ß.
Faire une recherche par tag.
Pouvoir mètre des interactions sur les messages

## Login
|Login		|Email				|Mot de passe	|
|---------------|-------------------------------|---------------|
|Vanéstarre	|vane@star.re			|Kiwi		|
|romain_ayme	|romain-ayme@alwaysdata.net	|Kiwi		|
|Lilian		|parmenio.abz@gmail.com		|drets		|
|Rauren		|rauren@hotmail.fr		|rauren		|
|marlin		|marlin.casalporte@hotmail.fr	|qtcreator	|
|Jean-Hugues	|Jean-Hugues@gmail.com		|jesuisunmdp	|

## Bases  de données
Dans le fichier [VANESTARRE.sql](https://github.com/Romain-Ayme/VANESTARRES/blob/main/VANESTARRE.sql "VANESTARRE.sql") vous trouverez la structure de la bases de données
### Les tables
 - messages 
	 - ID_MESSAGE = id du message
	 - ID_USER = id de l'utilisateur
	 - DATE_MESS = date du message 
	 - MESSAGE = contenu du message
	 - IMG = lien vers l'image
	 - NB_AVANT_DON = Nombre avant don
	 - DONNE = Si le nombre de love a étai atteint 
	 - DON_USER = utilisateur qui à fais le don

- message_tags
	- ID_TAG = id du tag
	- ID_MESSAGE = id du message
- notes
	- ID_NOTE = id de la réaction
	- ID_MESSAGE = id du message
	- ID_USER = id de l'utilisateur
	- NOTE = type de réaction
- parametres
	- ID_PARAM = id du paramètre
	- N_MSG = Nombre de message par page
	- N_MIN = Nombre minimal avant don
	- N_MAX = Nombre maximal avant don
- tags
	- ID_TAG = id du tag
	- NOM_TAG = nom du tag
- users
	- ID_USER = id de l'utilisateur
	- EMAIL = email de l’utilisateur
	- PSWD = mot de passe de l’utilisateur
	- PSEUDO = pseudo de l’utilisateur
	- DATE_INS = date de création du compte
	- ROLE_USER = role de l’utilisateur
	- DELETED = status du compte

## Login et Mot de passe
Les accès sont uniquement en lecture seule, vous pouvez envoyer un mail à romain.ayme.1@etu.univ-amu.fr pour avoir un compte admin si nécessité.
### SQL
#### Hôte MySQL
	mysql-romain-ayme.alwaysdata.net
#### Utilisateur
	223609_guest
#### Mot de passe
	q3SS7Ec9h
### SSH
#### Hôte SSH
	ssh-romain-ayme.alwaysdata.net
#### Utilisateur
	romain-ayme_guest
#### Mot de passe
	7Z28jLbWe
