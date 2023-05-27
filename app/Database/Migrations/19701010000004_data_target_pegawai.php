<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;


class DataTargetPegawai extends Migration
{
    /**
     * Buat tabel data_target_pegawai
     */
    public function up()
    {
        //autoincrement id
        $this->forge->addField('id');

        $this->forge->addField([
            'id_kegiatan' => [
                'type' => 'INT'
            ],
            'id_pegawai' => [
                'type' => 'INT'
            ],
            'jumlah_target' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'persen_kualitas' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'id_fungsional_kredit_kegiatan' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'id_kredit_kegiatan' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp',
        ]);

        $this->forge->addForeignKey('id_pegawai', 'master_pegawai', 'id', 'CASCADE');
        $this->forge->addForeignKey('id_kegiatan', 'data_kegiatan', 'id', 'CASCADE');
        $this->forge->addForeignKey('id_kredit_kegiatan', 'master_kredit_kegiatan', 'id', 'CASCADE');
        $this->forge->addForeignKey('id_fungsional_kredit_kegiatan', 'master_fungsional', 'id', 'CASCADE');
        $this->forge->addUniqueKey(['id_kegiatan', 'id_pegawai']);

        $this->forge->createTable('data_target_pegawai');
    }

    public function down()
    {
        $this->forge->dropTable('data_target_pegawai');
    }
}
