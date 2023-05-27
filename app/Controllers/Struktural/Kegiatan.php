<?php

namespace App\Controllers\Struktural;

use App\Controllers\BaseController;

class Kegiatan extends BaseController
{
    /**
     * Tampilan untuk tambah kegiatan oleh struktural
     * View yang dihasilkan juga memanggil  
     * `App\Controllers\Api\Ajax::get_kredit_kegiatan` dan  
     * `App\Controllers\Api\Data_tables::kredit_kegiatan`
     * 
     */

    public function unit_kerja($id_unit_kerja = null, $tahun = null, $bulan = null)
    {
        if (!$id_unit_kerja) {
            return redirect()->to(base_url('struktural/kegiatan/unit-kerja/' . $this->request->user['id_unit_kerja']));
        }
        if (!$tahun) {
            $tahun = date('Y');
            $bulan = date('n');
        } else if (!$bulan) {
            $bulan = date('n');
        }


        $unit_kerja = (new \App\Models\UnitKerjaModel())->find($id_unit_kerja);

        if (intval($tahun) < 2019 || intval($tahun) > 2100 || intval($bulan) < 1 || intval($bulan) > 12 || empty($unit_kerja)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Tidak bisa menemukan daftar kegiatan dengan parameter yang disediakan');
        }


        $first_day_this_month = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-01';
        if (intval($bulan) === 12) {
            $last_day_this_month  = ($tahun + 1) . '-01-01';
        } else {
            $last_day_this_month  = $tahun . '-' . str_pad($bulan + 1, 2, '0', STR_PAD_LEFT) . '-01';
        }

        /** Dapatkan semua kegiatan */
        $kegiatan_model = new \App\Models\DataKegiatanModel();
        $kegiatan = $kegiatan_model
            ->where([
                'id_unit_kerja' => $id_unit_kerja,
                'tgl_mulai >=' => $first_day_this_month,
                'tgl_mulai <' => $last_day_this_month,
            ])
            ->orderBy('tgl_mulai')
            ->findAll();

        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();
        $sum_target_pegawai = $target_pegawai_model
            ->where([
                'id_unit_kerja' => $id_unit_kerja,
                'tgl_mulai >= ' => $first_day_this_month,
                'tgl_mulai < ' => $last_day_this_month,
            ])
            ->join('data_kegiatan', 'data_target_pegawai.id_kegiatan = data_kegiatan.id')
            ->selectSum('data_target_pegawai.jumlah_target', 'count')
            ->select('id_kegiatan')
            ->groupBy('id_kegiatan')
            ->findAll();

        $sum_target_dinilai = $target_pegawai_model
            ->where([
                'id_unit_kerja' => $id_unit_kerja,
                'tgl_mulai >= ' => $first_day_this_month,
                'tgl_mulai < ' => $last_day_this_month,
            ])
            ->join('data_kegiatan', 'data_target_pegawai.id_kegiatan = data_kegiatan.id')
            ->selectSum('data_target_pegawai.jumlah_target', 'count')
            ->select('id_kegiatan')
            ->where('persen_kualitas > 0')
            ->groupBy('id_kegiatan')
            ->findAll();

        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        $sum_realisasi_target =  $realisasi_target_model
            ->getSumRealisasiTarget(['id_unit_kerja' => $id_unit_kerja], true)
            ->join('data_kegiatan', 'data_kegiatan.id = data_realisasi_target.id_kegiatan')
            ->select('id_kegiatan')
            ->groupBy('id_kegiatan')
            ->findAll();

        $sum_realisasi_target_terverifikasi =  $realisasi_target_model
            ->getSumRealisasiTarget(['id_unit_kerja' => $id_unit_kerja], true)
            ->join('data_kegiatan', 'data_kegiatan.id = data_realisasi_target.id_kegiatan')
            ->where('waktu_acc IS NOT NULL')
            ->select('id_kegiatan')
            ->groupBy('id_kegiatan')
            ->findAll();

        $sum_realisasi_target_terlambat =  $realisasi_target_model
            ->getSumRealisasiTarget(['id_unit_kerja' => $id_unit_kerja], true)
            ->where('tanggal_realisasi > tgl_selesai')
            ->join('data_kegiatan', 'data_kegiatan.id = data_realisasi_target.id_kegiatan')
            ->select('id_kegiatan')
            ->groupBy('id_kegiatan')
            ->findAll();


        $target_pegawai = [];
        foreach ($sum_target_pegawai as $target) {
            $target_pegawai[$target['id_kegiatan']] = $target['count'];
        }
        $target_dinilai = [];
        foreach ($sum_target_dinilai as $target) {
            $target_dinilai[$target['id_kegiatan']] = $target['count'];
        }

        $realisasi_target = [];
        foreach ($sum_realisasi_target as $target) {
            $realisasi_target[$target['id_kegiatan']] = $target['count'];
        }
        $realisasi_target_terverifikasi = [];
        foreach ($sum_realisasi_target_terverifikasi as $target) {
            $realisasi_target_terverifikasi[$target['id_kegiatan']] = $target['count'];
        }
        $realisasi_target_terlambat = [];
        foreach ($sum_realisasi_target_terlambat as $target) {
            $realisasi_target_terlambat[$target['id_kegiatan']] = $target['count'];
        }

        $daftar_unit_kerja = [];
        if ($this->request->user['is_admin'] || in_array($this->request->user['id_eselon'], [2, 3]))
            $daftar_unit_kerja = (new \App\Models\UnitKerjaModel())->findAll();

        return view('struktural/kegiatan_unit_kerja', [
            'list_kegiatan' => $kegiatan,
            'title' => 'Kegiatan Unit Kerja ' . $unit_kerja['unit_kerja'] . ' (' . date('F Y', strtotime($first_day_this_month)) . ')',
            'target_pegawai' => $target_pegawai,
            'target_dinilai' => $target_dinilai,
            'realisasi_target' => $realisasi_target,
            'realisasi_target_terverifikasi' => $realisasi_target_terverifikasi,
            'realisasi_target_terlambat' => $realisasi_target_terlambat,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'id_unit_kerja' => $id_unit_kerja,
            'bulan_tahun' =>  date('F Y', strtotime($first_day_this_month)),
            'daftar_unit_kerja' => $daftar_unit_kerja
        ]);
    }

    public function detail($id_kegiatan)
    {
        $kegiatan_model = new \App\Models\DataKegiatanModel();
        $kegiatan = $kegiatan_model->getKegiatanDetailOne(['data_kegiatan.id' => $id_kegiatan]);
        if (empty($kegiatan)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Detail Kegiatan tidak ditemukan atau anda tidak mempunyai akses ke halaman ini');
        }

        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();
        $sum_alokasi_kegiatan =  $target_pegawai_model
            ->where('id_kegiatan', $id_kegiatan)
            ->selectSum('jumlah_target', 'count')
            ->select('id_kegiatan')
            ->groupBy('id_kegiatan')
            ->first();

        $alokasi_kegiatan = $target_pegawai_model->getTargetKegiatanMany([
            'data_target_pegawai.id_kegiatan' => $id_kegiatan
        ]);

        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();

        $sum_realisasi_target =  $realisasi_target_model
            ->getSumRealisasiTarget(['id_kegiatan' => $id_kegiatan], true)
            ->select('id_pegawai')
            ->groupBy('id_pegawai')
            ->findAll();

        $sum_realisasi_target_terverifikasi =  $realisasi_target_model
            ->getSumRealisasiTarget(['id_kegiatan' => $id_kegiatan], true)
            ->where('waktu_acc IS NOT NULL')
            ->select('id_pegawai')
            ->groupBy('id_pegawai')
            ->findAll();

        $sum_realisasi_target_terlambat =  $realisasi_target_model
            ->getSumRealisasiTarget(['id_kegiatan' => $id_kegiatan], true)
            ->where('tanggal_realisasi > tgl_selesai')
            ->join('data_kegiatan', 'data_kegiatan.id = data_realisasi_target.id_kegiatan')
            ->select('id_pegawai')
            ->groupBy('id_pegawai')
            ->findAll();

        $realisasi_target = [];
        foreach ($sum_realisasi_target as $target) {
            $realisasi_target[$target['id_pegawai']] = $target['count'];
        }
        $realisasi_target_terverifikasi = [];
        foreach ($sum_realisasi_target_terverifikasi as $target) {
            $realisasi_target_terverifikasi[$target['id_pegawai']] = $target['count'];
        }
        $realisasi_target_terlambat = [];
        foreach ($sum_realisasi_target_terlambat as $target) {
            $realisasi_target_terlambat[$target['id_pegawai']] = $target['count'];
        }

        $realisasi_kegiatan = $realisasi_target_model->getRealisasiTargetMany(['data_kegiatan.id' => $id_kegiatan]);

        $sum_realisasi_kegiatan =  $realisasi_target_model->getSumRealisasiTarget(['id_kegiatan' => $id_kegiatan]);
        $sum_realisasi_kegiatan_terverifikasi =  intval($realisasi_target_model->getSumRealisasiTarget(['id_kegiatan' => $id_kegiatan], true)->where('waktu_acc IS NOT NULL')->first()['count']);

        return view('struktural/detail_kegiatan', [
            'title' =>  esc($kegiatan['nama_kegiatan']),
            'alokasi_kegiatan' => $alokasi_kegiatan,
            'sum_alokasi_kegiatan' => $sum_alokasi_kegiatan['count'] ?? 0,
            'sum_realisasi_kegiatan' => $sum_realisasi_kegiatan,
            'sum_realisasi_kegiatan_terverifikasi' => $sum_realisasi_kegiatan_terverifikasi,
            'kegiatan' => $kegiatan,
            'realisasi_target' => $realisasi_target,
            'realisasi_target_terverifikasi' => $realisasi_target_terverifikasi,
            'realisasi_target_terlambat' => $realisasi_target_terlambat,
            'realisasi_kegiatan' => $realisasi_kegiatan,
        ]);
    }

    public function tambah()
    {
        return view('struktural/tambah_kegiatan', ['title' => 'Tambah Kegiatan']);
    }

    public function do_tambah()
    {
        $post_request = $this->request->getPost();

        if (isset($post_request['kegiatan'])) {
            $data_kegiatan_model = new \App\Models\DataKegiatanModel();
            if ($post_request['kegiatan']['hubungkan_ak'] === 'false') {
                unset($post_request['kegiatan']['id_kredit_ahli']);
                unset($post_request['kegiatan']['id_kredit_terampil']);
            } else {
                if (empty($post_request['kegiatan']['id_kredit_ahli'])) {
                    $post_request['kegiatan']['id_kredit_ahli'] = null;
                }

                if (empty($post_request['kegiatan']['id_kredit_terampil'])) {
                    $post_request['kegiatan']['id_kredit_terampil'] = null;
                }
            }

            $kegiatan = $post_request['kegiatan'];

            $kegiatan['is_tampil'] = 1;
            $kegiatan['is_ckp'] = 1;
            $kegiatan['is_usulan'] = 1;
            $kegiatan['is_lock'] = 0;
            $kegiatan['id_pegawai_pembuat'] = $this->request->user['id'];

            if ($data_kegiatan_model->save($kegiatan)) {
                $this->session->setFlashdata('success', 'Input Kegiatan ' . $post_request['kegiatan']['nama_kegiatan'] . ' Sukses. Silahkan masukkan alokasi pegawai');
                $insert_id = $data_kegiatan_model->getInsertID();
                return redirect()->to(base_url('struktural/alokasi/kegiatan/' . $insert_id));
            } else {
                $this->session->setFlashdata('errors', $data_kegiatan_model->errors());
                $this->session->setFlashdata('input', $post_request['kegiatan']);
                return redirect()->to(base_url('struktural/kegiatan/tambah'));
            }
        }
    }

    public function ubah($id_kegiatan)
    {
        $data_kegiatan_model = new \App\Models\DataKegiatanModel();

        /**
         * TODO Protect
         */
        $data_kegiatan  = $data_kegiatan_model->find($id_kegiatan);
        if (empty($data_kegiatan)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data kegiatan dengan id = ' . $id_kegiatan . ' tidak ada');
        }
        return view('struktural/ubah_kegiatan', ['title' => 'Ubah Kegiatan ' . esc($data_kegiatan['nama_kegiatan']), 'kegiatan' => $data_kegiatan]);
    }

    public function do_ubah($id_kegiatan)
    {
        $post_request = $this->request->getPost();

        if (isset($post_request['kegiatan'])) {
            $data_kegiatan_model = new \App\Models\DataKegiatanModel();
            if ($post_request['kegiatan']['hubungkan_ak'] === 'false') {
                $post_request['kegiatan']['id_kredit_ahli'] = null;
                $post_request['kegiatan']['id_kredit_terampil'] = null;
            } else {
                if (empty($post_request['kegiatan']['id_kredit_ahli'])) {
                    $post_request['kegiatan']['id_kredit_ahli'] = null;
                }

                if (empty($post_request['kegiatan']['id_kredit_terampil'])) {
                    $post_request['kegiatan']['id_kredit_terampil'] = null;
                }
            }

            $kegiatan = $post_request['kegiatan'];
            unset($post_request['kegiatan']['hubungkan_ak']);
            /**
             * TODO cek alokasi kalau berubah
             */
            if ($data_kegiatan_model->update($id_kegiatan, $kegiatan)) {
                $this->session->setFlashdata('success', 'Detail kegiatan sukses diubah.');
            } else {
                $this->session->setFlashdata('errors', $data_kegiatan_model->errors());
            }

            return redirect()->to(base_url('struktural/kegiatan/ubah/' . $id_kegiatan));
        }
    }
}
