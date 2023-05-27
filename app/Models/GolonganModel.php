<?php

namespace App\Models;

use CodeIgniter\Model;

class GolonganModel extends Model
{
    protected $table = 'master_golongan';

    protected $allowedFields = [
        'id',
        'golongan',
        'pangkat'
    ];

    protected $useSoftDeletes = false;
}
