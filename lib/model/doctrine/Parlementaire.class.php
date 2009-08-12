<?php

require_once "myTools.class.php";

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Parlementaire extends BaseParlementaire
{

  /*  public function save() {
    Doctrine::getTable('Personnalite')->hasChanged();
    return parent::save($conn);
    }*/

  public function setCirconscription($str) {
    if (preg_match('/(.*)\((\d+)/', $str, $match)) {
      $this->nom_circo = trim($match[1]);
      $this->num_circo = $match[2];
    }
  }

  public function getCirconscription() {
    $hashmap = array(
     "Ain" => "de l'",
     "Aisne" => "de l'",
     "Allier" => "de l'",
     "Alpes-de-Haute-Provence" => "des",
     "Alpes-Maritimes" => "des",
     "Ardèche" => "de l'",
     "Ardennes" => "des",
     "Ariège" => "d'",
     "Aube" => "de l'",
     "Aude" => "de l'",
     "Aveyron" => "de l'",
     "Bas-Rhin" => "du",
     "Bouches-du-Rhône" => "des",
     "Calvados" => "du",
     "Cantal" => "du",
     "Charente" => "de",
     "Charente-Maritime" => "de",
     "Cher" => "du",
     "Corrèze" => "de",
     "Corse-du-Sud" => "de",
     "Côte-d'Or" => "de",
     "Côtes-d'Armor" => "des",
     "Creuse" => "de la",
     "Deux-Sèvres" => "des",
     "Dordogne" => "de la",
     "Doubs" => "du",
     "Drôme" => "de la",
     "Essonne" => "de l'",
     "Eure" => "de l'",
     "Eure-et-Loir" => "d'",
     "Finistère" => "du",
     "Gard" => "du",
     "Gers" => "du",
     "Gironde" => "de la",
     "Guadeloupe" => "de",
     "Guyane" => "de",
     "Haut-Rhin" => "du",
     "Haute-Corse" => "de",
     "Haute-Garonne" => "de la",
     "Haute-Loire" => "de la",
     "Haute-Marne" => "de la",
     "Haute-Saône" => "de la",
     "Haute-Savoie" => "de",
     "Haute-Vienne" => "de la",
     "Hautes-Alpes" => "des",
     "Hautes-Pyrénées" => "des",
     "Hauts-de-Seine" => "des",
     "Hérault" => "de l'",
     "Ille-et-Vilaine" => "d'",
     "Indre" => "de l'",
     "Indre-et-Loire" => "de l'",
     "Isère" => "de l'",
     "Jura" => "du",
     "Landes" => "des",
     "Loir-et-Cher" => "du",
     "Loire" => "de la",
     "Loire-Atlantique" => "de",
     "Loiret" => "du",
     "Lot" => "du",
     "Lot-et-Garonne" => "du",
     "Lozère" => "de la",
     "Maine-et-Loire" => "du",
     "Manche" => "de la",
     "Marne" => "de la",
     "Martinique" => "de",
     "Mayenne" => "de la",
     "Mayotte" => "de",
     "Meurthe-et-Moselle" => "de",
     "Meuse" => "de la",
     "Morbihan" => "du",
     "Moselle" => "de la",
     "Nièvre" => "de la",
     "Nord" => "du",
     "Nouvelle-Calédonie" => "de la",
     "Oise" => "de l'",
     "Orne" => "de l'",
     "Paris" => "de",
     "Pas-de-Calais" => "du",
     "Polynésie Française" => "de la",
     "Puy-de-Dôme" => "du",
     "Pyrénées-Atlantiques" => "des",
     "Pyrénées-Orientales" => "des",
     "Réunion" => "de la",
     "Rhône" => "du",
     "Saint-Pierre-et-Miquelon" => "de",
     "Saône-et-Loire" => "de",
     "Sarthe" => "de la",
     "Savoie" => "de",
     "Seine-et-Marne" => "de",
     "Seine-Maritime" => "de",
     "Seine-Saint-Denis" => "de",
     "Somme" => "de la",
     "Tarn" => "du",
     "Tarn-et-Garonne" => "du",
     "Territoire-de-Belfort" => "du",
     "Val-d'Oise" => "du",
     "Val-de-Marne" => "du",
     "Var" => "du",
     "Vaucluse" => "du",
     "Vendée" => "de",
     "Vienne" => "de la",
     "Vosges" => "des",
     "Wallis-et-Futuna" => "de",
     "Yonne" => "de l'",
     "Yvelines" => "des"
    );
    $prefixe = $hashmap[trim($this->nom_circo)];
    if (! preg_match("/'/", $prefixe)) $prefixe = $prefixe.' ';
    return $prefixe.$this->nom_circo;
  }

  public function getNumCircoString() {
    if ($this->num_circo == 1) return $this->num_circo.'ère circonscription';
    else return $this->num_circo.'ème circonscription';
  }

  public function getStatut() {
    if ($this->type == 'depute') {
        if ($this->sexe == 'F') return 'Députée';
        else return 'Député';
    } else  {
        if ($this->sexe == 'F') return 'Sénatrice';
        else return 'Sénateur';
    }
  }
  
  public function getLongStatut() {
    if ($this->getGroupe()) {
      return $this->getStatut().' '.$this->getGroupe()->getNom().' de la '.$this->getNumCircoString().' '.$this->getCirconscription();
    }
      return $this->getStatut().' de la '.$this->getNumCircoString().' '.$this->getCirconscription();
  }

  public function setDebutMandat($str) {
    if (preg_match('/(\d{2})\/(\d{2})\/(\d{4})/', $str, $m)) {
      $this->_set('debut_mandat', $m[3].'-'.$m[2].'-'.$m[1]);
    }
  }
  public function setFinMandat($str) {
    if (preg_match('/(\d{2})\/(\d{2})\/(\d{4})/', $str, $m)) {
      $this->_set('fin_mandat', $m[3].'-'.$m[2].'-'.$m[1]);
    }
  }
  public function setFonctions($array) {
    return $this->setPOrganisme('parlementaire', $array);
  }
  public function setExtras($array) {
    return $this->setPOrganisme('extra', $array);
  }
  public function setGroupe($array) {
    return $this->setPOrganisme('groupe', $array);
  }

  public function setPOrganisme($type, $array) {
    $orgas = $this->getParlementaireOrganismes();
    foreach ($array as $item) {
      $args = preg_split('/\s+\/\s*/', $item);
      $orga = Doctrine::getTable('Organisme')->findOneByNom($args[0]);
      if (!$orga) {
	$orga = new Organisme();
	$orga->nom = $args[0];
	$orga->type = $type;
	$orga->save();
      }
      if ($type == 'groupe')
        $this->groupe_acronyme = $orga->getSmallNomGroupe();
      $po = new ParlementaireOrganisme();
      $po->setParlementaire($this);
      $po->setOrganisme($orga);
      $fonction = preg_replace("/\(/","",$args[1]);
      $po->setFonction($fonction);
      $importance = ParlementaireOrganisme::defImportance($fonction);
      $po->setImportance($importance);
  /*      if (isset($args[2])) {
	$po->setDebutFonction($args[2]);
	}*/
      $orgas->add($po);
    }
    $this->_set('ParlementaireOrganismes', $orgas);
  }

  private function getPOFromJoinIf($field, $value) {
    $p = $this->toArray();
    if (isset($p['ParlementaireOrganisme']) &&
	isset($p['ParlementaireOrganisme'][0]) &&
	$p['ParlementaireOrganisme'][0]['Organisme'][$field] == $value)  {
      $po = new ParlementaireOrganisme();
      $o = new Organisme();
      $o->fromArray($p['ParlementaireOrganisme'][0]['Organisme']);
      $po->setFonction($p['ParlementaireOrganisme'][0]['fonction']);
      $po->setParlementaire($this);
      $po->setOrganisme($o);
      return $po;
    }
    return NULL;
  }

  public function getPOrganisme($str) {
    if($po = $this->getPOFromJoinIf('nom', $str))
      return $po;
    foreach($this->getParlementaireOrganismes() as $po) {
      if ($po['Organisme']->nom == $str)
	return $po;
    }
  }
  public function setAutresmandats($array) {

  }
  public function setMails($array) {
  }
  public function setAdresses($array) {
  }
  public function getGroupe() {
    if($po = $this->getPOFromJoinIf('type', 'groupe'))
      return $po;
    foreach($this->getParlementaireOrganismes() as $po) {
      if ($po->type == 'groupe') 
	return $po;
    }
  }
  public function getExtras() {
    $res = array();
    foreach($this->getParlementaireOrganismes() as $po) {
      if ($po->type == 'extra') 
	array_push($res, $po);
    }
    return $res;
  }
  public function getResponsabilites() {
    $res = array();
    foreach($this->getParlementaireOrganismes() as $po) {
      if ($po->type == 'parlementaire') 
	$res[sprintf('%04d',abs(100-$po->importance)).$po->nom]=$po;
    }
    ksort($res);
    return array_values($res);
  }
  public function getPhoto() {
    $id_an = $this->getIdAN();
    return 'http://www.palais-bourbon.fr/13/tribun/photos/'.$id_an.'.jpg';
  }
  public function getPageLink() {
    return '@parlementaire?slug='.$this->slug;
  }
}
