<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterJenis extends Migration
{

    /**
     * Buat tabel master_jenis
     */
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'jenis' => [
                'type' => 'VARCHAR',
                'constraint' => 10
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('master_jenis');
    }

    public function down()
    {
        $this->forge->dropTable('master_jenis');
    }
}
