<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;

class C_kamus extends CI_Controller {


	public function inputFile()
	{
		$this->load->model('M_kamus');
		// $config['upload_path'] = './upload/';
        // $config['allowed_types'] = 'txt';
        // $config['max_size'] = 2000;

        // $this->load->library('upload', $config);

		// $fileName;

		// if ($this->do_upload('file'))
		//{
			// $fileData = $this->upload->data();
			$fileName = "./dummy.txt";
			$filePath = $fileName;

			// $filePath = $config['upload_path'] . $fileName;

			$myFile = fopen($filePath, "r") or die("Unable to open file!");
			$stringFile = fread($myFile,filesize($filePath));
			// echo $stringFile;
			fclose($myFile);
		// }
		$stemmerFactory = new StemmerFactory();
		$stemmer = $stemmerFactory->createStemmer();

		$stopWordFactory = new StopWordRemoverFactory();
		$stopWord = $stopWordFactory->createStopWordRemover();

		$arrayString = preg_split("/(\s)/ ", $stringFile);

		$inggris = 0;
		$indonesia = 0;
		$unknown = 0;

		foreach ($arrayString as $word)
		{
			$output = $stemmer->stem($word);
			echo $word . "->";
			echo $output . "<br>";
			$bahasa = $this->M_kamus->getBahasa($output);
			switch ($bahasa) {
				case 'Indonesia':
					$indonesia += 1;
					break;
				case 'Inggris':
					$inggirs += 1;
					break;
				default:
					$unknown += 1;
					break;
			}
		}
	}
}
