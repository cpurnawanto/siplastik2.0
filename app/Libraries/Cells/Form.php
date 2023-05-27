<?php

namespace App\Libraries\Cells;

/**
 * class cell yang berisi utility terkait form dan pesan error
 */
class Form
{
    /**
     * membangun status badge yang mengandung informasi error atau success 
     */
    public function statusBadge()
    {
        $session = \Config\Services::session();
        $errors = $session->getFlashdata('errors');
        $success = $session->getFlashdata('success');
        return view('cells/form_status_badge', ['errors' => $errors, 'success' => $success]);
    }

    /**
     * membangun tabel import berdasarkan informasi dari key sesi import_status
     * 
     * Struktur import status
     * [
     *     'data' => [
     *         'kolom1' => 'data1',
     *         'kolom2' => 'data2',
     *         ... 
     *      ],
     *     'errors' => [..., ...],
     *     'success => ...
     * ]
     */
    public function importStatusTable()
    {
        $session = \Config\Services::session();
        $import_status = $session->getFlashdata('import_status');

        if (!empty($import_status)) {
            return view('cells/form_import_status_table', ['import_status' => $import_status]);
        } else {
            return view('cells/empty');
        }
    }

    /**
     * membangun form pegawai method post 
     * beserta autocompletenya dengan nilai flashdata 'input' atau dengan parameter input
     * 
     * @param array $params
     * element array parameter yang dibutuhkan adalah:
     * 
     * uri : uri tujuan form pegawai
     * 
     * input (opsional) : array input untuk autocomplete form pegawai
     * jika key parameter input disediakan (tidak null), maka autocomplete akan menggunakan parameter tsb. 
     * daripada menggunakan nilai yang disediakan flashdata
     * 
     */
    public function pegawai(array $params)
    {
        /**
         * Dapatkan list untuk form select
         */
        $list_fungsional = (new \App\Models\FungsionalModel())->findAll();
        $list_golongan = (new \App\Models\GolonganModel())->findAll();
        $list_wilayah = (new \App\Models\WilayahModel())->findAll();
        $list_unit_kerja = (new \App\Models\UnitKerjaModel())->findAll();

        /**
         * jika key parameter input disediakan (tidak null), maka autocomplete akan menggunakan parameter tsb. 
         * jika tidak, maka akan menggunakan nilai input dari sesi
         */
        if (!empty($params['input'])) {
            $input = $params['input'];
        } else {
            $session = \Config\Services::session();
            $input = $session->getFlashdata('input');
        }

        return view('cells/form_pegawai', [
            'uri' => $params['uri'],
            'input' => $input,
            'list' => [
                'id_fungsional' => $list_fungsional,
                'id_golongan' => $list_golongan,
                'id_wilayah' => $list_wilayah,
                'id_unit_kerja' => $list_unit_kerja
            ]
        ]);
    }


    /**
     * membangun form kegiatan method post
     * berdasarkan jabatan struktural dari pegawai yang login 
     * beserta autocompletenya dengan nilai flashdata 'input' atau dengan parameter input
     * 
     * @param array $params
     * element array parameter yang dibutuhkan adalah:
     * 
     * uri : uri tujuan form kegiatan
     * 
     * input (opsional) : array input untuk autocomplete form kegiatan
     * jika key parameter input disediakan (tidak null), maka autocomplete akan menggunakan parameter tsb. 
     * daripada menggunakan nilai yang disediakan flashdata
     * 
     * template (opsional, default false) : true/false flag untuk menentukan apakah form adalah form template kegiatan 
     */
    public function kegiatan(array $params)
    {
        $list_unit_kerja = (new \App\Models\UnitKerjaModel())->findAll();

        /**
         * jika key parameter input disediakan (tidak null), maka autocomplete akan menggunakan parameter tsb. 
         * jika tidak, maka akan menggunakan nilai input dari sesi
         */
        if (!empty($params['input'])) {
            $input = $params['input'];
        } else {
            $session = \Config\Services::session();
            $input = $session->getFlashdata('input');
        }

        /**
         * parameter apakah template (default false)
         */

        if (!empty($params['template'])) {
            $template = $params['template'];
        } else {
            $template = false;
        }

        $kredit_kegiatan_model = new \App\Models\KreditKegiatanModel();

        if (!empty($input['id_kredit_ahli'])) {
            $kredit = $kredit_kegiatan_model->find($input['id_kredit_ahli']);
            $input['id_kredit_ahli_desc'] = $kredit['uraian_singkat'] . ' - ' . $kredit['kegiatan'] . ' (' . $kredit['kode']  . ' - ' . $kredit['nama_tingkat'] . ' ' . $kredit['kode_perka'] . ')';
        }

        if (!empty($input['id_kredit_terampil'])) {
            $kredit = $kredit_kegiatan_model->find($input['id_kredit_terampil']);
            $input['id_kredit_terampil_desc'] = $kredit['uraian_singkat'] . ' - ' . $kredit['kegiatan'] . ' (' . $kredit['kode']  . ' - ' . $kredit['nama_tingkat'] . ' ' . $kredit['kode_perka'] . ')';
        }

        return view('cells/form_data_kegiatan', [
            'uri' => $params['uri'],
            'input' => $input,
            'template' => $template,
            'list' => [
                'unit_kerja' => $list_unit_kerja
            ]
        ]);
    }
    /**
     *   
     * @param array $params
     * element array parameter yang dibutuhkan adalah:
     * 
     * uri : uri tujuan form realisasi
     * 
     * input (opsional) : array input untuk autocomplete form realisasi
     * jika key parameter input disediakan (tidak null), maka autocomplete akan menggunakan parameter tsb. 
     * daripada menggunakan nilai yang disediakan flashdata
     * 
     * struktural (opsional) : apakah form digunakan pada edit struktural atau tidak (true or false)
     * 
     * target_pegawai (opsional) : array dari ```App\Models\DataTargetPegawaiModel::getTargetKegiatanMany```
     * untuk populate form select id_pegawai
     * 
     */
    public function realisasiKegiatan(array $params)
    {
        /**
         * jika key parameter input disediakan (tidak null), maka autocomplete akan menggunakan parameter tsb. 
         * jika tidak, maka akan menggunakan nilai input dari sesi
         */
        if (!empty($params['input'])) {
            $input = $params['input'];
        } else {
            $session = \Config\Services::session();
            $input = $session->getFlashdata('input');
        }

        if (!empty($params['struktural'])) {
            $struktural = $params['struktural'];
        } else {
            $struktural = false;
        }

        if (!empty($params['target_pegawai'])) {
            $target_pegawai = $params['target_pegawai'];
        } else {
            $target_pegawai = false;
        }

        return view('cells/form_realisasi_kegiatan', [
            'uri' => $params['uri'],
            'input' => $input,
            'struktural' => $struktural,
            'target_pegawai' => $target_pegawai
        ]);
    }


    public function nilaiAlokasi(array $params)
    {
        /**
         * jika key parameter input disediakan (tidak null), maka autocomplete akan menggunakan parameter tsb. 
         * jika tidak, maka akan menggunakan nilai input dari sesi
         */
        if (!empty($params['input'])) {
            $input = $params['input'];
        } else {
            $session = \Config\Services::session();
            $input = $session->getFlashdata('input');
        }

        return view('cells/form_nilai_alokasi', [
            'uri' => $params['uri'],
            'input' => $input
        ]);
    }
}
