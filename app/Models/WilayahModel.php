<?php

namespace App\Models;

use CodeIgniter\Model;

class WilayahModel extends Model
{
    protected $table = 'master_wilayah';

    protected $allowedFields = [
        'id',
        'wilayah'
    ];

    protected $useSoftDeletes = false;
}
