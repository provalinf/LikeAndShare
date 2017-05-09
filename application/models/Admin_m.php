<?php

/**
 * Created by IntelliJ IDEA.
 * User: Valentin
 * Date: 05/05/2017
 * Time: 11:08
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_m extends CI_Model {
    public function refreshStats(){
        $this->db->from('V_SCROB_ARTISTE_SEMAINE');
        $this->db->from('V_SCROB_MEDIA_SEMAINE');
        $this->db->from('V_STAT_ARTISTE_SEMAINE');
        $this->db->from('V_STAT_MEDIA_SEMAINE');
        $query = $this->db->get();
        return $query->result();
    }
}
