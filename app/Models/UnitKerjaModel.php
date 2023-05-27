<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitKerjaModel extends Model
{
    protected $table = 'master_unit_kerja';

    protected $allowedFields = [
        'id',
        'unit_kerja'
    ];

    protected $useSoftDeletes = false;
}
