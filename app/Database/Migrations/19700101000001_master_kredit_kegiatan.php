<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterKreditKegiatan extends Migration
{

    /**
     * Buat tabel master_kredit_kegiatan
     */
    public function up()
    {
        $this->forge->addField([
            //rumus id = 'kode'.'kode_tingkat'
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'kode' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'kode_tingkat' =>
            [
                'type' => 'INT',
                'unsigned' => true
            ],
            'nama_tingkat' =>
            [
                'type' => 'VARCHAR',
                'constraint' => 8
            ],
            'kode_perka' =>
            [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => 10
            ],
            'kode_unsur' =>
            [
                'type' => 'VARCHAR',
                'constraint' => 3
            ],
            'nama_unsur' =>
            [
                'type' => 'VARCHAR',
                'constraint' => 40
            ],
            'uraian_singkat' =>
            [
                'type' => 'VARCHAR',
                'constraint' => 400,
                'null' => true
            ],
            'kegiatan' =>
            [
                'type' => 'VARCHAR',
                'constraint' => 160,
                'null' => true
            ],
            'satuan_hasil' =>
            [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'bukti_fisik' =>
            [
                'type' => 'VARCHAR',
                'constraint' => 400,
                'null' => true
            ],
            'angka_kredit' =>
            [
                'type' => 'DECIMAL',
                'constraint' => '8,4'
            ],
            'pelaksana_kegiatan' =>
            [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'bidang' =>
            [
                'type' => 'VARCHAR',
                'constraint' => 40,
            ],
            'seksi' =>
            [
                'type' => 'VARCHAR',
                'constraint' => 40,
                'null' => true
            ],
            'keterangan' =>
            [
                'type' => 'TEXT',
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('master_kredit_kegiatan');
    }

    public function down()
    {
        $this->forge->dropTable('master_kredit_kegiatan');
    }
}
