<?php

namespace App\Controllers\Personal;

use App\Controllers\BaseController;

class Fungsional extends BaseController
{

    public function kredit_kegiatan($id = null)
    {
        if (!$id) {
            return view('personal/indeks_kredit_kegiatan', ['title' => 'Indeks Kredit Kegiatan', 'not_admin' => true]);
        }

        $kredit_kegiatan_model = new \App\Models\KreditKegiatanModel();
        $kredit_kegiatan = $kredit_kegiatan_model->find($id);

        if (empty($kredit_kegiatan)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Rincian kredit kegiatan dengan id = ' . $id . ' tidak ada');
        }

        $kredit_fungsional_model = new \App\Models\KreditFungsionalModel();
        $kredit_fungsional = $kredit_fungsional_model->getKreditKegiatanFungsional($id);


        return view('personal/lihat_kredit_kegiatan', [
            'title' => 'Lihat Detail Kredit Kegiatan (' . $kredit_kegiatan['kode'] . ' - ' . $kredit_kegiatan['nama_tingkat'] . ')',
            'data' => [
                'kredit_kegiatan' => $kredit_kegiatan,
                'kredit_fungsional' => $kredit_fungsional
            ],
            'not_admin' => true
        ]);
    }

    public function rincian($tahun1 = null, $bulan1 = null, $tahun2 = null, $bulan2 = null)
    {
        if (!$tahun1 && !$bulan1 && !$tahun2 && !$bulan2) {
            $tahun1 = date('Y');
            $bulan1 = date('n');
            $tahun2 = date('Y');
            $bulan2 = date('n');
        } else if (
            !$tahun1 || !$bulan1 || !$tahun2 || !$bulan2 ||
            intval($tahun1) < 2019 || intval($tahun1) > 2100 || intval($bulan1) < 1 || intval($bulan1) > 12 ||
            intval($tahun2) < 2019 || intval($tahun2) > 2100 || intval($bulan2) < 1 || intval($bulan2) > 12
        ) {
            return redirect()->to(base_url(`personal/fungsional/rincian`));
        }

        if ($tahun1 > $tahun2 || ($tahun1 == $tahun2 && $bulan1 > $bulan2)) {
            return redirect()->to(base_url('personal/fungsional/rincian/' . $tahun2 . '/' . $bulan2 . '/' . $tahun1 . '/' . $bulan1));
        }

        $first_day_this_month1 = $tahun1 . '-' . str_pad($bulan1, 2, '0', STR_PAD_LEFT) . '-01';

        if (intval($bulan2) === 12) {
            $last_day_this_month2  = ($tahun2 + 1) . '-01-01';
        } else {
            $last_day_this_month2  = $tahun2 . '-' . str_pad($bulan2 + 1, 2, '0', STR_PAD_LEFT) . '-01';
        }

        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();
        $target_pegawai_with_kredit = $target_pegawai_model->getTargetKegiatanMany([
            'id_pegawai' => $this->request->user['id'],
            'tgl_mulai >= ' => $first_day_this_month1,
            'tgl_mulai < ' => $last_day_this_month2,
            'id_kredit_kegiatan != ' => null
        ]);

        $get_kredit_id = function ($target) {
            return $target['id_kredit_kegiatan'];
        };

        $get_kegiatan_id = function ($target) {
            return $target['id_kegiatan'];
        };

        $id_angka_kredit = array_map($get_kredit_id, $target_pegawai_with_kredit);
        $id_kegiatan_with_kredit = array_map($get_kegiatan_id, $target_pegawai_with_kredit);

        if ($id_angka_kredit) {
            $kredit_kegiatan_model = new \App\Models\KreditKegiatanModel();
            $kredit_kegiatan_get = $kredit_kegiatan_model->whereIn('id', $id_angka_kredit)->findAll();
        } else {
            $id_angka_kredit = [];
            $kredit_kegiatan_get = [];
        }

        $kredit_kegiatan = [];
        foreach ($kredit_kegiatan_get as $k) {
            $kredit_kegiatan[$k['id']] = $k;
        }

        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();
        if ($id_kegiatan_with_kredit) {
            $sum_realisasi_target =  $realisasi_target_model
                ->getSumRealisasiTarget([
                    'id_pegawai' => $this->request->user['id'],
                ], true)
                ->select('id_kegiatan')
                ->whereIn('id_kegiatan', $id_kegiatan_with_kredit)
                ->groupBy('id_kegiatan')
                ->findAll();
        } else {
            $sum_realisasi_target = [];
        }

        $realisasi_target = [];
        foreach ($sum_realisasi_target as $target) {
            $realisasi_target[$target['id_kegiatan']] = $target['count'];
        }

        return view('personal/rincian_fungsional', [
            'title' => 'Rincian Fungsional',
            'target_pegawai' => $target_pegawai_with_kredit,
            'realisasi_target' => $realisasi_target,
            'kredit_kegiatan' => $kredit_kegiatan,
            'tahun1' => $tahun1,
            'bulan1' => $bulan1,
            'tahun2' => $tahun2,
            'bulan2' => $bulan2
        ]);
    }
}
