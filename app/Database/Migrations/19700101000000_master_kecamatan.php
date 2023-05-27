<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterKecamatan extends Migration
{

    /**
     * Buat tabel master_kecamatan
     */
    public function up()
    {
        $this->forge->addField([
            'kdkecamatan' => [
                'type' => 'VARCHAR',
                'constraint' => 3
            ],
            'nmkecamatan' => [
                'type' => 'VARCHAR',
                'constraint' => 32
            ]
        ]);

        $this->forge->addPrimaryKey('kdkecamatan');
        $this->forge->createTable('master_kecamatan');
    }

    public function down()
    {
        $this->forge->dropTable('master_kecamatan');
    }
}
