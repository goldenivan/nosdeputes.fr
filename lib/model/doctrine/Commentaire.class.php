<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Commentaire extends BaseCommentaire
{
  public function getLink() {
    return $this->lien;
  }
  public function getPersonne() {
    return $this->citoyen->getLogin();
  }
  public function getTitre() {
    return $this->getPresentation();
  }

  public function __toString() {
    $str = substr($this->commentaire, 0, 250);
    if (strlen($str) == 250) {
      $str .= '...';
    }
    return $str;
  }

  protected static $mois = array('01'=>'janvier', '02'=>'février', '03'=>'mars', '04'=>'avril', '05'=>'mai', '06'=>'juin', '07'=>'juillet', '08'=>'août', '09'=>'septembre', '10'=>'octobre', '11'=>'novembre', '12'=>'décembre');

 /**
  * Overrides getPresentation from corresponding column
  * if $format is set to specific strings, returns a modified version :
  *   'none' => empty string
  *   'noauteur' => presentation without the author information
  *   'nodossier' => presentation without the section information
  *   'noloi' => presentation without the name of the law
  *   'noarticle' => presentation without the name of the law nor the article's
  * if $virgule is set to 1, adds a ', ' at the end of the string
  */
  public function getPresentation($format = '', $virgule = 0) {
    if ($format == 'none') return '';
    else $present = $this->_get('presentation');
    if ($format == 'noauteur') {
      $present = preg_replace('/\sd(\'|e\s)[A-ZÉÈÊ][\wçàéëêèïîôöûüÉ\s\-]+\sle\s(\d)/', ' du \2', $present);
      $present = preg_replace('/Suite aux/', 'Suite à ses', $present);
    } else if ($format == 'nodossier') {
      $present = preg_replace('/^.* - (Suite aux|Au sujet)/', '\1', $present);
    } else if ($format == 'noloi' || $format == 'noarticle') {
      $present = preg_replace('/^.* - /', '', $present);
    }
    if ($format == 'noarticle') {
      $present = preg_replace('/(A propos de l\')article\s.*\s(alinéa\s\d+)/', '\1\2', $present);
      $present = preg_replace('/A propos de l\'article\s.*$/', '', $present);
    }
    if ($virgule == 1 && $present != '') return $present.', ';
    else return $present;
  }

  public function addObject($object_type, $object_id) {
    if (!$this->id) {
      throw new Exception('no commentaire id');
    }
    $object = Doctrine::getTable($object_type)->find($object_id);
    if ($object) {
      Doctrine::getTable('CommentaireObject')->findUniqueOrCreate($object_type, $object_id, $this->id);
      $object->updateNbCommentaires();
      if ($object_type == 'Section' && $object->id != $object->section_id)
        $this->addObject($object_type, $object->section_id);
      else if ($object_type == 'TitreLoi') {
        if ($object->id != $object->titre_loi_id)
          $this->addObject($object_type, $object->titre_loi_id);
        if ($object->parlementaire_id)
          $this->addObject('Parlementaire', $object->parlementaire_id);
      } else if ($object_type == 'ArticleLoi' && $object->titre_loi_id)
        $this->addObject('TitreLoi', $object->titre_loi_id);
    }
  }

  public function updateNbCommentaires($inc = 0) {
    $o = Doctrine::getTable($this->object_type)->find($this->object_id);
    $o->updateNbCommentaires($inc);
    foreach ($this->getObjects() as $object) {
      $o = Doctrine::getTable($object->object_type)->find($object->object_id);
      if (isset($o))
        $o->updateNbCommentaires($inc);
    }
  }

  public function setIsPublic($b) {
    $this->_set('is_public', $b);
    if ($this->id) {
      $this->updateNbCommentaires(($b) ? 1 : -1);
    }
  }
  public function getLien() {
    return preg_replace('/@amendement\?id=/', '@amendement_id?id=', $this->_get('lien'));
  }
}
