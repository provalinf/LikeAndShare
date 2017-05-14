<?php
/**
 * Created by IntelliJ IDEA.
 * User: Valentin
 * Date: 07/05/2017
 * Time: 16:43
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Artistes_c extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('twig');
		$this->load->helper(array('url', 'prev_page'));
		$this->load->library(array('session', 'form_validation'));
		$this->load->model('Artistes_m');

		$this->twig->addGlobal('globlogin', $this->session->userdata('login'));
		$this->twig->addGlobal('globadmin', $this->session->userdata('admin'));
	}

	private function check_isConnected() {
		if (empty($this->session->userdata('login'))) redirect('c=Artistes_c');
	}

	public function index() {
		$this->twig->display('artistes_list', array(
			'titre' => "Liste des artistes et auteurs", 'artistes_list' => $this->Artistes_m->getListeArtistes()
		));
	}

	public function addArtiste($donnees = array()) {
		$this->check_isConnected();

		$this->twig->display('form_addArtiste', array_merge($donnees, array(
			'titre' => 'Ajouter un artiste/auteur'
		)));
	}

	public function validFormAddArtiste() {
		$this->check_isConnected();

		$this->form_validation->set_rules('nom_artiste', 'Nom d\'artiste', 'trim|required|is_unique[ARTISTE.NOM_ARTISTE]|max_length[50]');
		$this->form_validation->set_rules('nom', 'Nom', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('prenom', 'Prénom', 'trim|required|max_length[50]');

		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');

		$donnees = array(
			'nom_artiste' => $this->input->post('nom_artiste'), 'nom' => $this->input->post('nom'),
			'prenom'      => $this->input->post('prenom')
		);

		if ($this->form_validation->run() == False) {
			$this->addArtiste($donnees);
		} else {
			$this->Artistes_m->addArtiste($donnees);
			redirect('c=Artistes_c');
		}
	}

}
