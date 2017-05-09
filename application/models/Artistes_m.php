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

	public function getTitresMusicaux() {
		//$this->db->select('*'); <= Inutile si rien dans le select
		$this->db->from('MEDIA');
		$this->db->join('TITRE_MUSICAL', 'MEDIA.TITRE = TITRE_MUSICAL.TITRE AND MEDIA.TITRE=TITRE_MUSICAL.TITRE');
		$query = $this->db->get();
		return $query->result();
	}

	public function getListeAuteur() {
		$result         = $this->db->select("NOM_ARTISTE")->from("ARTISTE")->get();
		$liste_dropDown = array();
		if ($result->num_rows() > 0) {
			$liste_dropDown[''] = '--';
			foreach ($result->result_array() as $row) {
				$liste_dropDown[$row['NOM_ARTISTE']] = $row['NOM_ARTISTE'];
			}
		}
		return $liste_dropDown;
	}

	public function check_isExist($artist) {
		$this->db->from('ARTISTE')->where('NOM_ARTISTE', $artist);
		return $this->db->get()->num_rows() == 1;
	}

}
