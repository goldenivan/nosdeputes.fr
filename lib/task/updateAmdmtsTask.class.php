<?php

class updateAmdmtsTask extends sfBaseTask {
  protected function configure() {
    $this->namespace = 'update';
    $this->name = 'Amdmts';
    $this->briefDescription = 'Update Amendements data to set auteur_id';
    $this->addOption('env', null, sfCommandOption::PARAMETER_OPTIONAL, 'Changes the environment this task is run in', 'prod');
    $this->addOption('app', null, sfCommandOption::PARAMETER_OPTIONAL, 'Changes the environment this task is run in', 'frontend');
    $this->addOption('max', null, sfCommandOption::PARAMETER_OPTIONAL, 'Changes the environment this task is run in', '10');

  }

  protected function execute($arguments = array(), $options = array()) {
    // your code here
    $dir = dirname(__FILE__).'/../../batch/amendements/OpenDataAN/';
    $this->configuration = sfProjectConfiguration::getApplicationConfiguration($options['app'], $options['env'], true);
    $manager = new sfDatabaseManager($this->configuration);
    $nb_json_files = 0;

    if (is_dir($dir)) {
      if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) != false) {
          if (substr($file, 0, 6) != 'amdts_') continue;
          $ct_lines = 0;
          $ct_lus = 0;
          $ct_crees = 0;
          if ($nb_json_files > options['max'])
            break;
          foreach(file($dir.$file) as $line) {
            $ct_lines++;
            $json = json_decode($line);
            if (!$json) {
              echo "ERROR json : $line";
              continue;
            }
            if (!$json->legislature || !$json->numero || !$json->loi || !$json->sujet || !isset($json->rectif)) {
              echo "ERROR mandatory arg missing (source|legis|numero|loi|sujet|texte|date|rectif): $line\n";
              continue;
            }
            $ct_lus++;
            $amdmt = Doctrine::getTable('Amendement')->findOneByLegisLoiNumRect($json->legislature, $json->loi, $json->numero, $json->rectif);
            if (!$amdmt) {
              echo "ERROR amdmt from OpenData AN missing from ND data: $line\n";
              continue;
            }
            if ($json->auteur_reel) {
              $parl = Doctrine::getTable('Parlementaire')->findOneByIdAn($json->auteur_reel);
              if (!$parl) {
                echo "ERROR, cannot find auteur from AN ID: $line\n";
                continue;
              }
              $amdmt->setAuteur($parl);
              $amdmt->save();
            }
            $amdmt->free();
          }
          unlink($dir.$file);
          $nb_json_files++;
        }
        if ($ct_crees) echo $ct_lines." amendements lus : ".$ct_lus." mis à jour dont ".$ct_crees." nouveaux.\n";
        closedir($dh);
      }
    }
  }
}