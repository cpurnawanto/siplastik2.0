<?php

namespace App\Models;

use CodeIgniter\Model;

class DataKegiatanModel extends Model
{
    protected $table = 'data_kegiatan';

    protected $allowedFields = [
        'nama_kegiatan',
        'id_pegawai_pembuat',
        'id_unit_kerja',
        'tgl_mulai',
        'tgl_selesai',
        'satuan_target',
        'jumlah_target',
        'keterangan',
        'is_tampil',
        'is_ckp',
        'bobot',
        'id_kredit_ahli',
        'id_kredit_terampil',
        'id_skp',
        'is_usulan',
        'id_pegawai_usulan',
        'is_lock'
    ];


    protected $useSoftDeletes = true;

    protected $useTimestamps = true;

    protected $validationRules = [
        'nama_kegiatan' => 'min_length[3]|max_length[160]|required',
        'id_pegawai_pembuat' => 'is_not_unique[master_pegawai.id]|required',
        'id_unit_kerja' => 'required',
        'tgl_mulai' => 'required|valid_date[Y-m-d]',
        'tgl_selesai' => 'required|valid_date[Y-m-d]|date_ymd_greater_than_equal_to[tgl_mulai]',
        'satuan_target' => 'required',
        'jumlah_target' => 'required|is_natural',
        'is_tampil' => 'required|in_list[0,1]',
        'is_ckp' => 'required|in_list[0,1]',
        'bobot' => 'required|greater_than_equal_to[0]',
        'id_kredit_ahli' => 'permit_empty|is_natural|is_not_unique[master_kredit_kegiatan.id]',
        'id_kredit_terampil' => 'permit_empty|is_natural|is_not_unique[master_kredit_kegiatan.id]',
        'id_skp' => 'permit_empty|is_natural',
        'is_usulan' => 'required|in_list[0,1]',
        'id_pegawai_usulan' => 'permit_empty|is_not_unique[master_pegawai.id]',
        'is_lock' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'nama_kegiatan' => [
            'min_length' => 'Nama kegiatan terlalu singkat',
            'required' => 'Masukkan nama kegiatan'
        ],
        'id_pegawai_pembuat' => [
            'is_not_unique' => 'ID Pegawai Pembuat tidak ada, hubungi administrator/developer',
        ],
        'id_unit_kerja' => [
            'required' => 'ID unit kerja belum dipilih'
        ],
        'tgl_mulai' => [
            'required' => 'Masukkan tanggal mulai',
            'valid_date' => 'Format tanggal mulai tidak valid'
        ],
        'satuan_target' => [
            'required' => 'Masukkan satuan target'
        ],
        'tgl_selesai' => [
            'required' => 'Masukkan tanggal selesai',
            'valid_date' => 'Format tanggal selesai tidak valid',
            'date_ymd_greater_than_equal_to' => 'Tanggal selesai tidak boleh lebih kecil dari tanggal mulai'
        ],
        'bobot' => [
            'required' => 'Mohon masukkan bobot',
            'greater_than_equal_to' => 'Bobot tidak boleh negatif'
        ],
    ];

    /**
     * Insert dan update harus melalui validasi
     */
    protected $skipValidation = false;


    public function getKegiatanDetailOne($where = [])
    {
        $this->where($where);
        $this->join('master_pegawai pembuat', 'data_kegiatan.id_pegawai_pembuat = pembuat.id');
        $this->join('master_unit_kerja', 'data_kegiatan.id_unit_kerja = master_unit_kerja.id');
        $this->join('master_pegawai pengusul', 'data_kegiatan.id_pegawai_usulan = pengusul.id', 'left');
        $this->select('
            data_kegiatan.*,
            pembuat.nama_pegawai as nama_pembuat,
            pengusul.nama_pegawai as nama_pengusul,
            master_unit_kerja.unit_kerja
        ');
        return $this->first();
    }
}
