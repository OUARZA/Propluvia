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
      sleep(15);
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
    $url = 'https://geo.api.gouv.fr/communes?code='.$codeInseeCommune.'&fields=code,nom,departement';
    $request_http = new com_http($url);
    $request_http->setCURLOPT_HTTPAUTH(CURLAUTH_DIGEST);
    $jsonData=json_decode(trim($request_http->exec()), true);
    if(is_array($jsonData)){
      $nomCommune = $jsonData['0']['nom'];
      $nomDepartement = $jsonData['0']['departement']['nom'];
      $this->setConfiguration('commune', $nomCommune);
      $this->setConfiguration('departement', $nomDepartement);
    } else {
      log::add(__CLASS__, 'error', 'Code INSEE de commune ('.$codeInseeCommune.') invalide');
    }
  }

  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  public function postSave() {
    $typeRestriction = $this->getConfiguration('typeRestriction');

    $info = $this->getCmd(null, 'departement');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Département', __FILE__));
    }
    $info->setLogicalId('departement');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setOrder(1);
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
    $info->setOrder(2);
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
    $info->setOrder(4);
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
    $info->setOrder(5);
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
    $info->setOrder(6);
    $info->save();
    
    $info = $this->getCmd(null, 'urlPdf');
    if (!is_object($info)) {
      $info = new propluviaCmd();
      $info->setName(__('Url arrêté en pdf', __FILE__));
    }
    $info->setLogicalId('urlPdf');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setOrder(7);
    $info->save();
    
    //commandes pour la zone d'eau superficielle
    if ($typeRestriction == 'sup' || $typeRestriction == 'all') {
      $info = $this->getCmd(null, 'nom_zone_sup');
      if (!is_object($info)) {
        $info = new propluviaCmd();
        $info->setName(__('Nom zone SUP', __FILE__));
      }
      $info->setLogicalId('nom_zone_sup');
      $info->setEqLogic_id($this->getId());
      $info->setType('info');
      $info->setSubType('string');
      $info->setOrder(8);
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
      $info->setOrder(9);
      $info->save();

      $info = $this->getCmd(null, 'niveau_restriction_sup');
      if (!is_object($info)) {
        $info = new propluviaCmd();
        $info->setName(__('Niveau restriction zone SUP', __FILE__));
      }
      $info->setLogicalId('niveau_restriction_sup');
      $info->setEqLogic_id($this->getId());
      $info->setType('info');
      $info->setSubType('numeric');
      $info->setOrder(10);
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
      $info->setOrder(11);
      $info->save();
      
	  if ($typeRestriction == 'sup') {
        $info = $this->getCmd(null, 'nom_zone_sou');
        if (is_object($info)) {
          $info->remove();
        }

        $info = $this->getCmd(null, 'nom_restriction_sou');
        if (is_object($info)) {
          $info->remove();
        }

        $info = $this->getCmd(null, 'niveau_restriction_sou');
        if (is_object($info)) {
          $info->remove();
        }

        $info = $this->getCmd(null, 'editorial_zone_sou');
        if (is_object($info)) {
          $info->remove();
        }
      }
    }
  
    //commandes pour la zone d'eau souterraine
    if ($typeRestriction == 'sou' || $typeRestriction == 'all') {
      $info = $this->getCmd(null, 'nom_zone_sou');
      if (!is_object($info)) {
        $info = new propluviaCmd();
        $info->setName(__('Nom zone SOU', __FILE__));
      }
      $info->setLogicalId('nom_zone_sou');
      $info->setEqLogic_id($this->getId());
      $info->setType('info');
      $info->setSubType('string');
      $info->setOrder(12);
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
      $info->setOrder(13);
      $info->save();

      $info = $this->getCmd(null, 'niveau_restriction_sou');
      if (!is_object($info)) {
        $info = new propluviaCmd();
        $info->setName(__('Niveau restriction zone SOU', __FILE__));
      }
      $info->setLogicalId('niveau_restriction_sou');
      $info->setEqLogic_id($this->getId());
      $info->setType('info');
      $info->setSubType('numeric');
      $info->setOrder(14);
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
      $info->setOrder(15);
      $info->save();
    	  
      if ($typeRestriction == 'sou') {
        $info = $this->getCmd(null, 'nom_zone_sup');
        if (is_object($info)) {
          $info->remove();
        }

        $info = $this->getCmd(null, 'nom_restriction_sup');
        if (is_object($info)) {
          $info->remove();
        }

        $info = $this->getCmd(null, 'niveau_restriction_sup');
        if (is_object($info)) {
          $info->remove();
        }

        $info = $this->getCmd(null, 'editorial_zone_sup');
        if (is_object($info)) {
          $info->remove();
        }
      }
    }

    $refresh = $this->getCmd(null, 'refresh');
    if (!is_object($refresh)) {
      $refresh = new propluviaCmd();
      $refresh->setName(__('Rafraichir', __FILE__));
    }
    $refresh->setEqLogic_id($this->getId());
    $refresh->setLogicalId('refresh');
    $refresh->setType('action');
    $refresh->setSubType('other');
    $refresh->setOrder(99);
    $refresh->save();
  }

  public function pullpropluvia() {
    $date = date('Y-m-d');
	$dateFormat = date('d/m/Y');
    $codeInseeCommune = $this->getConfiguration('codeInseeCommune');
    $typeInfo = $this->getConfiguration('typeInfo');
    $typeRestriction = $this->getConfiguration('typeRestriction');
    if (!empty($typeInfo)){
      $typeInfo = '_'.$typeInfo;
    } else {
      $typeInfo = '';
    }
    $trans = array(" " => "_", "é" => "e", "è" => "e");
    $eqName = $this->getName();
    log::add(__CLASS__, 'debug', ' ');
    log::add(__CLASS__, 'debug', '*********** PROPLUVIA ['.$eqName.'] ***********');
    
    //récupération nom commune
    $url = 'https://geo.api.gouv.fr/communes?code='.$codeInseeCommune.'&fields=code,nom,departement';
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
 	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);   
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);         
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	$response = curl_exec($ch);
	curl_close($ch);
  	$jsonData = json_decode($response, true);  
    
    if(is_array($jsonData)){
      $nomCommune = $jsonData['0']['nom'];
    } else {
      $nomCommune = 'commune invalide';
      log::add(__CLASS__, 'error', 'Code INSEE de commune ('.$codeInseeCommune.') invalide');
    }

    //récupération info arrêté
    $url = 'https://eau.api.agriculture.gouv.fr/apis/propluvia/arretes/'.$date.'/commune/'.$codeInseeCommune;
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
 	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);   
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);         
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	$response = curl_exec($ch);
	curl_close($ch);
  	$jsonData = json_decode($response, true);  
     
    if(!is_array($jsonData)){
    	log::add(__CLASS__, 'error', 'le site \'https://eau.api.agriculture.gouv.fr\' renvoie une erreur ou n\'est pas accessible');        
    } else {      
      //sauvegarde date et heure de récupérations des info Propluvia
      $this->setConfiguration('lastActuPropluvia', time())->save();
      //vérifie qu'un arrêté existe
      if ($jsonData['message'] != NULL) {
        log::add(__CLASS__, 'info', 'Aucun arrêté trouvé à la date du '.$dateFormat. ' pour la commune '.$nomCommune);

        // mise à jour des commandes
        $this->checkAndUpdateCmd('departement', substr($codeInseeCommune, 0, 2));
        $this->checkAndUpdateCmd('numero_arrete', 'Aucun arrêté trouvé à la date du '.$dateFormat);
        $this->checkAndUpdateCmd('date_debut', '');
        $this->checkAndUpdateCmd('date_fin', '');
        $this->checkAndUpdateCmd('commune', $nomCommune);
        $this->checkAndUpdateCmd('nom_zone_sup', '');
        $this->checkAndUpdateCmd('niveau_restriction_sup', 0);
        $this->checkAndUpdateCmd('nom_restriction_sup', '');      
        $this->checkAndUpdateCmd('editorial_zone_sup', '');      
        $this->checkAndUpdateCmd('nom_zone_sou', '');
        $this->checkAndUpdateCmd('niveau_restriction_sou', 0);
        $this->checkAndUpdateCmd('nom_restriction_sou', '');      
        $this->checkAndUpdateCmd('editorial_zone_sou', '');
        $this->checkAndUpdateCmd('urlPdf', '');
        
      } else {
        $codeInseeDepartement = $jsonData[0]['codeInseeDepartement'];
        $dateDebutValiditeArrete = date("d/m/Y",strtotime($jsonData[0]['dateDebutValiditeArrete']));
        $dateFinValiditeArrete = date("d/m/Y",strtotime($jsonData[0]['dateFinValiditeArrete']));
        $numeroArrete = $jsonData[0]['numeroArrete'];
        $urlPdf = 'https://eau.api.agriculture.gouv.fr/apis/propluvia/file/pdf/'.$jsonData[0]['fdCdn'];
        //mise à jour des commandes et log avec info arrêté
      	log::add(__CLASS__, 'debug', 'Département            : '.$codeInseeDepartement);
        log::add(__CLASS__, 'debug', 'Numéro arrêté          : '.$numeroArrete);
        log::add(__CLASS__, 'debug', 'Début validité arrêté  : '.$dateDebutValiditeArrete);
        log::add(__CLASS__, 'debug', 'Fin validité arrêté    : '.$dateFinValiditeArrete);
        log::add(__CLASS__, 'debug', 'Commune                : '.$nomCommune);
        log::add(__CLASS__, 'debug', 'url pdf arrêté         : '.$urlPdf);

        $this->checkAndUpdateCmd('departement', $codeInseeDepartement);
        $this->checkAndUpdateCmd('numero_arrete', $numeroArrete);
        $this->checkAndUpdateCmd('date_debut', $dateDebutValiditeArrete);
        $this->checkAndUpdateCmd('date_fin', $dateFinValiditeArrete);
        $this->checkAndUpdateCmd('commune', $nomCommune);
        $this->checkAndUpdateCmd('urlPdf', $urlPdf);
                
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
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url_legende);
              curl_setopt($ch, CURLOPT_HEADER, false);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);   
              curl_setopt($ch, CURLOPT_TIMEOUT, 15);         
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
              curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
              $response = curl_exec($ch);
              curl_close($ch);
              $legende = json_decode($response, true);  
            
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
              if ($typeZone == 'SUP' && ($typeRestriction == 'sup' || $typeRestriction == 'all')) {
                $this->checkAndUpdateCmd('nom_zone_sup', $nomZone);
                $this->checkAndUpdateCmd('niveau_restriction_sup', $niveauRestriction);
                $this->checkAndUpdateCmd('nom_restriction_sup', $nomNiveau);      
                $this->checkAndUpdateCmd('editorial_zone_sup', $contenuEditorial);
              }
              if ($typeZone == 'SOU' && ($typeRestriction == 'sou' || $typeRestriction == 'all')) {
                $this->checkAndUpdateCmd('nom_zone_sou', $nomZone);
                $this->checkAndUpdateCmd('niveau_restriction_sou', $niveauRestriction);
                $this->checkAndUpdateCmd('nom_restriction_sou', $nomNiveau);      
                $this->checkAndUpdateCmd('editorial_zone_sou', $contenuEditorial);      
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

  /** Permet de modifier l'affichage du widget (également utilisable par les commandes)*/
  public function toHtml($_version = 'dashboard') {
  	$typeRestriction = $this->getConfiguration('typeRestriction'); //récupération de la valeur pour afficher le bon template
    /* 
    // a n'utiliser que si dans la config de l'eqLogic, on laisse le choix a l'user d'utiliser le widget du plugin, ou les widget par défaut du core
     if ($this->getConfiguration('widgetTemplate') != 1) {
      return parent::toHtml($_version);
    } 
    */
    $this->emptyCacheWidget(); // a utiliser qu'en environnement de dev.
    $replace = $this->preToHtml($_version); // initialise les tag standards : #id#, #name# ...

    if (!is_array($replace)) {
      return $replace;
    }

    $version = jeedom::versionAlias($_version);

    foreach ($this->getCmd('info') as $cmd) { // recherche toute les cmd de type info
      $replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd(); //initialise les tag en fonction du logicalId
      //gestion de l'affichage de l'échelle en couleurs
      $replace['#nom_restriction_sou_N1#'] = '';
      $replace['#nom_restriction_sou_N2#'] = '';
      $replace['#nom_restriction_sou_N3#'] = '';
      $replace['#nom_restriction_sou_N4#'] = '';
      $replace['#nom_restriction_sou_N5#'] = '';

      switch ($replace['#niveau_restriction_sou#']) {
        case 0:
          $replace['#nom_restriction_sou_N1#'] = '<center><i class="fab fa-mixer"></i></center>';
          break;
        case 1:
          $replace['#nom_restriction_sou_N2#'] = '<center><i class="fab fa-mixer"></i></center>';
          break;
        case 3:
          $replace['#nom_restriction_sou_N3#'] = '<center><i class="fab fa-mixer"></i></center>';
          break;
        case 4:
          $replace['#nom_restriction_sou_N4#'] = '<center><i class="fab fa-mixer"></i></center>';
          break;
        case 5:
          $replace['#nom_restriction_sou_N5#'] = '<center><i class="fab fa-mixer"></i></center>';
          break;
      }

      $replace['#nom_restriction_sup_N1#'] = '';
      $replace['#nom_restriction_sup_N2#'] = '';
      $replace['#nom_restriction_sup_N3#'] = '';
      $replace['#nom_restriction_sup_N4#'] = '';
      $replace['#nom_restriction_sup_N5#'] = '';

      switch ($replace['#niveau_restriction_sup#']) {
        case 0:
          $replace['#nom_restriction_sup_N1#'] = '<center><i class="fab fa-mixer"></i></center>';
          break;
        case 1:
          $replace['#nom_restriction_sup_N2#'] = '<center><i class="fab fa-mixer"></i></center>';
          break;
        case 3:
          $replace['#nom_restriction_sup_N3#'] = '<center><i class="fab fa-mixer"></i></center>';
          break;
        case 4:
          $replace['#nom_restriction_sup_N4#'] = '<center><i class="fab fa-mixer"></i></center>';
          break;
        case 5:
          $replace['#nom_restriction_sup_N5#'] = '<center><i class="fab fa-mixer"></i></center>';
          break;
      }

    }    
    $lastActuPropluvia = $this->getConfiguration('lastActuPropluvia','');
    $replace['#lastActuPropluvia#'] = 'Données PROPLUVIA importées le '.date('d/m/Y à H:i:s', $lastActuPropluvia);

    /* plusieurs lignes séparées pour comprendre */
    if ($typeRestriction == 'sup') {
      $getTemplate = getTemplate('core', $version, 'propluvia_sup.template', __CLASS__); // on récupère le template 'propluvia.template' du plugin.
      $template_replace = template_replace($replace, $getTemplate); // on remplace les tags
      $postToHtml = $this->postToHtml($_version,$template_replace); // on met en cache le widget, si la config de l'user le permet.
      return $postToHtml; // renvoie le code du template du widget.
    }
    if ($typeRestriction == 'sou') {
      $getTemplate = getTemplate('core', $version, 'propluvia_sou.template', __CLASS__); // on récupère le template 'propluvia.template' du plugin.
      $template_replace = template_replace($replace, $getTemplate); // on remplace les tags
      $postToHtml = $this->postToHtml($_version,$template_replace); // on met en cache le widget, si la config de l'user le permet.
      return $postToHtml; // renvoie le code du template du widget.
    }
    if ($typeRestriction == 'all') {
      $getTemplate = getTemplate('core', $version, 'propluvia_all.template', __CLASS__); // on récupère le template 'propluvia.template' du plugin.
      $template_replace = template_replace($replace, $getTemplate); // on remplace les tags
      $postToHtml = $this->postToHtml($_version,$template_replace); // on met en cache le widget, si la config de l'user le permet.
      return $postToHtml; // renvoie le code du template du widget.
    }
  
  /* 
  // Ces 4 lignes ci-dessus peuvent être concaténer comme ceci : 
  return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'propluvia.template' , __CLASS__)));
  */
  
  }

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
