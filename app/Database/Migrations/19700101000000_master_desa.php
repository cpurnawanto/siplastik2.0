<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterDesa extends Migration
{

    /**
     * Buat tabel master_desa
     */
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'kddesa' => [
                'type' => 'VARCHAR',
                'constraint' => 4
            ],
            'nmdesa' => [
                'type' => 'VARCHAR',
                'constraint' => 32
            ],
            'idkecamatan' => [
                'type' => 'VARCHAR',
                'constraint' => 3,
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('master_desa');
    }

    public function down()
    {
        $this->forge->dropTable('master_desa');
    }
}
