# Qui-Paie-Quoi

Application permettant aux utilisateurs d’entrer des dépenses
partagées et d’obtenir à chaque instant l’information de qui doit quelle somme à qui.

## Scénarios pour réalisaton de l'application

### Scénario 1 :

Participants : Alice, Charles et Camille :

Dépenses :

- Alice achète des bouteilles d’eau pour tout le groupe pour 9 euros
- Charles s’achète un sandwitch à 6 euros
- Charles achètes de la nourriture pour Alice et Camille pour 12 euros
- Camille paie un plein d’essence pour 36 euros pour tout le groupe

Attentes :
Alice soit dans le négatif de -12 euros
Charles soit dans le négatif de 3 euros
Camille soit dans le positif de 15 euros

Et que la proposition réduite des transactions :
Alice doit 12 euros à Camille
Charles doit 3 euros à Camille

### Scénario 2 : Gestion des arrondis

Participants : David, Émilie, et Florence
Dépenses :

- David paie pour le taxi pour le groupe d’un montant de 10 euros

Attentes :
Calculer qui doit quoi à qui, avec des montants qui ne sont pas divisibles de façon égale, pour voir comment l'application gère les arrondis.

### Scénario 3 : Plusieurs Transactions avec le Même Participant

Participants : George, Hélène
Dépenses :

- George achète le petit déjeuner pour 10 euros
- Hélène achète le déjeuner pour George pour 15 euros
- George achète le dîner pour Hélène pour 20 euros

Attentes :
Vérifier que l'application peut suivre plusieurs transactions entre les mêmes personnes et calculer correctement les soldes finaux.

### Scénario 4 : Équilibre Zéro

Participants : Isabelle, Julien, et Léo
Dépenses :

- Chaque participant dépense exactement le même montant pour des activités communes, par exemple 50 euros chacun pour un total de 150 euros.

Attentes :
Vérifier que l'application affiche correctement que personne ne doit rien à personne.

---

Pour installer le projet sur votre machine :

- Installer docker engine
- Clonez le projet github
- Accedez au repertoire du projet : cd ......./Qui-paie-quoi
- Lancer la stack docker : docker compose up
- Installez les dépendances PHP en executant : composer install
- Installez le module PHP php8.3-xml en exécutant la commande : sudo apt install php8.3-xml.

- Vous pouvez lancer votre serveur local en executant : `php -S localhost:3000 -t public`

- Pour populer la base de données : `php App/bin/console app:upsertInDB`

-Pour reset la base de données : `make reset`
