<?php
/**
 * Created by IntelliJ IDEA.
 * User: Valentin
 * Date: 05/05/2017
 * Time: 18:29
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Medias_c extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('twig');
		$this->load->helper(array('url', 'prev_page', 'date'));
		$this->load->library(array('session', 'form_validation'));
		$this->load->model(array('Medias_m', 'Artistes_m'));

		$this->twig->addGlobal('globlogin', $this->session->userdata('login'));
		$this->twig->addGlobal('globadmin', $this->session->userdata('admin'));
	}

	private function check_isConnected() {
		if (empty($this->session->userdata('login'))) redirect('c=Medias_c');
	}

	public function index() {
		$this->liste_Medias();
	}

	public function liste_Medias() {
		$filter = new Twig_SimpleFunction('deja_scrobble', function($titre_media, $nom_artiste){
			return $this->Medias_m->check_scrobbleJourExiste($titre_media, $nom_artiste, $this->session->userdata('login'));

		});
		$this->twig->getTwig()->addFunction($filter);
		$this->twig->display('medias_list', array(
			'titre'       => "Liste des médias", 'musiques_list' => $this->Medias_m->getTitresMusicaux(),
			'livres_list' => $this->Medias_m->getLivres(), 'films_list' => $this->Medias_m->getFilms()
		));
	}

	public function addMedia($type = -1, $donnees = array()) {
		$type = $this->input->get('type');
		$this->check_isConnected();

		if ($type == -1) {
			$type = 0;
		} else {
			$this->form_validation->set_rules('type_media', 'Type de média', 'trim|required|numeric|callback_verif_typeMedia');
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			if ($this->form_validation->run() != False) {
				$type = $this->input->post('type_media');
			}
		}

		$this->twig->display('form_addMedia', array_merge($donnees, array(
			'titre'                => "Ajouter un média", 'type' => $type,
			'list_typeMedia'       => $this->Medias_m->getTypesMedia(),
			'list_auteurs'         => $this->Artistes_m->getListeAuteur(),
			'list_langueMedia'     => $this->Medias_m->getListeLangueMedia(),
			'list_genreMusical'    => $this->Medias_m->getListeGenreMusical(),
			'list_typeAlbum'       => $this->Medias_m->getTypeAlbum(),
			'list_genreLitteraire' => $this->Medias_m->getListeGenreLitteraire(),
			'list_genreCinemato'   => $this->Medias_m->getListeGenreCinematographique(),
			'list_roleArtSec'      => $this->Medias_m->getListeRoleArtisteSecond()
		)));
	}

	public function verif_typeMedia($type) {
		if ($type > 0 && $type < count($this->Medias_m->getTypesMedia())) return TRUE;

		$this->form_validation->set_message('verif_typeMedia', 'Le %s n\'est pas définie');
		return FALSE;
	}

	public function validFormAddMedia($type = null) {
		$type = $this->input->get('type');

		$this->check_isConnected();

		if ($type < 1 || $type > count($this->Medias_m->getTypesMedia())) {
			redirect('c=Medias_c&m=addMedia');
		}

		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		$this->form_validation->set_rules('nom_auteur', 'Auteur principal', 'trim|required|callback_verifArtiste');
		$this->form_validation->set_rules('titre_media', 'Titre', "trim|required|max_length[100]|callback_verifUniqueMedia[{$this->input->post('nom_auteur')}]");
		$this->form_validation->set_rules('nom_auteurSec', 'Auteur secondaire', "trim|callback_verifArtisteSec[{$this->input->post('nom_auteur')}]");
		$this->form_validation->set_rules('roleArtSec', 'Role de l\'auteur secondaire', "trim|numeric|callback_verifRoleArtSecond[{$this->input->post('nom_auteurSec')}]");
		$this->form_validation->set_rules('annee', 'Année d\'édition', 'trim|required|numeric|exact_length[4]|callback_verifAnnee');
		$this->form_validation->set_rules('langue', 'Langue du média', 'trim|required|numeric|is_natural_no_zero|callback_verifLangue');
		$this->form_validation->set_rules('editeur', 'Éditeur', 'trim|required|max_length[100]');

		$donnees = array(
			'titre_media'   => $this->input->post('titre_media'), 'nom_auteur' => $this->input->post('nom_auteur'),
			'nom_auteurSec' => $this->input->post('nom_auteurSec'), 'roleArtSec' => $this->input->post('roleArtSec'),
			'annee'         => $this->input->post('annee'), 'langue' => $this->input->post('langue'),
			'editeur'       => $this->input->post('editeur')
		);

		if ($type == 1) {
			$this->form_validation->set_rules('duree', 'Durée', 'trim|required|callback_verifDuree');
			$this->form_validation->set_rules('genre', 'Genre musical', 'trim|required|numeric|is_natural_no_zero|callback_verifGenreMusic');
			$this->form_validation->set_rules('type_albm', 'Type d\'album', 'trim|required|numeric|is_natural_no_zero|callback_verifTypeAlbum');

			$donnees += array(
				'duree' => $this->input->post('duree'), 'type_albm' => $this->input->post('type_albm')
			);
		} elseif ($type == 2) {
			$this->form_validation->set_rules('genre', 'Genre littéraire', 'trim|required|numeric|is_natural_no_zero|callback_verifGenreLitter');
		} else {
			$this->form_validation->set_rules('genre', 'Genre cinématographique', 'trim|required|numeric|is_natural_no_zero|callback_verifGenreCinemat');
		}
		$donnees['genre'] = $this->input->post('genre');

		if ($this->form_validation->run() == False) {
			$this->addMedia($type, $donnees);
		} else {
			$donnees['langue']     = $this->Medias_m->getListeLangueMedia()[$donnees['langue']];
			$donnees['roleArtSec'] = $this->Medias_m->getListeRoleArtisteSecond()[$donnees['roleArtSec']];

			if ($type == 1) {
				$donnees['genre']     = $this->Medias_m->getListeGenreMusical()[$donnees['genre']];
				$donnees['type_albm'] = $this->Medias_m->getTypeAlbum()[$donnees['type_albm']];
			} elseif ($type == 2) {
				$donnees['genre'] = $this->Medias_m->getListeGenreLitteraire()[$donnees['genre']];
			} else {
				$donnees['genre'] = $this->Medias_m->getListeGenreCinematographique()[$donnees['genre']];
			}

			$this->Medias_m->add_media($donnees, $type);
			redirect('c=Medias_c');
		}

	}

	public function verifArtiste($artiste) {
		if ($this->Artistes_m->check_isExist($artiste)) {
			return true;
		}

		$this->form_validation->set_message('verifArtiste', 'L\'%s n\'est pas définie');
		return false;
	}

	public function verifUniqueMedia($titre, $artiste) {
		if (!$this->Medias_m->check_mediaIsExist($titre, $artiste)) {
			return true;
		}

		$this->form_validation->set_message('verifUniqueMedia', 'Le média est déjà répertorié !');
		return false;
	}

	public function verifArtisteSec($artistesec, $artistePr) {
		if (empty($artistesec)) return true;
		if ($this->Artistes_m->check_isExist($artistesec) && $artistesec != $artistePr) {
			return true;
		}

		$this->form_validation->set_message('verifArtisteSec', 'L\'%s n\'est pas définie');
		return false;
	}

	public function verifRoleArtSecond($role, $artisteSec) {
		if (empty($artisteSec)) return true;

		if ($role > 0 && $role < count($this->Medias_m->getListeRoleArtisteSecond())) return TRUE;

		$this->form_validation->set_message('verifRoleArtSecond', 'Le %s n\'est pas définie');
		return FALSE;
	}

	public function verifAnnee($annee) {
		if ($annee >= 1900 && $annee <= date('Y')) return TRUE;

		$this->form_validation->set_message('verifAnnee', 'La %s doit être comprise de 1900 à ' . date('Y'));
		return FALSE;
	}

	public function verifLangue($lang) {
		if ($lang > 0 && $lang < count($this->Medias_m->getListeLangueMedia())) return TRUE;

		$this->form_validation->set_message('verifLangue', 'La %s n\'est pas définie');
		return FALSE;
	}

	public function verifGenreMusic($genre) {
		if ($genre > 0 && $genre < count($this->Medias_m->getListeGenreMusical())) return TRUE;

		$this->form_validation->set_message('verifGenreMusic', 'La %s n\'est pas définie');
		return FALSE;
	}

	public function verifDuree($duree) {
		if (count(preg_split("/(?!\.?$)\d{0,3}(\.\d{0,2})?/", $duree)) <= 2) {
			return TRUE;
		}

		$this->form_validation->set_message('verifDuree', 'La syntaxe de la %s doit être sous la forme xxx.xx');
		return FALSE;
	}

	public function verifTypeAlbum($type) {
		if ($type > 0 && $type < count($this->Medias_m->getTypeAlbum())) return TRUE;

		$this->form_validation->set_message('verifTypeAlbum', 'La %s n\'est pas définie');
		return FALSE;
	}

	public function verifGenreLitter($genre) {
		if ($genre > 0 && $genre < count($this->Medias_m->getListeGenreLitteraire())) return TRUE;

		$this->form_validation->set_message('verifGenreLitter', 'La %s n\'est pas définie');
		return FALSE;
	}

	public function verifGenreCinemat($genre) {
		if ($genre > 0 && $genre < count($this->Medias_m->getListeGenreCinematographique())) return TRUE;

		$this->form_validation->set_message('verifGenreCinemat', 'La %s n\'est pas définie');
		return FALSE;
	}

	public function post_scrobbler() {
		$this->scrobbler($this->input->post('titre'), $this->input->post('artiste'));
	}

	public function scrobbler($titre, $artiste) {
		$this->check_isConnected();

		if (!$this->Medias_m->check_mediaIsExist($titre, $artiste)) redirect('c=Medias_c');
		if ($this->Medias_m->check_scrobbleJourExiste($titre, $artiste, $this->session->userdata('login'))) redirect('c=Medias_c');
		$this->Medias_m->scrobbler($titre, $artiste, $this->session->userdata('login'));
		redirect_back();
	}

	public function afficherStatsSemaine() {
		$this->twig->display('scrobbleSemaine', array(
			'titre'               => "Statistiques Semaine",
			'statArtisteSemaine'  => $this->Medias_m->getStatArtisteSemaine(),
			'statMediaSemaine'    => $this->Medias_m->getStatMediaSemaine(),
			'scrobArtisteSemaine' => $this->Medias_m->getScrobArtisteSemaine(),
			'scrobMediaSemaine'   => $this->Medias_m->getScrobMediaSemaine()
		));
	}

	public function afficherStats() {
		$this->twig->display('scrobble', array(
			'titre'      => "Statistiques globales", 'statArtiste' => $this->Medias_m->getStatArtiste(),
			'statMedia'  => $this->Medias_m->getStatMedia(), 'scrobArtiste' => $this->Medias_m->getScrobArtiste(),
			'scrobMedia' => $this->Medias_m->getScrobMedia()
		));
	}


}
