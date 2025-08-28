<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\CursosModel;
use App\Models\VersoesModel;
use App\Models\AmbientesModel;
use App\Models\AulaHorarioModel;
use App\Models\AulaHorarioAmbienteModel;
use CodeIgniter\HTTP\Request;

class AulaHorarioController extends BaseController
{
  public function verificarConflitosRotina() {

   $aulaHorarioModel = new AulaHorarioModel();
   $aulas = $aulaHorarioModel
   ->where('bypass =', null)
   ->findAll();

   $conflitos = [];
    //construindo o array de conflitos com o retorno das verificações
    foreach ($aulas as $aula) {
        $id = $aula['id'];

        $conflitoAmbiente = $aulaHorarioModel->choqueAmbiente($id);
        if ($conflitoAmbiente > 0) {
            $conflitos[] = [
                'aula_horario_id' => $id,
                'tipo' => 'CONFLITO-AMBIENTE',
                'referencia' => $conflitoAmbiente
            ];
            continue;
        }

        $conflitoDocente = $aulaHorarioModel->choqueDocente($id);
        if ($conflitoDocente > 0) {
            $conflitos[] = [
             'aula_horario_id' => $id,
             'tipo' => 'CONFLITO-PROFESSOR',
             'referencia' => $conflitoDocente
            ];
        }

        $conflitoTurno = $aulaHorarioModel->verificarTresTurnos($id);
        if($conflitoTurno > 0) {
         $conflitos[] = [
           'aula_horario_id' => $id,
           'tipo' => 'CONFLITO-TURNOS',
           'referencia' => $conflitoTurno
         ];
        }

        $conflitoDocenteRestricao = $aulaHorarioModel->restricaoDocente($id);
        if($conflitoDocenteRestricao > 0) {
          $conflitos[] = [
           'aula_horario_id' => $id,
           'tipo' => 'RESTRIÇÃO-DOCENTE',
           'referencia' => $conflitoDocenteRestricao,
          ];
        }

        $conflitoIntervalo = $aulaHorarioModel->verificarTempoEntreTurnos($id);
        if($conflitoIntervalo > 0){
          $conflitos[] = [
           'aula_horario_id' => $id,
           'tipo' => 'CONFLITO-INTERVALO',
           'referencia' => $conflitoIntervalo,
          ];

        }

    }
    // dd($conflitos);
    return $this->response->setJSON($conflitos);

  }

  public function destacarConflitosAmbiente()
{
    $data = $this->request->getPost();
    $idTempoDeAula = $data['tempo_de_aula_id'];

    $aulaHorarioModel = new AulaHorarioModel();
    $conflitos = $aulaHorarioModel->destacandoConflitoAmbiente($idTempoDeAula);
    $ambienteModel = new AmbientesModel();
    $nomeAmbiente = (!empty($conflitos)) ? $ambienteModel->select('nome')->where('id', $conflitos['ambiente'])->first() : null;

    // dd($conflitos);

    if (!empty($conflitos)) {
        return $this->response->setJSON([
            'mensagem' => 'Conflito de Ambiente detectado!',
            'conflitos' => $conflitos['conflito'],
            'ambiente' => $nomeAmbiente,
        ]);
    } else {
        return $this->response->setJSON([
            'mensagem' => 'Sem Conflitos!',
        ]);
    }

    return $this->response->setJSON(['status' => 'ok']);
}

  
}
