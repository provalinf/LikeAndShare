<?php

/**
 * Created by IntelliJ IDEA.
 * User: Valentin
 * Date: 05/05/2017
 * Time: 11:08
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Medias_m extends CI_Model {

	public function getListeMedias() {
		$this->db->from('MEDIA');
		$query = $this->db->get();
		return $query->result();
	}

    public function getTitresMusicaux() {
        $this->db->select('*');
        $this->db->from('MEDIA');
        $this->db->join('TITRE_MUSICAL', 'MEDIA.TITRE = TITRE_MUSICAL.TITRE');
        $query = $this->db->get();
        return $query->result();
    }

    public function getLivres() {
        $this->db->select('*');
        $this->db->from('MEDIA');
        $this->db->join('LIVRE', 'MEDIA.TITRE = LIVRE.TITRE');
        $query = $this->db->get();
        return $query->result();
    }

    public function getFilms() {
        $this->db->select('*');
        $this->db->from('MEDIA');
        $this->db->join('FILM', 'MEDIA.TITRE = FILM.TITRE');
        $query = $this->db->get();
        return $query->result();
    }
}
