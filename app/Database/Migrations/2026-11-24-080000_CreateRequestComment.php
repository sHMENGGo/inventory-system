<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateRequestComment extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'    => 'UUID',
                'default' => new RawSql('gen_random_uuid()'),
            ],
            'request_id' => [
                'type' => 'UUID',
            ],
            'user_id' => [
                'type' => 'UUID',
            ],
            'body' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('request_id');
        $this->forge->addForeignKey('request_id', 'request', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('request_comment');
    }

    public function down()
    {
        $this->forge->dropTable('request_comment');
    }
}
