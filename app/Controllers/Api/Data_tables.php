<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;

class Data_tables extends \CodeIgniter\Controller
{
    use ResponseTrait;

    public $supportedResponseFormats = [
        'application/json',
    ];

    public function kredit_kegiatan($kode_tingkat = null)
    {
        $get_params = $this->request->getGet();
        $where = [];
        if ($kode_tingkat) {
            $where = ['kode_tingkat' => $kode_tingkat];
        }
        $get_response = $this->_getDataTablesResponse($get_params, new \App\Models\KreditKegiatanModel(), $where);
        return $this->respond($get_response, 200);
    }

    /**
     * Fungsi untuk mengirimkan ajax table response dari frontend DataTables
     * Selengkapnya lihat
     * https://datatables.net/manual/server-side
     * 
     * @param array $get_params array dari GET query
     * @param \CodeIgniter\Model $model_instance instance dari model (new ...Model())
     * @param array $internalWhere clause where tambahan dari routes (bukan dari user)
     * 
     * @return array array response yang akan dikirimkan kembali ke frontend DataTables
     */
    private function _getDataTablesResponse(array $get_params, \CodeIgniter\Model $model_instance, array $internalWhere = [])
    {
        /**
         * $draw : urutan draw dari DataTables
         * $start : offset
         * $length : limit
         * $searchTerm : string pencarian
         * 
         */
        $draw = isset($get_params['draw']) ? intval($get_params['draw']) : 0;
        $start = isset($get_params['start']) ? intval($get_params['start']) : 0;
        $length = isset($get_params['length']) ? intval($get_params['length']) : 10;

        $search_term = '*';
        if (isset($get_params['search'])) {
            if (isset($get_params['search']['value'])) {
                $search_term = $get_params['search']['value'];
            }
        }


        /**
         * Struktur array $order
         * [
         *      ['column' => 'column1', 'dir' => 'asc'],
         *      ['column' => 'column2', 'dir' => 'desc'],
         * ]
         * 
         * Selengkapnya https://datatables.net/manual/server-side
         * di bagian Sent Parameters
         */
        $order = [];

        if (isset($get_params['order'])) {
            if (!empty($get_params['order'])) {
                $order = $get_params['order'];
            }
        }

        /**
         * Struktur array $columns
         * [
         *      ['data' => 'column_db_name_1', ...],
         *      ['data' => 'column_db_name_2', ...],
         * ]
         * 
         * Selengkapnya https://datatables.net/manual/server-side
         * di bagian Sent Parameters
         */
        $columns = [];
        if (isset($get_params['columns'])) {
            if (!empty($get_params['columns'])) {
                $columns = $get_params['columns'];
            }
        }


        /**
         * Asumsi semua tabel ada idnya
         * dapatkan total record di db
         */
        $model_instance->selectCount('id');
        $records_total = intval($model_instance->get()->getResultArray()[0]['id']);

        /**
         * meratakan nama kolom dari request agar bisa masuk ke query select
         * contoh array ['column1', 'column2', ...]
         * diimplode menjadi string 'column1, column2, ...'
         * harusnya otomatis terescape saat di dalam $db->select();
         * 
         */
        $column_names_array = array_map(function ($col) {
            return $col['data'];
        }, $columns);

        $model_instance->select(implode(', ', $column_names_array));

        /**
         * query like
         */
        $model_instance->orGroupStart();
        if ($search_term !== '*') {
            foreach ($columns as $idx => $col) {
                if ($idx) {
                    $model_instance->orLike($col['data'], $search_term);
                } else {
                    $model_instance->like($col['data'], $search_term);
                }
            }
        }
        $model_instance->groupEnd();
        /**
         * query internal where
         * 
         */

        $model_instance->where($internalWhere);

        /**
         * query order
         */
        foreach ($order as $ord) {
            $model_instance->orderBy($columns[$ord['column']]['data'], $ord['dir']);
        }

        /**
         * Dapatkan datanya
         */
        $data = $model_instance->findAll($length, $start);

        /**
         * Ini untuk menghitung berapa row yang sebenarnya
         */
        $model_instance->selectCount('id');


        $model_instance->orGroupStart();
        if ($search_term !== '*') {
            foreach ($columns as $idx => $col) {
                if ($idx) {
                    $model_instance->orLike($col['data'], $search_term);
                } else {
                    $model_instance->like($col['data'], $search_term);
                }
            }
        }
        $model_instance->groupEnd();

        /**
         * query internal where
         * 
         */

        $model_instance->where($internalWhere);

        $count = intval($model_instance->findAll()[0]['id']);

        return [
            'draw' => $draw,
            'recordsTotal' => $records_total,
            'recordsFiltered' => $count,
            'data' => $data,
        ];
    }
}
