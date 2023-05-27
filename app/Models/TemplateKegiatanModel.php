<?php

namespace App\Models;

use CodeIgniter\Model;

class TemplateKegiatanModel extends Model
{
    protected $table = 'master_template_kegiatan';

    protected $allowedFields = [
        'nama_kegiatan',
        'id_unit_kerja',
        'satuan_target',
        'jumlah_target',
        'keterangan',
        'is_tampil',
        'is_ckp',
        'bobot',
        'id_kredit_ahli',
        'id_kredit_terampil',
        'id_skp',
    ];


    protected $useSoftDeletes = false;

    protected $useTimestamps = true;

    protected $validationRules = [
        'nama_kegiatan' => 'min_length[3]|max_length[160]|required',
        'id_unit_kerja' => 'required',
        'satuan_target' => 'required',
        'is_tampil' => 'required|in_list[0,1]',
        'is_ckp' => 'required|in_list[0,1]',
        'bobot' => 'required|greater_than_equal_to[0]',
        'id_kredit_ahli' => 'permit_empty|is_natural|is_not_unique[master_kredit_kegiatan.id]',
        'id_kredit_terampil' => 'permit_empty|is_natural|is_not_unique[master_kredit_kegiatan.id]',
        'id_skp' => 'permit_empty|is_natural',
    ];

    protected $validationMessages = [
        'nama_kegiatan' => [
            'min_length' => 'Nama kegiatan terlalu singkat',
            'required' => 'Masukkan nama kegiatan'
        ],
        'id_unit_kerja' => [
            'required' => 'ID unit kerja belum dipilih'
        ],
        'satuan_target' => [
            'required' => 'Masukkan satuan target'
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

    public function getTemplateKegiatanMany(array $where = [], bool $return_this = false)
    {
        $this->where($where);

        $this->join('master_unit_kerja', 'master_template_kegiatan.id_unit_kerja = master_unit_kerja.id');
        $this->select('master_template_kegiatan.*, master_unit_kerja.unit_kerja');

        if ($return_this) {
            return $this;
        } else {
            return $this->findAll();
        }
    }
}
