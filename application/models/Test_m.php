<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: Valentin
 * Date: 05/05/2017
 * Time: 11:08
 */

class Test_m extends CI_Model {

	public function test() {
		$result = $this->db->from("EMPLOYE")->get();
		return $result->result_array();
	}

}
