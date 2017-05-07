<?php

/**
 * Created by IntelliJ IDEA.
 * User: Valentin
 * Date: 05/05/2017
 * Time: 11:08
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Artistes_m extends CI_Model {

	public function getListeArtistes() {
		$this->db->from('ARTISTE');
		$query = $this->db->get();
		return $query->result();
	}

	public function addArtiste($donnees) {
		$this->db->insert('ARTISTE', array_change_key_case($donnees, CASE_UPPER));
	}

}
