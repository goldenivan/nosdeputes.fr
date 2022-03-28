<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PersonnaliteTable extends Doctrine_Table
{
  protected $changed = 0;
  protected $all = null;

  public function cleanString($str) {
    $str = preg_replace('/^\s+/', '', preg_replace('/\s+$/', '', $str));
    $str = preg_replace('/\(.*\)/', '', $str);
    $str = preg_replace('/[\(\)]/', '', $str);
    $strcl = array('str' => $str);
    if (preg_match('/^M([\.Mmle]+) (.*)$/', $str, $match)) {
      $strcl['str'] = $match[2];
      $strcl['sexe'] = "H";
      if (preg_match('/e/', $match[1]))
        $strcl['sexe'] = "F";
    }
    return $strcl;
  }

  public function similarToCheckPrenom($str, $sexe = null, $return_array = 0, $year = 0) {
    $strcl = $this->cleanString($str);
    $str = $strcl['str'];
    if (!$sexe && isset($strcl['sexe']))
      $sexe = $strcl['sexe'];
    $first = preg_replace('/[\WàÀéèÉÈêÊîÎïÏôÔüÜùÙ]/', '.', strtolower(preg_replace('/^\s*(\S{4}).*$/i', '\\1', $str)));
    $res = $this->similarTo($str, $sexe, $return_array, $year);
    if ($res && (preg_match("/^".$first."/i", $res->getNom()) || preg_match("/^".$first."/i", $res->getNomDeFamille())))
      return $res;
    return null;
  }

  public function similarTo($str, $sexe = null, $return_array = 0, $year = 0)
  {
    if (preg_match('/^\s*$/', $str))
      return null;
    $strcl = $this->cleanString($str);
    $str = $strcl['str'];
    if (!$sexe && isset($strcl['sexe']))
      $sexe = $strcl['sexe'];
    $word = preg_replace('/^.*\s(\S+)\s*$/i', '\\1', $str);
    $q = $this->createQuery('p')->where('nom LIKE ?', '% '.$word.'%');
    $res = $q->Execute();
    if ($res->count() == 1) {
      if ($return_array)
	return array($res[0]);
      return $res[0];
    }else{
      $similar = array();
      foreach ($res as $r) {
	if (preg_match('/ '.$str.'$/', $r->nom) && (!$year || (preg_replace('/-.*/', '', $r->fin_mandat) >= $year && preg_replace('/-.*/', '', $r->debut_mandat) <= $year) || !$r->fin_mandat))
	  $similar[] = $r;
      }
      if (count($similar) == 1 && (!$sexe || $similar[0]->sexe == $sexe) )
	if ($return_array)
	  return $similar;
	else
	  return $similar[0];

      if  (count($similar) > 1 && !$sexe)
	if ($return_array)
	  return array();
	else
	  return null;
    }

    //load parlementaires only once
    if (!$this->all) {
      $this->all = $this->createQuery('p')
	->select('id, nom, nom_de_famille, sexe, slug')
	->fetchArray();
      $this->changed = 0;
    }

    $closest = null;
    $closest_res = -1;
    $best_res = -1;
    $champ = 'nom';
    if (!preg_match('/ /', $str))
      $champ = 'nom_de_famille';
    $similar = array();
    //Compare each parlementaire with the string and keep the best
    for ($i = 0 ; $i < count($this->all) ; $i++) {
      $parl = $this->all[$i];
      if ($sexe && $sexe != $parl['sexe'])
        continue;
      $res = similar_text(preg_replace('/[^a-z]+/i', ' ', $parl[$champ]), preg_replace('/[^a-z]+/i', ' ', $str), $pc);
      if ($res > 0 && $pc > 65)
	$similar[$i] = $pc;
    }

    arsort($similar);
    $keys = array_keys($similar);
    if (count($keys)) {
      $closest_res = $similar[$keys[0]];
      $closest = $this->all[$keys[0]];
    }

    if ($return_array) {
      $res = array();
      foreach(array_keys($similar) as $i) {
	array_push($res, $this->all[$i]);
      }
      return $res;
    }

#    echo "$str "; echo $closest['nom'];    echo " $closest_res\n";
    if (strlen($str) < 8) $seuil = 65;
    else $seuil = 85;
    //If more than 85% similarities, it is the best
    if ($closest_res > $seuil)
      return $this->find($closest['id']);
    //If str is the end of the best parlementaire, it is OK (remove non alpha car to avoid preg pb)
    if ($closest && preg_match('/'.preg_replace('/[^a-z]/i', '', $str).'$/', preg_replace('/[^a-z]/i', '', $closest['nom'])))
      return $this->find($closest['id']);

    return null;
  }

  public function hasChanged() {
    $this->changed = 1;
  }

}
