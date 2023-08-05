# Plugin Propluvia

Ce plugin permet de remonter les informations du site de l'information sécheresse du Gouvernement [**Propluvia**](https://propluvia.developpement-durable.gouv.fr/propluviapublic/recherche-particulier) via l'API du site https://eau.api.agriculture.gouv.fr.

## Installation
Le plugin s'installe comme n'importe quel autre plugin sur Jeedom.

## Configuration
Une fois installé et activé, sur la page de configuration, vous pouvez définir l'heure à laquelle le plugin ira chercher les information.<br/>
![image](https://github.com/OUARZA/Propluvia/assets/34892335/84857d98-5694-40d4-ad00-e04770220738)

Lancer le plugin qui se trouve dans la catégorie Météo.<br/>
![image](https://github.com/OUARZA/Propluvia/assets/34892335/0db2b09b-a0c6-48fc-99f1-9fa58c3ad5da)

Ajouter un équipement, comme n'importe quel équipement sous Jeedom.
Configurer les paramètres généraux, puis dans les aramètres spécifiques, indiquer le code INSEE de la commune que vous souhaitez consulter. Indiquer quels types de restrictions vous souhaitez suivre (Eaux superficielles, Eaux sonterraines), ainsi que le type éditorial (Particulier, Profesionnel).<br/>
![image](https://github.com/OUARZA/Propluvia/assets/34892335/ddf81407-3b43-45c3-b67f-301f38e4e514)

Sauvegarder.

## Pour aller plus loin
Dans vos scénarios, vous pouvez utiliser comme déclencheur la commande "Niveau restriction zone SUP" et/ou "Niveau restriction zone SOU".
