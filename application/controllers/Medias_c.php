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
		$this->load->library(array('session'));
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

	public function index() {
		/*$this->twig->display('index', array('titre' => "Page d'accueil"));*/
		echo "Administration";
	}

}
