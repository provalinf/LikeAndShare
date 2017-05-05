<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General_c extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('twig');
		$this->load->helper(array('url'));
		$this->load->model(array('Test_m'));
	}

	public function index() {
		$this->twig->display("index", array('titre' => "Titre de la page"));
	}

	public function teetetetet() {
		echo "Seconde page";
	}
}
