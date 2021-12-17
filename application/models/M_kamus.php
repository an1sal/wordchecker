<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kamus extends CI_Model {

	
	public function getBahasa($word)
	{
        $query = $this->db->query("SELECT bahasa FROM kamus WHERE kata = $word");
        return $query->row();
	}
}
