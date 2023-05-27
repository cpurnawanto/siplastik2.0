<?php

namespace App\Models;

use CodeIgniter\Model;

class FungsionalModel extends Model
{
    protected $table = 'master_fungsional';

    protected $allowedFields = [
        'id',
        'fungsional'
    ];

    protected $useSoftDeletes = false;
}
