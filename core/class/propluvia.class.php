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
  * Fonction exécutée automatiquement toutes les minutes par Jeedom */
  public static function cron()
  {
    $cronMinute = config::byKey('cronHeure', __CLASS__);
    if (!empty($cronHeure) && date('h') != $cronHeure) return;

    $eqLogics = self::byType(__CLASS__, true);

    foreach ($eqLogics as $eqLogic)
    {
      if (date('G') < 4 || date('G') >= 22)
      {
        if ($eqLogic->getCache('getpropluviaData') == 'done') {
          $eqLogic->setCache('getpropluviaData', null);
        }
        return;
      }

      if ($eqLogic->getCache('getpropluviaData') != 'done')
      {
        $eqLogic->pullpropluvia();
      }
    }
  }
  

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
  * Fonction exécutée automatiquement toutes les heures par Jeedom
  public static function cronHourly() {}
  */

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
    
  $info = $this->getCmd(null, 'arrete_sup');
  if (!is_object($info)) {
    $info = new propluviaCmd();
    $info->setName(__('Arrêté SUP', __FILE__));
  }
  $info->setLogicalId('arrete_sup');
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
  }

  /*     * **********************Getteur Setteur*************************** */

}
