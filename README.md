# **ZENHEALTH \- Gestion d'Instituts de Beauté**

MILOIKOVITCH Maxime e68712u  
MARCOLE Matteo e26462u

[https://github.com/Maxime0306/ZENHEALTH.git](https://github.com/Maxime0306/ZENHEALTH.git) 

Ce projet est une application de gestion de base de données pour l'institut **ZENHEALTH**. Elle permet de gérer les réservations de cabines, les commandes de services et l'administration par les gestionnaires.

## **Installation et Lancement**

### **1\. Prérequis**

* **Serveur Web** : Apache ou XAMPP.  
* **Base de données** : MariaDB ou MySQL.  
* **PHP**.  
* **Composer**.

### **2\. Configuration de la base de données**

1. Importez le fichier zenhealth.sql dans mysql/phpMyAdmin.

2. Configurez le fichier src/conf/conf.ini avec vos identifiants :

driver=mysql  
host=localhost  
database=zenhealth  
username=root  
password=root  
charset=utf8  
collation=utf8\_unicode\_ci  
prefix=

### **3\. Installation des dépendances**

Ouvrez un terminal à la racine du projet et exécutez :

composer install  
composer dump-autoload

## **Utilisation des Fonctionnalités**

### **1\. Connexion**

* **Accès** sur index.php.

* **Identifiants** : Utilisez l'email et le mot de passe d'une hôtesse ou d'une gestionnaire présents dans la table hotesse.

* **Sécurité** : Les mots de passe sont hachés.

  Exemple: [user1@mail.com](mailto:user1@mail.com) mdp: User1

### **2\. Espace Hôtesse**

Toute hôtesse connectée peut effectuer les opérations suivantes :

* **Réserver une cabine**: Permet de choisir une cabine, un créneau et un nombre de personnes. Vérifie si la cabine est disponible pour éviter les doublons.

* **Commander des services** : Permet d'ajouter des soins à une réservation existante.

### **3\. Espace Gestionnaire**

Les utilisatrices ayant le grade gestionnaire disposent de droits supplémentaires:

* **Affectation** : Affecte une hôtesse à une ou plusieurs cabines pour toute une journée.

* **Annulation** : Supprime une réservation non consommée. Cette action recrédite automatiquement les stocks de services annulés.

* **Modifier les tarifs** : Permet de changer le prix ou le nombre d'interventions maximum d'un service.

* **Encaissement**  : Calcule le montant total des services, enregistre le mode de paiement et valide la transaction avec la date et l'heure.

## **Détails Techniques**

* **Mode Transactionnel** : Toutes les opérations sensibles (réservation, commande, encaissement) utilisent des transactions SQL (DB::beginTransaction()) pour garantir l'intégrité des données en cas d'accès simultanés.

* **Contraintes d'intégrité** : La base de données gère les clés étrangères pour empêcher la suppression accidentelle de données liées.

## **Contenu du Rendu**

1. **Code Source** : Tous les fichiers PHP et modèles.

2. **SQL** : Script de création des tables et jeu de données.

3. **Documentation** : Ce fichier Read.me. 
