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
		$this->load->library(array('session'));
		$this->load->model('Artistes_m');

		$this->twig->addGlobal('globlogin', $this->session->userdata('login'));
		$this->twig->addGlobal('globadmin', $this->session->userdata('admin'));

	}

	public function index() {
		$this->twig->display('artistes_list', array(
			'titre' => "Liste des artistes et auteurs", 'artistes_list' => $this->Artistes_m->getListeArtistes()
		));
	}


}
