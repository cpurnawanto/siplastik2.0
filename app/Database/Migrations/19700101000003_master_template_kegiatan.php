<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterTemplateKegiatan extends Migration
{

    /**
     * Buat tabel master_template_kegiatan
     */
    public function up()
    {
        //autoincrement id
        $this->forge->addField('id');

        $this->forge->addField([
            'nama_kegiatan' => [
                'type' => 'varchar',
                'constraint' => 160
            ],
            'id_unit_kerja' => [
                'type' => 'int',
                'unsigned' => true,
            ],
            'satuan_target' => [
                'type' => 'VARCHAR',
                'constraint' => 80
            ],
            'keterangan' => [
                'type' => 'text'
            ],
            'is_tampil' => [
                'type' => 'boolean',
                'default' => true
            ],
            'is_ckp' => [
                'type' => 'boolean',
                'default' => false
            ],
            'bobot' => [
                'type' => 'decimal(6,3)',
                'unsigned' => true,
                'default' => 0.001
            ],
            /**
             * TODO
             */
            'id_kredit_terampil' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'id_kredit_ahli' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'id_skp' => [
                'type' => 'int',
                'unsigned' => true,
                'null' => true
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp',
            'deleted_at datetime null',
        ]);

        $this->forge->addForeignKey('id_unit_kerja', 'master_unit_kerja', 'id', 'CASCADE');
        $this->forge->addForeignKey('id_kredit_terampil', 'master_kredit_kegiatan', 'id', 'CASCADE');
        $this->forge->addForeignKey('id_kredit_ahli', 'master_kredit_kegiatan', 'id', 'CASCADE');

        $this->forge->createTable('master_template_kegiatan');
    }

    public function down()
    {
        $this->forge->dropTable('master_template_kegiatan');
    }
}
