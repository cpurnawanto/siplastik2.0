<?php

namespace App\Models;

use CodeIgniter\Model;

class DataRealisasiTargetModel extends Model
{
    protected $table = 'data_realisasi_target';

    protected $allowedFields = [
        'id_kegiatan',
        'id_pegawai',
        'tanggal_realisasi',
        'jumlah_realisasi',
        'keterangan',
        'id_pegawai_acc',
        'waktu_acc'
    ];


    protected $useSoftDeletes = false;

    protected $useTimestamps = true;

    protected $validationRules = [
        'id_kegiatan' => 'required|is_not_unique[data_kegiatan.id]',
        'id_pegawai' => 'required|is_not_unique[master_pegawai.id]',
        'tanggal_realisasi' => 'required|valid_date[Y-m-d]',
        'jumlah_realisasi' => 'required|is_natural|greater_than[0]',
        'id_pegawai_acc' => 'permit_empty|is_not_unique[master_pegawai.id]',
        'waktu_acc' => 'permit_empty|valid_date[Y-m-d H:i:s]'
    ];

    protected $validationMessages = [
        'id_kegiatan' => [
            'required' => 'ID Kegiatan kosong',
            'is_not_unique' => 'ID Kegiatan tidak valid'
        ],
        'id_pegawai' => [
            'required' => 'ID Pegawai tidak ada',
            'is_not_unique' => 'ID Pegawai kosong'
        ],
        'tanggal_realisasi' =>  [
            'required' => 'Tanggal realisasi kosong',
            'valid_date' => 'Format tanggal tidak valid',
        ],
        'jumlah_realisasi' => [
            'required' => 'Jumlah realisasi kosong',
            'is_natural' => 'Jumlah realisasi tidak boleh negatif dan harus bulat',
            'greater_than' => 'Jumlah realisasi harus > 1'
        ],
        'waktu_acc' => [
            'valid_date' => 'Format waktu acc tidak valid',
        ]
    ];

    /**
     * @return int|DataRealisasiTargetModel
     */
    public function getSumRealisasiTarget(array $where = [], bool $return_this = false)
    {
        $this->where($where);
        $this->select('SUM(`jumlah_realisasi`) as count');
        if ($return_this) {
            return $this;
        } else {
            return intval($this->first()['count']);
        }
    }

    /**
     * @return array[array]|DataRealisasiTargetModel
     */
    public function getRealisasiTargetMany(array $where = [], bool $return_this = false)
    {
        $this->where($where);
        $this->join('master_pegawai', 'data_realisasi_target.id_pegawai = master_pegawai.id');
        $this->join('data_kegiatan', 'data_realisasi_target.id_kegiatan = data_kegiatan.id');
        $this->join('master_unit_kerja', 'data_kegiatan.id_unit_kerja = master_unit_kerja.id');
        $this->join('data_target_pegawai', 'data_realisasi_target.id_kegiatan = data_target_pegawai.id_kegiatan AND data_target_pegawai.id_pegawai = data_realisasi_target.id_pegawai');
        $this->select('
            master_pegawai.nama_pegawai, 
            master_unit_kerja.unit_kerja as unit_kerja_kegiatan, 
            data_realisasi_target.keterangan as keterangan_realisasi, 
            data_realisasi_target.id as id_realisasi,
            data_kegiatan.jumlah_target as target_kegiatan,
            data_target_pegawai.jumlah_target as target_pegawai,
            data_kegiatan.*, 
            data_realisasi_target.*
        ');
        $this->orderBy('data_realisasi_target.tanggal_realisasi', 'ASC');
        if ($return_this) {
            return $this;
        } else {
            return $this->findAll();
        }
    }
}
