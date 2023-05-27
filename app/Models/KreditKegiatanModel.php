<?php

namespace App\Models;

use CodeIgniter\Model;

class KreditKegiatanModel extends Model
{
    protected $table = 'master_kredit_kegiatan';

    protected $allowedFields = [
        'id',
        'kode',
        'kode_tingkat',
        'nama_tingkat',
        'kode_perka',
        'kode_unsur',
        'nama_unsur',
        'uraian_singkat',
        'kegiatan',
        'satuan_hasil',
        'bukti_fisik',
        'angka_kredit',
        'pelaksana_kegiatan',
        'bidang',
        'seksi',
        'keterangan'
    ];

    protected $validationRules = [
        'id' => 'required|is_unique[master_kredit_kegiatan.id]',
        'kode' => 'required|is_natural',
        'kode_tingkat' => 'required|is_natural',
        'nama_tingkat' => 'required',
        'kode_perka' => 'max_length[10]',
        'kode_unsur' => 'required|max_length[3]',
        'nama_unsur' => 'required|max_length[40]',
        'uraian_singkat' => 'max_length[400]',
        'kegiatan' => 'max_length[160]',
        'satuan_hasil' => 'max_length[100]',
        'bukti_fisik' => 'max_length[400]',
        'angka_kredit' => 'required|decimal',
        'pelaksana_kegiatan' => 'required|max_length[20]',
        'bidang' => 'required|max_length[40]',
        'seksi' => 'max_length[40]'
    ];

    protected $validationMessages = [
        'id' => [
            'required' => 'Mohon masukkan id',
            'is_unique' => 'Kombinasi kode.kode_tingkat : {value} ada yang sama'
        ],
        'kode' => [
            'required' => 'Kode kegiatan kosong',
            'is_natural' => 'Kode kegiatan bukan angka',
        ],
        'kode_tingkat' => [
            'required' => 'Kode tingkat kosong',
            'is_natural' => 'Kode tingkat bukan angka',
        ],
        'nama_tingkat' => [
            'required' => 'Nama tingkat harus ada',
        ],
        'angka_kredit' => [
            'required' => 'Angka kredit kosong',
            'decimal' => 'Angka kredit bukan desimal'
        ],
        'pelaksana_kegiatan' => [
            'required' => 'Pelaksana kegiatan kosong'
        ],
        'bidang' => [
            'required' => 'Bidang kosong'
        ]
    ];

    protected $useSoftDeletes = false;
}
