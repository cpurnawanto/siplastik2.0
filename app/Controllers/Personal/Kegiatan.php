<?php

namespace App\Controllers\Personal;

use App\Controllers\BaseController;

class Kegiatan extends BaseController
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
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Tidak bisa menemukan daftar kegiatan dengan parameter yang disediakan');
        }

        $first_day_this_month = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-01';
        if (intval($bulan) === 12) {
            $last_day_this_month  = ($tahun + 1) . '-01-01';
        } else {
            $last_day_this_month  = $tahun . '-' . str_pad($bulan + 1, 2, '0', STR_PAD_LEFT) . '-01';
        }

        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();
        $target_pegawai = $target_pegawai_model->getTargetKegiatanMany([
            'id_pegawai' => $this->request->user['id'],
            'tgl_mulai >= ' => $first_day_this_month,
            'tgl_mulai < ' => $last_day_this_month,
        ]);

        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();

        $sum_realisasi_target =  $realisasi_target_model
            ->getSumRealisasiTarget(['id_pegawai' => $this->request->user['id']], true)
            ->select('id_kegiatan')
            ->groupBy('id_kegiatan')
            ->findAll();
        $sum_realisasi_target_terverifikasi =  $realisasi_target_model
            ->getSumRealisasiTarget(['id_pegawai' => $this->request->user['id']], true)
            ->where('waktu_acc IS NOT NULL')->select('id_kegiatan')
            ->groupBy('id_kegiatan')
            ->findAll();

        $realisasi_target = [];
        foreach ($sum_realisasi_target as $target) {
            $realisasi_target[$target['id_kegiatan']] = $target['count'];
        }
        $realisasi_target_terverifikasi = [];
        foreach ($sum_realisasi_target_terverifikasi as $target) {
            $realisasi_target_terverifikasi[$target['id_kegiatan']] = $target['count'];
        }

        return view('personal/kegiatan_saya', [
            'target_pegawai' => $target_pegawai,
            'title' => 'Kegiatan Saya (' . date('F Y', strtotime($first_day_this_month)) . ')',
            'realisasi_target' => $realisasi_target,
            'realisasi_target_terverifikasi' => $realisasi_target_terverifikasi,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'bulan_tahun' =>  date('F Y', strtotime($first_day_this_month))
        ]);
    }

    public function tahunan($tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }

        if (intval($tahun) < 2019 || intval($tahun) > 2100) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Tidak bisa menemukan daftar kegiatan dengan parameter yang disediakan');
        }

        $first_day_this_year =  $tahun . '-01-01';
        $last_day_this_year  = ($tahun + 1) . '-01-01';

        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();
        $target_pegawai = $target_pegawai_model->getTargetKegiatanMany([
            'id_pegawai' => $this->request->user['id'],
            'tgl_mulai >= ' => $first_day_this_year,
            'tgl_mulai < ' => $last_day_this_year,
        ], 'asc');

        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();

        $sum_realisasi_target =  $realisasi_target_model
            ->getSumRealisasiTarget(['id_pegawai' => $this->request->user['id']], true)
            ->select('id_kegiatan')
            ->groupBy('id_kegiatan')
            ->findAll();
        $sum_realisasi_target_terverifikasi =  $realisasi_target_model
            ->getSumRealisasiTarget(['id_pegawai' => $this->request->user['id']], true)
            ->where('waktu_acc IS NOT NULL')->select('id_kegiatan')
            ->groupBy('id_kegiatan')
            ->findAll();

        $realisasi_target = [];
        foreach ($sum_realisasi_target as $target) {
            $realisasi_target[$target['id_kegiatan']] = $target['count'];
        }
        $realisasi_target_terverifikasi = [];
        foreach ($sum_realisasi_target_terverifikasi as $target) {
            $realisasi_target_terverifikasi[$target['id_kegiatan']] = $target['count'];
        }

        return view('personal/kegiatan_saya', [
            'target_pegawai' => $target_pegawai,
            'title' => 'Kegiatan Saya (Tahun ' . $tahun . ')',
            'realisasi_target' => $realisasi_target,
            'realisasi_target_terverifikasi' => $realisasi_target_terverifikasi,
            'tahun' => $tahun
        ]);
    }

    public function detail($id)
    {
        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();

        $target_pegawai = $target_pegawai_model->getTargetKegiatanMany([
            'data_target_pegawai.id_pegawai' => $this->request->user['id'],
            'data_target_pegawai.id_kegiatan' => $id
        ]);

        if (empty($target_pegawai)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kegiatan tidak ditemukan atau anda tidak memiliki akses untuk detail kegiatan ini');
        }
        //first
        $target_kegiatan_pegawai = $target_pegawai[0];

        $unit_kerja_kegiatan = (new \App\Models\UnitKerjaModel())->find($target_kegiatan_pegawai['id_unit_kerja']);

        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        $realisasi_target = $realisasi_target_model
            ->where([
                'id_pegawai' => $this->request->user['id'],
                'id_kegiatan' => $id
            ])
            ->orderBy('tanggal_realisasi', 'ASC')
            ->findAll();

        $sum_realisasi_target =  $realisasi_target_model->getSumRealisasiTarget(['id_pegawai' => $this->request->user['id'], 'id_kegiatan' => $id]);
        $sum_realisasi_target_terverifikasi =  intval($realisasi_target_model->getSumRealisasiTarget(['id_pegawai' => $this->request->user['id'], 'id_kegiatan' => $id], true)->where('waktu_acc IS NOT NULL')->first()['count']);

        $fungsional_target = '-';
        if ($target_kegiatan_pegawai['id_fungsional_kredit_kegiatan']) {
            $fungsional_target = (new \App\Models\FungsionalModel())->find($target_kegiatan_pegawai['id_fungsional_kredit_kegiatan'])['fungsional'];
        }

        return view('personal/detail_kegiatan', [
            'title' => 'Detail Kegiatan',
            'target_kegiatan_pegawai' => $target_kegiatan_pegawai,
            'sum_realisasi_target' => $sum_realisasi_target,
            'sum_realisasi_target_terverifikasi' => $sum_realisasi_target_terverifikasi,
            'realisasi_target' => $realisasi_target,
            'unit_kerja_kegiatan' => $unit_kerja_kegiatan,
            'fungsional_target' => $fungsional_target
        ]);
    }

    public function do_ubah_kredit_kegiatan_terhubung($id_kegiatan)
    {
        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();

        $target_pegawai = $target_pegawai_model->getTargetKegiatanMany([
            'data_target_pegawai.id_pegawai' => $this->request->user['id'],
            'data_target_pegawai.id_kegiatan' => $id_kegiatan
        ]);

        if (empty($target_pegawai)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kegiatan tidak ditemukan atau anda tidak memiliki akses untuk detail kegiatan ini');
        }

        $post_request = $this->request->getPost();
        if (!isset($post_request['kredit_kegiatan'])) {
            return redirect()->to(base_url('personal/kegiatan/detail/' . $id_kegiatan));
        }

        $kredit_kegiatan = $post_request['kredit_kegiatan'];
        if ($kredit_kegiatan['id_kegiatan'] != $id_kegiatan) {
            return redirect()->to(base_url('personal/kegiatan/detail/' . $id_kegiatan));
        }


        if (!$kredit_kegiatan['id_kredit_kegiatan']) {
            $kredit_kegiatan['id_kredit_kegiatan'] = null;
        }

        if ($target_pegawai_model
            ->where([
                'id_pegawai' => $this->request->user['id'],
                'id_kegiatan' => $id_kegiatan
            ])
            ->set([
                'id_kredit_kegiatan' => $kredit_kegiatan['id_kredit_kegiatan']
            ])
            ->update()
        ) {
            $this->session->setFlashdata('success', 'Angka kredit kegiatan terhubung sukses diubah');
        } else {
            $this->session->setFlashdata('errors', $target_pegawai_model->errors());
        }

        return redirect()->to(base_url('personal/kegiatan/detail/' . $id_kegiatan));
    }
}
