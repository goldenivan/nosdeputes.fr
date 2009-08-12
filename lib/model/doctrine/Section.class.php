<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Section extends BaseSection
{
  public function setTitreComplet($titre) {
    $this->_set('titre_complet', $titre);
    $this->md5 = md5($titre);
    $titres = preg_split('/\s*>\s*/', $titre);
    $parent = null;
    if (count($titres) > 1) {
      $parent_titre = array_shift($titres);
      $parent = doctrine::getTable('Section')->findOneByContexteOrCreateIt($parent_titre);
    }
    $this->_set('titre', $titres[0]);
    $this->save();
    if (!$parent)
      $parent = $this;
    $this->section_id = $parent->id;
    $this->save();
  }
  public function getSubSections() {
    return $q = doctrine::getTable('Section')->createQuery('s')
      ->where('s.section_id = ?', $this->id)
      ->orderBy('s.min_date ASC, s.timestamp ASC')->execute()
      ;
  }
  public function getSeances() {
    $q = doctrine_query::create()
      ->from('Seance s, Section st, Intervention i')
      ->select('s.*')
      ->where('i.seance_id = s.id')
      ->andwhere('i.section_id = st.id')
      ->andwhere('(st.section_id = ? OR i.section_id = ? )', array($this->id, $this->id))
      ->groupBy('s.id')
      ;
    return $q->execute();
  }

  public function updateNbInterventions() {
    $a = Doctrine_Query::create()
      ->select('count(*) as nb')
      ->from('Intervention i')
      ->leftJoin('i.Section s')
      ->where('(i.section_id = ? OR s.section_id = ?)', array($this->id, $this->id))
      ->andWhere('(i.fonction NOT LIKE ? AND i.fonction NOT LIKE ?)', array('président', 'présidente'))
      ->fetchArray();
    $this->_set('nb_interventions', $a[0]['nb']);
    $this->save();
  }

  public function getSection() {
    if ($this->id == $this->section_id)
      return NULL;
    return $this->_get('Section');
  }

}