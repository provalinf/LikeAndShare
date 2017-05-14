<?php

/**
 * Created by IntelliJ IDEA.
 * User: Valentin
 * Date: 05/05/2017
 * Time: 11:08
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_m extends CI_Model {
	public function genererStatSemaine() {
		return $this->db->insert('SEMAINE', array(
			'NUM_SEMAINE' => date('W'), 'ANNEE' => date('Y')
		));
	}

	public function getLastGenerationStat() {
		$this->db->from('SEMAINE')->order_by('NUM_SEMAINE', 'DESC')->order_by('ANNEE', 'DESC')->limit(1);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function check_isSemaineGenerate() {
		$last = $this->getLastGenerationStat();
		if (count($last) == 0) return false;

		if ($last['NUM_SEMAINE'] == date('W') && $last['ANNEE'] == date('Y')) return true;
		return false;
	}
}
