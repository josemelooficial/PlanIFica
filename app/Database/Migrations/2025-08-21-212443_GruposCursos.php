<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class GruposCursos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'              => 'INT', 
                'constraint'        => 11, 
                'unsigned'          => TRUE, 
                'auto_increment'    => TRUE
            ], 

            'nome' => [
                'type'              => 'VARCHAR', 
                'constraint'        => 64, 
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('grupos_de_cursos');
    }

    public function down()
    {
        $this->forge->dropTable('grupos_de_cursos', true, true);
    }
}
