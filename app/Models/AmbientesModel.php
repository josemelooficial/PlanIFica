<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Exceptions\ReferenciaException;

class AmbientesModel extends Model
{
    protected $table            = 'ambientes';
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
        'nome' => 'required|is_unique[ambientes.nome,id,{id}]|max_length[128]',
    ];
    protected $validationMessages   = [
        "nome" => [
            "required" => "Informe o nome do Ambiente.",
            "is_unique" => "Este ambiente já está cadastrado.",
            "max_length" => "O tamanho do Ambiente deve ser de no máximo 128 caracteres.",
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['getRestricoes'];
    protected $afterDelete    = [];

    public function getRestricoes($id)
    {
        $id = $id['id'];

        $horarios = $this->db->table('aula_horario_ambiente')->where('ambiente_id', $id)->get();

        if ($horarios->getNumRows()) {
            $horarios = $this->db->table('aula_horario_ambiente AS aha')
                ->select("ta.*, t.sigla AS turma, c.nome AS curso, v.nome AS versao")
                ->join("aula_horario AS ah", "aha.aula_horario_id = ah.id")
                ->join("tempos_de_aula AS ta", "ah.tempo_de_aula_id = ta.id")
                ->join("versoes AS v", "ah.versao_id = v.id")
                ->join("aulas AS a", "ah.aula_id = a.id")
                ->join("turmas AS t", "a.turma_id = t.id")
                ->join("cursos AS c", "t.curso_id = c.id")           
                ->where("aha.ambiente_id", $id)
                ->get()->getResult();

            $horarios = array_map(function($h) {
                switch ($h->dia_semana) {
                    case 1:
                        $h->dia_semana = "Segunda-Feira";
                        break;
                    case 2:
                        $h->dia_semana = "Terça-Feira";
                        break;
                    case 3:
                        $h->dia_semana = "Quarta-Feira";
                        break;
                    case 4:
                        $h->dia_semana = "Quinta-Feira";
                        break;
                    case 5:
                        $h->dia_semana = "Sexta-Feira";
                        break;
                }

                $h->minuto_inicio = $h->minuto_inicio == "0" ? "00" : $h->minuto_inicio;
                $h->minuto_fim = $h->minuto_fim == "0" ? "00" : $h->minuto_fim;
                $h->intervalo = "$h->hora_inicio:$h->minuto_inicio - $h->hora_fim:$h->minuto_fim";

                return $h;
            }, $horarios);
        } else {
            $horarios = null;
        }

        $restricoes = [
            'horarios' => $horarios
        ];

        return $restricoes;
    }
}
