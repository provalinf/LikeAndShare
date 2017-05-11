<?php

/**
 * Created by IntelliJ IDEA.
 * User: Valentin
 * Date: 05/05/2017
 * Time: 11:08
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_m extends CI_Model {
    public function genererStatSemaine(){
        return $this->db->insert('SEMAINE', array(
            'NUM_SEMAINE'=>date('W'),
            'ANNEE'=>date('Y')
        ));
    }
}
