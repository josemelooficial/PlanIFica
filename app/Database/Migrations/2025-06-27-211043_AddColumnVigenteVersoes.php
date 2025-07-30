<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnVigenteVersoes extends Migration
{
    public function up()
    {
        $fields = [
            'vigente' => [
                'type'              => 'TINYINT', 
                'constraint'        => 1, 
                'null'              => TRUE, 
                'default'           => 0
            ]
        ];

        $this->forge->addColumn('versoes', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('versoes', 'vigente');
    }
}
