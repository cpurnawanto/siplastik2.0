<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BerkasModel;
use App\Models\KecamatanModel;
use App\Models\DesaModel;

class Berkas extends BaseController
{

	public function index()
		{
			$berkas = new BerkasModel();
			$data['WA'] = $berkas->getJenisPeta(['berkas.kdJenis' => 'WA']);
			$data['WB1'] = $berkas->getJenisPeta(['berkas.kdJenis' => 'WB1']);
			$data['WB2'] = $berkas->getJenisPeta(['berkas.kdJenis' => 'WB2']);
			$data['WS'] = $berkas->getJenisPeta(['berkas.kdJenis' => 'WS']);
			$data['title'] = 'Beranda';
			return view('view_berkas', $data);
		}
	public function create()
	{
		$kecamatan = new KecamatanModel();
		$desa = new DesaModel();
		$data['kec'] = $kecamatan->getIndexKecamatan();
		$data['desa'] = $desa->getBanyakDesa();
		$data['title'] = '[Admin] Unggah Peta';
		return view('upload_berkas', $data);
	}
	public function save()
	{
		if (!$this->validate([
			'keterangan' => [
				'rules' => 'required',
				'errors' => [
					'required' => '{field} Tidak boleh kosong'
				]
			],
			'berkas' => [
				'rules' => 'uploaded[berkas]|mime_in[berkas,image/jpg,image/jpeg,image/gif,image/png]',
				'errors' => [
					'uploaded' => 'Harus Ada File yang diupload',
					'mime_in' => 'File Extention Harus Berupa jpg,jpeg,gif,png'
				]
 
			]
		])) {
			session()->setFlashdata('error', $this->validator->listErrors());
			return redirect()->back()->withInput();
		}
 

		$folderUpload = "uploads/berkas";
		$files = $_FILES;
		$jumlahFile = count($files['berkas']['name']);
		for ($i = 0; $i < $jumlahFile; $i++) {
			
			$namaFile = $files['berkas']['name'][$i];
			$lokasiTmp = $files['berkas']['tmp_name'][$i];
		
			# kita tambahkan uniqid() agar nama gambar bersifat unik
			$namaBaru = uniqid() . '-' . $namaFile;
			$lokasiBaru = "{$folderUpload}/{$namaBaru}";
			$berkas = new BerkasModel();
			$data = [
				'berkas' => $namaBaru,
				'keterangan' => $this->request->getPost('keterangan'),
				'kdKec' => $this->request->getPost('kdKec'),
				'kdDesa' => $this->request->getPost('kdDesa'),
				'kdJenis' => $this->request->getPost('kdJenis'),
				'kdBS' => $this->request->getPost('kdBS')
			];
			$berkas->set($data);
			$insert = $berkas->insert();
			$statusMsg = $errorMsg = '';
			if($insert){ 
                $statusMsg = "Files are uploaded successfully.".$errorMsg; 
            }else{ 
                $statusMsg = "Sorry, there was an error uploading your file."; 
            } 
			$prosesUpload = move_uploaded_file($lokasiTmp, $lokasiBaru);


			session()->setFlashdata('success', $namaBaru . ' Berhasil diupload');
		}
		return redirect()->to(base_url('berkas'));
	}

	function download($id)
		{
			$berkas = new BerkasModel();
			$data = $berkas->find($id);
			return $this->response->download('uploads/berkas/' . $data->berkas, null);
		}
	public function import()
	{
		return view('unduh_template', ['title' => 'Unduh Peta via Template']);
	}
	
	public function do_import()
		{
	
			$validation =  \Config\Services::validation();
			
	
			$file = $this->request->getFile('excel_import');
			/**
			 * Rule validasi file excel ada di \Config\Validation::excel_import
			 */
			if ($validation->run(['excel_import' => $file], 'excel_import')) {
	
				/**
				 * Struktur import status
				 * [
				 *     'data' => [
				 *         'kolom1' => 'data1',
				 *         'kolom2' => 'data2',
				 *         ... 
				 *      ],
				 *     'errors' => [..., ...],
				 *     'success => ...
				 * ]
				 */
				$import_status = [];
				$success_count = 0;
				$error_count = 0;
	
	
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
				$spreadsheet = $reader->load($file);
				
	
				$data = $spreadsheet->getSheetByName('data')->toArray();

				$zipname = 'file.zip';
				helper('filesystem');
				if(file_exists($zipname)){
					unlink($zipname);
				}
				$zip = new \ZipArchive();
				$zip->open($zipname, \ZipArchive::CREATE);
				$arrayFile = array();
				$wa = "";
				$jenis = "";


				foreach ($data as $col => $row) {
					//donlot WA gak?
					if($col == 0){
						if($row[1] == '1'){
							$wa = 'WA';
						} else {
							$wa = '';
						}
					}
					if($col == 1){
						if($row[1] == 'WB1'){
							$jenis = 'WB1';
						} elseif($row[1] == 'WB2') {
							$jenis = 'WB2';
						} elseif($row[1] == 'WS'){
							$jenis = 'WS';
						}
					}
					// skip header 
					if ($col < 4) {
						continue;
					}
					if ($row[0] == NULL) {
						continue;
					}

					// PERHATIKAN TEMPLATE!
					$berkas_model = new \App\Models\BerkasModel();
					$berkas1 = [];

					$berkas1['IDDesa'] = esc(substr(strval($row[0]), 0, 10));
					$berkas1['IDBS'] = esc($row[0]);
					$berkas1['kdKec'] = esc(substr(strval($row[0]), 4, 3));
					$berkas1['kdDesa'] = esc(substr(strval($row[0]), 7, 3));
					if(!empty(substr(strval($row[0]), 10, 4))){
						$berkas1['kdBS'] = esc(substr(strval($row[0]), 10, 4));
					} else {
							$berkas1['kdBS'] = "";
							}
					//$berkas = $berkas_model->getSatuPeta(['si_berkas.kdKec =' . $berkas1['kdKec'] . 'AND si_berkas.kdDesa =' . $berkas1['kdDesa'] . 'AND kdBS =' . $berkas1['kdBS']]);
					// :)
					//$jenis = 'WB2';
					if(empty($jenis)){
						continue;
					}
					$berkas = $berkas_model->where('CONCAT("6110",si_berkas.kdKec,si_berkas.kdDesa,kdBS)',$berkas1['IDBS'])->where('si_berkas.kdJenis',$jenis)->first();
											//->where(['si_berkas.kdJenis' => 'WB1'])
											//->first();
					if ($wa == 'WA'){
						$berkaswa = $berkas_model->where('CONCAT("6110",si_berkas.kdKec,si_berkas.kdDesa)',$berkas1['IDDesa'])->where('si_berkas.kdJenis', $wa)->first(); 
						}
					print_r($berkas1['IDBS']);
						if(!empty($berkas->berkas)) {
							$namaPeta = "uploads/berkas/$berkas->berkas";
							if(!empty($berkaswa->berkas)){	
								$namaWA = "uploads/berkas/$berkaswa->berkas";
								$zip->addFile($namaWA,$berkaswa->berkas);
							}
							array_push($arrayFile,strval($berkas->berkas)); //biar gak duplikat
								$zip->addFile($namaPeta,$berkas->berkas);

								array_push($import_status, [
									'data' => [
										'kdKec' => $berkas1['kdKec'],
										'kdDesa' => $berkas1['kdDesa'],
										'kdBS' => $berkas1['kdBS']
									],
									'success' => 'Berhasil diimport'
								]);
								$success_count++;
							
						} else {
							array_push($import_status, [
								'data' => [
									'kdKec' => $berkas1['kdKec'],
									'kdDesa' => $berkas1['kdDesa'],
									'kdBS' => $berkas1['kdBS']
								],
								'errors' => $berkas_model->errors()
							]);
							$error_count++;
						}		
				}
				$zip->close();
				//stream file zipnya
				header('Content-Type: application/zip');
				header('Content-disposition: attachment; filename='.$zipname);
				header('Content-Length: ' . filesize($zipname));
				//force download filenya
				/**
				 * Meskipun 0 dari sekian baris berhasil diinsert, tetap dianggap sukses import
				 */
				$this->session->setFlashdata('success', 'Data diimport, berhasil memproses ' . $success_count . ' dari ' . ($success_count + $error_count) . ' baris');
				$this->session->setFlashdata('import_status', $import_status);
				return redirect()->to(base_url('berkas/download_import'));
			} else {
				//Error jika ada masalah di excel (salah upload format etc...)
				$this->session->setFlashdata('errors', $validation->getErrors());
				return redirect()->to(base_url('berkas/import'));
			}
		}
	public function download_import(){
		return view('download_import', ['title' => 'Unduh Peta Ter-import']);
	}	
}
