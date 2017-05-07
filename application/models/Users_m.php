<?php

/**
 * Created by IntelliJ IDEA.
 * User: Valentin
 * Date: 05/05/2017
 * Time: 11:08
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_m extends CI_Model {

	public function verif_connexion($donnees) {
		$this->db->select("PSEUDO AS login");
		$this->db->select("ADMINISTRATEUR AS admin");
		$this->db->from("UTILISATEUR");
		$this->db->where('PSEUDO', $donnees['login']);
		$this->db->where('MOT_DE_PASSE', $donnees['pass']);
		$query  = $this->db->get();

		return (!empty($query)) ? $query->row_array() : false;
	}

	public function getCategorieAgeDropdown() {
		$liste_dropDown[0] = '--';
		$liste_dropDown[1] = '0-20';
		$liste_dropDown[2] = '21-30';
		$liste_dropDown[3] = '31-101';
		return $liste_dropDown;
	}

	public function add_user($donnees) {
		$data = array(
			'PSEUDO'         => $donnees['login'], 'MOT_DE_PASSE' => $donnees['pass'],
			'CATEGORIE_AGE'  => $donnees['catego_age'], 'CODE_POSTAL' => $donnees['code_postal'],
			'ADMINISTRATEUR' => $donnees['admin']
		);

		$this->db->insert('UTILISATEUR', $data);
	}

	public function getListeUtilisateurs() {
		$this->db->from('UTILISATEUR');
		$query = $this->db->get();
		return $query->result();
	}

}
