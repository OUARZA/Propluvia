<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class propluvia extends eqLogic {  

  
  
  
  
  /*     * *************************Attributs****************************** */

  /*
  * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
  * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
  public static $_widgetPossibility = array();
  */

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration du plugin
  * Exemple : "param1" & "param2" seront cryptés mais pas "param3"
  public static $_encryptConfigKey = array('param1', 'param2');
  */

  /*     * ***********************Methode static*************************** */

  /*
  * Fonction exécutée automatiquement toutes les minutes par Jeedom
  public static function cron()
  */  

  /*
  * Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
  public static function cron5() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
  public static function cron10() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
  public static function cron15() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
  public static function cron30() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les heures par Jeedom */
  public static function cronHourly() {
    $cronHeure = config::byKey('cronHeure', __CLASS__);
    if (!empty($cronHeure) && date('G') != $cronHeure) return;
 
    foreach (eqLogic::byType(__CLASS__, true) as $propluvia) {
      $propluvia->pullpropluvia();
    }  
  }

  /*
  * Fonction exécutée automatiquement tous les jours par Jeedom
  public static function cronDaily() {}
  */

  /*     * *********************Méthodes d'instance************************* */

  // Fonction exécutée automatiquement avant la création de l'équipement
  public function preInsert() {
  }

  // Fonction exécutée automatiquement après la création de l'équipement
  public function postInsert() {
  }

  // Fonction exécutée automatiquement avant la mise à jour de l'équipement
  public function preUpdate() {
  }

  // Fonction exécutée automatiquement après la mise à jour de l'équipement
  public function postUpdate() {
  }

  // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
  public function preSave() {
    $codeInseeCommune = $this->getConfiguration('codeInseeCommune');
    //récupération nom commune
    $url = 'https://geo.api.gouv.fr/communes?code='.$codeInseeCommune.'&fields=code,nom,contour&format=geojson&geometry=contour';
    $request_http = new com_http($url);
    $request_http->setCURLOPT_HTTPAUTH(CURLAUTH_DIGEST);
    $jsonData=json_decode(trim($request_http->exec()), true);
    if(is_array($jsonData)){
      $nomCommune = $jsonData['features']['0']['properties']['nom'];
      $this->setConfiguration('commune', $nomCommune);
    } else {
      log::add(__CLASS__, 'error', 'Code INSEE de commune ('.$codeInseeCommune.') ivalide');
    }
  }

  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  public function postSave() {
    $info = $this->getCmd(null, 'departement');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Département', __FILE__));
    }
    $info->setLogicalId('departement');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

     $info = $this->getCmd(null, 'commune');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Commune', __FILE__));
    }
    $info->setLogicalId('commune');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $info = $this->getCmd(null, 'numero_arrete');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Numéro arrêté', __FILE__));
    }
    $info->setLogicalId('numero_arrete');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $info = $this->getCmd(null, 'date_debut');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Date début arrêté', __FILE__));
    }
    $info->setLogicalId('date_debut');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $info = $this->getCmd(null, 'date_fin');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Date fin arrêté', __FILE__));
    }
    $info->setLogicalId('date_fin');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $info = $this->getCmd(null, 'type_eau_sup');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Type eau SUP', __FILE__));
    }
    $info->setLogicalId('type_eau_sup');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $info = $this->getCmd(null, 'nom_zone_sup');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Nom eau SUP', __FILE__));
    }
    $info->setLogicalId('nom_zone_sup');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $info = $this->getCmd(null, 'nom_restriction_sup');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Nom restriction zone SUP', __FILE__));
    }
    $info->setLogicalId('nom_restriction_sup');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $info = $this->getCmd(null, 'niveau_restriction_sup');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Niveau restriction zone SUP', __FILE__));
    }
    $info->setLogicalId('niveau_restriction_sup');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $info = $this->getCmd(null, 'editorial_zone_sup');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Editorial zone SUP', __FILE__));
    }
    $info->setLogicalId('editorial_zone_sup');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $info = $this->getCmd(null, 'type_eau_sou');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Type eau SOU', __FILE__));
    }
    $info->setLogicalId('type_eau_sou');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $info = $this->getCmd(null, 'nom_zone_sou');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Nom eau SOU', __FILE__));
    }
    $info->setLogicalId('nom_zone_sou');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $info = $this->getCmd(null, 'nom_restriction_sou');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Nom restriction zone SOU', __FILE__));
    }
    $info->setLogicalId('nom_restriction_sou');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $info = $this->getCmd(null, 'niveau_restriction_sou');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Niveau restriction zone SOU', __FILE__));
    }
    $info->setLogicalId('niveau_restriction_sou');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $info = $this->getCmd(null, 'editorial_zone_sou');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Editorial zone SOU', __FILE__));
    }
    $info->setLogicalId('editorial_zone_sou');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $refresh = $this->getCmd(null, 'refresh');
    if (!is_object($refresh)) {
      $refresh = new propluviaCmd();
      $refresh->setName(__('Rafraichir', __FILE__));
    }
    $refresh->setEqLogic_id($this->getId());
    $refresh->setLogicalId('refresh');
    $refresh->setType('action');
    $refresh->setSubType('other');
    $refresh->save();
  }

  public function pullpropluvia() {
    $date = date('Y-m-d');
    $codeInseeCommune = $this->getConfiguration('codeInseeCommune');
    $typeInfo = $this->getConfiguration('typeInfo','');
    if (!empty($typeInfo)){
      $typeInfo = '_'.$typeInfo;
    } else {
      $typeInfo = '';
    }
    $trans = array(" " => "_", "é" => "e", "è" => "e");
    log::add(__CLASS__, 'debug', '*********** PROPLUVIA ***********');
    
    //récupération nom commune
    $url = 'https://geo.api.gouv.fr/communes?code='.$codeInseeCommune.'&fields=code,nom,contour&format=geojson&geometry=contour';
    $request_http = new com_http($url);
    $request_http->setCURLOPT_HTTPAUTH(CURLAUTH_DIGEST);
    $jsonData=json_decode(trim($request_http->exec()), true);
    if(is_array($jsonData)){
      $nomCommune = $jsonData['features']['0']['properties']['nom'];
    } else {
      $nomCommune = 'commune invalide';
      log::add(__CLASS__, 'error', 'Code INSEE de commune ('.$codeInseeCommune.') ivalide');
    }

    //récupération info arrêté
    $url = 'https://eau.api.agriculture.gouv.fr/apis/propluvia/arretes/'.$date.'/commune/'.$codeInseeCommune;
    $request_http = new com_http($url);
    $request_http->setCURLOPT_HTTPAUTH(CURLAUTH_DIGEST);
    $jsonData=json_decode(trim($request_http->exec()), true);
    if(is_array($jsonData)){
      //vérifie qu'un arrêté existe
      if ($jsonData['message'] != NULL) {
        log::add(__CLASS__, 'error', 'Aucun arrêté trouvé à la date du '.$date. ' pour la commune '.$nomCommune);

        // mise à jour des commandes
        $this->checkAndUpdateCmd('departement', '');
        $this->checkAndUpdateCmd('numero_arrete', '');
        $this->checkAndUpdateCmd('date_debut', 'Aucun arrêté trouvé à la date du '.$date);
        $this->checkAndUpdateCmd('date_fin', '');
        $this->checkAndUpdateCmd('commune', $nomCommune);
        $this->checkAndUpdateCmd('nom_zone_sup', '');
        $this->checkAndUpdateCmd('niveau_restriction_sup', '');
        $this->checkAndUpdateCmd('nom_restriction_sup', '');      
        $this->checkAndUpdateCmd('editorial_zone_sup', '');      
        $this->checkAndUpdateCmd('nom_zone_sou', '');
        $this->checkAndUpdateCmd('niveau_restriction_sou', '');
        $this->checkAndUpdateCmd('nom_restriction_sou', '');      
        $this->checkAndUpdateCmd('editorial_zone_sou', '');      

      } else {
        $codeInseeDepartement = $jsonData[0]['codeInseeDepartement'];
        $dateDebutValiditeArrete = date("d/m/Y",strtotime($jsonData[0]['dateDebutValiditeArrete']));
        $dateFinValiditeArrete = date("d/m/Y",strtotime($jsonData[0]['dateFinValiditeArrete']));
        $numeroArrete = $jsonData[0]['numeroArrete'];
        //mise à jour des commandes et log avec info arrêté
      	log::add(__CLASS__, 'debug', 'Département            : '.$codeInseeDepartement);
        log::add(__CLASS__, 'debug', 'Numéro arrêté          : '.$numeroArrete);
        log::add(__CLASS__, 'debug', 'Début validité arrêté  : '.$dateDebutValiditeArrete);
        log::add(__CLASS__, 'debug', 'Fin validité arrêté    : '.$dateFinValiditeArrete);
        log::add(__CLASS__, 'debug', 'Commune                : '.$nomCommune);

        $this->checkAndUpdateCmd('departement', $codeInseeDepartement);
        $this->checkAndUpdateCmd('numero_arrete', $numeroArrete);
        $this->checkAndUpdateCmd('date_debut', $dateDebutValiditeArrete);
        $this->checkAndUpdateCmd('date_fin', $dateFinValiditeArrete);
        $this->checkAndUpdateCmd('commune', $nomCommune);

        //balayage des zones
        foreach ($jsonData[0]['restrictions'] as $value=>$jsonKey) {       
          $niveauRestriction = $jsonKey['niveauRestriction'];
          $nomNiveau = $jsonKey['nomNiveau'];
          $nomZone =  $jsonKey['zoneAlerte']['nomZone'];
          $typeZone = $jsonKey['zoneAlerte']['typeZone'];

          //balayage des communes pour récupérer uniquement info de celle recherchée
          foreach ($jsonKey['zoneAlerte']['communes'] as $value2=>$jsonKey2) {
            if ( $jsonKey2['codeInseeCommune'] == $codeInseeCommune) {
              //recupération info détaillé sur le niveau d'alerte
              $url_legende = 'https://eau.api.agriculture.gouv.fr/apis/propluvia/editoriaux/?idEditorial=legende_'.strtr(strtolower($nomNiveau),$trans).$typeInfo;   
              $request_http_legende = new com_http($url_legende);
              $request_http_legende->setCURLOPT_HTTPAUTH(CURLAUTH_DIGEST);
              $legende=json_decode(trim($request_http_legende->exec()), true);
              if(is_array($legende)){
                  $contenuEditorial = $legende[0]['contenuEditorial'];
              } else {
                  $contenuEditorial = 'Aucune information disponible';
              }

              //affichage résultat dans le log
              log::add(__CLASS__, 'debug', '----------'.strtoupper($nomZone.' ['.$typeZone.']').'----------');
              log::add(__CLASS__, 'debug', 'Niveau >> '.$nomNiveau.' ('.$niveauRestriction.')');
              log::add(__CLASS__, 'debug', $contenuEditorial);

              //mise à jour des commmandes
              if ($typeZone == 'SUP') {
                $this->checkAndUpdateCmd('nom_zone_sup', $nomZone);
                $this->checkAndUpdateCmd('niveau_restriction_sup', $niveauRestriction);
                $this->checkAndUpdateCmd('nom_restriction_sup', $nomNiveau);      
                $this->checkAndUpdateCmd('editorial_zone_sup', $contenuEditorial);      
              } elseif ($typeZone == 'SOU') {
                $this->checkAndUpdateCmd('nom_zone_sou', $nomZone);
                $this->checkAndUpdateCmd('niveau_restriction_sou', $niveauRestriction);
                $this->checkAndUpdateCmd('nom_restriction_sou', $nomNiveau);      
                $this->checkAndUpdateCmd('editorial_zone_sou', $contenuEditorial);      
              } else {
                $this->checkAndUpdateCmd('nom_zone_sup', '');
                $this->checkAndUpdateCmd('niveau_restriction_sup', '');
                $this->checkAndUpdateCmd('nom_restriction_sup', '');      
                $this->checkAndUpdateCmd('editorial_zone_sup', '');      
                $this->checkAndUpdateCmd('nom_zone_sou', '');
                $this->checkAndUpdateCmd('niveau_restriction_sou', '');
                $this->checkAndUpdateCmd('nom_restriction_sou', '');      
                $this->checkAndUpdateCmd('editorial_zone_sou', '');      
              }  
            }
          }
        }
      }
    }
  }
  
  
  // Fonction exécutée automatiquement avant la suppression de l'équipement
  public function preRemove() {
  }

  // Fonction exécutée automatiquement après la suppression de l'équipement
  public function postRemove() {
  }

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration des équipements
  * Exemple avec le champ "Mot de passe" (password)
  public function decrypt() {
    $this->setConfiguration('password', utils::decrypt($this->getConfiguration('password')));
  }
  public function encrypt() {
    $this->setConfiguration('password', utils::encrypt($this->getConfiguration('password')));
  }
  */

  /*
  * Permet de modifier l'affichage du widget (également utilisable par les commandes)
  public function toHtml($_version = 'dashboard') {}
  */

  /*
  * Permet de déclencher une action avant modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function preConfig_param3( $value ) {
    // do some checks or modify on $value
    return $value;
  }
  */

  /*
  * Permet de déclencher une action après modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function postConfig_param3($value) {
    // no return value
  }
  */

  /*     * **********************Getteur Setteur*************************** */

}

class propluviaCmd extends cmd {
  /*     * *************************Attributs****************************** */

  /*
  public static $_widgetPossibility = array();
  */

  /*     * ***********************Methode static*************************** */


  /*     * *********************Methode d'instance************************* */

  /*
  * Permet d'empêcher la suppression des commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
  public function dontRemoveCmd() {
    return true;
  }
  */

  // Exécution d'une commande
  public function execute($_options = array()) {
  	$eqlogic = $this->getEqLogic(); //récupère l'éqlogic de la commande $this
  	switch ($this->getLogicalId()) { //vérifie le logicalid de la commande
    	case 'refresh': // LogicalId de la commande rafraîchir que l’on a créé dans la méthode Postsave de la classe vdm .
    	$eqlogic->pullpropluvia();
     	break;
  	}
  }

  /*     * **********************Getteur Setteur*************************** */

}
