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
		$this->load->helper(array('url'));
		$this->load->library(array('session', 'form_validation'));
		$this->load->model('Medias_m');

		$this->twig->addGlobal('globlogin', $this->session->userdata('login'));
		$this->twig->addGlobal('globadmin', $this->session->userdata('admin'));
	}

	public function liste_Medias() {
		$this->twig->display('medias_list', array(
			'titre'       => "Liste des médias", 'musiques_list' => $this->Medias_m->getTitresMusicaux(),
			'livres_list' => $this->Medias_m->getLivres(), 'films_list' => $this->Medias_m->getFilms()
		));
	}

	public function addMedia($type = null) {
		if ($this->verif_typeMedia($type)) {
			$type = null;
		} else {
			$this->form_validation->set_rules('type_media', 'Type de média', 'trim|required|numeric');
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			if ($this->form_validation->run() != False) {
				$type = $this->input->post('type_media');
			}
		}
		$this->twig->display('form_addMedia', array(
			'titre' => "Ajouter un média", 'type' => $type, 'list_typeMedia' => $this->Medias_m->getTypesMedia()
		));
	}

	public function verif_typeMedia($type) {
		if ($type < 1 || $type > count($this->Medias_m->getTypesMedia())) return TRUE;

		$this->form_validation->set_message('verif_typeMedia', 'Le %s n\'est pas définie');
		return FALSE;
	}

	public function validFormAddMedia($type) {
		if ($type < 1 || $type > count($this->Medias_m->getTypesMedia())) {
			redirect_back();
		}

		if ($type == 1) {

		}
	}

}
