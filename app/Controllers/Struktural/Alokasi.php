<?php

namespace App\Controllers\Struktural;

use App\Controllers\BaseController;

class Alokasi extends BaseController
{

    /**
     * Tampilan untuk alokasi target kegiatan oleh struktural
     * View yang dihasilkan juga memanggil  
     * `App\Controllers\Api\Struktural\Ajax::set_target_pegawai` 
     * 
     */
    public function kegiatan($id)
    {
        $data_kegiatan_model = new \App\Models\DataKegiatanModel();

        $data_kegiatan  = $data_kegiatan_model->find($id);
        if (empty($data_kegiatan)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data kegiatan dengan id = ' . $id . ' tidak ada');
        }

        $data_target_pegawai = (new \App\Models\DataTargetPegawaiModel())->getIndeksTargetKegiatanPegawai($id);
        return view('struktural/alokasi_target_kegiatan', ['title' => 'Alokasi Target Kegiatan', 'kegiatan' => $data_kegiatan, 'data_target' => $data_target_pegawai]);
    }

    public function ubah($id_kegiatan, $id_pegawai)
    {
        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();

        $target_pegawai = $target_pegawai_model->getTargetKegiatanMany([
            'data_target_pegawai.id_pegawai' => $id_pegawai,
            'data_target_pegawai.id_kegiatan' => $id_kegiatan
        ]);


        if (empty($target_pegawai)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kegiatan tidak ditemukan atau anda tidak memiliki akses untuk detail kegiatan ini');
        }
        $input = $target_pegawai_model->where([
            'id_pegawai' => $id_pegawai,
            'id_kegiatan' => $id_kegiatan
        ])->first();

        $target_pegawai = $target_pegawai[0];

        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        $sum_realisasi_target =  $realisasi_target_model->getSumRealisasiTarget(['id_pegawai' => $id_pegawai, 'id_kegiatan' => $id_kegiatan]);
        $sum_realisasi_target_terverifikasi =  intval($realisasi_target_model->getSumRealisasiTarget(['id_pegawai' => $id_pegawai, 'id_kegiatan' => $id_kegiatan], true)->where('waktu_acc IS NOT NULL')->first()['count']);

        return view('struktural/ubah_nilai_alokasi', [
            'title' => 'Ubah Alokasi Target',
            'target_kegiatan' => $target_pegawai,
            'input' => $input,
            'sum_realisasi_target' => $sum_realisasi_target,
            'sum_realisasi_target_terverifikasi' => $sum_realisasi_target_terverifikasi
        ]);
    }

    public function do_ubah($id_kegiatan, $id_pegawai)
    {
        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();

        $target_pegawai = $target_pegawai_model->getTargetKegiatanMany([
            'data_target_pegawai.id_pegawai' => $id_pegawai,
            'data_target_pegawai.id_kegiatan' => $id_kegiatan
        ]);


        if (empty($target_pegawai)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kegiatan tidak ditemukan atau anda tidak memiliki akses untuk detail kegiatan ini');
        }
        $target_pegawai = $target_pegawai[0];

        $post_request = $this->request->getPost();


        if (isset($post_request['alokasi'])) {
            $alokasi = $post_request['alokasi'];

            $data_realisasi_target_model  = new \App\Models\DataRealisasiTargetModel();
            $sum_realisasi_target =  $data_realisasi_target_model->getSumRealisasiTarget([
                'id_pegawai' => $id_pegawai,
                'id_kegiatan' => $id_kegiatan
            ]);


            $count_alokasi =  $target_pegawai_model
                ->where('id_kegiatan', $id_kegiatan)
                ->where('id != ', $target_pegawai['id_target'])
                ->selectSum('jumlah_target', 'count')
                ->select('id_kegiatan')
                ->groupBy('id_kegiatan')
                ->first();

            $error = [];
            if ($sum_realisasi_target > $alokasi['jumlah_target']) {
                $error = ['jumlah_target' => 'Tidak bisa mengubah alokasi menjadi ' . $alokasi['jumlah_target'] . ', sudah ada realisasi sebanyak ' . $sum_realisasi_target];
            } else if (($count_alokasi['count'] ?? 0) + $alokasi['jumlah_target'] > $target_pegawai['target_kegiatan']) {
                $error = ['jumlah_target' => 'Total target lainnya + total target ini (' . ($count_alokasi['count'] ?? 0) . ' + ' . intval($alokasi['jumlah_target']) . ') tidak boleh melebihi ' . $target_pegawai['target_kegiatan']];
            }

            if (empty($error)) {
                $target_pegawai_model
                    ->where([
                        'id_pegawai' => $id_pegawai,
                        'id_kegiatan' => $id_kegiatan
                    ])
                    ->set([
                        'jumlah_target' => $alokasi['jumlah_target'],
                        'persen_kualitas' => $alokasi['persen_kualitas'],
                        'keterangan' => $alokasi['keterangan']
                    ])
                    ->update();

                if ($target_pegawai_model->errors()) {
                    $error = $target_pegawai_model->errors();
                }
            }

            if (!empty($error)) {
                $this->session->setFlashdata('errors', $error);
            } else {
                $this->session->setFlashdata('success', 'Berhasil diperbarui');
            }
        }
        return redirect()->to(base_url('struktural/alokasi/ubah/' . $id_kegiatan . '/' . $id_pegawai));
    }
}
