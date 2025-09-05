<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Exceptions\ReferenciaException;

class AulasModel extends Model
{
    protected $table            = 'aulas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['disciplina_id', 'turma_id', 'versao_id', 'destaque'];

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
        'disciplina_id' => 'required|is_not_unique[disciplinas.id]|max_length[11]',
        'turma_id' => 'required|is_not_unique[turmas.id]|max_length[11]',
        'versao_id' => 'is_not_unique[versoes.id]|max_length[11]',
    ];

    protected $validationMessages   = [

        "disciplina_id" => [
            "required" => "Informe a disciplina",
            "is_not_unique" => "A disciplina informada deve estar cadastrada",
            "max_length" => "O tamanho máximo de Disciplina é 11 caracteres",
        ],
        "turma_id" => [
            "required" => "Informe a Turma",
            "is_not_unique" => "A Turma informada deve estar cadastrada",
            "max_length" => "O tamanho máximo de Turma é 11 caracteres",
        ],
        "versao_id" => [
            "is_not_unique" => "A versão precisa ser registrada",
            "max_length" => "O tamanho máximo são 11 digitos",
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

    public function getAulasComTurmaDisciplinaEProfessores()
    {
        return $this->select(
            "aulas.*,
                turma.sigla as turma_sigla,
                turma.id as turma_id,
                disciplina.nome as disciplina_nome,
                disciplina.id as disciplina_id,
                disciplina.ch as disciplina_ch,
                CASE
                    WHEN curso.regime = 1 THEN CAST((disciplina.ch / 40) as SIGNED)
                    WHEN curso.regime = 2 THEN CAST((disciplina.ch / 20) as SIGNED)
                END AS disciplina_ch_semanal,
                curso.nome as curso_nome,
                curso.id as curso_id,
                GROUP_CONCAT(professores.nome) as professores_nome,
                GROUP_CONCAT(professores.id) as professores_id"
        )
            ->join("disciplinas as disciplina", "aulas.disciplina_id = disciplina.id")
            ->join("turmas as turma", "aulas.turma_id = turma.id")
            ->join("cursos as curso", "turma.curso_id = curso.id")
            ->join("aula_professor as ap", "aulas.id = ap.aula_id", 'left') // Relaciona aula com os professores
            ->join("professores as professores", "ap.professor_id = professores.id", 'left') // Relaciona a aula_professor com os professores
            ->where("aulas.versao_id", (new VersoesModel())->getVersaoByUser(auth()->id())) // Filtra pela versão ativa
            ->groupBy("aulas.id") // Agrupa por ID da aula
            ->findAll();
    }

    /*public function checkAulaByVersao($versao)
    {
        $builder = $this->db->table($this->table);
        $builder->where('versao_id', $versao);
        $query = $builder->get();

        if ($query->getNumRows() > 0)
        {
            return true; // A versão existe na tabela
        }
        else
        {
            return false; // A versão não existe na tabela
        }
    }*/

    public function getRestricoes($id)
    {
        $id = $id['id'];

        $professores = $this->db->table('aula_professor')
            ->where('aula_id', $id)
            ->get()
            ->getNumRows();

        $horarios = $this->db->table('aula_horario')
            ->where('aula_id', $id)
            ->where('versao_id', (new \App\Models\VersoesModel())->getVersaoByUser(auth()->id()))
            ->get()
            ->getNumRows();

        if ($horarios) {
            $horarios = $this->db->table('aula_horario AS ah')
                ->select("ta.*")
                ->join("tempos_de_aula AS ta", "ah.tempo_de_aula_id = ta.id")
                ->where("ah.aula_id", $id)
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
            'professores' => $professores,
            'horarios' => $horarios
        ];

        return $restricoes;
    }
}
