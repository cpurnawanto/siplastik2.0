<?php

namespace App\Models;

use CodeIgniter\Model;

class KreditFungsionalModel extends Model
{
    protected $table = 'master_kredit_fungsional';

    protected $allowedFields = [
        'id_kegiatan',
        'id_fungsional',
        'angka_kredit'
    ];

    protected $validationRules = [
        'id_kegiatan' => 'required|is_not_unique[master_kredit_kegiatan.id]',
        'id_fungsional' => 'required|is_not_unique[master_fungsional.id]',
        'angka_kredit' => 'required|decimal'
    ];
    protected $validationMessages = [
        'id_kegiatan' => [
            'required' => 'ID Kegiatan kosong',
            'is_not_unique' => 'ID Kegiatan tidak ada atau gagal disimpan'
        ],
        'id_fungsional' => [
            'required' => 'ID Fungsional kosong',
            'is_not_unique' => 'ID Fungsional tidak ada'
        ],
        'angka_kredit' => [
            'required' => 'Angka kredit kosong',
            'decimal' => 'Angka kredit ada yang tidak valid'
        ]
    ];


    /**
     * Mendapatkan angka kredit fungsional untuk kegiatan tertenru
     * @param int|string $id_kegiatan
     * id kredit kegiatan
     * 
     * @return array[array]
     * detail kredit kegiatan fungsional jika ada
     */
    public function getKreditKegiatanFungsional($id_kegiatan)
    {
        $this->join('master_fungsional', 'master_kredit_fungsional.id_fungsional = master_fungsional.id AND id_kegiatan = ' . $this->escape($id_kegiatan), 'RIGHT');
        $this->select('magi_master_kredit_fungsional.*, fungsional');
        $this->orderBy('master_fungsional.id');
        return $this->findAll();
    }
}
