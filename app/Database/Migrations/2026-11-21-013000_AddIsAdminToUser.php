<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class AddIsAdminToUser extends Migration
{
    public function up()
    {
        $this->forge->addColumn('user', [
            'is_admin' => [
                'type'    => 'BOOLEAN',
                'default' => new RawSql('false'),
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('user', 'is_admin');
    }
}
