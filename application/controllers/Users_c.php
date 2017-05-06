<?php
/**
 * Created by IntelliJ IDEA.
 * User: Valentin
 * Date: 05/05/2017
 * Time: 18:29
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_c extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('twig');
		$this->load->helper(array('url'));
		$this->load->library(array('session', 'form_validation'));
		$this->load->model('Users_m');

		$this->twig->addGlobal('globlogin', $this->session->userdata('login'));
		$this->twig->addGlobal('globadmin', $this->session->userdata('admin'));
	}

	private function check_isConnected() {
		if (!empty($this->session->userdata('login'))) redirect(base_url());
	}

	public function index() {
		$this->twig->display('index', array('titre' => "Page d'accueil"));
	}

	public function connexion($donnees = array()) {
		$this->check_isConnected();

		$this->twig->display('form_connexion', array_merge($donnees, array(
			'titre' => "Page de connexion"
		)));
	}

	public function form_valid_connexion() {
		$this->check_isConnected();

		$this->form_validation->set_rules('login', 'login', 'trim|required');
		$this->form_validation->set_rules('pass', 'Mot de passe', 'trim|required');

		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');

		$donnees = array(
			'login' => $this->input->post('login'), 'pass' => $this->input->post('pass')
		);
		if ($this->form_validation->run() == False) {
			$this->twig->display('form_connexion', $donnees);
		} else {
			if (($donnees_session = $this->Users_m->verif_connexion($donnees)) != False) {
				$this->session->set_userdata($donnees_session);
				redirect(base_url());
			} else {
				$donnees['erreur'] = "Pseudo ou mot de passe incorrect";
				$this->connexion($donnees);
			}
		}
	}

	public function deconnexion() {
		$this->session->sess_destroy();
		redirect(base_url());
	}

	public function inscription($donnees = array()) {
		$this->check_isConnected();

		$this->twig->display('form_inscription', array_merge($donnees, array(
			'titre' => "Page d'inscription", 'liste_categoAge' => $this->Users_m->getCategorieAgeDropdown()
		)));
	}

	public function validFormInscription() {
		$this->check_isConnected();
		$this->form_validation->set_rules('login', 'Pseudo', 'trim|required|is_unique[UTILISATEUR.PSEUDO]|min_length[4]|max_length[8]');
		$this->form_validation->set_rules('pass', 'Mot de passe', 'trim|required|min_length[6]|max_length[12]');
		$this->form_validation->set_rules('pass2', 'Confirmation de mot de passe', 'trim|required|matches[pass]');
		$this->form_validation->set_rules('code_postal', 'Code postal', 'trim|required|numeric|min_length[5]|max_length[5]');
		$this->form_validation->set_rules('catego_age', 'Catégorie d\'âge', 'trim|required|callback_verif_categoAge');

		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');

		$donnees = array(
			'login'      => $this->input->post('login'), 'pass' => $this->input->post('pass'),
			'catego_age' => $this->input->post('catego_age'), 'code_postal' => $this->input->post('code_postal'),
			'admin'      => $this->input->post('admin')
		);

		if ($this->form_validation->run() == False) {
			$this->inscription($donnees);
		} else {
			$donnees['catego_age'] = $this->Users_m->getCategorieAgeDropdown()[$donnees['catego_age']];
			$donnees['admin']      = $donnees['admin'] == 1 ? 1 : 0;
			$this->Users_m->add_user($donnees);
			redirect(base_url());
		}
	}

	public function verif_categoAge($age) {
		if ($age > 0 && $age < count($this->Users_m->getCategorieAgeDropdown())) return TRUE;

		$this->form_validation->set_message('verif_categoAge', 'La %s n\'est pas définie');
		return FALSE;
	}

}
