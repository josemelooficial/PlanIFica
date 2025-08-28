<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CursoGrupo extends Migration
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

            'curso_id' => [
                'type'              => 'INT', 
                'constraint'        => 11, 
                'unsigned'          => TRUE
            ], 

            'grupo_de_cursos_id' => [
                'type'              => 'INT', 
                'constraint'        => 11, 
                'unsigned'          => TRUE
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('curso_id', 'cursos', 'id');
        $this->forge->addForeignKey('grupo_de_cursos_id', 'grupos_de_cursos', 'id');
        $this->forge->createTable('curso_grupo');
    }

    public function down()
    {
        $this->forge->dropTable('curso_grupo', true, true);
    }
}
