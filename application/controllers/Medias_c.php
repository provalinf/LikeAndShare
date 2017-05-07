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
		//$this->load->model('Users_m');

		$this->twig->addGlobal('globlogin', $this->session->userdata('login'));
		$this->twig->addGlobal('globadmin', $this->session->userdata('admin'));
	}

    private function showList() {
        if (!empty($this->session->userdata('login'))) redirect(base_url());
    }

	public function index() {
		/*$this->twig->display('index', array('titre' => "Page d'accueil"));*/
		echo "Administration";
	}

}
