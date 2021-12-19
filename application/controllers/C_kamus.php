<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Sastrawi\Stemmer\StemmerFactory;

class C_kamus extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper('url', 'form'); 
        $this->load->library('form_validation');
    }

	public function home()
	{
		$session = $this->session->userdata('count');
		$data['data_points'] = array();
		if(!isset($session)){
			$data['error'] = "Tidak ada data";
		} else {
			foreach ($session as $key => $value){
				if($key != 'Total'){
					$arrayData = array(
						'y' => $value/$session['Total']*100,
						'label' => $key
					);
					array_push($data['data_points'], $arrayData);
				}
			}
			$data['result_content'] = $session;
			$data['unknown_words'] = $this->session->userdata('unknownword');
		}
		$this->load->view('V_kamus', $data);
	}

	public function inputFile()
	{
		$this->load->model('M_kamus');
		$config['upload_path'] = './upload/';
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 20000;

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('file'))
        {
            $fileData = $this->upload->data();
            $fileName = $fileData['file_name'];
        }

		$filePath = $fileData['file_path'] . $fileName;

		$parser = new Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($filePath);

		$text = $pdf->getText();
		// var_dump($text);

		// echo $text;

		$stemmerFactory = new StemmerFactory();
		$stemmer = $stemmerFactory->createStemmer();

		// $arrayString = preg_split("/(\s,\s),+(\s,\s)/", $filePath);
		// $arrayString = preg_split('/\s+/', $filePath);
		$arrayString = preg_split('/\W/', $text, 0, PREG_SPLIT_NO_EMPTY);
		// var_dump($arrayString);

		// echo $filePath . "<br>" . "<br>";
		// var_dump($arrayString);

		$inggris = 0;
		$indonesia = 0;
		$daerah = 0;
		$gaul = 0;
		$unknown = 0;
		$UW = array();

		foreach ($arrayString as $word)
		{
			$output = $stemmer->stem($word);
			// echo $word . "->";
			// echo $output . "<br>";
			$kategori = $this->M_kamus->getBahasa($output);
			// switch($kategori["kategori"]){
			// 	case 'indonesia':
			// 		$indonesia += 1;
			// 		break;
				  
			// 	default:
			// 		$unknown += 1;
			// 		break;
			// }
			if($kategori != null)
			{
				if($kategori["kategori"] == 'indonesia')
				{
					$indonesia += 1;
				}
				else if($kategori["kategori"] == 'daerah')
				{
					$daerah += 1;
				}
				else if($kategori["kategori"] == 'inggris')
				{
					$inggris += 1;
				}
				else if($kategori["kategori"] == 'gaul')
				{
					$gaul += 1;
				}
			} else {
				$unknown += 1;
				array_push($UW, $word);
			}
			$total = $indonesia + $daerah + $inggris + $gaul + $unknown;
		}
		$sessionData = array(
			'Indonesia' => $indonesia,
			'Daerah' => $daerah,
			'Inggris' => $inggris,
			'Gaul' => $gaul,
			'Unknown' => $unknown,
			'Total' => $total
		);
		$this->session->set_userdata('count',$sessionData);
		$this->session->set_userdata('unknownword', $UW);
		unlink($filePath);
		redirect(base_url('C_kamus/home'));
	}

	public function reset()
	{
		session_destroy();
		redirect(base_url('C_kamus/home'));
	}
}
