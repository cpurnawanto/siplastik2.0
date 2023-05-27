<?php

namespace App\Controllers\Personal;

use App\Controllers\BaseController;

class Realisasi extends BaseController
{

    public function bulanan($tahun = null, $bulan = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
            $bulan = date('n');
        } else if (!$bulan) {
            $bulan = date('n');
        }

        if (intval($tahun) < 2019 || intval($tahun) > 2100 || intval($bulan) < 1 || intval($bulan) > 12) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Tidak bisa menemukan daftar realisasi dengan parameter yang disediakan');
        }

        $first_day_this_month = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-01';
        if (intval($bulan) === 12) {
            $last_day_this_month  = ($tahun + 1) . '-01-01';
        } else {
            $last_day_this_month  = $tahun . '-' . str_pad($bulan + 1, 2, '0', STR_PAD_LEFT) . '-01';
        }


        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        $realisasi_pegawai = $realisasi_target_model->getRealisasiTargetMany([
            'data_realisasi_target.id_pegawai' => $this->request->user['id'],
            'tanggal_realisasi >= ' => $first_day_this_month,
            'tanggal_realisasi < ' => $last_day_this_month,
        ]);


        return view('personal/indeks_realisasi_kegiatan', [
            'realisasi_pegawai' => $realisasi_pegawai,
            'title' => 'Realisasi Kegiatan (' . date('F Y', strtotime($first_day_this_month)) . ')',
            'tahun' => $tahun,
            'bulan' => $bulan,
            'bulan_tahun' =>  date('F Y', strtotime($first_day_this_month))
        ]);
    }

    public function tambah($id_kegiatan)
    {
        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();

        $target_pegawai = $target_pegawai_model->getTargetKegiatanMany([
            'data_target_pegawai.id_pegawai' => $this->request->user['id'],
            'data_target_pegawai.id_kegiatan' => $id_kegiatan
        ]);

        if (empty($target_pegawai)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kegiatan tidak ditemukan atau anda tidak memiliki akses untuk detail kegiatan ini');
        }
        //first
        $target_kegiatan_pegawai = $target_pegawai[0];

        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        $sum_realisasi_target =  $realisasi_target_model->getSumRealisasiTarget(['id_pegawai' => $this->request->user['id'], 'id_kegiatan' => $id_kegiatan]);
        $sum_realisasi_target_terverifikasi =  intval($realisasi_target_model->getSumRealisasiTarget(['id_pegawai' => $this->request->user['id'], 'id_kegiatan' => $id_kegiatan], true)->where('waktu_acc IS NOT NULL')->first()['count']);

        return view('personal/tambah_realisasi_kegiatan', [
            'title' => 'Tambah Realisasi Kegiatan',
            'target_kegiatan' => $target_kegiatan_pegawai,
            'sum_realisasi_target' => $sum_realisasi_target,
            'sum_realisasi_target_terverifikasi' => $sum_realisasi_target_terverifikasi,
        ]);
    }

    public function do_tambah($id_kegiatan)
    {
        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();

        $target_pegawai = $target_pegawai_model->getTargetKegiatanMany([
            'data_target_pegawai.id_pegawai' => $this->request->user['id'],
            'data_target_pegawai.id_kegiatan' => $id_kegiatan
        ]);

        if (empty($target_pegawai)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kegiatan tidak ditemukan atau anda tidak memiliki akses untuk detail kegiatan ini');
        }

        //first
        $target_kegiatan_pegawai = $target_pegawai[0];


        $post_request = $this->request->getPost();
        if (!isset($post_request['realisasi'])) {
            return redirect()->to(base_url('personal/realisasi/tambah/' . $id_kegiatan));
        }
        $realisasi = $post_request['realisasi'];
        $realisasi['id_kegiatan'] = $id_kegiatan;
        $realisasi['id_pegawai'] = $this->request->user['id'];

        $validation =  \Config\Services::validation();

        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        $validation->setRules($realisasi_target_model->getValidationRules(),  $realisasi_target_model->getValidationMessages());
        if (!$validation->run($realisasi)) {
            $this->session->setFlashdata('errors', $validation->getErrors());
            $this->session->setFlashdata('input', $post_request['realisasi']);
            return redirect()->to(base_url('personal/realisasi/tambah/' . $id_kegiatan));
        }

        $errors = [];

        if (strtotime($target_kegiatan_pegawai['tgl_mulai']) > strtotime($realisasi['tanggal_realisasi'])) {
            $errors['tanggal_realisasi'] = 'Tanggal realisasi tidak boleh melebihi tanggal mulai';
        }
        $sum_realisasi_target =  $realisasi_target_model->getSumRealisasiTarget(['id_pegawai' => $this->request->user['id'], 'id_kegiatan' => $id_kegiatan]);

        if ($sum_realisasi_target + intval($realisasi['jumlah_realisasi']) > intval($target_kegiatan_pegawai['target_pegawai'])) {
            $errors['jumlah_realisasi'] = 'Jumlah realisasi sekarang + ditambahkan (' . $sum_realisasi_target .  ' + ' . intval($realisasi['jumlah_realisasi']) . ') tidak boleh melebihi target realisasi personal (' . intval($target_kegiatan_pegawai['target_pegawai']) . ')';
        }

        if (!empty($errors)) {
            $this->session->setFlashdata('errors', $errors);
            $this->session->setFlashdata('input', $post_request['realisasi']);
            return redirect()->to(base_url('personal/realisasi/tambah/' . $id_kegiatan));
        }

        if ($realisasi_target_model->insert($realisasi)) {
            $this->session->setFlashdata('success', 'Input realisasi sukses ditambahkan');
            return redirect()->to(base_url('personal/kegiatan/detail/' . $id_kegiatan));
        } else {
            $this->session->setFlashdata('errors', $realisasi_target_model->errors());
            $this->session->setFlashdata('input', $post_request['realisasi']);
            return redirect()->to(base_url('personal/realisasi/tambah/' . $id_kegiatan));
        }
    }

    public function ubah($id_realisasi)
    {
        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        $detail_realisasi = $realisasi_target_model
            ->getRealisasiTargetMany([
                'data_realisasi_target.id_pegawai' => $this->request->user['id'],
                'data_realisasi_target.id' => $id_realisasi,
                'data_realisasi_target.waktu_acc' => null
            ], true)
            ->first();

        if (empty($detail_realisasi)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Detail Realisasi tidak ditemukan atau anda tidak mempunyai akses ke halaman ini');
        }

        $sum_realisasi_target =  $realisasi_target_model->getSumRealisasiTarget(['id_pegawai' => $this->request->user['id'], 'id_kegiatan' => $detail_realisasi['id_kegiatan']]);
        $sum_realisasi_target_terverifikasi =  intval($realisasi_target_model->getSumRealisasiTarget(['id_pegawai' => $this->request->user['id'], 'id_kegiatan' => $detail_realisasi['id_kegiatan']], true)->where('waktu_acc IS NOT NULL')->first()['count']);

        $input = $realisasi_target_model->find($id_realisasi);


        return view('personal/ubah_realisasi_kegiatan', [
            'title' => 'Ubah Realisasi Kegiatan',
            'detail_realisasi' => $detail_realisasi,
            'sum_realisasi_target' => $sum_realisasi_target,
            'sum_realisasi_target_terverifikasi' => $sum_realisasi_target_terverifikasi,
            'input' => $input
        ]);
    }

    public function do_ubah($id_realisasi)
    {
        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        $detail_realisasi = $realisasi_target_model
            ->getRealisasiTargetMany([
                'data_realisasi_target.id_pegawai' => $this->request->user['id'],
                'data_realisasi_target.id' => $id_realisasi,
                'data_realisasi_target.waktu_acc' => null
            ], true)
            ->first();

        if (empty($detail_realisasi)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Detail Realisasi tidak ditemukan atau anda tidak mempunyai akses ke halaman ini');
        }

        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();

        $target_pegawai = $target_pegawai_model->getTargetKegiatanMany([
            'data_target_pegawai.id_pegawai' => $detail_realisasi['id_pegawai'],
            'data_target_pegawai.id_kegiatan' => $detail_realisasi['id_kegiatan']
        ]);

        //first
        $target_kegiatan_pegawai = $target_pegawai[0];


        $post_request = $this->request->getPost();
        if (isset($post_request['realisasi'])) {

            $realisasi = $post_request['realisasi'];
            $errors = [];

            if (strtotime($target_kegiatan_pegawai['tgl_mulai']) > strtotime($realisasi['tanggal_realisasi'])) {
                $errors['tanggal_realisasi'] = 'Tanggal realisasi tidak boleh melebihi tanggal mulai';
            }
            $sum_realisasi_target =  $realisasi_target_model->getSumRealisasiTarget([
                'id_pegawai' => $detail_realisasi['id_pegawai'],
                'id_kegiatan' => $detail_realisasi['id_kegiatan']
            ]);

            if ($sum_realisasi_target - $detail_realisasi['jumlah_realisasi'] + intval($realisasi['jumlah_realisasi']) > intval($target_kegiatan_pegawai['target_pegawai'])) {
                $errors['jumlah_realisasi'] = 'Jumlah realisasi lainnya  + realisasi ini (' . ($sum_realisasi_target - $detail_realisasi['jumlah_realisasi']) .  ' + ' . intval($realisasi['jumlah_realisasi']) . ') tidak boleh melebihi target realisasi personal (' . intval($target_kegiatan_pegawai['target_pegawai']) . ')';
            }

            if (!empty($errors)) {
                $this->session->setFlashdata('errors', $errors);
                $this->session->setFlashdata('input', $post_request['realisasi']);
            } else {

                if ($realisasi_target_model->update($id_realisasi, $realisasi)) {
                    $this->session->setFlashdata('success', 'Input realisasi sukses diubah');
                } else {
                    $this->session->setFlashdata('errors', $realisasi_target_model->errors());
                    $this->session->setFlashdata('input', $post_request['realisasi']);
                }
            }
        }


        return redirect()->to(base_url('personal/realisasi/ubah/' . $id_realisasi));
    }


    public function do_hapus($id_realisasi)
    {
        $post_request = $this->request->getPost();

        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        $detail_realisasi = $realisasi_target_model
            ->getRealisasiTargetMany([
                'data_realisasi_target.id_pegawai' => $this->request->user['id'],
                'data_realisasi_target.id' => $id_realisasi,
                'data_realisasi_target.waktu_acc' => null
            ], true)
            ->first();

        if (empty($detail_realisasi)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Detail Realisasi tidak ditemukan atau anda tidak mempunyai akses ke halaman ini');
        }

        if (isset($post_request['realisasi'])) {
            /**
             * memastikan agar request hapus hanya dilakukan oleh form konfirmasi hapus
             */
            $realisasi = $post_request['realisasi'];

            if ($id_realisasi == $realisasi['id_realisasi']) {
                $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
                if ($realisasi_target_model->delete($realisasi['id_realisasi'])) {
                    $this->session->setFlashdata('success', 'Data realisasi sukses dihapus');
                } else {
                    $this->session->setFlashdata('errors', $realisasi_target_model->errors());
                }
            }


            return redirect()->to(base_url('personal/kegiatan/detail/' . $realisasi['id_kegiatan']));
        }

        /**
         * Harusnya kalau valid, tidak sampai sini
         */
        return redirect()->to(base_url('personal/kegiatan/bulanan'));
    }
}
