<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateItem extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'UUID',
                'default'        => new RawSql('gen_random_uuid()'),
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'stock' => [
                'type' => 'INT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('item');
    }

    public function down()
    {
        $this->forge->dropTable('item');
    }
}
