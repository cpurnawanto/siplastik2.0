<?php

namespace App\Controllers\Api\Struktural;

use CodeIgniter\API\ResponseTrait;

class Ajax extends \CodeIgniter\Controller
{
    use ResponseTrait;

    public $supportedResponseFormats = [
        'application/json',
    ];

    public function set_target_pegawai()
    {
        $post_data = $this->request->getPost();

        if (!isset($post_data['alokasi'])) {
            return $this->respond(['error' => 'Bad Request'], 400);
        }

        $alokasi = $post_data['alokasi'];

        if (!$alokasi['id_kredit_kegiatan']) {
            $alokasi['id_kredit_kegiatan'] = null;
        }

        $data_kegiatan = (new \App\Models\DataKegiatanModel())->find($alokasi['id_kegiatan']);

        if (empty($data_kegiatan)) {
            return $this->respond(['error' => ['id_kegiatan' => 'ID Kegiatan tidak ada']], 400);
        }

        $pegawai = (new \App\Models\PegawaiModel())->find($alokasi['id_pegawai']);
        if (empty($pegawai)) {
            return $this->respond(['error' => ['id_pegawai' => 'ID Pegawai tidak ada']], 400);
        }

        $data_target_pegawai_model  = new \App\Models\DataTargetPegawaiModel();
        $data_realisasi_target_model  = new \App\Models\DataRealisasiTargetModel();

        $sum_realisasi_target =  $data_realisasi_target_model->getSumRealisasiTarget([
            'id_pegawai' => $alokasi['id_pegawai'],
            'id_kegiatan' => $alokasi['id_kegiatan']
        ]);

        if ($alokasi['jumlah_target'] == '0') {
            if ($sum_realisasi_target) {
                return $this->respond(['error' => ['jumlah_target' => 'Tidak bisa menghapus alokasi, sudah ada realisasi sebanyak ' . $sum_realisasi_target]], 400);
            }
            $data_target_pegawai_model
                ->where([
                    'id_pegawai' => $alokasi['id_pegawai'],
                    'id_kegiatan' => $alokasi['id_kegiatan']
                ])
                ->delete();

            $alokasi['id_kegiatan'] = 0;
            return $this->respond(['info' => 'deleted', 'data' => $alokasi], 200);
        }

        $target = $data_target_pegawai_model
            ->where([
                'id_pegawai' => $alokasi['id_pegawai'],
                'id_kegiatan' => $alokasi['id_kegiatan']
            ])
            ->first();

        if (empty($target)) {
            $count_alokasi =  $data_target_pegawai_model
                ->where('id_kegiatan', $alokasi['id_kegiatan'])
                ->selectSum('jumlah_target', 'count')
                ->select('id_kegiatan')
                ->groupBy('id_kegiatan')
                ->first();

            if (($count_alokasi['count'] ?? 0) + $alokasi['jumlah_target'] > $data_kegiatan['jumlah_target']) {
                return $this->respond(['error' => ['jumlah_target' => 'Total target sekarang + total target ini (' . ($count_alokasi['count'] ?? 0) . ' + ' . intval($alokasi['jumlah_target']) . ') tidak boleh melebihi ' . $data_kegiatan['jumlah_target']]], 400);
            }

            $alokasi['persen_kualitas'] = 0;
            $alokasi['id_fungsional_kredit_kegiatan'] = $pegawai['id_fungsional'];
            $data_target_pegawai_model->insert($alokasi);
        } else {
            $count_alokasi =  $data_target_pegawai_model
                ->where('id_kegiatan', $alokasi['id_kegiatan'])
                ->where('id != ', $target['id'])
                ->selectSum('jumlah_target', 'count')
                ->select('id_kegiatan')
                ->groupBy('id_kegiatan')
                ->first();

            if ($sum_realisasi_target > $alokasi['jumlah_target']) {
                return $this->respond(['error' => ['jumlah_target' => 'Tidak bisa mengubah alokasi menjadi ' . $alokasi['jumlah_target'] . ', sudah ada realisasi sebanyak ' . $sum_realisasi_target]], 400);
            }

            if (($count_alokasi['count'] ?? 0) + $alokasi['jumlah_target'] > $data_kegiatan['jumlah_target']) {
                return $this->respond(['error' => ['jumlah_target' => 'Total target lainnya + total target ini (' . ($count_alokasi['count'] ?? 0) . ' + ' . intval($alokasi['jumlah_target']) . ') tidak boleh melebihi ' . $data_kegiatan['jumlah_target']]], 400);
            }

            $data_target_pegawai_model
                ->where([
                    'id_pegawai' => $alokasi['id_pegawai'],
                    'id_kegiatan' => $alokasi['id_kegiatan']
                ])
                ->set([
                    'jumlah_target' => $alokasi['jumlah_target'],
                    'id_kredit_kegiatan' => $alokasi['id_kredit_kegiatan']
                ])
                ->update();
        }

        if ($data_target_pegawai_model->errors()) {
            return $this->respond(['error' => $data_target_pegawai_model->errors()], 500);
        }

        $response = $data_target_pegawai_model
            ->where([
                'id_pegawai' => $alokasi['id_pegawai'],
                'id_kegiatan' => $alokasi['id_kegiatan']
            ])
            ->first();

        return $this->respond(['info' => 'success', 'data' => $response], 200);
    }

    public function get_alokasi_kegiatan($id_kegiatan)
    {
        $target_pegawai_model = new \App\Models\DataTargetPegawaiModel();
        $alokasi =  $target_pegawai_model
            ->where('id_kegiatan', $id_kegiatan)
            ->selectSum('jumlah_target', 'count')
            ->select('id_kegiatan')
            ->groupBy('id_kegiatan')
            ->first();
        return $this->respond($alokasi, 200);
    }
}
