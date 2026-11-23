<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDescriptionToItem extends Migration
{
    public function up()
    {
        $this->forge->addColumn('item', [
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        //
    }
}
