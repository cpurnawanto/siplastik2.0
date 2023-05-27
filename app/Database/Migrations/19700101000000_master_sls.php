<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterSLS extends Migration
{

    /**
     * Buat tabel master_sls
     */
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'kdkecamatan' => [
                'type' => 'VARCHAR',
                'constraint' => 4
            ],
            'kddesa' => [
                'type' => 'VARCHAR',
                'constraint' => 4
            ],
            'kdsls' => [
                'type' => 'VARCHAR',
                'constraint' => 4
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('master_sls');
    }

    public function down()
    {
        $this->forge->dropTable('master_sls');
    }
}
