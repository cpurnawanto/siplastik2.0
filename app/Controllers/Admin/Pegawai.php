<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Pegawai extends BaseController
{
    /**
     * Menampilkan daftar pegawai
     */
    public function index()
    {
        $pegawai_model = new \App\Models\PegawaiModel();
        $all_pegawai = $pegawai_model->getIndexPegawai();
        return view('admin/daftar_pegawai', ['pegawai' => $all_pegawai, 'title' => '[Admin] Daftar Pengguna']);
    }

    /**
     * Menampilkan form tambah pegawai
     * 
     * menggunakan cell Form::pegawai
     */
    public function tambah()
    {
        return view('admin/tambah_pegawai', ['title' => '[Admin] Tambah Pengguna']);
    }

    /**
     * Menghandle post request dari form tambah pegawai.
     * 
     * Berhasil atau gagal, akan redirect kembali ke form tambah pegawai.
     * Bedanya kalau berhasil, nanti ada status sukses, pegawai dimasukkan ke db dan form akan direset.
     * kalau gagal, muncul error dan form akan diautocomplete dengan isian sebelumnya
     */
    public function do_tambah()
    {
        $post_request = $this->request->getPost();

        if (isset($post_request['pegawai'])) {
            $pegawai_model = new \App\Models\PegawaiModel();


            if ($pegawai_model->save($post_request['pegawai'])) {
                $this->session->setFlashdata('success', 'Input Pengguna ' . $post_request['pegawai']['nama_pegawai'] . ' Sukses');
            } else {
                $this->session->setFlashdata('errors', $pegawai_model->errors());
                $this->session->setFlashdata('input', $post_request['pegawai']);
            }
        }

        return redirect()->to(base_url('admin/pegawai/tambah'));
    }

    /**
     * Menampilkan form ubah pegawai
     * 
     * 404 jika id pegawai tidak ditemukan
     * menggunakan cell Form::pegawai
     */
    public function ubah($id)
    {
        $pegawai_model = new \App\Models\PegawaiModel();
        $input = $pegawai_model->getPegawaiOne(['master_pegawai.id' => $id]);
        if (empty($input)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pegawai dengan id = ' . $id . ' tidak ada');
        }

        return view('admin/ubah_pegawai', [
            'title' => '[Admin] Ubah Detail Pegawai ( ' . $input['nama_pegawai'] . ' )',
            'input' => $input
        ]);
    }

    /**
     * Menghandle post request dari form ubah pegawai.
     * dibutuhkan id pegawai, jika tidak maka akan muncul exception
     * 
     * Berhasil atau gagal, akan redirect kembali ke form ubah pegawai.
     * Bedanya kalau berhasil, nanti ada status sukses, data pegawai diubah dan form akan menyesuaikan data terbaru.
     * kalau gagal, muncul error dan form akan direset sesuai data pegawai sebelum percobaan perubahan data
     */
    public function do_ubah($id)
    {
        $post_request = $this->request->getPost();

        if (isset($post_request['pegawai'])) {
            $post_request['pegawai']['id'] = $id;
            $pegawai_model = new \App\Models\PegawaiModel();

            if ($pegawai_model->save($post_request['pegawai'])) {
                $this->session->setFlashdata('success', 'Ubah data pegawai ' . $post_request['pegawai']['nama_pegawai'] . ' sukses');
            } else {
                $this->session->setFlashdata('errors', $pegawai_model->errors());
            }
        }
        return redirect()->to(base_url('admin/pegawai/ubah/' . $id));
    }

    /**
     * Menampilkan form konfirmasi hapus pegawai
     * 
     * 404 jika id pegawai tidak ditemukan
     */
    public function hapus($id)
    {
        $pegawai_model = new \App\Models\PegawaiModel();
        $input = $pegawai_model->getPegawaiOne(['master_pegawai.id' => $id]);

        if (empty($input)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pegawai dengan id = ' . $id . ' tidak ada');
        }

        return view('admin/hapus_pegawai', [
            'title' => '[Admin] Hapus Pegawai ( ' . $input['nama_pegawai'] . ' )',
            'input' => $input
        ]);
    }

    /**
     * Menghandle post request dari form hapus pegawai.
     * dibutuhkan id pegawai, jika tidak maka akan muncul exception
     * 
     * Berhasil atau gagal hapus, akan redirect kembali ke form ubah pegawai.
     */
    public function do_hapus($id)
    {
        $post_request = $this->request->getPost();
        if (isset($post_request['id_hapus'])) {
            /**
             * memastikan agar request hapus hanya dilakukan oleh form konfirmasi hapus
             */
            if ($id == $post_request['id_hapus']) {
                $pegawai_model = new \App\Models\PegawaiModel();
                if ($pegawai_model->delete($post_request['id_hapus'])) {
                    $this->session->setFlashdata('success', 'Data pegawai sukses dihapus');
                } else {
                    $this->session->setFlashdata('errors', $pegawai_model->errors());
                }
            }
        }

        return redirect()->to(base_url('admin/pegawai'));
    }


    public function import()
    {
        return view('admin/import_pegawai', ['title' => '[Admin] Import Pengguna']);
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
            foreach ($data as $col => $row) {
                // skip header 
                if ($col < 2) {
                    continue;
                }

                // PERHATIKAN TEMPLATE!
                $pegawai_model = new \App\Models\PegawaiModel();
                $pegawai = [];

                $pegawai['nama_pegawai'] = esc($row[0]);
                $pegawai['nama_singkat'] = esc($row[1]);
                //$pegawai['nip_lama'] = esc($row[2]);
                //$pegawai['nip_baru'] = esc($row[3]);
                //$pegawai['id_golongan'] = esc($row[4]);
                //$pegawai['id_unit_kerja'] = esc($row[5]);
                //$pegawai['id_eselon'] = esc($row[6]);
                //$pegawai['id_fungsional'] = esc($row[7]);
                $pegawai['is_aktif'] = 'true';
                $pegawai['username'] = esc($row[2]);
                $pegawai['password'] = esc($row[3]);
                $pegawai['password2'] = esc($row[3]);
                $pegawai['is_admin'] = $row[4] == 1 ? 'true' : 'false';
                //$pegawai['id_wilayah'] = esc($row[11]);

                // Mencoba save pegawai,
                // Karena tidak ada id maka insert mode
                if ($pegawai_model->save($pegawai)) {
                    array_push($import_status, [
                        'data' => [
                            'nama_pegawai' => $pegawai['nama_pegawai'],
                            //'nip_lama' => $pegawai['nip_lama'],
                            //'nip_baru' => $pegawai['nip_baru']
                        ],
                        'success' => 'Berhasil dimasukkan'
                    ]);
                    $success_count++;
                } else {
                    array_push($import_status, [
                        'data' => [
                            'nama_pegawai' => $pegawai['nama_pegawai'],
                            //'nip_lama' => $pegawai['nip_lama'],
                            //'nip_baru' => $pegawai['nip_baru']
                        ],
                        'errors' => $pegawai_model->errors()
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

        return redirect()->to(base_url('admin/pegawai/import'));
    }
}
