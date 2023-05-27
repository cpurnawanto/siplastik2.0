<?php

namespace App\Controllers\Struktural;

use App\Controllers\BaseController;

class Monitoring extends BaseController
{
    public function ckp($tahun = null, $bulan = null)
    {

        if (!$tahun) {
            $tahun = date('Y');
            $bulan = date('n');
        } else if (!$bulan) {
            $bulan = date('n');
        }

        $first_day_this_month = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-01';
        if (intval($bulan) === 12) {
            $last_day_this_month  = ($tahun + 1) . '-01-01';
        } else {
            $last_day_this_month  = $tahun . '-' . str_pad($bulan + 1, 2, '0', STR_PAD_LEFT) . '-01';
        }
        $pegawai_model = new \App\Models\PegawaiModel();
        $indeks_pegawai = $pegawai_model->getPegawaiMany();

        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();
        $indeks_kualitas_kuantitas = $target_pegawai_model->getIndeksKualitasKuantitas($first_day_this_month, $last_day_this_month);
        $ckp_pegawai_accumulator = [];

        foreach ($indeks_kualitas_kuantitas as $ikk) {
            if (!isset($ckp_pegawai_accumulator[$ikk['id_pegawai']])) {
                $ckp_pegawai_accumulator[$ikk['id_pegawai']] = [
                    'id_pegawai' => $ikk['id_pegawai'],
                    'jumlah_kualitas' => 0,
                    'jumlah_kuantitas' => 0,
                    'jumlah_kegiatan' => 0,
                    'jumlah_bobot_realisasi' => 0,
                    'jumlah_bobot_target' => 0,
                    'persentase_bobot' => 0,
                    'avg_kualitas' => 0,
                    'avg_kuantitas' => 0,
                    'ckp' => 0
                ];
            }

            if ($ikk['jumlah_target']) {
                $ckp_pegawai_accumulator[$ikk['id_pegawai']]['jumlah_kuantitas'] += ($ikk['jumlah_realisasi'] / $ikk['jumlah_target']) > 1 ? 100 : ($ikk['jumlah_realisasi'] / $ikk['jumlah_target']) * 100;
                $ckp_pegawai_accumulator[$ikk['id_pegawai']]['jumlah_kualitas'] += $ikk['persen_kualitas'];
                $ckp_pegawai_accumulator[$ikk['id_pegawai']]['jumlah_bobot_realisasi'] += $ikk['jumlah_realisasi'] * $ikk['bobot'];
                $ckp_pegawai_accumulator[$ikk['id_pegawai']]['jumlah_bobot_target'] += $ikk['jumlah_target'] * $ikk['bobot'];
                $ckp_pegawai_accumulator[$ikk['id_pegawai']]['jumlah_kegiatan']++;
            }
        }

        foreach ($ckp_pegawai_accumulator as $key => $val) {
            if ($val['jumlah_kegiatan']) {
                $ckp_pegawai_accumulator[$key]['avg_kualitas'] = number_format($val['jumlah_kualitas'] / $val['jumlah_kegiatan']);
                $ckp_pegawai_accumulator[$key]['avg_kuantitas'] = number_format($val['jumlah_kuantitas'] / $val['jumlah_kegiatan']);
                $ckp_pegawai_accumulator[$key]['ckp'] = ($ckp_pegawai_accumulator[$key]['avg_kuantitas'] + $ckp_pegawai_accumulator[$key]['avg_kualitas']) / 2;
                $ckp_pegawai_accumulator[$key]['persentase_bobot'] = number_format($val['jumlah_bobot_realisasi'] / $val['jumlah_bobot_target'] * 100, 2);
            }
        }

        ksort($ckp_pegawai_accumulator);

        return view('struktural/monitoring_indeks_ckp', [
            'title' => 'Monitoring CKP',
            'pegawai' => $indeks_pegawai,
            'ckp' => $ckp_pegawai_accumulator,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'bulan_tahun' =>  date('F Y', strtotime($first_day_this_month)),
        ]);
    }

    public function sebaran_unit_kerja($tahun = null, $bulan = null)
    {

        if (!$tahun) {
            $tahun = date('Y');
            $bulan = date('n');
        } else if (!$bulan) {
            $bulan = date('n');
        }

        $first_day_this_month = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-01';
        if (intval($bulan) === 12) {
            $last_day_this_month  = ($tahun + 1) . '-01-01';
        } else {
            $last_day_this_month  = $tahun . '-' . str_pad($bulan + 1, 2, '0', STR_PAD_LEFT) . '-01';
        }

        $pegawai_model = new \App\Models\PegawaiModel();
        $indeks_pegawai = $pegawai_model->getPegawaiMany();

        $unit_kerja_model = new \App\Models\UnitKerjaModel();
        $indeks_unit_kerja = $unit_kerja_model
            ->whereNotIn('id', [9287, 9288])
            ->findAll();

        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();
        $indeks_kualitas_kuantitas = $target_pegawai_model->getIndeksKualitasKuantitas($first_day_this_month, $last_day_this_month);

        $matriks_accumulator = [];

        foreach ($indeks_kualitas_kuantitas as $ikk) {
            if (!isset($matriks_accumulator[$ikk['id_pegawai']])) {
                $matriks_accumulator[$ikk['id_pegawai']] = [
                    'id_pegawai' => $ikk['id_pegawai'],
                    'jumlah_bobot_target' => 0,
                    'jumlah_kegiatan' => 0
                ];

                foreach ($indeks_unit_kerja as $unit) {
                    $matriks_accumulator[$ikk['id_pegawai']]['unit_kerja'][$unit['id']] = [
                        'id' => $unit['id'],
                        'jumlah_bobot_target' => 0,
                        'jumlah_kegiatan' => 0
                    ];
                }
            }

            if ($ikk['jumlah_target']) {
                $matriks_accumulator[$ikk['id_pegawai']]['jumlah_bobot_target'] += $ikk['jumlah_target'] * $ikk['bobot'];
                $matriks_accumulator[$ikk['id_pegawai']]['jumlah_kegiatan']++;

                $matriks_accumulator[$ikk['id_pegawai']]['unit_kerja'][$ikk['id_unit_kerja_kegiatan']]['jumlah_bobot_target'] += $ikk['jumlah_target'] * $ikk['bobot'];
                $matriks_accumulator[$ikk['id_pegawai']]['unit_kerja'][$ikk['id_unit_kerja_kegiatan']]['jumlah_kegiatan']++;
            }
        }

        ksort($matriks_accumulator);

        return view('struktural/monitoring_matriks_alokasi_unit_kerja', [
            'title' => 'Sebaran Alokasi Kegiatan Unit Kerja',
            'pegawai' => $indeks_pegawai,
            'unit_kerja' => $indeks_unit_kerja,
            'matriks' => $matriks_accumulator,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'bulan_tahun' =>  date('F Y', strtotime($first_day_this_month)),
        ]);
    }

    public function pegawai($id_pegawai = null, $tahun = null, $bulan = null)
    {
        $pegawai_model = new \App\Models\PegawaiModel();
        $pegawai = $pegawai_model->getPegawaiOne(['master_pegawai.id' => $id_pegawai]);

        if (empty($pegawai)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pegawai dengan id = ' . $id_pegawai . ' tidak ada');
        }

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
            'id_pegawai' => $id_pegawai,
            'tgl_mulai >= ' => $first_day_this_month,
            'tgl_mulai < ' => $last_day_this_month,
        ]);

        $realisasi_target_model = new \App\Models\DataRealisasiTargetModel();

        $sum_realisasi_target =  $realisasi_target_model
            ->getSumRealisasiTarget(['id_pegawai' => $id_pegawai], true)
            ->select('id_kegiatan')
            ->groupBy('id_kegiatan')
            ->findAll();
        $sum_realisasi_target_terverifikasi =  $realisasi_target_model
            ->getSumRealisasiTarget(['id_pegawai' => $id_pegawai], true)
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


        $indeks_kualitas_kuantitas = $target_pegawai_model->getIndeksKualitasKuantitas(
            $first_day_this_month,
            $last_day_this_month,
            ['master_pegawai.id' => $id_pegawai]
        );
        $ckp_pegawai_accumulator = [];

        foreach ($indeks_kualitas_kuantitas as $ikk) {
            if (!isset($ckp_pegawai_accumulator[$ikk['id_pegawai']])) {
                $ckp_pegawai_accumulator[$ikk['id_pegawai']] = [
                    'id_pegawai' => $ikk['id_pegawai'],
                    'jumlah_kualitas' => 0,
                    'jumlah_kuantitas' => 0,
                    'jumlah_kegiatan' => 0,
                    'jumlah_bobot_realisasi' => 0,
                    'jumlah_bobot_target' => 0,
                    'persentase_bobot' => 0,
                    'avg_kualitas' => 0,
                    'avg_kuantitas' => 0,
                    'ckp' => 0
                ];
            }

            if ($ikk['jumlah_target']) {
                $ckp_pegawai_accumulator[$ikk['id_pegawai']]['jumlah_kuantitas'] += ($ikk['jumlah_realisasi'] / $ikk['jumlah_target']) > 1 ? 100 : ($ikk['jumlah_realisasi'] / $ikk['jumlah_target']) * 100;
                $ckp_pegawai_accumulator[$ikk['id_pegawai']]['jumlah_kualitas'] += $ikk['persen_kualitas'];
                $ckp_pegawai_accumulator[$ikk['id_pegawai']]['jumlah_bobot_realisasi'] += $ikk['jumlah_realisasi'] * $ikk['bobot'];
                $ckp_pegawai_accumulator[$ikk['id_pegawai']]['jumlah_bobot_target'] += $ikk['jumlah_target'] * $ikk['bobot'];
                $ckp_pegawai_accumulator[$ikk['id_pegawai']]['jumlah_kegiatan']++;
            }
        }

        foreach ($ckp_pegawai_accumulator as $key => $val) {
            if ($val['jumlah_kegiatan']) {
                $ckp_pegawai_accumulator[$key]['avg_kualitas'] = number_format($val['jumlah_kualitas'] / $val['jumlah_kegiatan']);
                $ckp_pegawai_accumulator[$key]['avg_kuantitas'] = number_format($val['jumlah_kuantitas'] / $val['jumlah_kegiatan']);
                $ckp_pegawai_accumulator[$key]['ckp'] = ($ckp_pegawai_accumulator[$key]['avg_kuantitas'] + $ckp_pegawai_accumulator[$key]['avg_kualitas']) / 2;
                $ckp_pegawai_accumulator[$key]['persentase_bobot'] = number_format($val['jumlah_bobot_realisasi'] / $val['jumlah_bobot_target'] * 100, 2);
            }
        }

        ksort($ckp_pegawai_accumulator);


        return view('struktural/monitoring_pegawai', [
            'pegawai' => $pegawai,
            'target_pegawai' => $target_pegawai,
            'title' => 'Kegiatan ' . $pegawai['nama_pegawai'] . ' (' . date('F Y', strtotime($first_day_this_month)) . ')',
            'realisasi_target' => $realisasi_target,
            'realisasi_target_terverifikasi' => $realisasi_target_terverifikasi,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'ckp' => $ckp_pegawai_accumulator,
            'bulan_tahun' =>  date('F Y', strtotime($first_day_this_month))
        ]);
    }
}
