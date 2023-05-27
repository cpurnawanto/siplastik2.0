<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterKreditFungsional extends Migration
{

    /**
     * Buat tabel master_kredit_fungsional
     */
    public function up()
    {
        $this->forge->addField('id');

        $this->forge->addField([
            'id_kegiatan' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'id_fungsional' =>
            [
                'type' => 'INT',
                'unsigned' => true
            ],
            'angka_kredit' =>
            [
                'type' => 'DECIMAL',
                'constraint' => '8,4'
            ],
        ]);

        $this->forge->addForeignKey('id_kegiatan', 'master_kredit_kegiatan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_fungsional', 'master_fungsional', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('master_kredit_fungsional');
    }

    public function down()
    {
        $this->forge->dropTable('master_kredit_fungsional');
    }
}
