<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDestaqueToAulas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('aulas', [
            'destaque' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'versao_id'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('aulas', 'destaque');
    }
}
