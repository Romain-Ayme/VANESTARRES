# VANESTARRES
[Lien du site](http://romain-ayme.alwaysdata.net/)

## Bases  de données
Dans le fichier [VANESTARRE.sql](https://github.com/Romain-Ayme/VANESTARRES/blob/main/VANESTARRE.sql "VANESTARRE.sql") vous trouverez la structure de la bases de données
### Les tables
 - messages 
	 - ID_MESSAGE
	 - ID_USER
	 - DATE_MESS
	 - MESSAGE
	 - IMG
	 - NB_AVANT_DON
	 - DONNE
	 - DON_USER

- message_tags
	- ID_TAG
	- ID_MESSAGE
- notes
	- ID_NOTE
	- ID_MESSAGE
	- ID_USER
	- NOTE
- parametres
	- ID_PARAM
	- N_MSG
	- N_MIN
	- N_MAX
- tags
	- ID_TAG
	- NOM_TAG
- users
	- ID_USER
	- EMAIL
	- PSWD
	- PSEUDO
	- DATE_INS
	- ROLE_USER
	- DELETED

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
