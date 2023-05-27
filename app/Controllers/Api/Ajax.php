<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;

class Ajax extends \CodeIgniter\Controller
{
    use ResponseTrait;

    public $supportedResponseFormats = [
        'application/json',
    ];

    public function get_kredit_kegiatan($id)
    {
        $kredit_kegiatan_model = new \App\Models\KreditKegiatanModel();
        $kredit_kegiatan = $kredit_kegiatan_model->find($id);

        if (empty($kredit_kegiatan)) {
            return $this->respond(
                [
                    'error' => 'not found'
                ],
                404
            );
        }

        $kredit_fungsional_model = new \App\Models\KreditFungsionalModel();
        $kredit_fungsional = $kredit_fungsional_model->getKreditKegiatanFungsional($id);

        return $this->respond(
            [
                'data' => [
                    'kredit_kegiatan' => $kredit_kegiatan,
                    'kredit_fungsional' => $kredit_fungsional
                ]
            ],
            200
        );
    }
}
