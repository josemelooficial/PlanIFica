<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBypassAula extends Migration
{
    public function up()
    {
        $fields = [
            'bypass' => [
                'type'              => 'INT',
                'constraint'        => 1,
                'unsigned'          => TRUE
            ]
        ];

        $this->forge->addColumn('aula_horario', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('aula_horario', 'bypass');
    }
}
