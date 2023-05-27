<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;


class DataRealisasiTarget extends Migration
{
    /**
     * Buat tabel data_realisasi_target
     */

    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'id_kegiatan' => [
                'type' => 'INT'
            ],
            'id_pegawai' => [
                'type' => 'INT'
            ],
            'tanggal_realisasi' => [
                'type' => 'date'
            ],
            'jumlah_realisasi' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'id_pegawai_acc' => [
                'type' => 'INT',
                'null' => true
            ],
            'waktu_acc' => [
                'type' => 'datetime',
                'null' => true
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp',
        ]);

        $this->forge->addForeignKey('id_kegiatan', 'data_kegiatan', 'id');
        $this->forge->addForeignKey('id_pegawai', 'master_pegawai', 'id');
        $this->forge->addForeignKey('id_pegawai_acc', 'master_pegawai', 'id');

        $this->forge->createTable('data_realisasi_target');
    }
    public function down()
    {
        $this->forge->dropTable('data_realisasi_target');
    }
}
