<?php

namespace App\Controllers\Struktural;

use App\Controllers\BaseController;

class Realisasi extends BaseController
{
    public function detail_verifikasi($id_realisasi)
    {
        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        $detail_realisasi = $realisasi_target_model
            ->getRealisasiTargetMany([
                'data_kegiatan.id_unit_kerja' => $this->request->user['id_unit_kerja'],
                'data_realisasi_target.id' => $id_realisasi
            ], true)
            ->first();

        if (empty($detail_realisasi)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Detail Realisasi tidak ditemukan atau anda tidak mempunyai akses ke halaman ini');
        }
        return view('struktural/detail_verifikasi_realisasi_kegiatan', ['title' => 'Verifikasi Realisasi Kegiatan', 'detail_realisasi' => $detail_realisasi]);
    }


    public function verifikasi()
    {
        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();

        $menunggu_verifikasi = $realisasi_target_model
            ->getRealisasiTargetMany(['data_kegiatan.id_unit_kerja' => $this->request->user['id_unit_kerja']], true)
            ->where('data_realisasi_target.waktu_acc IS NULL')
            ->findAll();

        $unit_kerja = $this->request->user['unit_kerja'];

        return view('struktural/verifikasi_realisasi_kegiatan', ['title' => 'Realisasi Menunggu Verifikasi (Unit Kerja ' . $unit_kerja . ')', 'daftar_realisasi' => $menunggu_verifikasi, 'unit_kerja' => $unit_kerja]);
    }


    public function do_verifikasi($id_realisasi)
    {
        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        $detail_realisasi = $realisasi_target_model
            ->getRealisasiTargetMany([
                'data_kegiatan.id_unit_kerja' => $this->request->user['id_unit_kerja'],
                'data_realisasi_target.id' => $id_realisasi
            ], true)
            ->first();

        if (empty($detail_realisasi)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Detail Realisasi tidak ditemukan atau anda tidak mempunyai akses ke halaman ini');
        }

        if ($realisasi_target_model->update($id_realisasi, [
            'waktu_acc' => date('Y-m-d H:i:s'),
            'id_pegawai_acc' => $this->request->user['id'],
        ])) {
            $this->session->setFlashdata('success', 'Realisasi kegiatan berhasil diverifikasi');
        } else {
            $this->session->setFlashdata('errors', $realisasi_target_model->errors());
        }

        return redirect()->to(base_url('struktural/realisasi/verifikasi'));
    }

    public function tambah($id_kegiatan)
    {

        $kegiatan_model = new \App\Models\DataKegiatanModel();
        $kegiatan = $kegiatan_model->find($id_kegiatan);

        if (empty($kegiatan)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kegiatan tidak ditemukan atau anda tidak memiliki akses untuk detail kegiatan ini');
        }

        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();
        $target_pegawai = $target_pegawai_model->getTargetKegiatanMany([
            'data_target_pegawai.id_kegiatan' => $id_kegiatan
        ]);

        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();

        $sum_realisasi_target =  $realisasi_target_model->getSumRealisasiTarget(['id_kegiatan' => $id_kegiatan]);
        $sum_realisasi_target_terverifikasi =  intval($realisasi_target_model->getSumRealisasiTarget(['id_kegiatan' => $id_kegiatan], true)->where('waktu_acc IS NOT NULL')->first()['count']);

        return view('struktural/tambah_realisasi_kegiatan', [
            'title' => 'Tambah Realisasi Kegiatan',
            'kegiatan' => $kegiatan,
            'target_pegawai' => $target_pegawai,
            'sum_realisasi_target' => $sum_realisasi_target,
            'sum_realisasi_target_terverifikasi' => $sum_realisasi_target_terverifikasi,
        ]);
    }

    public function do_tambah($id_kegiatan)
    {

        $post_request = $this->request->getPost();
        if (isset($post_request['realisasi'])) {

            $realisasi = $post_request['realisasi'];
            $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();

            $target_pegawai = $target_pegawai_model->getTargetKegiatanMany([
                'data_target_pegawai.id_pegawai' => $realisasi['id_pegawai'],
                'data_target_pegawai.id_kegiatan' => $id_kegiatan
            ]);

            if (empty($target_pegawai)) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kegiatan tidak ditemukan atau anda tidak memiliki akses untuk detail kegiatan ini');
            }

            //first
            $target_kegiatan_pegawai = $target_pegawai[0];

            $realisasi['id_kegiatan'] = $id_kegiatan;
            if (isset($realisasi['verifikasi'])) {
                if ($realisasi['verifikasi'] === 'true') {
                    $realisasi['waktu_acc'] = date('Y-m-d H:i:s');
                    $realisasi['id_pegawai_acc'] = $this->request->user['id'];
                } else {
                    $realisasi['waktu_acc'] = null;
                }

                unset($realisasi['verifikasi']);
            }

            $validation =  \Config\Services::validation();

            $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
            $validation->setRules($realisasi_target_model->getValidationRules(),  $realisasi_target_model->getValidationMessages());
            if (!$validation->run($realisasi)) {
                $this->session->setFlashdata('errors', $validation->getErrors());
                $this->session->setFlashdata('input', $post_request['realisasi']);
                return redirect()->to(base_url('struktural/realisasi/tambah/' . $id_kegiatan));
            }

            $errors = [];

            if (strtotime($target_kegiatan_pegawai['tgl_mulai']) > strtotime($realisasi['tanggal_realisasi'])) {
                $errors['tanggal_realisasi'] = 'Tanggal realisasi tidak boleh melebihi tanggal mulai';
            }
            $sum_realisasi_target =  $realisasi_target_model->getSumRealisasiTarget(['id_pegawai' => $realisasi['id_pegawai'], 'id_kegiatan' => $id_kegiatan]);

            if ($sum_realisasi_target + intval($realisasi['jumlah_realisasi']) > intval($target_kegiatan_pegawai['target_pegawai'])) {
                $errors['jumlah_realisasi'] = 'Jumlah realisasi sekarang + ditambahkan (' . $sum_realisasi_target .  ' + ' . intval($realisasi['jumlah_realisasi']) . ') tidak boleh melebihi target realisasi personal (' . intval($target_kegiatan_pegawai['target_pegawai']) . ')';
            }

            if (!empty($errors)) {
                $this->session->setFlashdata('errors', $errors);
                $this->session->setFlashdata('input', $post_request['realisasi']);
            } else {
                if ($realisasi_target_model->insert($realisasi)) {
                    $this->session->setFlashdata('success', 'Input realisasi sukses ditambahkan');
                    return redirect()->to(base_url('struktural/kegiatan/detail/' . $id_kegiatan));
                } else {
                    $this->session->setFlashdata('errors', $realisasi_target_model->errors());
                    $this->session->setFlashdata('input', $post_request['realisasi']);
                }
            }
        }

        return redirect()->to(base_url('struktural/realisasi/tambah/' . $id_kegiatan));
    }

    public function ubah($id_realisasi)
    {

        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        $detail_realisasi = $realisasi_target_model
            ->getRealisasiTargetMany([
                // 'data_kegiatan.id_unit_kerja' => $this->request->user['id_unit_kerja'],
                'data_realisasi_target.id' => $id_realisasi
            ], true)
            ->first();

        if (empty($detail_realisasi)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Detail Realisasi tidak ditemukan atau anda tidak mempunyai akses ke halaman ini');
        }

        $input = $realisasi_target_model->find($id_realisasi);

        $sum_realisasi_target =  $realisasi_target_model->getSumRealisasiTarget(['id_pegawai' => $detail_realisasi['id_pegawai'], 'id_kegiatan' => $detail_realisasi['id_kegiatan']]);
        $sum_realisasi_target_terverifikasi =  intval($realisasi_target_model->getSumRealisasiTarget(['id_pegawai' => $detail_realisasi['id_pegawai'], 'id_kegiatan' => $detail_realisasi['id_kegiatan']], true)->where('waktu_acc IS NOT NULL')->first()['count']);

        return view('struktural/ubah_realisasi_kegiatan', [
            'title' => 'Ubah Realisasi Kegiatan',
            'input' => $input,
            'detail_realisasi' => $detail_realisasi,
            'sum_realisasi_target' => $sum_realisasi_target,
            'sum_realisasi_target_terverifikasi' => $sum_realisasi_target_terverifikasi,
        ]);
    }

    public function do_ubah($id_realisasi)
    {
        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        $detail_realisasi = $realisasi_target_model
            ->getRealisasiTargetMany([
                // 'data_kegiatan.id_unit_kerja' => $this->request->user['id_unit_kerja'],
                'data_realisasi_target.id' => $id_realisasi
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


            if (isset($realisasi['verifikasi'])) {
                if ($realisasi['verifikasi'] === 'true') {
                    if (!$detail_realisasi['waktu_acc']) {
                        $realisasi['waktu_acc'] = date('Y-m-d H:i:s');
                        $realisasi['id_pegawai_acc'] = $this->request->user['id'];
                    }
                } else {
                    $realisasi['waktu_acc'] = null;
                }

                unset($realisasi['verifikasi']);
            }
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


        return redirect()->to(base_url('struktural/realisasi/ubah/' . $id_realisasi));
    }

    public function do_hapus($id_realisasi)
    {
        $post_request = $this->request->getPost();

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


            return redirect()->to(base_url('struktural/kegiatan/detail/' . $realisasi['id_kegiatan']));
        }

        /**
         * Harusnya kalau valid, tidak sampai sini
         */
        return redirect()->to(base_url('struktural/kegiatan/unit-kerja'));
    }
}
