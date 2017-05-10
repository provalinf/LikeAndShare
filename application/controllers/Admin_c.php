<?php
/**
 * Created by IntelliJ IDEA.
 * User: Valentin
 * Date: 05/05/2017
 * Time: 18:29
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_c extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('twig');
		$this->load->helper(array('url', 'prev_page'));
		$this->load->library(array('session'));
        $this->load->model('Admin_m');

		$this->twig->addGlobal('globlogin', $this->session->userdata('login'));
		$this->twig->addGlobal('globadmin', $this->session->userdata('admin'));

		$this->check_droit();
	}

	private function check_droit() {
		if ($this->session->userdata('admin') != 1) redirect(base_url());
	}

	public function index() {
		echo "Administration";
	}



}
