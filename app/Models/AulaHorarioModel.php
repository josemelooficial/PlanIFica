<?php

namespace App\Models;

use CodeIgniter\Model;

class AulaHorarioModel extends Model
{
    protected $table            = 'aula_horario';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'aula_id',
        'tempo_de_aula_id',
        'versao_id',
        'fixa',
        'bypass',
        'destaque'
    ];

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
        'aula_id' => 'required|is_not_unique[aulas.id]|max_length[11]',
        'tempo_de_aula_id' => 'required|is_not_unique[tempos_de_aula.id]',
        'versao_id' => 'required|is_not_unique[versoes.id]|max_length[11]'
    ];
    protected $validationMessages   = [
        "aula_id" => [
            "required" => "O campo Aula é obrigatório",
            "is_not_unique" => "A aula já deve estar cadastrada",
            "max_length" => "O tamanho máximo é 11 dígitos",
        ],
        "tempo_de_aula_id" => [
            "required" => "O campo Tempo de Aula é obrigatório",
            "is_not_unique" => "O Tempo de Aula deve estar cadatrado",
        ],
        "versao_id" => [
            "is_not_unique" => "A versão deve estar cadastrada",
            "max_length" => "O tamanho máximo é 11 dígitos"
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
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getAulasFromTurma($turma_id)
    {
        return $this->select('aula_horario.*, aulas.destaque as aula_destaque')
            ->join('aulas', 'aulas.id = aula_horario.aula_id')
            ->where('aulas.turma_id', $turma_id)
            ->where('aula_horario.versao_id', (new VersoesModel())->getVersaoByUser(auth()->id()))
            ->findAll();
    }

    public function getAmbientesFromAulaHorario($aulaHorarioId)
    {
        return $this->select('aula_horario_ambiente.*')
            ->join('aula_horario_ambiente', 'aula_horario_ambiente.aula_horario_id = aula_horario.id')
            ->where('aula_horario.id', $aulaHorarioId)
            ->findAll();
    }

    public function getAulaHorario($aulaHorarioId)
    {
        return $this->select('cursos.nome as curso, disciplinas.nome as disciplina, turmas.sigla as turma, professores.nome as professor, ambientes.nome as ambiente')
            ->join('tempos_de_aula', 'aula_horario.tempo_de_aula_id = tempos_de_aula.id')
            ->join('aula_horario_ambiente', 'aula_horario_ambiente.aula_horario_id = aula_horario.id')
            ->join('ambientes', 'aula_horario_ambiente.ambiente_id = ambientes.id')
            ->join('aulas', 'aula_horario.aula_id = aulas.id')
            ->join('aula_professor', 'aulas.id = aula_professor.aula_id')
            ->join('professores', 'professores.id = aula_professor.professor_id')
            ->join('disciplinas', 'disciplinas.id = aulas.disciplina_id')
            ->join('turmas', 'turmas.id = aulas.turma_id')
            ->join('cursos', 'cursos.id = turmas.curso_id')
            ->where('aula_horario.id', $aulaHorarioId)
            ->get()
            ->getResult();
    }

    public function deleteAulaNoHorario($aula_id, $tempo_de_aula_id, $versao_id)
    {
        $idHorarioAula = $this->select('aula_horario.id')
            ->join('aulas', 'aula_horario.aula_id = aulas.id')
            ->where('aulas.id', $aula_id)
            ->where('aula_horario.tempo_de_aula_id', $tempo_de_aula_id)
            ->where('aula_horario.versao_id', $versao_id)
            //->where('aula_horario.fixa !=', '1')
            ->get();

        if ($idHorarioAula->getNumRows() > 0) {
            $idHorarioAula = $idHorarioAula->getRowArray()['id'];
            $this->db->simpleQuery("DELETE FROM aula_horario_ambiente WHERE aula_horario_id = '$idHorarioAula'");
            $this->db->simpleQuery("DELETE FROM aula_horario WHERE id = '$idHorarioAula'");
        }
    }

    public function fixarAulaHorario($tempo_de_aula_id)
    {
        $this->db->simpleQuery("UPDATE aula_horario SET fixa = 1 WHERE id = '$tempo_de_aula_id'");
    }

    public function desfixarAulaHorario($tempo_de_aula_id)
    {
        $this->db->simpleQuery("UPDATE aula_horario SET fixa = 0 WHERE id = '$tempo_de_aula_id'");
    }

    public function bypassarAulaHorario($tempo_de_aula_id)
    {
        $this->db->simpleQuery("UPDATE aula_horario SET bypass = 1 WHERE id = '$tempo_de_aula_id'");
    }

    public function desBypassarAulaHorario($tempo_de_aula_id)
    {
        $this->db->simpleQuery("UPDATE aula_horario SET bypass = 0 WHERE id = '$tempo_de_aula_id'");
    }

    /*public function checkAulaHorarioByVersao($versao)
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

    public function checkAulaHorarioByAula($aula)
    {
        $builder = $this->db->table($this->table);
        $builder->where('aula_id', $aula);
        $builder->where('versao_id', (new VersoesModel())->getVersaoByUser(auth()->id()));
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return true; // A aula existe na tabela
        } else {
            return false; // A aula não existe na tabela
        }
    }

    public function choqueAmbiente($aulaHorarioId)
    {
        $builder = $this->select('ambiente_id, tempo_de_aula_id')
            ->join('aula_horario_ambiente', 'aula_horario_ambiente.aula_horario_id = aula_horario.id')
            ->where('aula_horario.id', $aulaHorarioId)
            ->groupStart()
            ->where('bypass is null')
            ->orWhere('bypass', '0')
            ->groupEnd()
            ->where('versao_id', (new VersoesModel())->getVersaoByUser(auth()->id()))
            ->get();

        foreach ($builder->getResult() as $row) {
            $ambiente = $row->ambiente_id;
            $tempo = $row->tempo_de_aula_id;

            $builder2 = $this->db->table('tempos_de_aula')->select('*')->where('id', $tempo)->get();
            $dia_semana = $builder2->getRowArray()['dia_semana'];
            $hora_inicio = $builder2->getRowArray()['hora_inicio'];
            $minuto_inicio = $builder2->getRowArray()['minuto_inicio'];

            $builder3 = $this->select('aula_horario.id as theid')
                ->join('tempos_de_aula', 'aula_horario.tempo_de_aula_id = tempos_de_aula.id')
                ->join('aula_horario_ambiente', 'aula_horario_ambiente.aula_horario_id = aula_horario.id')
                ->where('aula_horario.id !=', $aulaHorarioId)
                ->groupStart()
                ->where('bypass is null')
                ->orWhere('bypass', '0')
                ->groupEnd()
                ->where('aula_horario_ambiente.ambiente_id', $ambiente)
                ->where('tempos_de_aula.dia_semana', $dia_semana)
                ->where('(tempos_de_aula.hora_inicio * 60 + tempos_de_aula.minuto_inicio) <=', $hora_inicio * 60 + $minuto_inicio)
                ->where('(tempos_de_aula.hora_fim * 60 + tempos_de_aula.minuto_fim) >', $hora_inicio * 60 + $minuto_inicio)
                ->where('versao_id', (new VersoesModel())->getVersaoByUser(auth()->id()))
                ->get();

            //die($this->db->getLastQuery());

            if ($builder3->getNumRows() > 0) {
                return $builder3->getRowArray()['theid']; // Conflito encontrado, retorna o ID do horário de aula em conflito
            }
        }

        return 0; // Sem conflito
    }

    public function destacandoConflitoAmbiente($horarioId)
    {
        $ambientes = $this->db->table('ambientes')
            ->select('id, nome')
            ->get()->getResultArray();
        
        if (!$ambientes) {
            return null; 
        }

        $tempo = $this->db->table('tempos_de_aula')
            ->select('dia_semana, hora_inicio, minuto_inicio, hora_fim, minuto_fim')
            ->where('id', $horarioId)
            ->get()->getRowArray();


        if (!$tempo) {
            return null; 
        }

        $novoInicio = $tempo['hora_inicio']*60 + $tempo['minuto_inicio'];
        $novoFim   = $tempo['hora_fim']*60   + $tempo['minuto_fim'];

        $ambientesConflitantes = [];
        //Para cada ambiente, checa conflitos
        foreach ($ambientes as $amb) {
            $builder = $this->select('aula_horario.id as conflito_id')
                ->join('tempos_de_aula t', 'aula_horario.tempo_de_aula_id = t.id')
                ->join('aula_horario_ambiente aha', 'aha.aula_horario_id = aula_horario.id')
                ->where('aha.ambiente_id', $amb['id'])
                ->where('t.dia_semana', $tempo['dia_semana'])
                ->groupStart()
                    ->where('aula_horario.bypass', null)
                    ->orWhere('aula_horario.bypass', '0')
                ->groupEnd()
                ->groupStart()
                    ->where('(t.hora_inicio*60 + t.minuto_inicio) <', $novoFim)
                    ->where($novoInicio.' < (t.hora_fim*60 + t.minuto_fim)', null, false)
                ->groupEnd();

            $conflitoDetectado = $builder->get()->getResultArray();
            
            foreach($conflitoDetectado as $conflito) {
                $ambientesConflitantes[] = [
                    'conflito_id' => $conflito['conflito_id'],
                    'ambiente_id' => $amb['id'],
                    'nome_ambiente' => $amb['nome'],
                ]; 
            }
        } 
        if (!empty($ambientesConflitantes)) {
            return $ambientesConflitantes ?? null; 
        }
    }



    public function choqueDocente($aulaHorarioId)
    {
        $builder = $this->select('professor_id, tempo_de_aula_id')
            ->join('aula_professor', 'aula_professor.aula_id = aula_horario.aula_id')
            ->where('aula_horario.id', $aulaHorarioId)
            ->groupStart()
            ->where('bypass is null')
            ->orWhere('bypass', '0')
            ->groupEnd()
            ->where('versao_id', (new VersoesModel())->getVersaoByUser(auth()->id()))
            ->get();

        foreach ($builder->getResult() as $row) {
            $professor = $row->professor_id;
            $tempo = $row->tempo_de_aula_id;

            $builder2 = $this->db->table('tempos_de_aula')->select('*')->where('id', $tempo)->get();
            $dia_semana = $builder2->getRowArray()['dia_semana'];
            $hora_inicio = $builder2->getRowArray()['hora_inicio'];
            $minuto_inicio = $builder2->getRowArray()['minuto_inicio'];

            $builder3 = $this->select('aula_horario.id as theid')
                ->join('tempos_de_aula', 'aula_horario.tempo_de_aula_id = tempos_de_aula.id')
                ->join('aula_professor', 'aula_professor.aula_id = aula_horario.aula_id')
                ->where('aula_horario.id !=', $aulaHorarioId)
                ->groupStart()
                ->where('bypass is null')
                ->orWhere('bypass !=', '1')
                ->groupEnd()
                ->where('aula_professor.professor_id', $professor)
                ->where('tempos_de_aula.dia_semana', $dia_semana)
                ->where('(tempos_de_aula.hora_inicio * 60 + tempos_de_aula.minuto_inicio) <=', $hora_inicio * 60 + $minuto_inicio)
                ->where('(tempos_de_aula.hora_fim * 60 + tempos_de_aula.minuto_fim) >', $hora_inicio * 60 + $minuto_inicio)
                ->where('versao_id', (new VersoesModel())->getVersaoByUser(auth()->id()))
                ->get();

            if ($builder3->getNumRows() > 0) {
                return $builder3->getRowArray()['theid']; // Conflito encontrado, retorna o ID do horário de aula em conflito
            }
        }

        return 0; // Sem conflito
    }

    public function restricaoDocente($aulaHorarioId)
    {
        // Obter professor(es) e o tempo de aula do horário atual
        $builder = $this->select('professor_id, tempo_de_aula_id')
            ->join('aula_professor', 'aula_professor.aula_id = aula_horario.aula_id')
            ->where('aula_horario.id', $aulaHorarioId)
            ->where('versao_id', (new VersoesModel())->getVersaoByUser(auth()->id()))
            ->get();

        // Iterar sobre os resultados
        foreach ($builder->getResult() as $row) {
            $professor = $row->professor_id;
            $tempo = $row->tempo_de_aula_id;

            //Obter o dia da semana, hora e minuto de início do tempo de aula
            $builder2 = $this->db->table('tempos_de_aula')->select('*')->where('id', $tempo)->get();
            $dia_semana = $builder2->getRowArray()['dia_semana'];
            $hora_inicio = $builder2->getRowArray()['hora_inicio'];
            $minuto_inicio = $builder2->getRowArray()['minuto_inicio'];

            //Verificar se há restrições para o professor no mesmo dia e horário
            $builder3 = $this->db->table('professor_regras')
                ->select('tempos_de_aula.id as theid')
                ->join('tempos_de_aula', 'tempo_de_aula_id = tempos_de_aula.id')
                ->where('professor_regras.professor_id', $professor)
                ->where('tipo', '2') //restrição
                ->where('tempos_de_aula.dia_semana', $dia_semana)
                ->where('(tempos_de_aula.hora_inicio * 60 + tempos_de_aula.minuto_inicio) <=', $hora_inicio * 60 + $minuto_inicio)
                ->where('(tempos_de_aula.hora_fim * 60 + tempos_de_aula.minuto_fim) >', $hora_inicio * 60 + $minuto_inicio)
                ->get();

            if ($builder3->getNumRows() > 0) {
                return $builder3->getRowArray()['theid']; // Conflito encontrado, retorna o ID do da regra conflitante do professor
            }
        }

        return 0; // Sem conflito
    }

    public function verificarTresTurnos($aulaHorarioId)
    {
        // Obter professor(es) e o tempo de aula do horário atual
        $builder = $this->select('professor_id, tempo_de_aula_id')
            ->join('aula_professor', 'aula_professor.aula_id = aula_horario.aula_id')
            ->where('aula_horario.id', $aulaHorarioId)
            ->where('versao_id', (new VersoesModel())->getVersaoByUser(auth()->id()))
            ->get();

        // Iterar sobre os resultados, para o caso de mais de um professor na aula
        foreach ($builder->getResult() as $row) {
            $professor = $row->professor_id;
            $tempo = $row->tempo_de_aula_id;

            //Obter o dia da semana, hora e minuto de início do tempo de aula
            $builder2 = $this->db->table('tempos_de_aula')->select('*')->where('id', $tempo)->get();
            $dia_semana = $builder2->getRowArray()['dia_semana'];
            $hora_inicio = $builder2->getRowArray()['hora_inicio'];

            //Flags para os turnos
            $manha = $tarde = $noite = false;

            //Obter o dia da semana, hora e minuto de início do tempo de aula
            $builder2 = $this->select()
                ->join('aula_professor', 'aula_professor.aula_id = aula_horario.aula_id')
                ->join('tempos_de_aula', 'aula_horario.tempo_de_aula_id = tempos_de_aula.id')
                ->where('aula_professor.professor_id', $professor)
                ->where('tempos_de_aula.dia_semana', $dia_semana)
                ->where('versao_id', (new VersoesModel())->getVersaoByUser(auth()->id()))
                ->get();

            foreach ($builder2->getResult() as $row2) {
                $hora_inicio = $row2->hora_inicio;

                if ($hora_inicio < 12)
                    $manha = true;
                else if ($hora_inicio >= 12 && $hora_inicio < 18)
                    $tarde = true;
                else if ($hora_inicio >= 18)
                    $noite = true;

                if ($manha && $tarde && $noite) {
                    return 1; // Três turnos para o dia
                }
            }
        }

        return 0; // Sem três turnos para o dia
    }

    public function verificarTempoEntreTurnos($aulaHorarioId)
    {
        // Obter professor(es) e o tempo de aula do horário atual
        $builder = $this->select('professor_id, tempo_de_aula_id')
            ->join('aula_professor', 'aula_professor.aula_id = aula_horario.aula_id')
            ->where('aula_horario.id', $aulaHorarioId)
            ->where('versao_id', (new VersoesModel())->getVersaoByUser(auth()->id()))
            ->get();

        // Iterar sobre os resultados, para o caso de mais de um professor na aula
        foreach ($builder->getResult() as $row) {
            $professor = $row->professor_id;
            $tempo = $row->tempo_de_aula_id;

            //Obter o dia da semana, hora e minuto de início do tempo de aula
            $builder2 = $this->db->table('tempos_de_aula')->select('*')->where('id', $tempo)->get();
            $dia_semana = $builder2->getRowArray()['dia_semana'];
            $hora_inicio = $builder2->getRowArray()['hora_inicio'];
            $hora_fim = $builder2->getRowArray()['hora_fim'];
            $minuto_inicio = $builder2->getRowArray()['minuto_inicio'];
            $minuto_fim = $builder2->getRowArray()['minuto_fim'];

            //dados da aula sendo verificada
            $aula_timestamp_inicio = $hora_inicio * 60 + $minuto_inicio;
            $aula_timestamp_fim = $hora_fim * 60 + $minuto_fim;

            //Flags para os turnos
            $aula_turno = 0;

            if ($hora_inicio < 12) $aula_turno = 1; // Manhã
            else if ($hora_inicio >= 12 && $hora_inicio < 18) $aula_turno = 2; // Tarde
            else if ($hora_inicio >= 18) $aula_turno = 3; // Noite

            //Flags para os turnos
            $manha = $tarde = $noite = false;

            $menor_inicio_manha = $menor_inicio_tarde = $menor_inicio_noite = $amanha_manha_inicio = 9999999;
            $maior_fim_manha = $maior_fim_tarde = $maior_fim_noite = $ontem_noite_fim = 0;

            $menor_inicio_manha_aulaid = $menor_inicio_tarde_aulaid = $menor_inicio_noite_aulaid = $amanha_manha_inicio_aulaid = 0;
            $maior_fim_manha_aulaid = $maior_fim_tarde_aulaid = $maior_fim_noite_aulaid = $ontem_noite_fim_aulaid = 0;

            //Obter o dia da semana, hora e minuto de início do tempo de aula
            $builder2 = $this->select('*, aula_horario.id as theid')
                ->join('aula_professor', 'aula_professor.aula_id = aula_horario.aula_id')
                ->join('tempos_de_aula', 'aula_horario.tempo_de_aula_id = tempos_de_aula.id')
                ->where('aula_professor.professor_id', $professor)
                ->whereIn('tempos_de_aula.dia_semana', [$dia_semana, ($dia_semana + 1), ($dia_semana - 1)]) //pega horários do dia da aula, e do dia seguinte e anterior também pra comparar a manhã com noite
                ->where('versao_id', (new VersoesModel())->getVersaoByUser(auth()->id()))
                ->get();

            foreach ($builder2->getResult() as $row2) {
                //dados da aula vinda do banco de dados
                $timestamp_inicio = $row2->hora_inicio * 60 + $row2->minuto_inicio;
                $timestamp_fim = $row2->hora_fim * 60 + $row2->minuto_fim;

                if ($row2->hora_inicio < 12) {
                    $manha = true;

                    /*if($row2->dia_semana == $dia_semana && $timestamp_inicio < $menor_inicio_manha) //manhã de hoje - inicio
                    {
                        $menor_inicio_manha = $timestamp_inicio;
                        $menor_inicio_manha_aulaid = $row2->theid;
                    }*/

                    if ($row2->dia_semana == $dia_semana && $timestamp_fim > $maior_fim_manha) //manhã de hoje - fim
                    {
                        $maior_fim_manha = $timestamp_fim;
                        $maior_fim_manha_aulaid = $row2->theid;
                    }

                    if ($row2->dia_semana == ($dia_semana + 1) && $timestamp_inicio < $amanha_manha_inicio) //manhã de amanhã - inicio
                    {
                        $amanha_manha_inicio = $timestamp_inicio;
                        $amanha_manha_inicio_aulaid = $row2->theid;
                    }
                } else if ($row2->hora_inicio >= 12 && $row2->hora_inicio < 18) {
                    $tarde = true;

                    if ($row2->dia_semana == $dia_semana && $timestamp_inicio < $menor_inicio_tarde) //tarde de hoje
                    {
                        $menor_inicio_tarde = $timestamp_inicio;
                        $menor_inicio_tarde_aulaid = $row2->theid;
                    }

                    if ($row2->dia_semana == $dia_semana && $timestamp_fim > $maior_fim_tarde) //tarde de hoje
                    {
                        $maior_fim_tarde = $timestamp_fim;
                        $maior_fim_tarde_aulaid = $row2->theid;
                    }
                } else if ($row2->hora_inicio >= 18) {
                    $noite = true;

                    if ($row2->dia_semana == $dia_semana && $timestamp_inicio < $menor_inicio_noite) //noite de hoje - inicio
                    {
                        $menor_inicio_noite = $timestamp_inicio;
                        $menor_inicio_noite_aulaid = $row2->theid;
                    }

                    /*if($row2->dia_semana == $dia_semana && $timestamp_fim < $maior_fim_noite) //noite de hoje - fim
                    {
                        $maior_fim_noite = $timestamp_fim;
                        $maior_fim_noite_aulaid = $row2->theid;
                    }*/

                    if ($row2->dia_semana == ($dia_semana - 1) && $timestamp_fim > $ontem_noite_fim) //noite de ontem
                    {
                        $ontem_noite_fim = $timestamp_fim;
                        $ontem_noite_fim_aulaid = $row2->theid;
                    }
                }
            }

            // aula atual manhã, e tem aula a tarde
            if (($aula_turno == 1 && $tarde)) {
                if (($menor_inicio_tarde - $aula_timestamp_fim) < (60)) // uma hora de intervalo
                    return "1-" . ($menor_inicio_tarde - $aula_timestamp_fim) . "-" . $menor_inicio_tarde_aulaid;
                //problema entre manhã e tarde = 1
                //seguido da diferença de tempo
                //seguido do id da aula que está causando o problema
            }

            // aula atual a tarde, e tem aula de manhã
            if ($aula_turno == 2 && $manha) {
                if (($aula_timestamp_inicio - $maior_fim_manha) < (60)) // uma hora de intervalo
                    return "1-" . ($aula_timestamp_inicio - $maior_fim_manha) . "-" . $maior_fim_manha_aulaid;
                //problema entre manhã e tarde = 1
                //seguido da diferença de tempo
                //seguido do id da aula que está causando o problema
            }

            // aula atual a tarde, e tem aula a noite
            if ($aula_turno == 2 && $noite) {
                if (($menor_inicio_noite - $aula_timestamp_fim) < (60)) // uma hora de intervalo
                    return "2-" . ($menor_inicio_noite - $aula_timestamp_fim) . "-" . $menor_inicio_noite_aulaid;
                //problema entre tarde e noite = 2
                //seguido da diferença de tempo
                //seguido do id da aula que está causando o problema
            }

            // aula atual a noite, e tem aula a tarde
            if ($aula_turno == 3 && $tarde) {
                if (($aula_timestamp_inicio - $maior_fim_tarde) < (60)) // uma hora de intervalo
                    return "2-" . ($aula_timestamp_inicio - $maior_fim_tarde) . "-" . $maior_fim_tarde_aulaid;
                //problema entre tarde e noite = 2
                //seguido da diferença de tempo
                //seguido do id da aula que está causando o problema
            }

            // aula atual noite, e tem aula amanhã de manhã
            if ($aula_turno == 3 && $amanha_manha_inicio != 9999999) {
                if ((($amanha_manha_inicio + 1440) - $aula_timestamp_fim) < (11 * 60)) // onze horas de intervalo
                    return "3-" . (($amanha_manha_inicio + 1440) - $aula_timestamp_fim) . "-" . $amanha_manha_inicio_aulaid;
                //problema entre noite e manhã do dia seguinte = 3
                //seguido da diferença de tempo
                //seguido do id da aula que está causando o problema
            }

            // aula atual manhã, e tem aula ontem a noite
            if ($aula_turno == 1 && $ontem_noite_fim != 0) {
                if ((($aula_timestamp_inicio + 1440) - $ontem_noite_fim) < (11 * 60)) // onze horas de intervalo
                    return "4-" . (($aula_timestamp_inicio + 1440) - $ontem_noite_fim) . "-" . $ontem_noite_fim_aulaid;
                //problema entre manhã e noite do dia anterior = 4
                //seguido da diferença de tempo
                //seguido do id da aula que está causando o problema
            }

            //FALTA AVALIAR ALGUMAS SITUAÇÕES NO OPOSTO, POR EXEMPLO, VERIFICAR NA NOITE SE TEM HORÁRIO A TARDE COM POUCO TEMPO
        }

        return 0; // Sem problemas de intervalo
    }
    public function getAulaHorarioCompleta($aulaHorarioId)
    {
        $builder = $this->db->table('aula_horario ah');
        $builder->select('ah.*, a.disciplina, GROUP_CONCAT(DISTINCT p.nome SEPARATOR ", ") as professores');
        $builder->join('aulas a', 'a.id = ah.aula_id');
        $builder->join('aula_professor ap', 'ap.aula_id = a.id');
        $builder->join('professores p', 'p.id = ap.professor_id');
        $builder->where('ah.id', $aulaHorarioId);
        $builder->groupBy('ah.id');

        return $builder->get()->getRowArray();
    }

    public function destacarAulaHorario($aula_horario_id)
    {
        try {
            return $this->update($aula_horario_id, ['destaque' => 1]);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao destacar aula horário: ' . $e->getMessage());
            return false;
        }
    }

    public function desDestacarAulaHorario($aula_horario_id)
    {
        try {
            return $this->update($aula_horario_id, ['destaque' => 0]);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao remover destaque de aula horário: ' . $e->getMessage());
            return false;
        }
    }

    public function countConflitosAmbiente(int $versaoId): int
    {
        $sqlAmbiente = "
            SELECT COUNT(DISTINCT ah1.id) AS total
            FROM aula_horario ah1
            JOIN tempos_de_aula t1  ON t1.id = ah1.tempo_de_aula_id
            JOIN aula_horario_ambiente a1 ON a1.aula_horario_id = ah1.id
            JOIN aula_horario_ambiente a2 ON a2.ambiente_id = a1.ambiente_id
            JOIN aula_horario ah2   ON ah2.id = a2.aula_horario_id AND ah2.id <> ah1.id
            JOIN tempos_de_aula t2  ON t2.id = ah2.tempo_de_aula_id
            WHERE ah1.versao_id = :v:
              AND ah2.versao_id = :v:
              AND (ah1.bypass IS NULL OR ah1.bypass = '0')
              AND (ah2.bypass IS NULL OR ah2.bypass = '0')
              AND t1.dia_semana = t2.dia_semana
              AND (t1.hora_inicio*60 + t1.minuto_inicio) <  (t2.hora_fim*60 + t2.minuto_fim)
              AND (t2.hora_inicio*60 + t2.minuto_inicio) <  (t1.hora_fim*60 + t1.minuto_fim)
        ";
        $conflitos = $this->db->query($sqlAmbiente, ['v' => $versaoId])->getRowArray();
        return (int)($conflitos['total'] ?? 0);
    }

    public function countConflitosProfessor(int $versaoId): int
    {   
        $sqlProf = "
            SELECT COUNT(DISTINCT ah1.id) AS total
            FROM aula_horario ah1
            JOIN tempos_de_aula t1  ON t1.id = ah1.tempo_de_aula_id
            JOIN aula_professor ap1 ON ap1.aula_id = ah1.aula_id

            JOIN aula_professor ap2 ON ap2.professor_id = ap1.professor_id
            JOIN aula_horario ah2   ON ah2.aula_id = ap2.aula_id AND ah2.id <> ah1.id
            JOIN tempos_de_aula t2  ON t2.id = ah2.tempo_de_aula_id

            WHERE ah1.versao_id = :v:
              AND ah2.versao_id = :v:
              AND (ah1.bypass IS NULL OR ah1.bypass = '0')
              AND (ah2.bypass IS NULL OR ah2.bypass = '0')
              AND t1.dia_semana = t2.dia_semana
              AND (t1.hora_inicio*60 + t1.minuto_inicio) <  (t2.hora_fim*60 + t2.minuto_fim)
              AND (t2.hora_inicio*60 + t2.minuto_inicio) <  (t1.hora_fim*60 + t1.minuto_fim)
        ";
        $conflitos = $this->db->query($sqlProf, ['v' => $versaoId])->getRowArray();
        return (int)($conflitos['total'] ?? 0);
    }

    public function countRestricaoDocente(int $versaoId): int
    {
        $sqlRestricao = "
            SELECT COUNT(DISTINCT ah.id) AS total
            FROM aula_horario ah
            JOIN tempos_de_aula t1   ON t1.id = ah.tempo_de_aula_id
            JOIN aula_professor ap   ON ap.aula_id = ah.aula_id
            JOIN professor_regras pr ON pr.professor_id = ap.professor_id AND pr.tipo = '2'
            JOIN tempos_de_aula t2   ON t2.id = pr.tempo_de_aula_id
            WHERE ah.versao_id = :v:
              AND (ah.bypass IS NULL OR ah.bypass = '0')
              AND t1.dia_semana = t2.dia_semana
              -- start da aula dentro da janela de restrição (igual ao seu código)
              AND (t2.hora_inicio*60 + t2.minuto_inicio) <= (t1.hora_inicio*60 + t1.minuto_inicio)
              AND (t2.hora_fim*60 + t2.minuto_fim)       >  (t1.hora_inicio*60 + t1.minuto_inicio)
        ";
        $conflitos = $this->db->query($sqlRestricao, ['v' => $versaoId])->getRowArray();
        return (int)($conflitos['total'] ?? 0);
    }

    public function countTresTurnos(int $versaoId): int
    {
        $sqlTresTurnos = "
            SELECT COUNT(*) AS total
            FROM (
                SELECT ap.professor_id, t.dia_semana,
                    COUNT(DISTINCT
                        CASE
                            WHEN t.hora_inicio < 12 THEN 1
                            WHEN t.hora_inicio < 18 THEN 2
                            ELSE 3
                        END
                    ) AS qnt_turnos
                FROM aula_horario ah
                JOIN aula_professor ap ON ap.aula_id = ah.aula_id
                JOIN tempos_de_aula t  ON t.id = ah.tempo_de_aula_id
                WHERE ah.versao_id = :v:
                  AND (ah.bypass IS NULL OR ah.bypass = '0')
                GROUP BY ap.professor_id, t.dia_semana
                HAVING COUNT(DISTINCT
                    CASE
                        WHEN t.hora_inicio < 12 THEN 1
                        WHEN t.hora_inicio < 18 THEN 2
                        ELSE 3
                    END
                ) >= 3
            ) s
        ";
        $conflitos = $this->db->query($sqlTresTurnos, ['v' => $versaoId])->getRowArray();
        return (int)($conflitos['total'] ?? 0);
    }
}
