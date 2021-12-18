<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kamus extends CI_Model {

	
	function getBahasa($word)
	{
        $query = $this->db->query("SELECT kategori FROM kamus_kata WHERE kosa_kata = '$word'");
        return $query->row_array();
	}
}
