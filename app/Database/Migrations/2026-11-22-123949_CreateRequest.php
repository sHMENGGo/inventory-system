<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateRequest extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'       => 'UUID',
                'default'    => new RawSql('gen_random_uuid()'),
            ],
            'user_id' => [
                'type'       => 'UUID',
            ],
            'item_id' => [
                'type'       => 'UUID',
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'pending',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'user', 'id');
        $this->forge->addForeignKey('item_id', 'item', 'id');
        $this->forge->createTable('request');
    }

    public function down()
    {
        $this->forge->dropTable('request');
    }
}
