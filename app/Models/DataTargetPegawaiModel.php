<?php

namespace App\Models;

use CodeIgniter\Model;

class DataTargetPegawaiModel extends Model
{
    protected $table = 'data_target_pegawai';

    protected $allowedFields = [
        'id_kegiatan',
        'id_pegawai',
        'jumlah_target',
        'persen_kualitas',
        'keterangan',
        'id_fungsional_kredit_kegiatan',
        'id_kredit_kegiatan'
    ];


    protected $useSoftDeletes = false;

    protected $useTimestamps = true;

    protected $validationRules = [
        'id_kegiatan' => 'required|is_not_unique[data_kegiatan.id]',
        'id_pegawai' => 'required|is_not_unique[master_pegawai.id]',
        'jumlah_target' => 'required|is_natural',
        'persen_kualitas' => 'required|is_natural|less_than_equal_to[100]',
        'id_kredit_kegiatan' => 'permit_empty|is_natural|is_not_unique[master_kredit_kegiatan.id]',
        'id_fungsional_kredit_kegiatan' => 'permit_empty|is_natural|is_not_unique[master_fungsional.id]',
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
        'jumlah_target' => [
            'required' => 'Jumlah target kosong',
            'is_natural' => 'Jumlah target tidak boleh negatif dan harus bulat'
        ],
        'persen_kualitas' => [
            'is_natural' => 'Persen kualitas tidak boleh negatif dan harus bulat',
            'less_than_equal_to' => 'Persen kualitas tidak boleh dibawah 100'
        ]
    ];

    public function getIndeksTargetKegiatanPegawai(int $id_kegiatan, array $where = [])
    {
        $this->where($where);
        $this->join('data_kegiatan', 'data_target_pegawai.id_kegiatan = data_kegiatan.id AND data_target_pegawai.id_kegiatan = ' . $id_kegiatan);
        $this->join('master_pegawai', 'data_target_pegawai.id_pegawai = master_pegawai.id', 'right');
        $this->join('master_fungsional', 'master_pegawai.id_fungsional = master_fungsional.id ', 'left');
        $this->join('master_golongan', 'master_pegawai.id_golongan = master_golongan.id ', 'left');
        $this->join('master_unit_kerja', 'master_pegawai.id_unit_kerja = master_unit_kerja.id ', 'left');
        $this->join('master_wilayah', 'master_pegawai.id_wilayah = master_wilayah.id ', 'left');
        $this->select('data_target_pegawai.*, nama_pegawai, id_eselon AS eselon, golongan, fungsional, pangkat, unit_kerja, master_pegawai.id as id_pegawai, id_fungsional');
        $this->orderBy('data_target_pegawai.jumlah_target', 'desc');
        $this->orderBy('nip_baru');
        return  $this->findAll();
    }

    public function getTargetKegiatanMany(array $where = [], string $mulai_order = 'desc')
    {
        $this->where($where);
        $this->join('data_kegiatan', 'data_target_pegawai.id_kegiatan = data_kegiatan.id');
        $this->join('master_pegawai', 'data_target_pegawai.id_pegawai = master_pegawai.id');
        $this->join('master_unit_kerja', 'data_kegiatan.id_unit_kerja = master_unit_kerja.id');
        $this->join('master_kredit_fungsional', 'data_target_pegawai.id_fungsional_kredit_kegiatan = master_kredit_fungsional.id_fungsional AND data_target_pegawai.id_kredit_kegiatan = master_kredit_fungsional.id_kegiatan', 'left');
        $this->join('master_fungsional', 'data_target_pegawai.id_fungsional_kredit_kegiatan = master_fungsional.id', 'left');
        $this->select('
            data_target_pegawai.id as id_target,
            data_target_pegawai.*, 
            data_target_pegawai.keterangan as keterangan_target, 
            data_kegiatan.*, 
            data_target_pegawai.jumlah_target as target_pegawai, 
            data_kegiatan.jumlah_target as target_kegiatan,
            master_pegawai.nama_pegawai,
            master_pegawai.id as id_pegawai,
            master_pegawai.id_fungsional as id_fungsional_pegawai,
            master_unit_kerja.unit_kerja as unit_kerja_kegiatan,
            master_fungsional.fungsional as fungsional_angka_kredit_target,
            master_kredit_fungsional.angka_kredit as angka_kredit_target
        ');
        $this->orderBy('tgl_mulai', $mulai_order);
        $this->orderBy('tgl_selesai', 'asc');
        return $this->findAll();
    }

    public function getIndeksKualitasKuantitas(string $tgl_mulai, string $tgl_selesai_plus_1, array $where = [], bool $return_this = false)
    {
        $this->where($where);
        $this->select('
                    master_pegawai.id as id_pegawai,
                    data_kegiatan.id as id_kegiatan,
                    data_target_pegawai.jumlah_target as jumlah_target,
                    data_kegiatan.bobot as bobot,
                    data_target_pegawai.persen_kualitas as persen_kualitas,
                    data_kegiatan.id_unit_kerja as id_unit_kerja_kegiatan');
        $this->selectSum('data_realisasi_target.jumlah_realisasi', 'jumlah_realisasi');
        $this->join(
            'data_kegiatan',
            'data_target_pegawai.id_kegiatan = data_kegiatan.id AND data_kegiatan.tgl_mulai >= "' . $tgl_mulai . '" AND ' .
                'data_kegiatan.tgl_mulai < "' . $tgl_selesai_plus_1 . '"'
        );
        $this->join('data_realisasi_target', 'data_realisasi_target.id_kegiatan = data_target_pegawai.id_kegiatan AND data_target_pegawai.id_pegawai = data_realisasi_target.id_pegawai', 'left');
        $this->join('master_pegawai', 'data_target_pegawai.id_pegawai = master_pegawai.id', 'right');
        $this->groupBy('data_kegiatan.id, master_pegawai.id');

        if ($return_this) {
            return $this;
        } else {
            return $this->findAll();
        }
    }
}
