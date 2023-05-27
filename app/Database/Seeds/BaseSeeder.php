<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BaseSeeder extends Seeder
{
    /**
     * Tambahkan semua seeder di sini BERURUTAN
     */
    public function run()
    {
        $this->call('FGUWSeeder');
        $this->call('PegawaiSeeder');
    }
}
