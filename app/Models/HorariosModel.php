<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Exceptions\ReferenciaException;

class HorariosModel extends BaseModel
{
    protected $table            = 'horarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nome'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id' => 'permit_empty|is_natural_no_zero|max_length[11]',
        'nome' => 'required|is_unique[horarios.nome,id,{id}]|max_length[64]',
    ];
    protected $validationMessages   = [
        "nome" => [
            "required" => "Informe o nome da Grade de Horário.",
            "is_unique" => "A Grade de Horário informada já está cadastrada.",
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = ['logInsert'];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = ['logUpdate'];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['getRestricoes'];
    protected $afterDelete    = ['logDelete'];

    public function getHorariosAulas()
    {
        // Conecta ao banco de dados
        $db = \Config\Database::connect();

        // Cria o builder para a tabela 'horario'
        $builder = $this->builder();
        $builder->orderBy('id');
        $horarios = $builder->get()->getResultArray();

        // Para cada horário, busca os tempos de aula relacionados
        foreach ($horarios as &$horario) {
            $builder2 = $db->table('tempos_de_aula');
            $tempos = $builder2->where('horario_id', $horario['id'])->get()->getResultArray();
            $horario['tempos_de_aula'] = $tempos;
        }

        return $horarios;
    }

    protected function logInsert(array $data)
    {
        $this->registrarLog('Inserção', 'Nova grade de horário adicionado', $data['id'] ?? null);
        return $data;
    }

    protected function logUpdate(array $data)
    {
        $this->registrarLog('Edição', 'Grade de horário atualizado', $data['id'][0] ?? null);
        return $data;
    }

    protected function logDelete(array $data)
    {
        $this->registrarLog('Exclusão', 'Grade de horário removido', $data['id'][0] ?? null);
        return $data;
    }

    public function getRestricoes($id) 
    {
        $id = $id['id'];

        $tempos_aula = $this->db->table('tempos_de_aula')->where('horario_id', $id)->get();
        $turmas = $this->db->table('turmas')->where('horario_id', $id)->orWhere('horario_preferencial_id', $id)->get();

        if ($tempos_aula->getNumRows()) {
            $tempos_aula = $this->db->table('tempos_de_aula AS ta')
                ->select("ta.*")
                ->where("ta.horario_id", $id)
                ->get()->getResult();
            
            $tempos_aula = array_slice($tempos_aula, 0, 5);

            $tempos_aula = array_map(function($t) {
                switch ($t->dia_semana) {
                    case 1:
                        $t->dia_semana = "Segunda-Feira";
                        break;
                    case 2:
                        $t->dia_semana = "Terça-Feira";
                        break;
                    case 3:
                        $t->dia_semana = "Quarta-Feira";
                        break;
                    case 4:
                        $t->dia_semana = "Quinta-Feira";
                        break;
                    case 5:
                        $t->dia_semana = "Sexta-Feira";
                        break;
                }

                $t->minuto_inicio = $t->minuto_inicio == "0" ? "00" : $t->minuto_inicio;
                $t->minuto_fim = $t->minuto_fim == "0" ? "00" : $t->minuto_fim;
                $t->intervalo = "$t->hora_inicio:$t->minuto_inicio - $t->hora_fim:$t->minuto_fim";

                return $t;
            }, $tempos_aula);
        } else {
            $tempos_aula = null;
        }

        if ($turmas->getNumRows()) {
            $turmas = $this->db->table('turmas AS t')
                ->select("t.id, t.sigla AS turma")
                ->where('t.horario_id', $id)
                ->orWhere('t.horario_preferencial_id', $id)
                ->orderBy('t.curso_id')
                ->orderBy('t.periodo')
                ->get()->getResult();

            $turmas = array_slice($turmas, 0, 5);
        } else {
            $turmas = null;
        }

        $restricoes = [
            'tempos_aula' => $tempos_aula, 
            'turmas' => $turmas
        ];

        return $restricoes;
    }
}
