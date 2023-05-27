<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    /**
     * Seeder untuk tabel master_pegawai
     * sementara untuk seed akun admin saja
     */
    public function run()
    {
        $admin = [
            'nip_baru' => '000000000000000000',
            'nip_lama' => '000000000',
            'nama_pegawai' => 'Administrator',
            'nama_singkat' => 'Admin',
            'username' => 'admin',
            'password' => password_hash('adminpakaipassword', PASSWORD_BCRYPT),
            'id_golongan' => null,
            'id_wilayah' => null,
            'id_unit_kerja' => null,
            'id_eselon' => 0,
            'id_fungsional' => null,
            'is_aktif' => 1,
            'is_admin' => 1,
        ];

        $this->db->table('master_pegawai')->insert($admin);
    }
}
