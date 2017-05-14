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
		$this->db->join('TITRE_MUSICAL', 'MEDIA.TITRE = TITRE_MUSICAL.TITRE AND MEDIA.NOM_ARTISTE = TITRE_MUSICAL.NOM_ARTISTE');
		$query = $this->db->get();
		return $query->result();
	}

	public function getLivres() {
		$this->db->select('*');
		$this->db->from('MEDIA');
		$this->db->join('LIVRE', 'MEDIA.TITRE = LIVRE.TITRE AND MEDIA.NOM_ARTISTE = LIVRE.NOM_ARTISTE');
		$query = $this->db->get();
		return $query->result();
	}

	public function getFilms() {
		$this->db->select('*');
		$this->db->from('MEDIA');
		$this->db->join('FILM', 'MEDIA.TITRE = FILM.TITRE AND MEDIA.NOM_ARTISTE = FILM.NOM_ARTISTE');
		$query = $this->db->get();
		return $query->result();
	}

	public function getTypesMedia() {
		$liste_dropDown[0] = '--';
		$liste_dropDown[1] = 'Musique';
		$liste_dropDown[2] = 'Livre';
		$liste_dropDown[3] = 'Film';
		return $liste_dropDown;
	}

	public function getListeLangueMedia() {
		$liste_dropDown[0] = '--';
		$liste_dropDown[1] = 'fr';
		$liste_dropDown[2] = 'angl';
		$liste_dropDown[3] = 'autre';
		return $liste_dropDown;
	}

	public function getListeGenreMusical() {
		$liste_dropDown[0] = '--';
		$liste_dropDown[1] = 'rock';
		$liste_dropDown[2] = 'rap';
		$liste_dropDown[3] = 'pop';
		$liste_dropDown[4] = 'autre';
		return $liste_dropDown;
	}

	public function getTypeAlbum() {
		$liste_dropDown[0] = '--';
		$liste_dropDown[1] = 'single';
		$liste_dropDown[2] = 'full';
		$liste_dropDown[3] = 'mini';
		return $liste_dropDown;
	}

	public function getListeGenreLitteraire() {
		$liste_dropDown[0] = '--';
		$liste_dropDown[1] = 'roman';
		$liste_dropDown[2] = 'essai';
		$liste_dropDown[3] = 'nouvelle';
		$liste_dropDown[4] = 'autre';
		return $liste_dropDown;
	}

	public function getListeGenreCinematographique() {
		$liste_dropDown[0] = '--';
		$liste_dropDown[1] = 'comedie';
		$liste_dropDown[2] = 'SF';
		$liste_dropDown[3] = 'horreur';
		$liste_dropDown[4] = 'autre';
		return $liste_dropDown;
	}

	public function getListeRoleArtisteSecond() {
		$liste_dropDown[0] = '--';
		$liste_dropDown[1] = 'compositeur';
		$liste_dropDown[2] = 'producteur';
		$liste_dropDown[3] = 'acteur';
		$liste_dropDown[4] = 'autre';
		return $liste_dropDown;
	}

	public function add_media($donnees, $type) {
		$data = array(
			'TITRE'         => $donnees['titre_media'], 'NOM_ARTISTE' => $donnees['nom_auteur'],
			'ANNEE_EDITION' => $donnees['annee'], 'LANGUE' => $donnees['langue'], 'EDITEUR' => $donnees['editeur']
		);

		if ($this->db->insert("MEDIA", $data)) {

			if (!empty($donnees['nom_auteurSec'])) {
				$data = array(
					'TITRE'         => $donnees['titre_media'], 'NOM_ARTISTE' => $donnees['nom_auteur'],
					'NOM_ARTISTE_1' => $donnees['nom_auteurSec'], 'ROLE_AA' => $donnees['roleArtSec']
				);
				$this->add_AuteurSec($data);
			}
			if ($type == 1) {
				$data   = array(
					'TITRE'      => $donnees['titre_media'], 'NOM_ARTISTE' => $donnees['nom_auteur'],
					'DUREE'      => $donnees['duree'], 'GENRE_MUSICAL' => $donnees['genre'],
					'TYPE_ALBUM' => $donnees['type_albm']
				);
				$result = $this->add_TitreMusical($data);
			} elseif ($type == 2) {
				$data   = array(
					'TITRE'            => $donnees['titre_media'], 'NOM_ARTISTE' => $donnees['nom_auteur'],
					'GENRE_LITTERAIRE' => $donnees['genre']
				);
				$result = $this->add_Livre($data);
			} else {
				$data   = array(
					'TITRE'        => $donnees['titre_media'], 'NOM_ARTISTE' => $donnees['nom_auteur'],
					'GENRE_CINEMA' => $donnees['genre']
				);
				$result = $this->add_Film($data);
			}
			return $result;
		}
		return false;
	}

	private function add_TitreMusical($donnees) {
		return $this->db->insert("TITRE_MUSICAL", $donnees);
	}

	private function add_Livre($donnees) {
		return $this->db->insert("LIVRE", $donnees);
	}

	private function add_Film($donnees) {
		return $this->db->insert("FILM", $donnees);
	}

	private function add_AuteurSec($donnees) {
		return $this->db->insert("AUTRE_AUTEUR", $donnees);
	}

	public function check_mediaIsExist($titre, $artiste) {
		$this->db->from('MEDIA')->where('TITRE', $titre)->where('NOM_ARTISTE', $artiste);
		return $this->db->get()->num_rows() == 1;
	}

	public function scrobbler($titre, $artiste, $login) {
		if ($this->check_scrobbleJourExiste($titre, $artiste, $login)) return false;
		$donnees = array(
			'TITRE' => $titre, 'NOM_ARTISTE' => $artiste, 'PSEUDO' => $login
		);
		return $this->db->insert("SCROBBLING", $donnees);
	}

	public function check_scrobbleJourExiste($titre, $artiste, $login) {
		$donnees = array(
			'TITRE' => $titre, 'NOM_ARTISTE' => $artiste, 'PSEUDO' => $login, "to_char(DATE_SCROBBLING,'YYYY-MM-DD')" => date('Y-m-d')
		);
		$this->db->from('SCROBBLING')->where($donnees);
		return $this->db->get()->num_rows() == 1;
	}

	public function getScrobArtisteSemaine() {
		$this->db->from('V_SCROB_ARTISTE_SEMAINE');
		$query = $this->db->get();
		return $query->result();
	}

	public function getScrobMediaSemaine() {
		$this->db->from('V_SCROB_MEDIA_SEMAINE');
		$query = $this->db->get();
		return $query->result();
	}

	public function getScrobArtiste() {
		$this->db->from('V_SCROB_ARTISTE');
		$query = $this->db->get();
		return $query->result();
	}

	public function getScrobMedia() {
		$this->db->from('V_SCROB_MEDIA');
		$query = $this->db->get();
		return $query->result();
	}

	public function getStatArtisteSemaine() {
		$this->db->from('V_STAT_ARTISTE_SEMAINE');
		$query = $this->db->get();
		return $query->result();
	}

	public function getStatMediaSemaine() {
		$this->db->from('V_STAT_MEDIA_SEMAINE');
		$query = $this->db->get();
		return $query->result();
	}

	public function getStatArtiste() {
		$this->db->from('V_STAT_ARTISTE');
		$query = $this->db->get();
		return $query->result();
	}

	public function getStatMedia() {
		$this->db->from('V_STAT_MEDIA');
		$query = $this->db->get();
		return $query->result();
	}
}
