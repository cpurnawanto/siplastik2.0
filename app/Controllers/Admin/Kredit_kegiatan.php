<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Kredit_kegiatan extends BaseController
{
    public function index()
    {
        return view('admin/indeks_kredit_kegiatan', ['title' => '[Admin] Indeks Kredit Kegiatan']);
    }

    public function import()
    {
        return view('admin/import_kredit_kegiatan', ['title' => '[Admin] Import Data Angka Kredit Kegiatan']);
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
            // ID untuk fungsional pada tabel
            $fungsional_ids = [
                11, 12, 13, 14, 21, 22, 23, // Statistisi
                31, 32, 33, 34, 41, 42, 43  // Prakom
            ];


            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            $spreadsheet = $reader->load($file);

            $data = $spreadsheet->getSheetByName('data')->toArray();

            foreach ($data as $col => $row) {
                //skip header
                if ($col < 4) {
                    continue;
                }

                // untuk menampung error jika ada
                $row_errors = [];

                $kredit_kegiatan_model = new \App\Models\KreditKegiatanModel();
                $kredit_kegiatan = [];

                /**
                 * CEK MIGRASI TABEL, MODEL, DAN TEMPLATE XLS!!!
                 * jangan sampai tidak konsisten
                 * rumus id = 'kode'.'kode_tingkat'
                 */
                $kredit_kegiatan['id'] = $row[0] . $row[2];

                $kredit_kegiatan['kode'] = $row[0];
                $kredit_kegiatan['nama_tingkat'] = $row[1];
                $kredit_kegiatan['kode_tingkat'] = $row[2];
                $kredit_kegiatan['kode_perka'] = $row[3];
                $kredit_kegiatan['kode_unsur'] = $row[4];
                $kredit_kegiatan['nama_unsur'] = $row[5];
                $kredit_kegiatan['uraian_singkat'] = $row[6];
                $kredit_kegiatan['kegiatan'] = $row[7];
                $kredit_kegiatan['satuan_hasil'] = $row[8];
                $kredit_kegiatan['bukti_fisik'] = $row[9];
                $kredit_kegiatan['pelaksana_kegiatan'] = $row[10];
                $kredit_kegiatan['bidang'] = $row[11];
                $kredit_kegiatan['seksi'] = $row[12];
                $kredit_kegiatan['keterangan'] = $row[13];
                $kredit_kegiatan['angka_kredit'] = $row[14];


                if ($kredit_kegiatan_model->insert($kredit_kegiatan) !== false) {

                    /**
                     * Jika kredit kegiatan model sudah terinsert...
                     */

                    $data_kredit_fungsional = [];
                    foreach ($fungsional_ids as $idx => $id_fun) {
                        //offset col = 15
                        $angka_kredit = $row[15 + $idx];
                        if (!empty($angka_kredit)) {
                            $kredit_fungsional = [
                                'id_fungsional' => $id_fun,
                                'id_kegiatan' => $kredit_kegiatan['id'],
                                'angka_kredit' => $angka_kredit
                            ];

                            array_push($data_kredit_fungsional, $kredit_fungsional);
                        }
                    }

                    $kredit_fungsional_model = new \App\Models\KreditFungsionalModel();

                    if (!empty($data_kredit_fungsional)) {
                        /** All or Nothing approach untuk setiap baris untuk menjaga konsistensi tabel */
                        if ($kredit_fungsional_model->insertBatch($data_kredit_fungsional) === false) {
                            $row_errors = $kredit_fungsional_model->errors();
                            $kredit_kegiatan_model->delete($kredit_kegiatan['id']);
                        }
                    }
                } else {
                    /**
                     * Kesalahan karena query tabel kredit_kegiatan
                     */
                    $row_errors = $kredit_kegiatan_model->errors();
                }

                /**
                 * Untuk menampikan import status sesuai nilai dan $row_errors
                 */
                if (empty($row_errors)) {
                    array_push($import_status, [
                        'data' => [
                            // $success_count + $error_count = baris telah diinput
                            'baris_excel' => $success_count + $error_count + 5,
                            'kode' => $kredit_kegiatan['kode'],
                            'kode_perka' => $kredit_kegiatan['kode_perka'],
                            'nama_tingkat' => $kredit_kegiatan['nama_tingkat'],
                            'nama_unsur' => $kredit_kegiatan['nama_unsur']
                        ],
                        'success' => 'Berhasil dimasukkan'
                    ]);
                    $success_count++;
                } else {
                    array_push($import_status, [
                        'data' => [
                            'baris_excel' => $success_count + $error_count + 5,
                            'kode' => $kredit_kegiatan['kode'],
                            'kode_perka' => $kredit_kegiatan['kode_perka'],
                            'nama_tingkat' => $kredit_kegiatan['nama_tingkat'],
                            'nama_unsur' => $kredit_kegiatan['nama_unsur']
                        ],
                        'errors' => $row_errors
                    ]);
                    $error_count++;
                }
            }
            /**
             * Meskipun 0 dari sekian baris berhasil diinsert, tetap dianggap sukses import
             */
            $this->session->setFlashdata('success', 'Data diimport, berhasil memproses ' . $success_count . ' dari ' . ($success_count + $error_count) . ' baris');
            $this->session->setFlashdata('import_status', $import_status);
        } else {
            //Error jika ada masalah di excel (salah upload format etc...)
            $this->session->setFlashdata('errors', $validation->getErrors());
        }

        return redirect()->to(base_url('admin/kredit_kegiatan/import'));
    }

    /**
     * Controller untuk melihat detil kegiatan
     * 404 jika id master_kredit_kegiatan tidak ditemukan
     */
    public function lihat($id)
    {
        $kredit_kegiatan_model = new \App\Models\KreditKegiatanModel();
        $kredit_kegiatan = $kredit_kegiatan_model->find($id);

        if (empty($kredit_kegiatan)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Rincian kredit kegiatan dengan id = ' . $id . ' tidak ada');
        }

        $kredit_fungsional_model = new \App\Models\KreditFungsionalModel();
        $kredit_fungsional = $kredit_fungsional_model->getKreditKegiatanFungsional($id);


        return view('admin/lihat_kredit_kegiatan', [
            'title' => '[Admin] Lihat Detail Kredit Kegiatan (' . $kredit_kegiatan['kode'] . ' - ' . $kredit_kegiatan['nama_tingkat'] . ')',
            'data' => [
                'kredit_kegiatan' => $kredit_kegiatan,
                'kredit_fungsional' => $kredit_fungsional
            ],
        ]);
    }
}
