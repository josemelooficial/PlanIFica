<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDestaqueToAulaHorario extends Migration
{
    public function up()
    {
        $this->forge->addColumn('aula_horario', [
            'destaque' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'bypass'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('aula_horario', 'destaque');
    }
}
