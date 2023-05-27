<?php

namespace App\Database\Migrations;

use CodeIgniter\Config\Config;
use CodeIgniter\Database\Migration;

class MasterPegawai extends Migration
{

    /**
     * Buat tabel master_pegawai
     */
    public function up()
    {
        //autoincrement id
        $this->forge->addField('id');

        $this->forge->addField([
            'nip_baru' => [
                'type' => 'VARCHAR',
                'constraint' => 21,
            ],
            'nip_lama' => [
                'type' => 'VARCHAR',
                'constraint' => 9,
            ],
            'nama_pegawai' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
            ],
            'nama_singkat' => [
                'type' => 'VARCHAR',
                'constraint' => 32,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 60,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'id_golongan' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'id_wilayah' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'id_unit_kerja' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'id_eselon' => [
                'type' => 'INT',
                'unsigned' => true,
                'default' => 0
            ],
            'id_fungsional' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'is_aktif' => [
                'type' => 'BOOLEAN',
                'default' => 1,
            ],
            'is_admin' => [
                'type' => 'BOOLEAN',
                'default' => 0,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp',
        ]);

        $this->forge->addUniqueKey(['nip_baru', 'nip_lama', 'username']);

        $this->forge->addForeignKey('id_golongan', 'master_golongan', 'id');
        $this->forge->addForeignKey('id_wilayah', 'master_wilayah', 'id');
        $this->forge->addForeignKey('id_fungsional', 'master_fungsional', 'id');
        $this->forge->addForeignKey('id_unit_kerja', 'master_unit_kerja', 'id');

        $this->forge->createTable('master_pegawai');

        $seeder = \Config\Database::seeder();
        $seeder->call('FGUWSeeder');
        $seeder->call('PegawaiSeeder');
    }

    public function down()
    {
        $this->forge->dropTable('master_pegawai');
    }
}
